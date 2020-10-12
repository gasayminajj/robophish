<?php
namespace MaxieSystems;

class FileSizeProxy
{
	final public function __construct($field = null, $alias = null, $root = null, $precision = 2)
	 {
		$this->field = $field ?: 'href';
		$this->alias = $alias ?: 'size';
		$this->root = $root ?: DOCUMENT_ROOT;
		$this->precision = $precision;
	 }

	final public function __invoke(\stdClass $row)
	 {
		$size = File::GetSize($this->root.$row->{$this->field});
		$row->{$this->alias.'_value'} = $size->value;
		$row->{$this->alias.'_unit'} = $size->unit;
		$row->{$this->alias} = $size->__toString();
	 }

	private $field;
	private $alias;
	private $root;
	private $precision;
}

class FileSize
{
	final public function __construct($value, $precision = 2)
	 {
		static $units = ['', 'K', 'M', 'G', 'T', 'P'];
		$this->value = $this->v = $value;
		foreach($units as $this->unit)
		 {
			if($this->value >= 1024) $this->value /= 1024;
			else break;
		 }
		$this->value = round($this->value, $precision);
		$this->size_units = ['' => 'Б', 'M' => 'М', 'K' => 'К', 'G' => 'Г', 'T' => 'Т', 'P' => 'П'];
		$this->s = "$this->value ".$this->GetSizeUnit($this->unit);
		if($this->unit) $this->s .= $this->GetSizeUnit('');
	 }

	final public function __get($name)
	 {
		if('value' === $name) return $this->value;
		if('unit' === $name) return $this->unit;
	 }

	final public function __toString() { return $this->s; }
	final public function __debugInfo() { return ['value' => $this->value, 'unit' => $this->unit]; }

	final protected function GetSizeUnit($unit) { return isset($this->size_units[$unit]) ? $this->size_units[$unit] : $unit; }

	private $value;
	private $unit;
	private $s;
	private $v;
	private $size_units = ['' => 'B', 'M' => 'M', 'K' => 'K', 'G' => 'G', 'T' => 'T', 'P' => 'P'];
}

abstract class File implements \Iterator
{
	final public static function GetExt($file_name) { return '' !== ($v = pathinfo($file_name, PATHINFO_EXTENSION)) ? strtolower($v) : ''; }

	final public static function GetMaxUploads() { return ($val = (int)ini_get('max_file_uploads')) ? $val : 10; }

	final public static function CopyDir($src, $dest, array $o = null)
	 {
		$o = new Containers\Options($o, self::$meta['copy_dir']);
		if(false === ($s = realpath($src))) throw new \Exception("No such file or directory: $src");
		self::copy_dir($s, $dest, $o);
	 }

	final public static function rmdir($dir, $rm_this = true)
	 {
		if(false !== ($dir = realpath($dir)))
		 {
			self::remove_dir($dir);
			if($rm_this) rmdir($dir);
		 }
	 }

	final public static function GetDirSize($dir, \stdClass &$n = null)
	 {
		if(null === $n) $n = new \stdClass();
		$n->files = 0;
		$n->total = 0;
		return file_exists($dir) ? self::get_dir_size($dir, $n) : false;
	 }

	final public static function GetPerms($file_name)
	 {
		$perms = fileperms($file_name);
		$r = new \stdClass();
		$r->mode = $perms;
		$r->octal = base_convert($perms, 10, 8);
		$r->string = self::ModeToString($perms);
		$r->owner = ($perms & (0x0100 | 0x0080 | 0x0040)) >> 6;
		$r->group = ($perms & (0x0020 | 0x0010 | 0x0008)) >> 3;
		$r->others = $perms & (0x0004 | 0x0002 | 0x0001);
		return $r;
	 }

	final public static function GetOwner($file_name)
	 {
		$r = new \stdClass();
		$r->id = fileowner($file_name);
		$r->name = null;
		if(function_exists('posix_getpwuid')) $r->name = posix_getpwuid($r->id)['name'];
		return $r;
	 }

	final public static function GetGroup($file_name)
	 {
		$r = new \stdClass();
		$r->id = filegroup($file_name);
		$r->name = null;
		if(function_exists('posix_getgrgid')) $r->name = posix_getgrgid($r->id)['name'];
		return $r;
	 }

// 0 - запрещено всё
// 1 - разрешено выполнение
// 2 - разрешена запись
// 3 - разрешены запись и выполнение
// 4 - разрешено чтение
// 5 - разрешены чтение и выполнение
// 6 - разрешены чтение и запись
// 7 - разрешено всё
	final public static function ModeToString($perms)
	 {
		switch($perms & 0xF000)
		 {
			case 0xC000: $s = 's'; break;// socket
			case 0xA000: $s = 'l'; break;// symbolic link
			case 0x8000: $s = 'r'; break;// regular
			case 0x6000: $s = 'b'; break;// block special
			case 0x4000: $s = 'd'; break;// directory
			case 0x2000: $s = 'c'; break;// character special
			case 0x1000: $s = 'p'; break;// FIFO pipe
			default: $s = 'u';			// unknown
		 }
		// Owner
		$s .= ($perms & 0x0100) ? 'r' : '-';
		$s .= ($perms & 0x0080) ? 'w' : '-';
		$s .= ($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-');
		// Group
		$s .= ($perms & 0x0020) ? 'r' : '-';
		$s .= ($perms & 0x0010) ? 'w' : '-';
		$s .= ($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-');
		// World
		$s .= ($perms & 0x0004) ? 'r' : '-';
		$s .= ($perms & 0x0002) ? 'w' : '-';
		$s .= ($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-');
		return $s;
	 }

	final public static function SizeToBytes($val)
	 {
		if(empty($val)) return 0;
		if(is_numeric($val)) return (int)$val;
		if(!preg_match('/^([0-9]+(\.[0-9]+)?)[\s]*([a-z]+)$/i', $val, $m)) return 0;
		$val = (float)$m[1];
		switch(strtolower($m[3]))
		 {
			case 'p':
			case 'pb': $val *= 1024;
			case 't':
			case 'tb': $val *= 1024;
			case 'g':
			case 'gb': $val *= 1024;
			case 'm':
			case 'mb': $val *= 1024;
			case 'k':
			case 'kb': $val *= 1024;
		 }
		return (int)$val;
	 }

	final public static function GetUploadMaxSize($precision = 2)
	 {
		static $val = null;
		if(null === $val) $val = min(self::SizeToBytes(ini_get('upload_max_filesize')), self::SizeToBytes(ini_get('post_max_size')));
		return new FileSize($val, $precision);
	 }

	final public static function GetSize($file_name, $precision = 2)
	 {
		if($file_name = realpath($file_name)) return new FileSize(filesize($file_name), $precision);
	 }

	// final public static function UnlinkIgnoringExt($fpath)
	 // {
		// if($ext = self::GetExt($fpath)) $fpath = substr_replace($fpath, '.*', -strlen($ext) - 1);
		// else $fpath .= '.*';
		// if($files = glob($fpath)) foreach($files as $name) unlink($name);
		// $fpath = substr($fpath, 0, -2);
		// if(file_exists($fpath) && is_file($fpath)) unlink($fpath);
	 // }

	final public static function NormalizePath($path)
	 {
		$path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
		$path = preg_replace('/\\'.DIRECTORY_SEPARATOR.'+/', DIRECTORY_SEPARATOR, $path);// Combine multiple slashes into a single slash
		$test = '';
		$parts = [];
		foreach(explode(DIRECTORY_SEPARATOR, $path) as $segment)
		 {
			if($segment === '.') continue;
			$test = array_pop($parts);
			if(null === $test) $parts[] = $segment;
			elseif($segment === '..')
			 {
				if($test === '..') $parts[] = $test;
				if($test === '..' || $test === '') $parts[] = $segment;
			 }
			else
			 {
				$parts[] = $test;
				$parts[] = $segment;
			 }
		 }
		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	final private static function copy_dir($src, $dest, Containers\Options $o)
	 {
		if($fp = opendir($src))
		 {
			if(!file_exists($dest))
			 {
				mkdir($dest);
				chmod($dest, fileperms($src));
			 }
			while($f = readdir($fp))
			 {
				if($f === '.' || $f === '..') continue;
				$src_path = $src.DIRECTORY_SEPARATOR.$f;
				$dest_path = $dest.DIRECTORY_SEPARATOR.$f;
				if(is_dir($src_path) && !is_link($src_path)) self::copy_dir($src_path, $dest_path, $o);
				else
				 {
					copy($src_path, $dest_path);
					chmod($dest_path, fileperms($src_path));
				 }
			 }
			closedir($fp);
		 }
	 }

	final private static function remove_dir($dir)
	 {
		if($fp = opendir($dir))
		 {
			while($f = readdir($fp))
			 {
				if($f === '.' || $f === '..') continue;
				$file = $dir.DIRECTORY_SEPARATOR.$f;
				if(is_dir($file) && !is_link($file))
				 {
					self::remove_dir($file);
					rmdir($file);
				 }
				else unlink($file);
			 }
			closedir($fp);
		 }
	 }

	final private static function get_dir_size($dir, \stdClass $n)
	 {
		$size = 0;
        if($fp = opendir($dir))
		 {
			while($f = readdir($fp))
			 {
				if($f === '.' || $f === '..') continue;
				$file = $dir.DIRECTORY_SEPARATOR.$f;
				if(is_dir($file) && !is_link($file)) $size += self::get_dir_size($file, $n);
				else
				 {
					$size += filesize($file);
					++$n->files;
				 }
				++$n->total;
			 }
			closedir($fp);
		 }
		return $size;
	 }

	private static $meta = [
		'copy_dir' => [
			'replacements' => ['type' => 'array', 'value' => []],
		],
	];
}
?>