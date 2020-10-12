<?php
namespace MaxieSystems\L10N;

class Exception extends \Exception {}
	class EInitialized extends Exception {}
	class ENotInitialized extends Exception {}
	class EUndefinedValue extends Exception {}

interface ILanguages extends \Iterator, \Countable
{
	public function __isset($lang);
}

class LanguageHref extends \stdClass
{
	final public function __construct($id, $title)
	 {
		$this->id = $id;
		$this->title = $title;
	 }

	final public function __toString() { return "$this->href"; }

	public $id;
	public $title;
	public $href;
	public $host;
	public $selected;
	public $class;
}

class LanguagesArray implements ILanguages
{
	final public function __construct(array $data, array $options = null)
	 {
		$options = new \MaxieSystems\Containers\Options($options, ['keys' => ['type' => 'bool', 'value' => true]]);
		$this->data = $options->keys ? $data : array_combine($data, $data);
	 }

	final public function current()
	 {
		if(null === ($k = key($this->data))) return;
		return new LanguageHref($k, current($this->data));
	 }

	final public function rewind() { reset($this->data); }
	final public function key() { return key($this->data); }
	final public function next() { next($this->data); }
	final public function valid() { return null !== key($this->data); }
	final public function count() { return count($this->data); }
	final public function __isset($lang) { return isset($this->data[$lang]); }

	private $data;
}

abstract class SelectLanguage implements \Iterator, \Countable
{
	public function __construct(ILanguages $data, array $options = null)
	 {
		$this->data = $data;
	 }

	abstract public function GetLang();

	public function __debugInfo()
	 {
		return ['current' => $this->GetLang()];
	 }

	public function rewind() { $this->data->rewind(); }
	public function current() { return $this->data->current(); }
	public function key() { return $this->data->key(); }
	public function next() { $this->data->next(); }
	public function valid() { return $this->data->valid(); }
	public function count() { return count($this->data); }

	final public function __toString() { return $this->GetLang() ?: ''; }

	protected $data;
}

class SystemUpdatesSelectLanguage extends SelectLanguage
{
	final public function GetLang() { if(!empty($_GET['lang']) && isset($this->data->{$_GET['lang']})) return $_GET['lang']; }
}

class SiteManagerSelectLanguage extends SelectLanguage
{
	final public function GetLang()
	 {
		$lang = \Registry::GetValue('mssm', 'default_language');
		if($lang && isset($this->data->$lang)) return $lang;
	 }
}

class URLSelectLanguage extends SelectLanguage
{
	final public function __construct(ILanguages $data, $host, array $options = null)
	 {
		$this->host = $host;
		$this->options = new \MaxieSystems\Containers\Options($options, [
			'default' => ['type' => 'string,len_gt0', 'value' => 'en'],
			'protocol' => ['type' => 'string', 'value' => 'https://'],
			'host' => ['type' => 'callable,null'],
			'uri' => ['type' => 'callable,bool', 'value' => false],
			'www' => ['type' => 'bool,null', 'value' => null],
			'transform' => ['type' => 'callable', 'value' => function($lang, \stdClass $row){
				$row->class = 'select_language__item';
				$row->selected = $lang === $this->lang;
				if($row->selected) $row->class .= ' _selected';
			}],
			'define_language' => ['type' => 'callable,null'],
			'on_language_undefined' => ['type' => 'callable,null'],
		]);
		if($this->options->host) $this->mk_host = 'MkHost';
		if($this->options->uri) $this->mk_link = 'MkLink_'.(true === $this->options->uri ? 'HostAndUri' : 'CreateUrl');
		parent::__construct($data, $options);
	 }

	final public function rewind()
	 {
		if(null === $this->lang) $this->GetLang();
		parent::rewind();
	 }

	final public function current()
	 {
		if(null === ($lang = $this->key())) return;
		$row = $this->data->current();
		$this->options->transform($lang, $row);
		$row->href = $this->options->protocol.$this->{$this->mk_link}($row, $lang, $this->host, $_SERVER['REQUEST_URI']);
		return $row;
	 }

	final public function GetLang()
	 {
		if(null === $this->lang)
		 {
			$this->lang = $this->options->default;
			if($this->options->define_language)
			 {
				foreach($this->data as $lang => $v) if($this->MkLink_Host($v, $lang, $this->host) === $_SERVER['HTTP_HOST'] && call_user_func($this->options->define_language, $lang, $this->host, $_SERVER['REQUEST_URI'])) return ($this->lang = $lang);
			 }
			else foreach($this->data as $lang => $v) if($this->MkLink_Host($v, $lang, $this->host) === $_SERVER['HTTP_HOST']) return ($this->lang = $lang);
			$this->options->on_language_undefined($this->lang);
		 }
		return $this->lang;
	 }

	final private function MkLink_Host(\stdClass $row, $lang, $host) { return $row->host = $this->{$this->mk_host}($row, $lang, $host); }

	final private function MkHost_Default(\stdClass $row, $lang, $host)
	 {
		$h = "$lang.";
		if($this->options->default === $lang)
		 {
			if(false === $this->options->www) $h = '';
			elseif(true === $this->options->www) $h = 'www.';
		 }
		return $h.$host;
	 }

	final private function MkHost(\stdClass $row, $lang, $host)
	 {
		$row->host = $this->MkHost_Default($row, $lang, $host);
		if($h = $this->options->host($lang, $host)) $row->host = $h;
		return $row->host;
	 }

	final private function MkLink_HostAndUri(\stdClass $row, $lang, $host, $uri) { return $this->MkLink_Host($row, $lang, $host).$uri; }

	final private function MkLink_CreateUrl(\stdClass $row, $lang, $host, $uri)
	 {
		$h = $this->MkLink_Host($row, $lang, $host);
		return $h.call_user_func($this->options->uri, $lang, $h, $uri, $this->lang, $this->options->default);
	 }

	private $lang = null;
	private $host;
	private $options;
	private $mk_link = 'MkLink_Host';
	private $mk_host = 'MkHost_Default';
}

class URLSelectLanguageConfig
{
	final public function __construct(array $options = null)
	 {
		$this->options = new \MaxieSystems\Containers\Options($options, ['default_url' => ['type' => 'bool', 'value' => false]]);
	 }

	final public function GetOptions(array $o = null)
	 {
		if(null === $o) $o = [];
		$o['uri'] = [$this, 'Callback_uri'];
		$o['on_language_undefined'] = [$this, 'Callback_on_language_undefined'];
		$o['host'] = [$this, 'Callback_host'];
		$o['define_language'] = [$this, 'Callback_define_language'];
		return $o;
	 }

	final public function Callback_uri($lang, $host, $uri, $curr_lang, $default_lang)
	 {
		if($this->lang_undefined)
		 {
			if(!$this->options->default_url && $lang === $default_lang) return $uri;
		 }
		else
		 {
			$path = parse_url($uri, PHP_URL_PATH);
			if(false === $path) return;
			$base = "/$curr_lang/";
			$path = rawurldecode($uri);
			if($base === $path) $uri = '/';
			else
			 {
				$length = iconv_strlen($path, $this->charset);
				$start = iconv_strlen($base, $this->charset);
				if($base === iconv_substr($path, 0, $start, $this->charset))
				 {
					--$start;
					$uri = iconv_substr($path, $start, $length - $start, $this->charset);
				 }
			 }
			if(!$this->options->default_url && $lang === $default_lang) return $uri;
		 }
		return "/$lang$uri";
	 }

	final public function Callback_on_language_undefined($lang_default)
	 {
		$this->lang_undefined = $this->options->default_url;
	 }

	final public function Callback_host($lang, $host)
	 {
		return $host;
	 }

	final public function Callback_define_language($lang, $host, $uri)
	 {
		$path = parse_url($uri, PHP_URL_PATH);
		if(false === $path) return;
		$base = "/$lang/";
		$path = rawurldecode($path);
		if($base === $path) return true;
		$length = iconv_strlen($path, $this->charset);
		$start = iconv_strlen($base, $this->charset);
		return $base === iconv_substr($path, 0, $start, $this->charset);
	 }

	private $lang_undefined;
	private $charset = 'UTF-8';
	private $options;
}

abstract class LanguageItems
{
	abstract public function __call($name, array $args);
	abstract public function __get($name);
	abstract public function __debugInfo();

	final public function __set($name, $value) { throw new \Exception('All properties are read only!'); }

	protected $strings = null;
	protected $closures = null;
	protected $all_items = null;
}

class ProxyData extends LanguageItems
{
	final public function __construct($dir, Storage $owner)
	 {
		$this->dir = $dir;
		$this->owner = $owner;
	 }

	final public function __call($name, array $args) { return $this->__get_item($name, $args); }
	final public function __get($name) { return $this->__get_item($name); }
	final public function __debugInfo() { return []; }
	final public function __toArray() { return $this->owner->__toArray($this->dir); }

	final private function __get_item($name, array &$args = null)
	 {
		if('' !== $this->dir) $name = "$this->dir/$name";
		return null === $args ? $this->owner->__get($name) : $this->owner->__call($name, $args);
	 }

	private $dir;
	private $owner;
}

class Storage extends LanguageItems
{
	final public function __construct($lang, array $options = null)
	 {
		$options = new \MaxieSystems\Containers\Options($options, self::$meta['options']);
		if(isset(self::$instances[$options->index])) throw new EInitialized("Instance of ".get_class(self::$instances[$options->index])." with index [$options->index] already exists!");
		if($lang instanceof SelectLanguage) $this->select = $lang;
		$this->lang = ["$lang" ?: $options->primary, $options->primary];
		$this->root_dir = $options->root.$options->dir;
		$this->strings = new \stdClass();
		$this->closures = new \stdClass();
		self::$instances[$options->index] = $this;
	 }

	final public static function Instance($index = 0)
	 {
		if(empty(self::$instances[$index])) throw new ENotInitialized(get_called_class().": instance with index [$index] is not initialized! Call constructor explicitly.");
		return self::$instances[$index];
	 }

	final public static function Exists($index, Storage &$inst = null)
	 {
		$inst = null;
		if(null === $index) return count(self::$instances) > 0;
		elseif(isset(self::$instances[$index]))
		 {
			$inst = self::$instances[$index];
			return true;
		 }
		else return false;
	 }

	final public function __invoke($dir = '')
	 {
		$dir = "$dir";
		if('' !== $dir && '/' !== iconv_substr($dir, 0, 1, $this->charset)) $dir = "/$dir";
		if(!isset($this->proxy_data[$dir])) $this->InitProxyDataByDir($dir);
		return $this->proxy_data[$dir];
	 }

	final public function __call($name, array $args)
	 {
		if(isset($this->closures->$name)) return $this->closures->$name->__invoke(...$args);
		if(isset($this->strings->$name)) return $this->strings->$name;
		return $this->Init($this->InitProxyDataByName($name)) ? $this->__call($name, $args) : '';
	 }

	final public function __get($name)
	 {
		if(isset($this->strings->$name)) return $this->strings->$name;
		// if(isset($this->closures->$name)) return $this->closures->$name->__invoke();// здесь выбрасывать исключение или пропускать в зависимости от отладочного режима?
		return $this->Init($this->InitProxyDataByName($name)) ? $this->__get($name) : '';
		// throw new EUndefinedValue("Undefined value for lang item `$name`!");
	 }

	final public function GetLang(SelectLanguage &$select = null)
	 {
		$select = $this->select;
		return $this->lang[0];
	 }

	final public function GetSelect() { return $this->select; }

	final public function __debugInfo() { return []; }

	final public function __toArray($dir = '')
	 {
		$dir = "$dir";
		if('' !== $dir && '/' !== iconv_substr($dir, 0, 1, $this->charset)) $dir = "/$dir";
		$this->Init($dir);
		$this->Init($dir);
		return $this->all_items[$dir];
	 }

	protected function Load($dir, $lang, &$fname = null)
	 {
		$fname = "$this->root_dir$dir/$lang.php";
		$items = (require $fname);
		return ($items && $items !== 1) ? $items : [];
	 }

	final private function Init($dir)
	 {
		foreach($this->lang as $i => $lang)
		 if(empty($this->loaded[$dir][$lang]))
		  {
			$data = $this->Load($dir, $lang, $fname);
			if(!is_array($data) && !($data instanceof Iterator)) throw new \Exception("Language storage `$fname` must return array or Iterator, ".(is_object($data) ? 'instance of '.get_class($data) : gettype($data)).' given.');
			if(!isset($this->all_items[$dir])) $this->all_items[$dir] = [];
			$init_property = function($n, $k, $s) use($dir){
				$p = is_string($s) ? 'strings' : 'closures';
				if(!isset($this->$p->$n)) $this->$p->$n = $s;
				if(!isset($this->all_items[$dir][$k])) $this->all_items[$dir][$k] = $s;
			};
			if('' === $dir) foreach($data as $k => $s) $init_property($k, $k, $s);
			else foreach($data as $k => $s) $init_property("$dir/$k", $k, $s);
			return ($this->loaded[$dir][$lang] = true);
		  }
	 }

	final private function InitProxyDataByName($name)
	 {
		if(self::DELIMITER === $name[0])
		 {
			$pos = strrpos($name, self::DELIMITER);
			if(0 === $pos) throw new \Exception("Invalid value '$name' for attribute lang[name]: must have at least two delimiters!");
			$dir = substr($name, 0, $pos);
			if(!isset($this->proxy_data[$dir])) $this->proxy_data[$dir] = new ProxyData($dir, $this);
		 }
		else $dir = '';
		return $dir;
	 }

	final private function InitProxyDataByDir($dir)
	 {
		$this->Init($dir);
		return $this->proxy_data[$dir] = new ProxyData($dir, $this);
	 }

	private $lang;
	private $proxy_data = [];
	private $loaded = [];
	private $root_dir;
	private $select = null;
	private $charset = 'UTF-8';

	private static $instances = [];
	private static $meta = [
		'options' => ['index' => ['type' => 'string,int', 'value' => 0], 'primary' => ['type' => 'string,len_gt0', 'value' => 'en'], 'dir' => ['type' => 'string,len_gt0', 'value' => '/include/lang'], 'root' => ['type' => 'string,len_gt0', 'value' => DOCUMENT_ROOT]],
	];

	const DELIMITER = '/';
}

// class Exporter// extends Iterator, JsonSerializable
// {
	// public function ToArray($callback = false, ...$args);

	// final public static function CreateCallback($callback, $caller)
	 // {
		// if(!is_callable($callback)) throw new \Exception(get_called_class()."::$caller() requires argument 1 to be a valid callback");
		// if(is_object($callback)) return $callback;
		// if(is_array($callback))
		 // {
			// list($callback, $method) = $callback;
			// if(is_object($callback)) return function($k, &$v, $is_callable, ...$args) use($callback, $method) { return $callback->{$method}($k, $v, $is_callable, ...$args); };
		 // }
		// elseif(false !== strpos($callback, '::')) list($callback, $method) = explode('::', $callback);
		// else return $callback;
		// return function($k, &$v, $is_callable, ...$args) use($callback, $method) { return $callback::$method($k, $v, $is_callable, ...$args); };
	 // }

	// public function ToArray($callback = false, ...$args)
	 // {
		// $r = [];
		// if($callback)
		 // {
			// $callback = $this->CreateCallback($callback, __FUNCTION__);
			// foreach($this->keys as $k => $v)
			 // {
				// $name = $this->MkName($k);
				// if(isset($this->strings->$name))
				 // {
					// $v = $this->strings->$name;
					// $c = false;
				 // }
				// elseif(isset($this->closures->$name))
				 // {
					// $v = $this->closures->$name;
					// $c = true;
				 // }
				// else throw new EUndefinedValue("Undefined value for lang item `$name`!");
				// if(false === $callback($k, $v, $c, ...$args)) continue;
				// $r[$k] = $v;
			 // }
		 // }
		// else foreach($this as $k => $v) $r[$k] = $v;
		// return $r;
	 // }

	// final public function jsonSerialize()
	 // {
		// return $this->ToArray([$this, 'OnJSONSerialize']);
	 // }

	// public function rewind() { reset($this->keys); }

	// final public function current()
	 // {
		// $name = $this->MkName(key($this->keys));
		// if(isset($this->strings->$name)) return $this->strings->$name;
		// if(isset($this->closures->$name)) return $this->closures->$name;
	 // }

	// final public function key() { return key($this->keys); }
	// final public function next() { next($this->keys); }
	// final public function valid() { return null !== key($this->keys); }

	// final public function OnJSONSerialize($k, &$v, $c)
	 // {
		// if($c) $v = new stdClass;
	 // }

	// protected function MkName($name) { return $name; }
	// final public function GetJS()
	 // {
		// $r = [];
		// foreach(self::$export as $k => $v)
		 // {
			// $v = null;
			// if($this->DirExists($k, $v)) $r[$k] = $v->ToArray([$this, 'OnJSONSerialize']);
		 // }
		// return json_encode(['current' => self::GetLang($l), 'primary' => $l, 'items' => $r]);
	 // }

	// final public static function Export(...$names)
	 // {
		// foreach($names as $name)
		 // {
			// if(false === $name) unset(self::$export['']);
			// else self::$export[$name] = true;
		 // }
		// return self::Instance();
	 // }

	// final public function DirExists($dir, LanguageItems &$data = null)
	 // {
		// $dir = "$dir";
		// if($r = ('' === $dir)) $data = $this;
		// else
		 // {
			// $dir = "/$dir";
			// if($r = isset($this->proxy_data[$dir])) $data = $this->proxy_data[$dir];
			// elseif($r = $this->StorageExists($dir)) $data = $this->InitProxyDataByDir($dir);
			// else $data = null;
		 // }
		// return $r;
	 // }

	// final public function ToArray($callback = false, ...$args)
	 // {
		// if($callback && !isset($this->all_keys[''])) $this->Init('');
		// return parent::ToArray($callback, ...$args);
	 // }

	// final public function rewind()
	 // {
		// if(!isset($this->all_keys[''])) $this->Init('');
		// parent::rewind();
	 // }

	// protected function StorageExists($dir) { return file_exists("$this->root_dir$dir"); }

	// private static $export = ['' => true];
// }
?>