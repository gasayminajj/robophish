<?php
namespace MaxieSystems;

class ESimpleConfig extends \Exception {}
	class ESimpleConfigFileCorrupted extends ESimpleConfig {}
	class ESimpleConfigSave extends ESimpleConfig {}

class SimpleConfig implements \Iterator, \Countable
{
	use TOptions;

	final public static function Instance($index)
	 {
		if(isset(self::$f_aliases[$index])) return self::$f_aliases[$index];
		throw new \UnexpectedValueException(__METHOD__ .'(). Invalid index: '.Config::GetVarType($index));
	 }

	final public function __construct(array $options = null)
	 {
		$this->AddOptionsMeta([
			'file' => ['type' => 'string,len_gt0', 'value' => 'config.php', 'on_init' => function(&$v){
				$v = "$v";
				if('' === $v || '/' === $v || '\\' === $v) return;
				$ch = substr($v, 0, 1);
				if('/' !== $ch && '\\' !== $ch) $v = DIRECTORY_SEPARATOR.$v;
			}],
			'root' => ['type' => 'string', 'value' => DOCUMENT_ROOT],
			'readonly' => ['type' => 'bool', 'value' => true],
			'index' => ['type' => 'string,false', 'value' => '', 'set' => 1],
			'no_file' => ['type' => 'callable,bool', 'value' => false],
		]);
		$this->SetOptionsData($options);
		$path = $this->GetOption('root').$this->GetOption('file');
		$this->name = realpath($path);
		if(false === $this->name)
		 {
			if($opt = $this->GetOption('no_file'))
			 {
				$this->name = File::NormalizePath($path);
				if(true === $opt) $s = '';
				else
				 {
					$s = call_user_func($opt);
					if(is_array($s)) $s = SimpleConfigData::DataToCode($s);
					elseif(!is_string($s)) throw new \UnexpectedValueException('Invalid return value: '.Config::GetVarType($s));
				 }
				$h = fopen($this->name, 'a');
				fwrite($h, $s);
				fclose($h);
			 }
			else throw new ESimpleConfigFileCorrupted("No such file or directory: '$path'");
		 }
		elseif(!is_file($this->name)) throw new ESimpleConfigFileCorrupted("Config file '$this->name' is invalid");
		$index = $this->GetOption('index');
		$this->SetOption('index', '' === $index ? basename($this->name) : $index);
		$index = $this->GetOption('index');
		if(false !== $index)
		 {
			if(isset(self::$f_aliases[$index])) throw new \UnexpectedValueException(__METHOD__ ."(). Config with index [$index] exists");
			else self::$f_aliases[$index] = $this;
		 }
		if(!isset(self::$files[$this->name])) self::$files[$this->name] = new SimpleConfigData($this->name);
		self::$files[$this->name]->AddInstance($this);
	 }

	final public function current() { return self::$files[$this->name]->current(); }

	final public function next() { self::$files[$this->name]->next(); }

	final public function key() { return self::$files[$this->name]->key(); }

	final public function valid() { return self::$files[$this->name]->valid(); }

	final public function rewind() { self::$files[$this->name]->rewind(); }

	final public function __isset($name) { return self::$files[$this->name]->__isset($name); }

	final public function __unset($name)
	 {
		$this->CheckReadonly();
		self::$files[$this->name]->__unset($name);
	 }

	final public function __get($name) { return self::$files[$this->name]->__get($name); }

	final public function __set($name, $value)
	 {
		$this->CheckReadonly();
		if(is_scalar($value) || is_array($value)) self::$files[$this->name]->$name = $value;
		elseif(null === $value) $this->__unset($name);
		else throw new \UnexpectedValueException('Invalid value: '.Config::GetVarType($value));
	 }

	final public function count() { return count(self::$files[$this->name]); }

	final public function __debugInfo() { return ['name' => $this->name]; }

	final public function __clone()
	 {
		throw new \Exception('Can not clone instance of '.get_class($this));
	 }

	final public function Reload() { self::$files[$this->name]->Reload(); }

	final public function GetName() { return $this->name; }

	final public function Clear()
	 {
		$this->CheckReadonly();
		self::$files[$this->name]->Clear();
	 }

	final public function __destruct()
	 {
		if(isset(self::$files[$this->name])) self::$files[$this->name]->AutoSave($this);
	 }

	final protected function CheckReadonly() { if($this->GetOption('readonly')) throw new \Exception('Object is readonly! Instance of '.get_class($this).": '{$this->GetName()}'."); }

	private $name;

	private static $files = [];
	private static $f_aliases = [];
}

class SimpleConfigData implements \Iterator, \Countable
{
	final public static function DataToCode(array $d) { return '<?php'.PHP_EOL.'return '.var_export($d, true).';'.PHP_EOL.'?>'; }

	final public function __construct($name)
	 {
		$this->name = $name;
	 }

	final public function GetName() { return $this->name; }

	final public function AddInstance(SimpleConfig $inst)
	 {
		$this->instances[] = $inst;
	 }

	final public function count()
	 {
		if(null === $this->data) $this->Reload();
		$i = 0;
		foreach($this->data as $k => $v) if($this->__isset($k)) ++$i;
		return $i;
	 }

	final public function __get($name)
	 {
		if(null === $this->data) $this->Reload();
		if(array_key_exists($name, $this->__data))
		 {
			if(null !== $this->__data[$name]) return $this->__data[$name];
		 }
		elseif(array_key_exists($name, $this->data)) return $this->data[$name];
	 }

	final public function __set($name, $value)
	 {
		if(null === $this->data) $this->Reload();
		$this->__data[$name] = $value;
		$this->changed = true;
	 }

	final public function __isset($name)
	 {
		if(array_key_exists($name, $this->__data)) return null !== $this->__data[$name];
		elseif(null === $this->data) $this->Reload();
		return isset($this->data[$name]);
	 }

	final public function __unset($name)
	 {
		$this->__data[$name] = null;
		$this->changed = true;
	 }

	final public function current()
	 {
		if(null === ($k = key($this->data))) return;
		if(isset($this->__data[$k])) return $this->__data[$k];
		elseif(isset($this->data[$k])) return $this->data[$k];
	 }

	final public function next()
	 {
		next($this->data);
	 }

	final public function key()
	 {
		return key($this->data);
	 }

	final public function valid()
	 {
		do
		 {
			$k = key($this->data);
			if(null === $k) return false;
			if(array_key_exists($k, $this->__data) ? null !== $this->__data[$k] : isset($this->data[$k])) return true;
			next($this->data);
		 }
		while(1);
	 }

	final public function rewind()
	 {
		if(null === $this->data) $this->Reload();
		reset($this->data);
	 }

	final public function Clear()
	 {
		foreach($this->data as $k => $v) $this->__data[$k] = null;
		$this->changed = true;
	 }

	final public function AutoSave(SimpleConfig $config)
	 {
		foreach($this->instances as $k => $v)
		 if($v === $config)
		  {
			unset($this->instances[$k]);
			break;
		  }
		if(!$this->instances && $this->changed) $this->Save();
	 }

	final public function Save()
	 {
		$check_fres = function($r){
			if(false === $r)
			 {
				$message = ($error = error_get_last()) ? $error['message'] : '';
				throw new ESimpleConfigSave($message);
			 }
		};
		$changed = false;
		clearstatcache();
		ignore_user_abort(true);
		$h = fopen($this->name, 'c');
		$check_fres($h);
		$check_fres(flock($h, LOCK_EX));
		$t = $this->Load();
		foreach($this->__data as $k => $v)
		 {
			if(null === $v)
			 {
				if(isset($t[$k]))
				 {
					unset($t[$k]);
					$changed = true;
				 }
			 }
			elseif(isset($this->data[$k]) && $v === $this->data[$k]) continue;
			else
			 {
				$changed = true;
				$t[$k] = $v;
			 }
		 }
		if($changed)
		 {
			$code = $this->DataToCode($t);
			$check_fres(rewind($h));
			$check_fres(fflush($h));
			$length = strlen($code);
			$check_fres(ftruncate($h, $length));
			$written = $this->FWriteStream($h, $code);
			// ($written === $length);
		 }
		flock($h, LOCK_UN);
		fclose($h);
		$this->changed = false;
	 }

	final public function Reload() { $this->data = $this->Load(); }

	final public function __clone()
	 {
		throw new \Exception('Can not clone instance of '.get_class($this));
	 }

	final private function Load()
	 {
		$data = (require $this->name);
		return is_array($data) ? $data : [];
	 }

	final private function FWriteStream($fp, $string)
	 {
		$len = strlen($string);
		for($written = 0; $written < $len; $written += $n)
		 {
			$n = fwrite($fp, substr($string, $written));
			if(false === $n) return $written;
		 }
		return $written;
	 }

	private $name;
	private $data = null;
	private $__data = [];
	private $instances = [];
	private $changed = false;
}
?>