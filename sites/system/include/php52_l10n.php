<?php
class DollySitesPHP52L10N
{
	public static function Instance() { return self::$instance; }

	public function __construct($lang, $default, $dir)
	 {
		if(null !== self::$instance) throw new Exception();
		self::$instance = $this;
		$this->lang = array($lang, $default);
		$this->dir = $dir;
		$this->data = (require "$this->dir/{$this->lang[1]}.php");
		if($this->lang[0] !== $this->lang[1])
		 {
			$l = (require "$this->dir/{$this->lang[0]}.php");
			$this->data = array_merge($this->data, $l);
		 }
	 }

	public function __get($name)
	 {
		if(isset($this->data[$name])) return $this->data[$name];
		throw new Exception("Undefined lang item: $name");
	 }

	public function __call($name, array $arguments)
	 {
		if(isset($this->data[$name]) && is_callable($this->data[$name])) return call_user_func_array($this->data[$name], $arguments);
		throw new Exception("Undefined lang item: $name");
	 }

	public function __set($name, $value) { throw new Exception('All properties are read only!'); }

	private $dir;
	private $lang;
	private $data = array();
	private static $instance = null;
}
$lang = DollyConfig::Get('language');
new DollySitesPHP52L10N($lang ? $lang : 'ru', 'ru', DOCUMENT_ROOT.'/languages/php52');
function php52_l10n($dir = '')
{
	return DollySitesPHP52L10N::Instance();
}
?>