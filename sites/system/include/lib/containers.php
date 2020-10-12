<?php
namespace MaxieSystems\Containers;

class Exception extends \Exception {}
	class EInvalidMeta extends Exception {}
	class EInvalidValue extends Exception {}
	class EProperty extends Exception {}

interface IDataElementProxy
{
	public function Set(&$value, DataElement $data);
	public function Get(&$value, DataElement $data);
}

abstract class AbstractData extends \stdClass
{
	public function __debugInfo()
	 {
		$r = [];
		foreach($this->p as $k => $v) $r[$k] = $this->$k;
		return $r;
	 }

	public function __get($name)
	 {
		if(isset($this->p[$name])) return $this->$name;
		else throw new EProperty($this->GetEUndefinedMsg($name));
	 }

	final public static function CutOptions(array &$options = null, array $meta, $return_all = false)
	 {
		$r = [];
		if($options)
		 foreach($meta as $k => $m)
		  if(array_key_exists($k, $options))
		   {
			$r[$k] = $options[$k];
			unset($options[$k]);
		   }
		return $return_all ? [$r, $meta] : $r;
	 }

	final public static function SplitOptions(array $options = null, array ...$meta)
	 {
		$r = [];
		foreach($meta as $i => $m)
		 {
			$r[$i] = [];
			foreach($m as $k => $v) if(array_key_exists($k, $options)) $r[$i][$k] = $options[$k];
		 }
		return $r;
	 }

	final protected static function CheckArrayKeys(array $a, array $keys, array &$diff = null)
	 {
		$diff = ($diff = array_diff_key($a, $keys)) ? array_keys($diff) : null;
		return !$diff;
	 }

	final protected function GetEUndefinedMsg(...$names) { return 'Instance of '.get_class($this).' has undefined propert'.(count($names) > 1 ? 'ies' : 'y').': '.implode(', ', $names); }

	protected $name;
	protected $set;
	protected $has_value;
	protected $value;
	protected $type;
	protected $init;
	protected $proxy;

	private $p = ['name' => true, 'set' => true, 'has_value' => true, 'value' => true, 'type' => true, 'init' => true, 'proxy' => true];
}

class DataElement extends AbstractData
{
	public static function Create($name, array $meta)
	 {
		return new DataElement($name, $meta);
	 }

	protected function __construct($name, array $meta)
	 {
		if(!self::CheckArrayKeys($meta, self::$p_defaults, $diff)) throw new EInvalidMeta("Meta data '$name' has undefined option".(count($diff) > 1 ? 's' : '').': '.implode(', ', $diff));
		$this->name = $name;
		$this->set = isset($meta['set']) && (true === $meta['set'] || false === $meta['set'] || 1 === $meta['set']) ? $meta['set'] : self::$p_defaults['set'];
		if(isset($meta['type']))
		 {
			$this->type = "$meta[type]";
			if('' === $this->type) throw new EInvalidMeta("Empty type for property '$name'.");
		 }
		else $this->type = self::$p_defaults['type'];
		if(isset($meta['proxy']))
		 {
			if(!($meta['proxy'] instanceof IDataElementProxy)) throw new EInvalidMeta('Proxy must be of the type \MaxieSystems\Containers\IDataElementProxy, '.gettype($meta['proxy']).' given.');
			$this->proxy = $meta['proxy'];
		 }
		$this->init = isset($meta['init']) && (true === $meta['init'] || false === $meta['init']) ? $meta['init'] : self::$p_defaults['init'];
		if($this->has_value = array_key_exists('value', $meta))
		 {
			if($this->proxy) $this->proxy->Set($meta['value'], $this);
			$this->value = &$meta['value'];
			if(isset($meta['on_init'])) $meta['on_init']($this->value, $this->has_value);
		 }
		else $this->value = self::$p_defaults['value'];
	 }

	private static $p_defaults = ['init' => false, 'proxy' => false, 'set' => false, 'type' => null, 'value' => null, 'on_init' => null];
}

class DataElements implements IDataElementProxy
{
	use \MaxieSystems\TOptions;

	public function __construct(array $options = null)
	 {
		$this->AddOptionsMeta(['after' => ['value' => '', 'type' => 'string,number'], 'before' => ['value' => '', 'type' => 'string,number'], 'glue' => ['value' => ' ', 'type' => 'string,null'], 'unique' => ['value' => true], 'skip_empty' => ['value' => 'string', 'type' => 'string,bool']]);
		$this->SetOptionsData($options);
	 }

	public function Set(&$value, DataElement $data)
	 {
		if($this->GetOption('unique')) $this->values[$value] = $value;
		else $this->values[] = $value;
	 }

	public function Get(&$value, DataElement $data)
	 {
		$opt = $this->GetOption('skip_empty');
		if('string' === $opt) $v = array_filter($this->values, function($val){return '' !== "$val";});
		elseif(true === $opt) $v = array_filter($this->values);
		else $v = $this->values;
		if(false === ($opt = $this->GetOption('glue'))) $value = $v;
		elseif($v) $value = $this->GetOption('before').implode($opt, $v).$this->GetOption('after');
	 }

	private $values = [];
}

trait TDataTypes
{
	final protected static function ParseType($type) { return self::{self::$parse_type}($type); }

	final protected static function TestValue($type, &$val, $name)
	 {
		$curr_type = false;
		if(self::$p_types_parsed[$type]->types)
		 {
			$r = false;
			foreach(self::$p_types_parsed[$type]->types as $curr_type => $f) if($r = $f($val)) break;
			if(!$r) throw new EInvalidValue(self::GetInvalidValueMsg($type, $val, $name).' Must be '.(count(self::$p_types_parsed[$type]->types) > 1 ? 'one of the following types:' : 'of the type').' '.implode(', ', array_keys(self::$p_types_parsed[$type]->types)).'.');
		 }
		foreach(self::$p_types_parsed[$type]->constr as $n => $f)
		 {
			if(isset(self::$p_constraints[$n]->types[$curr_type]))
			 {
				if(!$f($val)) throw new EInvalidValue(self::GetInvalidValueMsg($type, $val, $name)." Constraint `$n` violated.");
			 }
			elseif('special' !== self::$p_types[$curr_type]->class && !array_intersect_key(self::$p_types_parsed[$type]->types, self::$p_constraints[$n]->types))
			 {
				throw new EInvalidMeta("Can not apply constraint `$n` for type `$curr_type`.");
			 }
		 }
	 }

	final protected static function ParseAndTest($type, &$val, $name)
	 {
		if($type)
		 {
			self::ParseType($type);
			self::TestValue($type, $val, $name);
		 }
		// elseif();
	 }

	final protected static function GetParsedType($type) { if($type) return self::$p_types_parsed[$type]; }

	final protected static function TypeIsNullable($type)
	 {
		$t = self::GetParsedType($type);
		return !$t || isset($t->types['null']);
	 }

	final protected static function TypeIsUnsigned($type)
	 {
		$t = self::GetParsedType($type);
		if($t && (isset($t->types['number']) || isset($t->types['float']) || isset($t->types['int']))) return isset($t->constr['gt0']) || isset($t->constr['gte0']);
	 }

	final protected static function TypeIsCompound($type, $ignore_special_types = 'null')
	 {
		$t = self::GetParsedType($type);
		if($ignore_special_types)
		 {
			if(is_string($ignore_special_types))
			 {
				$types = $t->types;
				if(self::TypeExists($ignore_special_types, 'special')) unset($types[$ignore_special_types]);
				else
				 {
					$t = explode(',', $ignore_special_types);
					foreach($t as $v)
					 if(self::TypeExists($v, 'special')) unset($types[$v]);
					 else throw new Exception("Invalid type name '$v'; must be one of the following names: ".implode(', ', self::GetSpecialTypes()));
				 }
			 }
			elseif(true === $ignore_special_types) $types = array_diff_key($t->types, self::GetSpecialTypes());
			else throw new InvalidArgumentException('Argument 2 passed to '.__METHOD__.'() must be one of the following types: boolean or string; '.MSConfig::GetVarType($ignore_special_types).' given');
		 }
		else $types = $t->types;
		return count($types) > 1;
	 }

	final protected static function TypeIsInt($type)
	 {
		$t = self::GetParsedType($type);
		return $t && isset($t->types['int']);
	 }

	final protected static function TypeIsFloat($type)
	 {
		$t = self::GetParsedType($type);
		return $t && isset($t->types['float']);
	 }

	final protected static function TypeIsString($type)
	 {
		$t = self::GetParsedType($type);
		return $t && isset($t->types['string']);
	 }

	final protected static function TypeIsBool($type)
	 {
		$t = self::GetParsedType($type);
		return $t && isset($t->types['bool']);
	 }

	final protected static function TypeIsArray($type)
	 {
		$t = self::GetParsedType($type);
		return $t && isset($t->types['array']);
	 }

	final protected static function TypeIsCallable($type)
	 {
		$t = self::GetParsedType($type);
		return $t && (isset($t->types['callable']) || isset($t->types['closure']));
	 }

	final protected static function TypeExists($name, $class = false) { return isset(self::$p_types[$name]) && (!$class || $class === self::$p_types[$name]->class); }

	final protected static function GetSpecialTypes()
	 {
		$r = [];
		foreach(self::$p_types as $k => $t) if('special' === $t->class) $r[$k] = $k;
		return $r;
	 }

	final private static function ParseDataElementType($type)
	 {
		if(!isset(self::$p_types_parsed[$type]))
		 {
			self::$p_types_parsed[$type] = $c = (object)['types' => [], 'constr' => []];
			$t = explode(',', $type);
			foreach($t as $i => $n)
			 {
				if(isset(self::$p_types[$n]))
				 {
					if(isset($c->types[$n])) throw new EInvalidMeta("Duplicate type `$n`");
					$c->types[$n] = self::$p_types[$n]->test;
					unset($t[$i]);
				 }
				elseif(isset(self::$p_constraints[$n]))
				 {
					if(isset($c->constr[$n])) throw new EInvalidMeta("Duplicate constraint `$n`");
					$c->constr[$n] = self::$p_constraints[$n]->test;
					unset($t[$i]);
				 }
			 }
			if($t) throw new EInvalidMeta('Can not parse type info: '.implode(',', $t).'.');
			if($c->constr && !$c->types) foreach($c->constr as $n => $f) foreach(self::$p_constraints[$n]->types as $t) $c->types[$t] = self::$p_types[$t]->test;
		 }
		return clone self::$p_types_parsed[$type];
	 }

	final private static function InitAndParseDataElementType($p)
	 {
		self::$p_types = [
			'callable' => (object)['test' => function($val){return is_callable($val);}, 'class' => 'pseudo'],
			'number' => (object)['test' => function($val){return is_int($val) || is_float($val);}, 'class' => 'pseudo'],
			'container' => (object)['test' => function($val){return ($val instanceof Data);}, 'class' => 'simple'],
			'array' => (object)['test' => 'is_array', 'class' => 'simple'],
			'bool' => (object)['test' => 'is_bool', 'class' => 'simple'],
			'float' => (object)['test' => 'is_float', 'class' => 'simple'],
			'int' => (object)['test' => function(&$val){
				if(is_int($val)) return true;
				elseif(is_string($val))
				 {
					$v = (int)$val;
					if("$v" === $val)
					 {
						$val = $v;
						return true;
					 }
				 }
				return false;
			}, 'class' => 'simple'],
			'closure' => (object)['test' => function($val){return ($val instanceof \Closure);}, 'class' => 'simple'],
			'stdclass' => (object)['test' => function($val){return ($val instanceof \stdClass);}, 'class' => 'simple'],
			'iterator' => (object)['test' => function($val){return ($val instanceof \Iterator);}, 'class' => 'simple'],
			'object' => (object)['test' => 'is_object', 'class' => 'simple'],
			'string' => (object)['test' => 'is_string', 'class' => 'simple'],
			'false' => (object)['test' => function($val){return false === $val;}, 'class' => 'special'],
			'true' => (object)['test' => function($val){return true === $val;}, 'class' => 'special'],
			'null' => (object)['test' => function($val){return null === $val;}, 'class' => 'special'],
		];
		self::$p_constraints = [
			'gt0' => (object)['test' => function($val){return $val > 0;}, 'types' => ['float' => 'float', 'int' => 'int', 'number' => 'number', 'string' => 'string']],
			'gte0' => (object)['test' => function($val){return $val >= 0;}, 'types' => ['float' => 'float', 'int' => 'int', 'number' => 'number', 'string' => 'string']],
			'lt0' => (object)['test' => function($val){return $val < 0;}, 'types' => ['float' => 'float', 'int' => 'int', 'number' => 'number', 'string' => 'string']],
			'lte0' => (object)['test' => function($val){return $val <= 0;}, 'types' => ['float' => 'float', 'int' => 'int', 'number' => 'number', 'string' => 'string']],
			'cnt_gt0' => (object)['test' => function(array $val){return count($val) > 0;}, 'types' => ['array' => 'array']],
			'len_gt0' => (object)['test' => function($val){return '' !== "$val";}, 'types' => ['string' => 'string']],
		];
		self::$parse_type = 'ParseDataElementType';
		return self::ParseDataElementType($p);
	 }

	final private static function GetInvalidValueMsg($type, $val, $name)
	 {
		$t = gettype($val);
		return "Invalid value for property '$name' of type '$type': ".(is_scalar($val) ? var_export($val, true)." ($t)" : ('object' === $t ? 'instance of '.get_class($val) : $t)).'.';
	 }

	private static $p_types = null;
	private static $p_constraints = null;
	private static $p_types_parsed = [];
	private static $parse_type = 'InitAndParseDataElementType';
}

class Data extends AbstractData implements \Iterator, \JsonSerializable
{
	use TDataTypes;

	public function __construct(array $meta)
	 {
		foreach($meta as $k => $v)
		 {
			$p = DataElement::Create($k, $v);
			self::ParseAndTest($p->type, $p->value, $k);
			$this->InitProperty($k, $p);
		 }
		$this->meta = $meta;
	 }

	public function __debugInfo()
	 {
		$r = [];
		foreach($this->data as $k => $v) $r[$k] = ['value' => $v->value, 'type' => $v->type];
		return $r;
	 }

	public function __clone()
	 {
		throw new Exception('Can not clone instance of '.get_class($this));
	 }

	final public function rewind() { reset($this->data); }
	final public function current() { return current($this->data)->value; }
	final public function key() { return key($this->data); }
	final public function next() { next($this->data); }
	final public function valid() { return null !== key($this->data); }
	final public function __isset($name) { return isset($this->data[$name]) && $this->data[$name]->has_value; }
	final public function __unset($name) { unset($this->data[$name]); }
	final public function PropertyIsEditable($name) { return isset($this->data[$name]) ? false !== $this->data[$name]->set && 0 !== $this->data[$name]->set : null; }

	final public function PropertyExists($name, &$type = null)
	 {
		$type = null;
		if(isset($this->data[$name]))
		 {
			if($t = self::GetParsedType($this->data[$name]->type))
			 {
				$type = $t->types;
				foreach($type as $t => $f) $type[$t] = $t;
			 }
			return true;
		 }
		return false;
	 }

	final public function __call($name, array $arguments)
	 {
		if(isset($this->data[$name]) && ($t = self::GetParsedType($this->data[$name]->type)))
		 {
			if(isset($t->types['callable'])) return null === $this->data[$name]->value ? null : call_user_func_array($this->data[$name]->value, $arguments);
			elseif(isset($t->types['closure'])) return null === $this->data[$name]->value ? null : $this->data[$name]->value->__invoke(...$arguments);
		 }
		throw new EProperty('Call to undefined method '.get_class($this)."::$name()");
	 }

	final public function __set($name, $value)
	 {
		if(isset($this->data[$name]))
		 {
			if(false === $this->data[$name]->set) throw new EProperty('Property '.get_class($this).'::$'.$name.' is read-only!');
			if(0 === $this->data[$name]->set) throw new EProperty('Property '.get_class($this).'::$'.$name.' can be set only once!');
			if($this->data[$name]->type) self::TestValue($this->data[$name]->type, $value, $name);
			if(1 === $this->data[$name]->set) --$this->data[$name]->set;
			if($this->data[$name]->proxy) $this->data[$name]->proxy->Set($value, $this->data[$name]);
			$this->data[$name]->value = $value;
			$this->data[$name]->has_value = true;
		 }
		else throw new EProperty($this->GetEUndefinedMsg($name));
	 }

	final public function &__get($name)
	 {
		$this->GetProperty($name);
		if($this->data[$name]->proxy)
		 {
			$v = $this->data[$name]->value;
			$this->data[$name]->proxy->Get($v, $this->data[$name]);
			return $v;
		 }
		if($this->data[$name]->set) return $this->data[$name]->value;
		else
		 {
			$v = $this->data[$name]->value;
			return $v;
		 }
	 }

	final public function PropertyIsDefault($name)
	 {
		$d = $this->GetProperty($name);
		return array_key_exists('value', $this->meta[$name]) ? $d->value === $this->meta[$name]['value'] : false;
	 }

	final public function PropertyIsNullable($name)
	 {
		$d = $this->GetProperty($name);
		return $this->TypeIsNullable($d->type);
	 }

	final public function PropertyIsUnsigned($name)
	 {
		$d = $this->GetProperty($name);
		return $this->TypeIsUnsigned($d->type);
	 }

	final public function jsonSerialize() { return $this->ToArray(); }

	final public function ToArray()
	 {
		$r = [];
		foreach($this->data as $k => $v) $r[$k] = $v->value;
		return $r;
	 }

	final private function InitProperty($name, DataElement $data)
	 {
		if(!is_string($name)) throw new EInvalidMeta('Only string keys are allowed! '.gettype($name)."($name) given.");
		if(isset($this->data[$name])) throw new EInvalidMeta('Duplicate property: '. __CLASS__ .'::$'.$name);
		$this->data[$name] = $data;
	 }

	final private function GetProperty($name)
	 {
		if(isset($this->data[$name]))
		 {
			if(!$this->data[$name]->has_value && $this->data[$name]->init) throw new EProperty('Uninitialized property: '.get_class($this).'::$'.$name);
			return $this->data[$name];
		 }
		else throw new EProperty($this->GetEUndefinedMsg($name));
	 }

	private $data = [];
	private $meta;
}

class Options extends Data
{
	public function __construct(array $values = null, array $meta)
	 {
		if($values)
		 {
			if(!$this->CheckArrayKeys($values, $meta, $diff)) throw new EProperty($this->GetEUndefinedMsg(...$diff));
			foreach($values as $k => &$v) $meta[$k]['value'] = &$v;
		 }
		parent::__construct($meta);
	 }
}

class StdClassProxy extends \stdClass implements \Iterator
{
	public function __construct(\stdClass $data = null, array $options = null)
	 {
		$this->options = new Options($options, ['fields' => ['type' => 'array', 'value' => []], 'stdclass' => ['type' => 'string', 'value' => get_class($this)]]);
		$this->data = new \stdClass;
		$this->data->{0} = $data ?: new \stdClass;
		$this->data->{1} = new \stdClass;
		if($this->options->fields) foreach($this->options->fields as $k) if(!isset($this->data->{0}->$k)) $this->data->{0}->$k = new \stdClass;
		$this->data->fields = [];
		foreach($this->data->{0} as $k => $v)
		 {
			$this->data->fields[$k] = new \stdClass;
			$this->data->fields[$k]->i = 0;
			$this->data->fields[$k]->t = null;
		 }
		$this->c = $this->options->stdclass;
	 }

	final public function __get($name)
	 {
		if(property_exists($this->data->{0}, $name))
		 {
			if($this->data->fields[$name]->t) return $this->data->fields[$name]->t;
			if(false === $this->data->fields[$name]->t) return $this->data->{0}->$name;
			return ($this->data->fields[$name]->t = (is_object($this->data->{0}->$name) && 'stdClass' === get_class($this->data->{0}->$name))) ? ($this->data->fields[$name]->t = new $this->c($this->data->{0}->$name)) : $this->data->{0}->$name;
		 }
		if(isset($this->data->{1}->$name)) return $this->data->{1}->$name;
	 }

	final public function __set($name, $value)
	 {
		if(property_exists($this->data->{0}, $name)) throw new Exception("Property '$name' is read only!");
		$this->data->{1}->$name = $value;
		if(!isset($this->data->fields[$name]))
		 {
			$this->data->fields[$name] = new \stdClass;
			$this->data->fields[$name]->i = 1;
			$this->data->fields[$name]->t = null;
		 }
	 }

	final public function Current()
	 {
		$k = key($this->data->fields);
		if(null !== $k) return $this->data->{$this->data->fields[$k]->i}->$k;
	 }

	final public function Key() { return key($this->data->fields); }
	final public function Next() { next($this->data->fields); }
	final public function Rewind() { reset($this->data->fields); }
	final public function Valid() { return null !== key($this->data->fields); }
	final public function __isset($name) { return property_exists($this->data->{0}, $name) || property_exists($this->data->{1}, $name); }

	final public function __unset($name)
	 {
		unset($this->data->{0}->$name);
		unset($this->data->{1}->$name);
		unset($this->data->fields[$name]);
	 }

	final public function __debugInfo()
	 {
		$r = [];
		foreach([0, 1] as $i) foreach($this->data->$i as $k => $v) $r[$k] = $v;
		return $r;
	 }

	private $data;
	private $options;
	private $c;
}
?>