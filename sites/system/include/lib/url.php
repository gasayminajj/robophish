<?php
namespace MaxieSystems;

class EURL extends \Exception {}
	class EURLMalformed extends EURL {}

abstract class URLComponent
{
	abstract public function __toString();
	abstract public function __debugInfo();

	public function __construct(\stdClass $url, $value, \Closure $on_modify)
	 {
		$this->value = "$value";
		$this->on_modify = $on_modify;
	 }

	final protected function OnModify()
	 {
		$this->modified = true;
		$this->on_modify->__invoke();
	 }

	protected $value;
	protected $modified = false;
	protected $on_modify;
}

class URLHost extends URLComponent
{
	final public static function Encode($value)
	 {
		if(null === self::$idna) self::$idna = new \idna_convert();
		return self::$idna->encode($value);
	 }

	final public function __construct(\stdClass $url, $value, \Closure $on_modify)
	 {
		parent::__construct($url, $value, $on_modify);
		$this->is_ip = (bool)filter_var($this->value, FILTER_VALIDATE_IP);
		if($this->value && !$this->is_ip) $this->value = self::Encode($this->value);
		$url->host = $this;
	 }

	final public function IsSubdomain($domain, &$label = '')
	 {
		if($this->IsIP()) throw new \UnexpectedValueException('Host must be a domain name, IP address given');
		return URL::IsSubdomain($this->value, $domain, $label);
	 }

	final public function AddLabel(...$labels)
	 {
		if($this->IsIP()) throw new \UnexpectedValueException('Host must be a domain name, IP address given');
		$this->OnModify();
		return $this->value = URL::AddDomainLabel($this->value, ...$labels);
	 }

	final public function ToggleSubdomain($label = 'www')
	 {
		if($this->IsIP()) throw new \UnexpectedValueException('Host must be a domain name, IP address given');
		$this->OnModify();
		return URL::ToggleSubdomain($this->value, $label, $this->value);
	 }

	final public function Decode()
	 {
		if(null === $this->value_decoded)
		 {
			if($this->value)
			 {
				if(null === self::$idna) self::$idna = new \idna_convert();
				$this->value_decoded = self::$idna->decode($this->value);
			 }
			else $this->value_decoded = $this->value;
		 }
		return $this->value_decoded;
	 }

	final public function IsIP() { return $this->is_ip; }
	final public function __toString() { return $this->value; }
	final public function __debugInfo() { return ['value' => $this->value, 'value_decoded' => $this->Decode()]; }

	private $value_decoded = null;
	private $is_ip = null;

	private static $idna = null;
}

class URLPath extends URLComponent
{
	final public static function Relative2Root($path, $base_path)
	 {
		if('.' === $path[0] && preg_match('#^(\.\.?\/)+#', $path, $m))
		 {
			$i = array_filter(explode('/', $m[0]), function($v){return $v !== '.' && $v !== '';});
			$path = substr($path, strlen($m[0]) - 1);
			if($i)
			 {
				for($j = count($i); $j >= 0; --$j)
				 if(false === ($pos = strrpos($base_path, '/'))) break;
				 else $base_path = substr($base_path, 0, $pos);
				return $base_path.$path;
			 }
		 }
		else $path = "/$path";
		return false === ($pos = strrpos($base_path, '/')) ? $path : substr($base_path, 0, $pos).$path;
	 }

	final public static function RemoveDots($path)
	 {
		$path = explode('/', $path);
		foreach($path as $k => $v)
		 {
			if('.' === $v) unset($path[$k]);
			elseif('..' === $v)
			 {
				if(0 === $k) continue;
				$j = $k;
				while(--$j)
				 {
					if(isset($path[$j]) && '' !== $path[$j])
					 {
						unset($path[$j]);
						break;
					 }
				 }
				unset($path[$k]);
			 }
		 }
		return implode('/', $path);
	 }

	final public function __construct(\stdClass $url, $value, \Closure $on_modify)
	 {
		parent::__construct($url, $value, $on_modify);
		$url->path = $this;
	 }

	// Это отличается от 0 === strpos($where, $what) тем, что ищется вхождение директории. То есть, path /test/page.html и $dir /test вернут 1, а path /test22b/page.html и $dir /test вернут false. 
	// false - не начинаются и не равны.
	// если хотя бы одна из переменных (path либо $dir) равна пустой строке, то возвращается false.
	// если обе переменные (и path, и $dir) равны пустой строке, то возвращается false.
	final public function StartsWith($dir, $encode = true)
	 {
		$dir = "$dir";
		if($this->InvalidStr($dir) || $this->InvalidStr($this->value)) return false;
		if($encode) $dir = URL::Encode($dir);
		if('/' !== substr($dir, 0, 1)) $dir = "/$dir";
		$dir_len = strlen($dir);
		if('/' !== substr($dir, $dir_len - 1, 1))
		 {
			$dir .= '/';
			++$dir_len;
		 }
		if($dir === $this->value) return 0;
		$path_len = strlen($this->value);
		if($dir_len < $path_len)
		 {
			if($dir === substr($this->value, 0, $dir_len)) return substr($this->value, $dir_len - 1);
		 }
		elseif($path_len === $dir_len)
		 {
			if($dir === $this->value) return '/';
		 }
		return false;
	 }

	final public function __get($name)
	 {
		if('dirname' === $name) return dirname($this->value);
		elseif('basename' === $name) return basename($this->value);
		elseif('extension' === $name) return pathinfo($this->value, PATHINFO_EXTENSION);
		elseif('is_dir' === $name) return '' !== $this->value && '/' === substr($this->value, strlen($this->value) - 1);
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final public function __set($name, $value)
	 {
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final public function Decode() { return urldecode($this->value); }
	final public function __toString() { return $this->value; }
	final public function __debugInfo() { return ['value' => $this->value, 'value_decoded' => $this->Decode()]; }

	final private function InvalidStr($dir) { return '' === $dir || '/' === $dir; }
}

class URLQuery extends URLComponent implements \Iterator, \Countable
{
	final public function __construct(\stdClass $url, $value, \Closure $on_modify)
	 {
		parent::__construct($url, $value, $on_modify);
		if('' !== $this->value) parse_str($this->value, $this->query);
		$url->query = $this;
	 }

	final public function Current() { return current($this->query); }
	final public function Next() { next($this->query); }
	final public function Valid() { return null !== key($this->query); }
	final public function Rewind() { reset($this->query); }
	final public function Key() { return key($this->query); }

	final public function __set($name, $value)
	 {
		$this->query[$name] = $value;
		$this->OnModify();
	 }

	final public function __get($name)
	 {
		if($this->query[$name]) return $this->query[$name];
	 }

	final public function Remove(...$names)
	 {
		$modified = false;
		foreach($names as $name)
		 if(isset($this->query[$name]))
		  {
			unset($this->query[$name]);
			$modified = true;
		  }
		if($modified) $this->OnModify();
		return $this;
	 }

	final public function __unset($name)
	 {
		if(isset($this->query[$name]))
		 {
			unset($this->query[$name]);
			$this->OnModify();
		 }
	 }

	final public function __toString()
	 {
		if($this->modified)
		 {
			$this->value = http_build_query($this->query);
			$this->modified = false;
		 }
		return $this->value;
	 }

	final public function count() { return $this->query ? count(array_filter($this->query, function($v){return $v !== null;})) : 0; }
	final public function __isset($name) { return isset($this->query[$name]); }
	final public function __debugInfo() { return $this->query; }

	private $query = [];
}

class URL implements \Iterator
{
	final public static function Encode($string, $exclude = false)
	 {
		$s = ['%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D'];
		$r = ['!',   '*',   "'",   "(",   ")",   ";",   ":",   "@",   "&",   "=",   "+",   "$",   ",",   "/",   "?",   "%",   "#",   "[",   "]"];
		if($exclude)
		 {
			$exclude = array_fill_keys(str_split($exclude, 1), true);
			foreach($r as $k => $v)
			 if(isset($exclude[$v]))
			  {
				unset($r[$k]);
				unset($s[$k]);
			  }
		 }
		return str_replace($s, $r, rawurlencode("$string"));
	 }

	final public static function ObjToString(\stdClass $url)
	 {
		$s = '';
		if('' !== $url->scheme) $s .= "$url->scheme:";
		if('' !== "$url->host")
		 {
			$s .= '//';
			if('' !== $url->user)
			 {
				$s .= $url->user;
				if($url->pass) $s .= ":$url->pass";
				$s .= '@';
			 }
			$s .= $url->host;
			if($url->port) $s .= ":$url->port";
		 }
		$s .= $url->path;
		if('' !== "$url->query") $s .= "?$url->query";
		if('' !== $url->fragment) $s .= "#$url->fragment";
		return $s;
	 }

	final public static function IsSubdomain($domain_0, $domain_1, &$label = '')
	 {
		$d = [new \stdClass, new \stdClass];
		foreach($d as $i => $v)
		 {
			$v->val = "${"domain_$i"}";
			if(!$v->val) throw new \UnexpectedValueException('Domain name can not be empty');
			$v->len = strlen($v->val);
		 }
		$label = '';
		if($d[0]->val === $d[1]->val) return 0;
		if($d[0]->len === $d[1]->len) return false;
		$i = $d[0]->len < $d[1]->len;
		$d[!$i]->val = '.'.$d[!$i]->val;
		++$d[!$i]->len;
		if(0 === substr_compare($d[$i]->val, $d[!$i]->val, -$d[!$i]->len))
		 {
			$label = substr($d[$i]->val, 0, -$d[!$i]->len);
			return 1 - 2 * $i;
		 }
		return false;
	 }

	final public static function AddDomainLabel($domain, ...$labels)
	 {
		if(!$domain) throw new \UnexpectedValueException('Domain name can not be empty');
		$f = false;
		foreach($labels as $l)
		 {
			$l = self::CheckDomainLabel($l);
			if($f) continue;
			elseif(0 === strpos($domain, $l))
			 {
				$domain = substr($domain, strlen($l));
				$f = true;
			 }
		 }
		return $l.$domain;
	 }

	final public static function ToggleSubdomain($domain, $label = 'www', &$new_domain = null)
	 {
		if(!$domain) throw new \UnexpectedValueException('Domain name can not be empty');
		$label = self::CheckDomainLabel($label);
		$new_domain = ($r = 0 === strpos($domain, $label)) ? substr($domain, strlen($label)) : $label.$domain;
		return !$r;
	 }

	final private static function CheckDomainLabel($label)
	 {
		$label = "$label";
		if('' === $label) throw new \UnexpectedValueException('Domain label can not be empty');
		if('www' !== $label) $label = URLHost::Encode($label);
		return $label.'.';
	 }

	final public static function Parse($value)
	 {
		$url = new \stdClass;
		foreach(self::$components as $k => $v) $url->$k = '';
		$value = "$value";
		if('#' === $value || '' === $value) return $url;
		if($value = parse_url($value)) foreach($value as $k => $v) $url->$k = $v;
		return $url;
	 }

	final public function __construct($raw_value, URL $base_url = null)
	 {
		if($raw_value instanceof \stdClass)
		 {
			$this->raw_url = clone $raw_value;
			foreach(self::$components as $k => $v) if(!isset($this->raw_url->$k)) $this->raw_url->$k = '';
			$this->raw_value = self::ObjToString($this->raw_url);
		 }
		else
		 {
			$this->raw_url = new \stdClass;
			if(is_array($raw_value))
			 {
				foreach(self::$components as $k => $v) $this->raw_url->$k = isset($raw_value[$k]) ? $raw_value[$k] : '';
				$this->raw_value = self::ObjToString($this->raw_url);
			 }
			else
			 {
				$this->raw_value = "$raw_value";
				if('#' === $this->raw_value) $this->raw_value = '';
				$raw_value = parse_url($this->raw_value);
				if(!$raw_value) throw new EURLMalformed("Invalid value: $this->raw_value");
				foreach(self::$components as $k => $v) $this->raw_url->$k = isset($raw_value[$k]) ? $raw_value[$k] : '';
			 }
		 }
		$this->url = clone $this->raw_url;
		if('' === $this->url->scheme)
		 {
			if(null === $base_url) throw new \Exception('Scheme undefined');
			$this->url->scheme = $base_url->scheme;
			if('' === $this->url->host)
			 {
				$this->url->host = "$base_url->host";
				$this->UpdatePath($this->url, "$base_url->path");
			 }
			else $this->UpdatePath($this->url);
		 }
		elseif('' === $this->url->host)
		 {
			if('' !== $this->url->path && '/' !== $this->url->path) $this->url->path = self::Encode($this->url->path);
		 }
		else $this->UpdatePath($this->url);
		$this->on_modify = function(){$this->Modify();};
		new URLHost($this->url, $this->url->host, $this->on_modify);
		new URLPath($this->url, $this->url->path, $this->on_modify);
		new URLQuery($this->url, $this->url->query, $this->on_modify);
		$this->base_url = $base_url;
	 }

	final public function __clone()
	 {
		// $raw_value;// это всегда строка, клонировать не нужно.
		// $base_url;// это не клонируется, потому что должен оставаться общим объектом для подчинённых.
		// $modified = false;// не ясно, что делать с этим: объект только что создан, но уже модифицирован? пока что решено оставить так.
		$this->raw_url = clone $this->raw_url;
		$this->url = clone $this->url;
		foreach($this->indexes as $i) if(is_object($this->url->$i)) $this->url->$i = clone $this->url->$i;
	 }

	final public function Current()
	 {
		if(null !== key($this->indexes))
		 {
			$k = current($this->indexes);
			return (string)$this->url->$k;
		 }
	 }

	final public function Next() { next($this->indexes); }
	final public function Key() { return current($this->indexes); }
	final public function Valid() { return null !== key($this->indexes); }
	final public function Rewind() { reset($this->indexes); }

	final public function __get($name)
	 {
		if(isset(self::$components[$name])) return $this->url->$name;
		elseif('raw_value' === $name) return $this->raw_value;
		elseif('modified' === $name) return $this->modified;
		elseif('type' === $name)
		 {
			$this->IsAbsolute($type);
			return $type;
		 }
		elseif('absolute' === $name) return $this->IsAbsolute();
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final public function __set($name, $value)
	 {
		if(!isset(self::$components[$name])) throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
		if((string)$this->url->$name !== "$value") $this->Modify();
		if('query' === $name) new URLQuery($this->url, $value, $this->on_modify);
		elseif('host' === $name) new URLHost($this->url, $value, $this->on_modify);
		elseif('path' === $name) new URLPath($this->url, self::Encode($value), $this->on_modify);
		else $this->url->$name = $value;
	 }

	final public function Copy(URL $url, ...$components)
	 {
		foreach($components as $c) $this->__set($c, $url->$c);
	 }

	final public function Crop($c1, $c2 = null)
	 {
		foreach(['c1', 'c2'] as $i) if($$i && !$this->CheckComponentName($$i, $e_msg)) throw new \Exception($e_msg);
		if($c1 === $c2) throw new \Exception('Arguments should not be equal. Use $url->'.($c1 ?: '__toString()').' instead.');
		$r = new \stdClass;
		$stop = (bool)$c1;
		foreach(self::$components as $k => $v)
		 {
			if($k === $c2) $stop = true;
			$r->$k = $stop ? '' : $this->$k;
			if($k === $c1) $stop = false;
		 }
		return $this->ObjToString($r);
	 }

	final public function IsAbsolute(&$type = null)
	 {
		if('' === $this->raw_url->scheme)
		 {
			if('' === $this->raw_url->host)
			 {
				$type = '' === $this->raw_url->path || '/' !== $this->raw_url->path[0] ? 'relative' : 'root-relative';
				return false;
			 }
			else $type = 'protocol-relative';
		 }
		else $type = 'absolute';
		return true;
	 }

	final public function GetType()
	 {
		$this->IsAbsolute($type);
		return $type;
	 }

	final public function ToStdClass($callback = null)
	 {
		$r = new \stdClass;
		if($callback) foreach(self::$components as $k => $v) $r->$k = call_user_func($callback, $k, $this->$k, $this);
		else foreach(self::$components as $k => $v) $r->$k = $this->$k;
		return $r;
	 }

	final public function ToArray($callback = null)
	 {
		$r = [];
		if($callback) foreach(self::$components as $k => $v) $r[$k] = call_user_func($callback, $k, $this->$k, $this);
		else foreach(self::$components as $k => $v) $r[$k] = $this->$k;
		return $r;
	 }

	final public function GetBaseURL() { return $this->base_url; }

	final public function __toString() { return $this->ObjToString($this->url); }

	final public function __debugInfo()
	 {
		$r = $this->ToArray();
		foreach(['raw_value', 'modified'] as $k) $r[$k] = $this->$k;
		$r['base_url'] = null === $this->base_url ? null : "$this->base_url";
		$r['absolute'] = $this->IsAbsolute($type);
		$r['type'] = $type;
		return $r;
	 }

	final protected static function CheckComponentName($name, &$e_msg = null)
	 {
		$e_msg = ($r = isset(self::$components[$name])) ? null : "Invalid component: $name. Allowed components are: ".implode(', ', array_keys(self::$components)).'.';
		return $r;
	 }

	final private static function UpdatePath(\stdClass $url, $base_path = '/')
	 {
		if('' === $url->path) $url->path = $base_path;
		elseif('\\' === $url->path) $url->path = '/';
		elseif('/' !== $url->path)
		 {
			$url->path = str_replace('\\', '/', $url->path);
			do
			 {
				$url->path = str_replace('//', '/', $url->path, $count);
			 }
			while($count);
			$url->path = self::Encode($url->path);
			if('/' !== $url->path[0]) $url->path = URLPath::Relative2Root($url->path, $base_path);
			self::RemoveDotsFromURLPath($url);
		 }
	 }

	final private static function RemoveDotsFromURLPath(\stdClass $url)
	 {
		if(false !== strpos($url->path, '/../') || false !== strpos($url->path, '/./')) $url->path = URLPath::RemoveDots($url->path);
	 }

	final private function Modify()
	 {
		if(!$this->modified)
		 {
			$this->modified = true;
		 }
	 }

	private $raw_value;
	private $raw_url;
	private $url;
	private $base_url;
	private $modified = false;
	private $on_modify;
	private $indexes = ['scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment'];

	private static $components = ['scheme' => true, 'host' => true, 'port' => true, 'user' => true, 'pass' => true, 'path' => true, 'query' => true, 'fragment' => true];
}

class URLs implements \Iterator
{
	public function __construct(URL $base_url, $data = null)
	 {
		$this->base_url = $base_url;
		// !!!
		// $base_url = "$this->base_url";
		// $this->abs_urls[$base_url] = $this->raw_urls[$base_url] = $this->base_url;
		// $this->hosts[$this->base_url->host] = $this->base_url->host;
		// !!!
		if($data) foreach($data as $v) if(null !== $v) $this->Add($v);
	 }

	final public function Add($v)
	 {
		if(!isset($this->raw_urls[$v])) $this->raw_urls[$v] = new URL($v, $this->base_url);
		$abs = $this->raw_urls[$v]->__toString();
		if(!isset($this->abs_urls[$abs])) $this->abs_urls[$abs] = new URL($abs, $this->base_url);
		$host = (string)$this->raw_urls[$v]->host;
		$this->hosts[$host] = $host;
		return $this->raw_urls[$v];
	 }

	final public function __get($name)
	 {
		if(!is_string($name)) throw new \Exception($this->GetInvalidTypeMsg($name, __FUNCTION__));
		if(isset($this->raw_urls[$name])) return $this->raw_urls[$name];
		if(isset($this->abs_urls[$name])) return $this->abs_urls[$name];
		throw new \Exception("Undefined URL: $name");
	 }

	final public function __set($name, $value)
	 {
		throw new \Exception('Read only!');
	 }

	final public function Current()
	 {
		$k = key($this->raw_urls);
		if(null !== $k) return $this->raw_urls[$k];
	 }

	final public function Key() { return key($this->raw_urls); }

	final public function Next() { next($this->raw_urls); }

	final public function Rewind() { reset($this->raw_urls); }

	final public function Valid() { return null !== key($this->raw_urls); }

	final public function __isset($name)
	 {
		if(!is_string($name)) throw new \Exception($this->GetInvalidTypeMsg($name, __FUNCTION__));
		return isset($this->raw_urls[$name]);
	 }

	final public function __unset($name)
	 {
		throw new \Exception('Read only!');
	 }

	final public function __debugInfo()
	 {
		return ['base_url' => "$this->base_url"];
	 }

	final public function GetBaseURL() { return $this->base_url; }

	final public function GetHosts() { return $this->hosts; }

	final private function GetInvalidTypeMsg($name, $method) { return 'Argument 1 passed to '.get_class($this).'::'.$method.'() must be of the type string, '.Config::GetVarType($name).' given'; }

	private $abs_urls = [];
	private $raw_urls = [];
	private $hosts = [];
	private $base_url;
}
?>