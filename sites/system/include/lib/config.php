<?php
namespace MaxieSystems;

const VERSION = '0.13.0';

class UnserializedProxy extends \stdClass
{
	public function __construct($class)
	 {
		$this->class = $class;
	 }

	public function __toString()
	 {
		return "$this->class";
	 }

	private $class;
}

abstract class Config
{
	const REGEX_VERSION = '/^([0-9]+)\.([0-9]+)\.([0-9]+)( beta)?$/';

	final public static function DisplayErrors($state = null) { return null === $state ? 'On' === ini_get('display_errors') : ini_set('display_errors', $state ? 'On' : 'Off'); }
	final public static function GetMSSMDir() { return null === self::$mssm_dir ? '/system' : self::$mssm_dir; }
	final public static function SetCompression($state) { self::$compress = $state; }
	final public static function CompressionEnabled() { return self::$compress; }
	final public static function GetHost() { return empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST']; }
	final public static function IsSecured() { return !(empty($_SERVER['HTTPS']) || 'off' === $_SERVER['HTTPS']) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO']) || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && 'on' === $_SERVER['HTTP_X_FORWARDED_SSL']); }
	final public static function GetScheme($s = '://') { if(isset($_SERVER['SERVER_PROTOCOL']) && 0 === strpos($_SERVER['SERVER_PROTOCOL'], 'HTTP')) return 'http'.(self::IsSecured() ? 's' : '').$s; }
	final public static function GetProtocol($s = '://') { return self::GetScheme($s); }
	final public static function GetLibDir() { return MSSE_LIB_DIR; }
	final public static function GetVarType($v, $add_value = true, $s = 'instance of ') { return 'object' === ($t = gettype($v)) ? $s.get_class($v) : $t.(is_scalar($v) ? ' '.var_export($v, true) : ''); }

	final public static function VersionValidate($v, &$m = null)
	 {
		$r = preg_match(self::REGEX_VERSION, $v, $m);
		if($r) $m[4] = empty($m[4]) ? 1 : 0;
		return $r;
	 }

	final public static function VersionCompare($ver1, $ver2)
	 {
		if(!self::VersionValidate($ver1, $m1) || !self::VersionValidate($ver2, $m2)) return null;
		for($i = 1; $i <= 4; ++$i)
		 {
			if($m1[$i] < $m2[$i]) return -1;
			if($m1[$i] > $m2[$i]) return 1;
		 }
		return 0;
	 }

	final public static function AddAutoload(...$args)
	 {
		foreach($args as $k => $v)
		 {
			if(!is_callable($v))
			 {
				if(!is_array($v)) throw new \Exception('Argument '.($k + 1).' passed to '.__METHOD__ .'() must be of the type array or callback, '.self::GetVarType($v).' given');
				static $meta = ['ns' => '', 'dir' => '', 'separator' => '/', 'prefix' => '', 'suffix' => '.php', 'ns_remove' => null, 'inc_ns' => false];
				if($diff = array_diff_key($v, $meta))
				 {
					$diff = array_keys($diff);
					throw new \Exception('Autoload configuration has invalid option'.(count($diff) > 1 ? 's' : '').': '.implode(', ', $diff).' (options allowed: '.implode(', ', array_keys($meta)).')');
				 }
				$v = array_merge($meta, $v);
				if('' !== $v['dir'] && '/' !== substr($v['dir'], -1)) $v['dir'] .= '/';
			 }
			self::$autoload[] = $v;
		 }
	 }

	final public static function Autoload($class_name)
	 {
		if(isset(self::$autoload_classes[$class_name])) return;
		else self::$autoload_classes[$class_name] = true;
		$name = strtolower($class_name);
		if(false === ($pos = strrpos($name, '\\')))
		 {
			foreach(self::$autoload as $a)
			 {
				if(is_callable($a))
				 {
					if(call_user_func($a, $name, $class_name)) return;
				 }
				elseif('' === $a['ns'])
				 {
					if('' === $a['dir'])
					 {
						if(self::HasRequiredFile($name)) return self::RequireFile($name);
					 }
					elseif(file_exists($fname = self::MakeAutoloadFileName($a, $name))) return require_once($fname);
				 }
			 }
		 }
		else
		 {
			$ns = substr($name, 0, $pos);
			$n = substr($name, $pos + 1);
			foreach(self::$autoload as $a)
			 {
				if(is_callable($a))
				 {
					if(call_user_func($a, $name, $class_name)) return;
				 }
				elseif('' === $a['ns']);
				elseif($ns === $a['ns'] || 0 === strpos($ns, "$a[ns]\\"))
				 {
					$fname = self::MakeAutoloadFileName($a, $name);
					if('' === $a['dir'])
					 {
						if(self::HasRequiredFile($fname)) return self::RequireFile($fname);
						elseif(!empty($a['inc_ns']))
						 {
							$bname = $n.$a['suffix'];
							if($fname === $bname);// это означает, что всё пространство имён было удалено, и осталось только название класса без namespace.
							elseif(self::HasRequiredFile($fname = self::CropAutoloadFileName($a, $fname, $bname))) return self::RequireFile($fname);
						 }
					 }
					elseif(file_exists($fname)) return require_once($fname);
					elseif(!empty($a['inc_ns']))
					 {
						$bname = $n.$a['suffix'];
						if(basename($fname) === $bname);
						elseif(file_exists($fname = self::CropAutoloadFileName($a, $fname, $bname))) return require_once($fname);
					 }				
				 }
			 }
		 }
	 }

	final public static function SendHTML($html, ...$args)
	 {
		header('Content-Type: text/html; charset=UTF-8');
		if($encoding = self::CompressionEnabled())
		 {
			if(empty($_SERVER['HTTP_ACCEPT_ENCODING'])) $encoding = false;
			else
			 {
				$enc = $_SERVER['HTTP_ACCEPT_ENCODING']; 
				if(strpos($enc, 'x-gzip') !== false) $encoding = 'x-gzip';
				elseif(strpos($enc, 'gzip') !== false) $encoding = 'gzip';
				else $encoding = false;
			 }
			if($encoding) header("Content-Encoding: $encoding");
		 }
		if(!is_string($html) && is_callable($html)) $html = (string)call_user_func($html, ...$args);
		if($encoding)
		 {
			$len = strlen($html); 
			$gzip_4_chars = function($v){
				$r = '';
				for($i = 0; $i < 4; ++$i)
				 {
					$r .= chr($v % 256);
					$v = floor($v / 256);
				 }
				return $r;
			};
			$crc = crc32($html);
			$html = gzcompress($html, 9);
			$html = "\x1f\x8b\x08\x00\x00\x00\x00\x00".substr($html, 0, strlen($html) - 4).$gzip_4_chars($crc).$gzip_4_chars($len);
		 }
		die($html);
	 }

	final public static function ErrorTracking($state, ...$errors)
	 {
		foreach($errors as $error)
		 switch($error)
		  {
			case E_STRICT:
			case E_CORE_WARNING:
			case E_DEPRECATED: self::$disable_error_tracking[$error] = is_callable($state) ? $state : !$state; break;
			default: die(__METHOD__.': you can change tracking only for E_STRICT, E_CORE_WARNING and E_DEPRECATED.');
		  }
	 }

	final public static function OnShutDown()
	 {
		if($error = error_get_last())
		 {
			if(self::DisplayErrors() && ob_get_level() > 0) ob_flush();
			self::HandleError($error);
		 }
	 }

	final public static function HandleError(array $error)
	 {
		if(!empty(self::$disable_error_tracking[$error['type']]))
		 {
			if(is_callable(self::$disable_error_tracking[$error['type']]))
			 {
				if(!call_user_func(self::$disable_error_tracking[$error['type']], $error)) return;
			 }
			else return;
		 }
		if(self::$error_streams)
		 {
			set_time_limit(4);
			self::CloseDBResults();
			foreach(self::$error_streams as $stream)
			 {
				try
				 {
					$stream->InsertError($error);
				 }
				catch(\Exception $e2) {}
			 }
		 }
	 }

	final public static function SetErrorStreams(\IMSErrorStream ...$streams)
	 {
		if(self::$error_streams) throw new \Exception('Error streams are already set!');
		self::$error_streams = $streams;
	 }

	final public static function SetMSSMDir($val)
	 {
		if(null !== self::$mssm_dir) throw new \Exception('Can\'t change MSSM dir.');
		self::$mssm_dir = $val;
	 }

	final public static function RequireFile(...$names) { foreach($names as $name) require_once(MSSE_LIB_DIR."/$name.php"); }
	final public static function RegisterClasses(...$names) { self::$files = array_fill_keys($names, true); }
	final public static function HasRequiredFile($name) { return isset(self::$files[$name]); }
	final public static function GetIP() { if(!empty($_SERVER['REMOTE_ADDR'])) return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP); }
	final public static function AddSoftware($val) { self::$software[$val] = $val; }

	final public static function Exception2Array(/* \Throwable */ $e)
	 {
		$data = self::E2Array(['file' => $e->getFile(), 'line' => \Filter::GetIntOrNull($e->getLine()), 'class' => get_class($e), 'message' => $e->getMessage(), 'code' => \Filter::GetIntOrNull($e->getCode())]);
		try
		 {
			$dump = $e->GetTrace();
			self::FilterDump($dump);
			$dump = serialize($dump);
			$data['dump'] = base64_encode($dump);
		 }
		catch(\Exception $e3)
		 {
			$data['no_dump_message'] = $e3->GetMessage();
		 }
		return $data;
	 }

	final public static function NoLogE(...$args)
	 {
		if($args)
		 {
			foreach($args as $arg)
			 {
				if(true === $arg)
				 {
					self::$no_log_e = ['Exception' => true];
					break;
				 }
				elseif(false === $arg) self::$no_log_e = [];
				elseif(is_string($arg)) self::$no_log_e[$arg] = true;
				else throw new \Exception('Invalid argument: '.self::GetVarType($arg));
			 }
		 }
		else self::$no_log_e = ['Exception' => true];
	 }

	final public static function LogException(\Exception $e)
	 {
		try
		 {
			$data = self::Exception2Array($e);
			if($ip = self::GetIP()) $data['remote_addr'] = $ip;
			DB::Insert('sys_exception', $data, ['date_time' => 'NOW()']);
		 }
		catch(\Exception $e2) {}
	 }

	final public static function Error2Array(array $error)
	 {
		return self::E2Array($error);
	 }

	final public static function LogError(array $error)
	 {
		try
		 {
			$error = self::Error2Array($error);
			if($ip = self::GetIP()) $error['remote_addr'] = $ip;
			DB::Insert('sys_error', $error, ['date_time' => 'NOW()']);
		 }
		catch(\Exception $e2) {}
	 }

	final public static function ShowException(/* \Throwable */ $e)
	 {
?><table class="exception">
<tr><th>Выброшено в файле</th><td><?=$e->getFile()?></td></tr>
<tr><th>на строке номер</th><td><?=$e->getLine()?></td></tr>
<tr><th>Класс</th><td><?=get_class($e)?></td></tr>
<tr><th>Сообщение</th><td><?=$e->getMessage()?></td></tr>
<tr><th>Код</th><td><?=$e->getCode()?></td></tr>
</table><?php
		self::ShowTrace($e->getTrace());
	 }

	final public static function ShowTrace(array $trace)
	 {
?><table class="exception trace"><?php
		$len = count($trace);
		foreach($trace as $key => $item)
		 {
?><tr><th class="num" colspan="3">#<?=($len - $key)?></th></tr>
<tr><th>file</th><td colspan="2"><?=@$item['file']?></td></tr>
<tr><th>line</th><td colspan="2"><?=@$item['line']?></td></tr>
<tr><th>caller</th><td colspan="2"><?=@$item['class'].@$item['type'].$item['function']?></td></tr><?php
			if(!empty($item['args']))
			 {
?><tr><th>args</th></tr><?php
				foreach($item['args'] as $i => $arg)
				 {
?><tr><th><?=$i?></th><td><em><?=gettype($arg)?></em></td><td><?php
					if(is_numeric($arg)) print($arg);
					elseif(is_string($arg)) print('<pre>'.htmlspecialchars($arg).'</pre>');
					elseif(is_object($arg)) print($arg instanceof UnserializedProxy ? $arg : get_class($arg));
					elseif(is_bool($arg)) print($arg ? 'true' : 'false');
					elseif(null === $arg);
					elseif(is_resource($arg)) print(get_resource_type($arg));
					elseif(is_array($arg)) print('['.(($count = count($arg)) ? 'array with '.$count.' element'.($count > 1 ? 's' : '') : 'empty array').']');
					else var_dump($arg);
?></td></tr><?php
				 }
			 }
		 }
?></table><?php
	 }

	final public static function HandleException(/* \Throwable */ $e, $display = true)
	 {
		if(self::CanLogE($e))
		 {
			self::CloseDBResults();
			foreach(self::$error_streams as $stream)
			 {
				try
				 {
					$stream->InsertException($e);
				 }
				catch(\Exception $e2) {}
			 }
		 }
		if($display)
		 {
			if(!headers_sent()) HTTP::Status(500, ['exit' => false]);
			if('On' === ini_get('display_errors'))
			 {
?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>MSSE exception</title>
<link rel="stylesheet" type="text/css" href="<?=\IConst::MSAPIS?>/css/exception/1.0/exception.css" />
</head>
<body><?php
				self::ShowException($e);
?></body></html><?php
			 }
			exit();
		 }
	 }

	final public static function FilterDump(array &$dump)
	 {
		static $walk = null;
		if(null === $walk)
		 {
			$marker = microtime();
			foreach($dump as $k => $v) $marker .= ":$k";
			$marker = '__'.sha1($marker);
			$walk = function(&$v, $k) use(&$walk, $marker){
				if(is_array($v))
				 {
					if(isset($v[$marker])) return;
					else
					 {
						$v[$marker] = true;
						array_walk($v, $walk);
						unset($v[$marker]);
					 }
				 }
				elseif(is_object($v))
				 {
					if(($v instanceof \Serializable) || method_exists($v, '__sleep') || get_class($v) === 'stdClass');
					else
					 {
						$c = get_class($v);
						$tmp = $v;
						Events::Dispatch('system:filter_dump', false, ['value' => &$v, 'class' => $c], ['value' => ['set' => true]]);
						if($tmp === $v) $v = new UnserializedProxy($c);
					 }
				 }
			};
		 }
		array_walk($dump, $walk);
	 }

	final public static function ErrorConstToString($val)
	 {
		switch($val)
		 {
			case E_ERROR: return 'E_ERROR';
			case E_WARNING: return 'E_WARNING';
			case E_PARSE: return 'E_PARSE';
			case E_NOTICE: return 'E_NOTICE';
			case E_CORE_ERROR: return 'E_CORE_ERROR';
			case E_CORE_WARNING: return 'E_CORE_WARNING';
			case E_COMPILE_ERROR: return 'E_COMPILE_ERROR';
			case E_COMPILE_WARNING: return 'E_COMPILE_WARNING';
			case E_USER_ERROR: return 'E_USER_ERROR';
			case E_USER_WARNING: return 'E_USER_WARNING';
			case E_USER_NOTICE: return 'E_USER_NOTICE';
			case E_STRICT: return 'E_STRICT';
			case E_RECOVERABLE_ERROR: return 'E_RECOVERABLE_ERROR';
			case E_DEPRECATED: return 'E_DEPRECATED';
			case E_USER_DEPRECATED: return 'E_USER_DEPRECATED';
			case E_ALL: return 'E_ALL';
			default: return $val;
		 }
	 }

	final private static function CanLogE(/* \Throwable */ $e)
	 {
		foreach(self::$no_log_e as $class => $v) if(is_a($e, $class)) return false;
		return true;
	 }

	final private static function E2Array(array $error = [])
	 {
		$error['protocol'] = self::GetProtocol('');
		$error['host'] = @$_SERVER['HTTP_HOST'];
		$error['uri'] = @$_SERVER['REQUEST_URI'];
		$error['referer'] = @$_SERVER['HTTP_REFERER'];
		$software = self::$software;
		$software[] = 'PHP '.phpversion();
		$software[] = 'MSSE '.VERSION;
		$error['software'] = implode(PHP_EOL, $software);
		return $error;
	 }

	final private static function MakeAutoloadFileName(array $a, $name)
	 {
		if(isset($a['ns_remove']))
		 {
			$fname = explode('\\', $name);
			unset($fname[$a['ns_remove']]);
			$fname = implode($a['separator'], $fname);
		 }
		elseif('' === $a['ns']) $fname = $name;
		else $fname = str_replace('\\', $a['separator'], $name);
		return $a['dir'].$a['prefix'].$fname.$a['suffix'];
	 }

	final private static function CropAutoloadFileName(array $a, $fname, $bname) { return substr($fname, 0, -strlen($bname) - strlen($a['separator'])).$a['suffix']; }

	final private static function CloseDBResults() { if(class_exists('\MaxieSystems\SQLDBResult', false)) SQLDBResult::CloseAll(); }

	final private function __construct() {}

	private static $autoload = [
		['ns' => '', 'dir' => '', 'separator' => '.', 'prefix' => '', 'suffix' => ''],
		['ns' => 'maxiesystems', 'dir' => '', 'separator' => '.', 'prefix' => '', 'suffix' => '', 'ns_remove' => 0, 'inc_ns' => true],
	];
	private static $no_log_e = [];
	private static $autoload_classes = [];
	private static $mssm_dir = null;
	private static $files = [];
	private static $error_streams = [];
	private static $disable_error_tracking = [];
	private static $compress = false;
	private static $software = [];
}
?>