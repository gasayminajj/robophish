<?php
namespace MaxieSystems;

trait TOptions
{
	final public function GetOption($name, &$is_default = null)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		$is_default = $this->ms_options->PropertyIsDefault($name);
		return $this->ms_options->$name;
	 }

	final public function GetOptionType($name)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		if($this->ms_options->PropertyExists($name, $type)) return $type;
		else throw new \Exception(get_class($this).'::'. __FUNCTION__ ."(). Undefined option `$name`!");
	 }

	final public function OptionExists($name, &$value = null)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		$value = ($ex = $this->ms_options->PropertyExists($name)) ? $this->ms_options->$name : null;
		return $ex;
	 }

	final public function OptionIsSet($name, &$value = null)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		$value = ($ex = isset($this->ms_options->$name)) ? $this->ms_options->$name : null;
		return $ex;
	 }

	final public function GetOptions(...$names)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		if($names)
		 {
			$r = [];
			foreach($names as $n) $r[$n] = $this->ms_options->$n;
			return $r;
		 }
		else return $this->ms_options->ToArray();
	 }

	final public function CopyOptions(array &$dest = null, ...$names)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		if(null === $dest) $dest = [];
		if($names) foreach($names as $n) $dest[$n] = $this->ms_options->$n;
		else foreach($this->ms_options as $n => $v) $dest[$n] = $v;
		return $dest;
	 }

	final public function SetOption($name, $value)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		$this->ms_options->$name = $value;
		return $this;
	 }

	final public function Options2Fields(stdClass $dest, ...$names)
	 {
		foreach($names as $name) if($this->OptionExists($name, $value)) $dest->$name = $value;
		return $this;
	 }

	final protected function SetOptionsData(array $data = null)
	 {
		if(null === $this->ms_options_data) $this->ms_options_data = null === $data ? [] : $data;
		else throw new \Exception(get_class($this).'::'. __FUNCTION__ .'(). Can not rewrite options: already initialized!');
		return $this;
	 }

	final protected function AddOptionsMeta(array $meta)
	 {
		foreach($meta as $k => $v)
		 if(isset($this->ms_options_meta[$k])) throw new \Exception(get_class($this).'::'. __FUNCTION__ ."(). Option `$k` exists!");
		 else $this->ms_options_meta[$k] = $v;
		return $this;
	 }

	final protected function ChangeOptionsMeta($name, array $meta)
	 {
		if(null === $this->ms_options)
		 {
			if(!isset($this->ms_options_meta_changes[$name])) $this->ms_options_meta_changes[$name] = $meta;
			return $this;
		 }
		throw new \Exception(get_class($this).'::'. __FUNCTION__ ."(). Can not rewrite meta data for option `$name`: options already initialized!");
	 }

	private function GetOption_Property($name)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		if(isset($this->ms_options_meta[$name]) && !empty($this->ms_options_meta[$name]['property'])) return $this->ms_options->$name;
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	private function SetOption_Property($name, $value)
	 {
		if(null === $this->ms_options) $this->InitializeOptionsObjects();
		if($this->ms_options->PropertyIsEditable($name)) $this->ms_options->$name = $value;
		else throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final private function InitializeOptionsObjects()
	 {
		if(null === $this->ms_options)
		 {
			foreach($this->ms_options_meta_changes as $name => $meta)
			 {
				if(isset($this->ms_options_meta[$name])) $this->ms_options_meta[$name] = $meta ? array_merge($this->ms_options_meta[$name], $meta) : $meta;
				else throw new \Exception(get_class($this).'::'. __FUNCTION__ ."(). Undefined option `$name`!");
			 }
			if($this->ms_options_meta)
			 {
				$options_meta = [];
				foreach($this->ms_options_meta as $name => $meta)
				 {
					unset($meta['property']);
					$options_meta[$name] = $meta;
				 }
				$this->ms_options = new Containers\Options($this->ms_options_data, $options_meta);
			 }
			else throw new \Exception(get_class($this).'::'. __FUNCTION__ ."(). Empty metadata for options!");
		 }
		else throw new \Exception('Can not rewrite options: already initialized!');
	 }

	private $ms_options = null;
	private $ms_options_data = null;
	private $ms_options_meta = null;
	private $ms_options_meta_changes = [];
}

trait TInstances
{
	final public static function Instance($index = 0)
	 {
		if(empty(self::$instances[$index])) if(!(self::$instances[$index] = self::OnUndefinedInstance($index))) throw new \Exception(get_called_class().": instance with index [$index] is undefined.");
		return self::$instances[$index];
	 }

	final public static function InstanceExists($index, &$inst = null)
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

	final public static function GetInstancesIDs()
	 {
		$r = [];
		foreach(self::$instances as $k => $v) $r[$k] = $k;
		return $r;
	 }

	protected static function OnUndefinedInstance($index) {}

	final protected static function SetInstance($index, $obj)
	 {
		if(isset(self::$instances[$index])) throw new \Exception(get_called_class().": Object with index [$index] already exists (".get_class(self::$instances[$index]).").");
		self::$instances[$index] = $obj;
	 }

	private static $instances = [];
}

trait TCallbacks
{
	final protected static function CreateCallbackArgs($callback, $method = false)
	 {
		if(is_array($callback)) list($callback, $method) = $callback;
		if(is_object($callback) && $method) return function(...$args) use($callback, $method) { return $callback->{$method}(...$args); };
		elseif(is_string($callback) && ($method || strpos($callback, '::') !== false))
		 {
			if(!$method) list($callback, $method) = explode('::', $callback);
			return function(...$args) use($callback, $method) { return $callback::$method(...$args); };
		 }
		else return $callback;
	 }
}
?>