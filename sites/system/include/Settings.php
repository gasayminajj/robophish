<?php
use \MaxieSystems as MS;
MS\Config::RequireFile('simpleconfig');
new MS\SimpleConfig([
	'readonly' => false,
	'index' => 'main',
	'no_file' => function(){
		$n = DOCUMENT_ROOT.'/config.ini';
		return (file_exists($n) && ($s = file_get_contents($n)) && ($p = parse_ini_string($s))) ? $p : '';
	},
]);

class Settings
{
    private $_params = null;

	public static function staticGet($key) { return MS\SimpleConfig::Instance('main')->$key; }

	public function get($param = null)
	 {
		$inst = MS\SimpleConfig::Instance('main');
		return $param ? $inst->$param : $inst;
	 }

	public function set($key, $value = null)
	 {
		if(is_array($key)) foreach($key as $k => $v) MS\SimpleConfig::Instance('main')->$k = $v;
		else MS\SimpleConfig::Instance('main')->$key = $value;
		return $this;
	 }

	public function remove($key)
	 {
		unset(MS\SimpleConfig::Instance('main')->$key);
		return $this;
	 }

	public function save()
	 {
		return $this;
	 }
}
?>