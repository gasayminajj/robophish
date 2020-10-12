<?php
namespace MaxieSystems;
use Exception;
use UnexpectedValueException;

abstract class file_system_storage_row_type
{
	abstract public function __toString();

	final public function __set($name, $value) { throw new Exception('Read only!'); }
	final public function __unset($name) { throw new Exception('Read only!'); }
	final public function __debugInfo() { return ['type' => $this->__toString()]; }
}

class file_system_storage_row_type__object extends file_system_storage_row_type
{
	public function __construct(\stdClass &$row) { $this->row = &$row; }
	public function __invoke() {}
	public function __isset($name) { return property_exists($this->row, $name) || isset($this->row->$name); }
	public function __get($name) { return $this->row->$name; }
	public function __toString() { return 'object'; }

	private $row;
}

class file_system_storage_row_type__array extends file_system_storage_row_type
{
	public function __construct(array &$row) { $this->row = &$row; }
	public function __invoke() {}
	public function __isset($name) { return array_key_exists($name, $this->row); }
	public function __get($name) { return $this->row[$name]; }
	public function __toString() { return 'array'; }

	private $row;
}

class file_system_storage_row_type__key extends file_system_storage_row_type
{
	public function __construct(array &$row) { $this->row = &$row; }
	public function __invoke() {}
	public function __isset($name) { return array_key_exists($name, $this->row); }
	public function __get($name) { return $this->row[$name]; }
	public function __toString() { return 'key'; }

	private $row;
}

trait TFileSystemStorageValidation
{
	final protected function GetEMsg_UndefinedProp($name) { return 'Undefined property: '.get_class($this).'::$'.$name; }

	final protected function GetEMsg_NullValue($name) { return "Field '$name' can not be null"; }

	final protected function GetEMsg_NoCPKeyMethod($method) { return "Can not use $method() with compound key"; }

	final protected function CheckE_NullValue(FileSystemStorageMetaElement $meta, $value, $cast)
	 {
		if(null === $value && !$meta->IsNullable()) throw new Exception($this->GetEMsg_NullValue($meta->name));
		return $cast ? $meta->CastValue($value) : $value;
	 }

	final protected function GetRowType($value, $use_key_type = false)
	 {
		if(is_array($value))
		 {
			if($use_key_type)
			 {
				$a = true;
				foreach(array_keys($value) as $k => $v)
				 if(is_int($k) && $k === $v);
				 else
				  {
					$a = false;
					break;
				  }
				if($a) return new file_system_storage_row_type__key($value);
			 }
			return new file_system_storage_row_type__array($value);
		 }
		if($value instanceof \stdClass) return new file_system_storage_row_type__object($value);
		throw new Exception('Invalid type: '.Config::GetVarType($value));
	 }

	final protected function E_ReadonlyStorage() { throw new Exception('Object is readonly! Instance of '.get_class($this).": '{$this->GetName()}'."); }
}

class FileSystemStorageData extends \stdClass implements \Countable
{
	use TFileSystemStorageValidation;

	final public function __construct($name)
	 {
		$this->name = $name;
	 }

	final public function __set($name, $value)
	 {
		if('items' === $name)
		 {
			if($value instanceof AbstractFileSystemStorage) $this->items[] = $value;
			else throw new Exception('Invalid type: '.Config::GetVarType($value));
		 }
		elseif('__data' === $name)
		 {
			if($value instanceof FileSystemStorageRow) $this->__data[$value->GetID()] = $value;
			else throw new Exception('Invalid type: '.Config::GetVarType($value));
		 }
		else throw new Exception($this->GetEMsg_UndefinedProp($name));
	 }

	final public function __get($name)
	 {
		if('items' === $name) return $this->items;
		if('__data' === $name) return empty($this->__data) ? null : reset($this->__data);
		throw new Exception($this->GetEMsg_UndefinedProp($name));
	 }

	final public function __isset($k) { return array_key_exists($k, $this->__data) ? null !== $this->__data[$k] : isset($this->data[$k]); }

	final public function RowExists($k) { return isset($this->__data[$k]) || isset($this->data[$k]); }

	final public function __unset($name)
	 {
		$this->__data[$name] = null;
		$this->changed = true;
	 }

	final public function GetMeta() { return $this->meta_data; }

	final public function Clear()
	 {
		foreach($this->data as $k => $v) $this->__data[$k] = null;
		if(count($this->pkey) > 1 && ($this->__keys || $this->keys))
		 {
			if($this->keys)
			 {
				if($this->__keys) $this->__keys = array_merge_recursive($this->__keys, $this->keys);
				else $this->__keys = $this->keys;
			 }
			$this->DeleteKeys($this->__keys, $this->pkey);
		 }
		$this->changed = true;
	 }

	final public function Reload()
	 {
		$tmp = $this->Load();
		foreach(['meta', 'data', 'keys'] as $i) $this->$i = $tmp[$i];
		$this->meta_data = new FileSystemStorageMeta($tmp['meta']);
		$this->pkey = $this->meta_data->GetPrimaryKey();
		return $tmp;
	 }

	final public function count()
	 {
		$i = 0;
		foreach($this->data as $k => $row) if($this->RowIsNotEmpty($k)) ++$i;
		return $i;
	 }

	final public function RowIsNotEmpty($k, FileSystemStorageRow &$row = null)
	 {
		$row = null;
		if(array_key_exists($k, $this->__data))
		 {
			if(null !== $this->__data[$k])
			 {
				$row = $this->__data[$k];
				return null === $this->data[$k] ? (bool)$this->__data[$k]->Changed() : true;
			 }
		 }
		elseif(array_key_exists($k, $this->data)) return null !== $this->data[$k];
	 }

	final public function RowIsModified($k, FileSystemStorageRow &$row = null)
	 {
		$row = null;
		if(isset($this->__data[$k]))
		 {
			$row = $this->__data[$k];
			return (bool)$this->__data[$k]->Changed();
		 }
	 }

	final public function RowIsNew($k, FileSystemStorageRow &$row = null, $add = false)
	 {
		$row = null;
		if(!array_key_exists($k, $this->__data)) $r = !array_key_exists($k, $this->data);
		elseif(null === $this->__data[$k]) $r = true;// это означает, что ряд был удалён, а теперь происходит попытка обращения к нему; создаём заново.
		else
		 {
			$row = $this->__data[$k];
			return;
		 }
		if(true === $r && $add) $this->data[$k] = null;
		return $r;
	 }

	final public function SaveStorage(AbstractFileSystemStorage $storage)
	 {
		foreach($this->items as $k => $v)
		 if($v === $storage)
		  {
			unset($this->items[$k]);
			break;
		  }
		if(!$this->items && $this->changed) $this->Save();
	 }

	final public function Save()
	 {
		$h = fopen($this->name, 'c');
		$t = $this->Load();
		$changed = false;
		$unset = function($row_id) use(&$t, &$changed){
			if(isset($t['data'][$row_id]))
			 {
				unset($t['data'][$row_id]);
				$changed = true;
			 }
		};
		$set = function($row_id, FileSystemStorageRow $row, array $d) use(&$t, &$changed){
			$changed = true;
			if(!isset($t['data'][$row_id]))
			 {
				$t['data'][$row_id] = [];
				$d = $row;
			 }
			foreach($d as $k => $v)
			 {
				if(null === $v)
				 {
					$m = $this->meta_data->$k;
					if(!$m->IsNullable() && null === $m->value)
					 {
						if(!$m->auto_increment) throw new Exception($this->GetEMsg_NullValue($k));
					 }
				 }
				$t['data'][$row_id][$k] = $v;
			 }
		};
		foreach($this->__data as $row_id => $row)
		 {
			if(null === $row) $unset($row_id);
			elseif($d = $row->Changed()) $set($row_id, $row, $d);
			elseif(array_key_exists($row_id, $this->data))
			 {
				if(true === $this->data[$row_id]) $set($row_id, $row, $d);
				elseif(null === $this->data[$row_id]) $unset($row_id);
			 }
		 }
		if($changed || !empty($this->__keys))
		 {
			if(count($this->pkey) > 1) $this->CopyKeys($t['keys'], $this->__keys, $t['data']);
			$code = '<?php'.PHP_EOL.'return '.var_export($t, true).';'.PHP_EOL.'?>';
			ftruncate($h, strlen($code));
			fwrite($h, $code);
		 }
		fclose($h);
		$this->changed = false;
	 }

	final public function AutoIncrement()
	 {
		$this->data[] = null;
		end($this->data);
		return key($this->data);
	 }

	final public function SetIndex($index, $k = 'primary')
	 {
		if('primary' === $k) $key = $this->pkey;
		else throw new Exception('not implemented yet...');
		$r = [];
		$v = &$this->__keys[$k];
		foreach($key as $f)
		 {
			$f = $this->__data[$index]->{$f->name};
			$v = &$v[$f];
			$r[] = $f;
		 }
		$v = $index;
		return $r;
	 }

	final public function UnsetIndex(array $values, $k = 'primary') { return $this->CreateIndex($values, null, $k); }

	final public function CreateIndex(array $values, $id, $k = 'primary')
	 {
		if('primary' === $k) $key = $this->pkey;
		else throw new Exception('not implemented yet...');
		$func = function(array &$keys = null, array &$values, DBKeyMeta &$key) use(&$func, $id){
			$j = $values[$key->key()];
			$key->next();
			if($key->valid()) $func($keys[$j], $values, $key);
			else $keys[$j] = $id;
		};
		$key->rewind();
		$func($this->__keys[$k], $values, $key);
		$this->changed = true;
	 }

	final public function GetIndex($row, $type, $k = 'primary')
	 {
		if('primary' === $k) $key = $this->pkey;
		else throw new Exception('not implemented yet...');
		switch("$type")
		 {
			case 'key': $callback = function($i, $n, array &$row) { return $row[$i]; }; break;
			case 'array': $callback = function($i, $n, array &$row) { return $row[$n]; }; break;
			case 'object': $callback = function($i, $n, \stdClass &$row) { return $row->$n; }; break;
			default: throw new Exception('Invalid type: '.Config::GetVarType($type));
		 }
		foreach(['__keys', 'keys'] as $i) if(array_key_exists($k, $this->$i) && false !== ($index = $this->MapKeys($this->{$i}[$k], $row, $callback, $key))) return $index;
	 }

	final public function ChangedBy($caller, $action)
	 {
		if(!($caller instanceof FileSystemStorageRow)) throw new Exception('Invalid caller');
		static $actions = ['__set' => 1, '__unset' => 1];
		if(empty($actions[$action])) throw new Exception('Invalid action: '.Config::GetVarType($action));
		$this->changed = true;
	 }

	final public function &GetRow($name, AbstractFileSystemStorage $stor, DBKeyMeta $pkey = null)
	 {
		$new = $this->RowIsNew($name, $row, true);
		if(null !== $new) $row = new FileSystemStorageRow($name, $stor, $this, $pkey && $new ? [$pkey->{0}->name = $name] : null);
		return $row;
	 }

	final public function MoveRow($id, $new_id)
	 {
		$this->__data[$new_id] = $this->__data[$id];
		$this->__data[$id] = null;
		$this->changed = true;
		if(!array_key_exists($new_id, $this->data)) $this->data[$new_id] = null;
	 }

	final public function Delete($id)
	 {
		unset($this->data[$id]);
		unset($this->__data[$id]);
		$this->changed = true;
	 }

	final public function EmptyData(FileSystemStorageRow $row, $null = true)
	 {
		$id = $row->GetID();
		$this->data[$id] = $null ? null : true;
		$this->changed = true;
		return $id;
	 }

	final private function CopyKeys(array &$keys = null, array $__keys, array &$data)
	 {
		foreach($__keys as $k => $v)
		 {
			if(is_array($v))
			 {
				$this->CopyKeys($keys[$k], $v, $data);
				if([] === $keys[$k] || null === $keys[$k]) unset($keys[$k]);
			 }
			elseif(null === $v) unset($keys[$k]);
			elseif(isset($data[$v])) $keys[$k] = $v;
		 }
	 }

	final private function DeleteKeys(array &$__keys = null, DBKeyMeta &$key, $i = 0)
	 {
		foreach($__keys as $k => $v)
		 {
			if(is_array($v) && $i < count($key)) $this->DeleteKeys($__keys[$k], $key, $i + 1);
			else $__keys[$k] = null;
		 }
	 }

	final private function MapKeys(array &$keys, &$row, \Closure &$callback, DBKeyMeta &$key, $i = 0)
	 {
		if(0 === $i) $key->rewind();
		$j = $callback($i, $key->current()->name, $row);
		if(array_key_exists($j, $keys))
		 {
			$key->next();
			if($key->valid()) return $this->MapKeys($keys[$j], $row, $callback, $key, $i + 1);
			elseif(is_scalar($keys[$j])) return $keys[$j];
		 }
		elseif($i < count($key)) return false;
	 }

	final private function Load()
	 {
		$v = (require $this->name);
		if(is_array($v) && isset($v['meta']) && is_array($v['meta'])) return ['meta' => $v['meta'], 'data' => isset($v['data']) ? $v['data'] : [], 'keys' => isset($v['keys']) ? $v['keys'] : []];
		throw new Exception("$this->name: invalid file format!");
	 }

	public $data = null;

	private $__data = [];
	private $changed = false;
	private $keys = null;
	private $__keys = [];
	private $name;
	private $meta_data;
	private $items = [];
	private $meta = [];
	private $pkey;
}

class FileSystemStorageRow extends \stdClass implements \Iterator, \JsonSerializable
{
	use TFileSystemStorageValidation;

	final public function __construct($id, FileSystemStorage $owner, FileSystemStorageData $rows, $init_val = null)
	 {
		$this->id = null === $id ? $rows->AutoIncrement() : $id;
		$this->owner = $owner;
		$this->rows = $rows;
		if($this->rows->__data && $this->rows->__data->owner === $owner)
		 {
			$this->meta = $this->rows->__data->meta;
			$this->pkey = $this->rows->__data->pkey;
			$this->on_set = $this->rows->__data->on_set;
		 }
		else
		 {
			$this->meta = $this->owner->GetMeta();
			if($this->pkey = $this->meta->GetPrimaryKey()) $this->on_set = 'OnSet_'.($this->pkey->IsSimple() ? 'Simple' : 'Compound');
		 }
		$this->rows->__data = $this;
		if($init_val)
		 {
			if(!is_array($this->rows->data[$this->id])) $this->rows->data[$this->id] = [];
			foreach($init_val as $k => $v) $this->rows->data[$this->id][$k] = $this->CheckE_NullValue($this->meta->$k, $v, true);
		 }
	 }

	final public function rewind() { $this->meta->rewind(); }
	final public function current() { return $this->GetValue($this->meta->key()); }
	final public function key() { return $this->meta->key(); }
	final public function next() { $this->meta->next(); }
	final public function valid() { return $this->meta->valid(); }
	final public function Changed() { return $this->data; }
	final public function GetID() { return $this->id; }

	final public function __set($name, $value)
	 {
		if(!$this->owner->ColExists($name)) throw new Exception($this->GetEMsg_UndefinedProp($name));
		$this->owner->CheckReadonly();
		$value = $this->CheckE_NullValue($this->meta->$name, $value, true);
		if(null !== $this->on_set) $this->{$this->on_set}($name, $value);
		$this->data[$name] = $value;
		$this->rows->ChangedBy($this, __FUNCTION__);
	 }

	final public function __get($name)
	 {
		if(!$this->owner->ColExists($name)) throw new Exception($this->GetEMsg_UndefinedProp($name));
		return $this->GetValue($name);
	 }

	final public function __isset($name)
	 {
		return isset($this->data[$name]) || (isset($this->rows->data[$this->id]) && isset($this->rows->data[$this->id][$name]));
	 }

	final public function __unset($name)
	 {
		$this->owner->CheckReadonly();
		$this->data[$name] = $this->CheckE_NullValue($this->meta->$name, $this->meta->$name->value, false);
		$this->rows->ChangedBy($this, __FUNCTION__);
	 }

	final public function jsonSerialize()
	 {
		$r = [];
		if($filter = $this->owner->GetOption('json_filter'))
		 {
			foreach($this->meta as $k => $m)
			 {
				$v = $this->GetValue($k);
				if(true === $filter($k, $v)) $r[$k] = $v;
			 }
		 }
		else foreach($this->meta as $k => $m) $r[$k] = $this->GetValue($k);
		return $r;
	 }

	final public function __debugInfo()
	 {
		$r = [];
		foreach($this->meta as $k => $v) $r[$k] = $this->GetValue($k);
		return $r;
	 }

	final public function __clone()
	 {
		throw new Exception('Can not clone instance of '.get_class($this));
	 }

	final public function ToStdClass()
	 {
		$r = new \stdClass();
		foreach($this->meta as $k => $v) $r->$k = $this->GetValue($k);
		return $r;
	 }

	final protected function GetValue($name)
	 {
		if(array_key_exists($name, $this->data)) return $this->data[$name];
		if(isset($this->rows->data[$this->id]) && isset($this->rows->data[$this->id][$name])) return $this->rows->data[$this->id][$name];
		return $this->meta->$name->value;
	 }

	final private function OnSet_Simple($name, $value)
	 {
		if(($this->pkey->{0}->name === $name) && ($this->meta->$name->CastValue($this->id) !== $value))
		 {
			$this->rows->MoveRow($this->id, $value);
			$this->id = $value;
		 }
	 }

	final private function OnSet_Compound($name, $value)
	 {
		if(!isset($this->pkey->$name)) return;
		$prev_key = $key = [];
		foreach($this->pkey as $k => $v)
		 {
			if(array_key_exists($v->name, $this->data)) $prev_key[$k] = $this->data[$v->name];
			elseif(isset($this->rows->data[$this->id]) && array_key_exists($v->name, $this->rows->data[$this->id])) $prev_key[$k] = $this->rows->data[$this->id][$v->name];
			else return;
			$key[$k] = $v->name === $name ? $value : $prev_key[$k];
		 }
		if($prev_key !== $key)
		 {
			$index = $this->rows->GetIndex($key, 'key');
			$this->rows->UnsetIndex($prev_key);
			if(null === $index) $this->rows->CreateIndex($key, $this->id);
			else
			 {
				$this->rows->MoveRow($this->id, $index);
				$this->id = $index;
			 }
		 }
	 }

	private $id;
	private $owner;
	private $data = [];
	private $meta = null;
	private $pkey;
	private $rows;
	private $on_set = null;
}

class FileSystemStorageMetaElement extends Containers\Options
{
	public function __construct($name, array $values = null)
	 {
		$values['name'] = $name;
		parent::__construct($values, [
			'name' => ['type' => 'string,len_gt0'],
			'type' => ['type' => 'string,len_gt0'],
			'length' => ['type' => 'int,gt0,null'],
			'value' => [],
			'auto_increment' => ['type' => 'bool', 'value' => false],
			'key' => ['type' => 'string', 'value' => ''],
		]);
		$t = $this->__get('type');
		$this->parsed_type = self::ParseType($t);
		if(self::TypeIsCompound($t)) throw new Exception("Invalid type '$t' for field '$name'");
		if($this->__get('auto_increment') && !$this->IsInt()) throw new Exception("Incorrect column specifier 'auto_increment' for field '$name'");
		$this->parsed_type = $this->parsed_type->types;
		unset($this->parsed_type['null']);
		$this->parsed_type = key($this->parsed_type);
		if(('int' === $this->parsed_type /* || 'float' === $this->parsed_type */) && !$this->__get('length')) throw new Exception("Length is not specified for field '$name' (type '$this->parsed_type')");
	 }

	final public function IsNullable() { return self::TypeIsNullable($this->__get('type')); }
	final public function IsUnsigned() { return self::TypeIsUnsigned($this->__get('type')); }
	final public function IsInt() { return self::TypeIsInt($this->__get('type')); }
	final public function IsFloat() { return self::TypeIsFloat($this->__get('type')); }
	final public function IsString() { return self::TypeIsString($this->__get('type')); }
	final public function IsBool() { return self::TypeIsBool($this->__get('type')); }
	final public function IsArray() { return self::TypeIsArray($this->__get('type')); }

	final public function GetSQLType()
	 {
		$len = $this->__get('length');
		if('string' === $this->parsed_type) return $len ? "varchar($len)" : 'text';
		if('int' === $this->parsed_type) return "int($len)";
		return $this->parsed_type;
	 }

	final public function CastValue($v)
	 {
		if(null === $v && $this->IsNullable()) return $v;
		switch($this->parsed_type)
		 {
			case 'float': if(!is_float($v)) return (float)$v; break;
			case 'int': if(!is_int($v)) return (int)$v; break;
			case 'bool': if(!is_bool($v)) return (bool)$v; break;
			case 'string': if(!is_string($v)) return "$v"; break;
			case 'array': if(!is_array($v)) throw new UnexpectedValueException('Value must be of the type array, '.Config::GetVarType($v).' given'); break;
		 }
		return $v;
	 }

	final public function __debugInfo()
	 {
		$r = [];
		foreach($this as $k => $v) $r[$k] = $v;
		return $r;
	 }

	private $parsed_type;
}

class FileSystemStorageMeta extends \stdClass implements \Iterator
{
	use TFileSystemStorageValidation;

	final public function __construct(array $data)
	 {
		foreach($data as $k => $v)
		 {
			$this->data[$k] = new FileSystemStorageMetaElement($k, $v);
			if($this->data[$k]->key)
			 {
				if('primary' === $this->data[$k]->key)
				 {
					if(!isset($this->keys[$this->data[$k]->key])) $this->keys[$this->data[$k]->key] = [];
					$key = new \stdClass;
					$key->name = $k;
					$this->keys[$this->data[$k]->key][] = $key;
				 }
				else throw new Exception("Invalid type '{$this->data[$k]->key}' for key '$k'");
			 }
		 }
	 }

	final public function rewind() { reset($this->data); }
	final public function current() { return current($this->data); }
	final public function key() { return key($this->data); }
	final public function next() { next($this->data); }
	final public function valid() { return null !== key($this->data); }
	final public function __isset($name) { return array_key_exists($name, $this->data); }
	final public function __set($name, $value) { throw new Exception('Read only!'); }
	final public function __unset($name) { throw new Exception('Read only!'); }
	final public function GetKeys() { return $this->keys; }

	final public function CPKeyExists(DBKeyMeta &$key = null)
	 {
		if(null === $this->cpkey_exists) $this->cpkey_exists = (($key = $this->GetPrimaryKey()) && !$key->IsSimple());
		else $key = $this->primary_key;
		return $this->cpkey_exists;
	 }

	final public function GetPrimaryKey()
	 {
		if(false === $this->primary_key)
		 {
			if(isset($this->keys['primary']))
			 {
				$this->primary_key = [];
				foreach($this->keys['primary'] as $k => $v) $this->primary_key[$v->name] = $this->__get($v->name);
				$this->primary_key = new DBKeyMeta('primary', $this->primary_key);
			 }
			else $this->primary_key = null;
		 }
		return $this->primary_key;
	 }

	final public function __get($name)
	 {
		if(array_key_exists($name, $this->data)) return $this->data[$name];
		throw new Exception($this->GetEMsg_UndefinedProp($name));
	 }

	public function __debugInfo()
	 {
		$r = [];
		foreach($this->data as $k => $v) $r[$k] = $v->type;
		return $r;
	 }

	private $data = [];
	private $keys = [];
	private $primary_key = false;
	private $cpkey_exists = null;
}

abstract class AbstractFileSystemStorage implements \Iterator, \JsonSerializable, \Countable
{
	use TOptions, TFileSystemStorageValidation;

	public function __construct($file_name, array $options = null)
	 {
		$this->AddOptionsMeta(['root' => ['type' => 'string', 'value' => $_SERVER['DOCUMENT_ROOT']], 'json_filter' => ['type' => 'closure,array,null', 'on_init' => function(&$o){
			if($o && is_array($o))
			 {
				$items = array_fill_keys($o, true);
				$o = function($k) use($o){ return isset($items[$k]); };
			 }
		}]]);
		$this->SetOptionsData($options);
		$this->file_name = $file_name;
		$this->root = $this->GetOption('root');
		$path = "$this->root/$this->file_name";
		$this->name = realpath($path);
		if(false === $this->name) throw new Exception("No such file or directory: '$path'");
		if(!isset(self::$files[$this->name])) self::$files[$this->name] = new FileSystemStorageData($this->name);
		self::$files[$this->name]->items = $this;
	 }

	public function __clone()
	 {
		throw new Exception('Can not clone instance of '.get_class($this));
	 }

	public function jsonSerialize()
	 {
		if(isset(self::$files[$this->name]))
		 {
			$this->InitData();
			$r = [];
			foreach($this as $k => $row) $r[$k] = $row;
			return $r;
		 }
		else return null;
	 }

	final public function GetName() { return $this->name; }
	final public function __debugInfo() { return ['name' => $this->name, 'data' => json_encode($this)]; }
	final public function GetKeys() { return $this->GetMeta()->GetKeys(); }
	final public function GetPrimaryKey() { return $this->GetMeta()->GetPrimaryKey(); }
	final public function Reload() { return self::$files[$this->name]->Reload(); }
	final public function count() { return count($this->InitData()); }
	final public function __destruct() { if(isset(self::$files[$this->name])) self::$files[$this->name]->SaveStorage($this); }

	final public function RowExists($v, \stdClass &$row = null)
	 {
		$row = null;
		$r = $this->{$this->impl_row_exists}($v, $row);
		if(null === $r) if($r = $this->__isset($v)) $row = $this->__get($v);
		return $r;
	 }

	final public function ColExists($name, &$col = null)
	 {
		$meta = $this->GetMeta();
		$col = ($r = isset($meta->$name)) ? $meta->$name : null;
		return $r;
	 }

	final public function ValueExists($col_name, $value)
	 {
		if(!isset($this->GetMeta($d)->$col_name)) throw new Exception($this->GetEMsg_UndefinedProp($col_name));
		$values = array_column($d->data, $col_name);
		if(null === $value || '' === $value) return false !== array_search($value, $values, true);
		$values = array_filter($values, function($v){return null !== $v;});
		return $values && false !== array_search($value, $values);
	 }

	final public function GetMeta(\stdClass &$d = null)
	 {
		$d = $this->InitData();
		return $d->GetMeta();
	 }

	protected function InitObject()
	 {
		if($this->pkey = $this->GetMeta()->GetPrimaryKey())
		 {
			if($this->pkey->IsSimple())
			 {
				$this->impl_row_exists = 'RowExists_Simple';
				return 'simple';
			 }
			else
			 {
				$this->impl_row_exists = 'RowExists_Compound';
				return 'compound';
			 }
		 }
		else $this->impl_row_exists = 'RowExists_Default';
	 }

	final protected function InitData()
	 {
		if(null === self::$files[$this->name]->data) $this->Reload();
		return self::$files[$this->name];
	 }

	final protected function CheckKeyArgsCount(array $args, $length)
	 {
		$count = count($args);
		if(is_int($length));
		elseif($length instanceof \Countable) $length = count($length);
		else throw new Exception('Invalid argument: '.Config::GetVarType($length));
		if($count !== $length) throw new Exception("Arguments count must be equal to $length ($count given)");
	 }

	final private function RowExists_Init(&$v, \stdClass &$row = null)
	 {
		$this->InitObject();
		return $this->{$this->impl_row_exists}($v, $row);
	 }

	final private function RowExists_Default(&$v, \stdClass &$row = null)
	 {
		if(!is_scalar($v)) throw new Exception(get_class($this).'::RowExists(): The first argument should be either a string or an integer');
	 }

	final private function RowExists_Simple(&$v, \stdClass &$row = null)
	 {
		if(!is_scalar($v)) $v = $this->GetRowType($v)->{$this->pkey->{0}->name};
	 }

	final private function RowExists_Compound(&$v, \stdClass &$row = null)
	 {
		$d = $this->InitData();
		$type = $this->GetRowType($v, true);
		$index = $d->GetIndex($v, $type);
		if(null === $index || !isset($d->$index)) return false;
		if('key' !== "$type") $v = $this->pkey->Combine($v, ['keys' => false, 'index' => 'name'])->ToArray();
		$row = $this->Get(...$v);
		return true;
	 }

	protected $pkey;

	private $file_name;
	private $root;
	private $name;
	private $impl_row_exists = 'RowExists_Init';

	private static $files = [];
}

class FileSystemStorage extends AbstractFileSystemStorage
{
	final public function __construct($file_name, array $options = null)
	 {
		$this->AddOptionsMeta(['readonly' => ['type' => 'bool', 'value' => true]]);
		parent::__construct($file_name, $options);
	 }

	final public function rewind() { reset($this->InitData()->data); }
	final public function key() { return key($this->InitData()->data); }
	final public function next() { next($this->InitData()->data); }
	final public function current() { return $this->GetRow(true); }

	final public function valid()
	 {
		$d = $this->InitData();
		do
		 {
			$k = key($d->data);
			if(null === $k) return false;
			if($d->RowIsNotEmpty($k)) return true;
			next($d->data);
		 }
		while(1);
	 }

	final public function __set($name, $value)
	 {
		if(null === $value) $this->__unset($name);
		else
		 {
			$this->CheckReadonly();
			$skip = null;
			$type = $this->GetRowType($value);
			$row = $this->GetRow($name, null, $new);
			if(null !== $this->impl___set) $skip = $this->{$this->impl___set}($name, $value, $type, $row, $new);
			if(true === $new) foreach($value as $k => $v) $row->$k = $v;
			else
			 foreach($row as $k => $v)
			  {
				if(isset($type->$k)) $row->$k = $type->$k;
				elseif($skip !== $k) unset($row->$k);
			  }
		 }
	 }

	final public function &__get($name)
	 {
		if(null !== $this->impl_nocpkey) $this->{$this->impl_nocpkey}(__FUNCTION__);
		return $this->InitData()->GetRow($name, $this, $this->pkey);
	 }

	final public function __isset($name)
	 {
		if(null !== $this->impl_nocpkey) $this->{$this->impl_nocpkey}(__FUNCTION__);
		$d = $this->InitData();
		return isset($d->$name);
	 }

	final public function __unset($name)
	 {
		$this->CheckReadonly();
		if(null !== $this->impl_nocpkey) $this->{$this->impl_nocpkey}(__FUNCTION__);
		$d = $this->InitData();
		$r = $d->RowIsNotEmpty($name);
		unset($d->$name);
		return $r;
	 }

	final public function &Get(...$key) { return $this->{$this->impl_get}(...$key); }

	final public function Delete(...$key)
	 {
		$this->CheckReadonly();
		return $this->{$this->impl_delete}(...$key);
	 }

	final public function __invoke($value)
	 {
		$this->CheckReadonly();
		$k = $kname = null;
		if(null !== $this->impl___invoke) $kname = $this->{$this->impl___invoke}($value, $k);
		$d = $this->InitData();
		$row = new FileSystemStorageRow($k, $this, $d);
		if($kname) $row->$kname = $row->GetID();
		foreach($value as $f => $v) $row->$f = $v;
		$id = $d->EmptyData($row, false);
		if(null !== $this->impl___invoke_2) $id = $this->{$this->impl___invoke_2}($value, $row);
		$d->Save();
		$d->Reload();
		return $id;
	 }

	final public function Clear()
	 {
		$this->CheckReadonly();
		return $this->InitData()->Clear();
	 }

	final public function CheckReadonly() { if($this->GetOption('readonly')) $this->E_ReadonlyStorage(); }

	final protected function InitObject()
	 {
		$type = parent::InitObject();
		$this->impl___invoke = null;
		if('compound' === $type)
		 {
			$this->impl_get = 'Get_Compound';
			$this->impl_delete = 'Delete_Compound';
			$this->impl_nocpkey = 'NoCPKey_Compound';
			$this->impl___set = '__set_Compound';
			$this->impl___invoke_2 = '__invoke_Compound';
		 }
		else
		 {
			$this->impl_get = 'Get_Default';
			$this->impl_delete = 'Delete_Default';
			$this->impl_nocpkey = null;
			$this->impl___invoke_2 = null;
			if('simple' === $type)
			 {
				$this->impl___set = '__set_Simple';
				$this->impl___invoke = '__invoke_Simple';
			 }
			else
			 {
				$this->impl___set = null;
			 }
		 }
	 }

	final private function &GetRow($k, $init = null, &$new = null)
	 {
		$d = $this->InitData();
		if(true === $k) $k = key($d->data);
		$new = $d->RowIsNew($k, $row, true);
		if(null !== $new) $row = new FileSystemStorageRow($k, $this, $d, $init);
		return $row;
	 }

	final private function HasKeyValue($name, file_system_storage_row_type $type, &$kval)
	 {
		$kval = ($has_kval = isset($type->$name)) ? $type->$name : null;
		return $has_kval;
	 }

	final private function &Get_Init(...$key)
	 {
		$this->InitObject();
		return $this->{$this->impl_get}(...$key);
	 }

	final private function &Get_Compound(...$key)
	 {
		$this->CheckKeyArgsCount($key, $this->pkey);
		$d = $this->InitData();
		$init = $this->pkey->Combine($key);
		$index = $d->GetIndex($key, 'key');
		if(null === $index)
		 {
			$row = new FileSystemStorageRow(null, $this, $d, $init);
			$d->SetIndex($row->GetID());
			return $row;
		 }
		else return $this->GetRow($index, $init);
	 }

	final private function &Get_Default(...$key)
	 {
		$this->CheckKeyArgsCount($key, 1);
		return $this->__get($key[0]);
	 }

	final private function Delete_Init(...$key)
	 {
		$this->InitObject();
		return $this->{$this->impl_delete}(...$key);
	 }

	final private function Delete_Compound(...$key)
	 {
		$this->CheckKeyArgsCount($key, $this->pkey);
		$d = $this->InitData();
		$index = $d->GetIndex($key, 'key');
		if(null !== $index)
		 {
			$d->UnsetIndex($key);
			if(isset($d->$index))
			 {
				unset($d->$index);
				return true;
			 }
		 }
		return false;
	 }

	final private function Delete_Default(...$key)
	 {
		$this->CheckKeyArgsCount($key, 1);
		return $this->__unset($key[0]);
	 }

	final private function NoCPKey_Compound($method)
	 {
		throw new Exception($this->GetEMsg_NoCPKeyMethod(get_class($this)."::$method"));
	 }

	final private function NoCPKey_Init($method)
	 {
		$this->InitObject();
		if(null !== $this->impl_nocpkey) return $this->{$this->impl_nocpkey}($method);
	 }

	final private function __set_Init($name, $value, file_system_storage_row_type $type, FileSystemStorageRow $row, $new)
	 {
		$this->InitObject();
		if(null !== $this->impl___set) return $this->{$this->impl___set}($name, $value, $type, $row, $new);
	 }

	final private function __set_Simple($name, $value, file_system_storage_row_type $type, FileSystemStorageRow $row, $new)
	 {
		$kname = $this->pkey->{0}->name;
		if($this->HasKeyValue($kname, $type, $kval))
		 {
			if(null === $kval) throw new Exception($this->GetEMsg_NullValue($kname));
			$m = $this->GetMeta($d);
			if($m->$kname->CastValue($name) !== $m->$kname->CastValue($kval))
			 {
				if($new)
				 {
					$d->Delete($name);
					throw new Exception("Key '$kname' must be equal to the index");
				 }
				$d->MoveRow($name, $kval);
			 }
		 }
		else
		 {
			$row->$kname = $name;
			return $kname;
		 }
	 }

	final private function __set_Compound($name, $value)
	 {
		throw new Exception($this->GetEMsg_NoCPKeyMethod(get_class($this).'::__set'));
	 }

	final private function __invoke_Init($value, &$k)
	 {
		$this->InitObject();
		if(null !== $this->impl___invoke) return $this->{$this->impl___invoke}($value, $k);
	 }

	final private function __invoke_Compound($value, FileSystemStorageRow $row)
	 {
		$d = $this->InitData();
		$index = $d->GetIndex($value, $this->GetRowType($value));
		if(null === $index) return $d->SetIndex($row->GetID());
		$k = $v = '';
		foreach($this->pkey as $i => $f)
		 {
			if($i)
			 {
				$k .= ', ';
				$v .= ', ';
			 }
			$k .= var_export($f->name, true);
			$v .= var_export($row->{$f->name}, true);
		 }
		$d->Delete($row->GetID());
		throw new Exception("Duplicate key $k: $v");
	 }

	final private function __invoke_Simple($value, &$k)
	 {
		$kname = $this->pkey->{0}->name;
		$m = $this->GetMeta($d);
		if($this->HasKeyValue($kname, $this->GetRowType($value), $k) && null !== $k)
		 {
			if(isset($d->$k)) throw new Exception("Duplicate key '$kname': ".var_export($k, true));
		 }
		elseif($m->$kname->IsInt() && $m->$kname->auto_increment) return $kname;
		else throw new Exception("Undefined key value '$kname'");
	 }

	private $impl___invoke = '__invoke_Init';
	private $impl___invoke_2 = null;
	private $impl_get = 'Get_Init';
	private $impl_delete = 'Delete_Init';
	private $impl___set = '__set_Init';
	private $impl_nocpkey = 'NoCPKey_Init';
}

class FileSystemStorageReadonly extends AbstractFileSystemStorage
{
	final public function rewind() { reset($this->InitData()->data); }
	final public function key() { return key($this->InitData()->data); }
	final public function next() { next($this->InitData()->data); }
	final public function current() { if(null !== ($k = key($this->InitData()->data))) return $this->__get($k); }
	final public function __set($k, $value) { $this->E_ReadonlyStorage(); }
	final public function __unset($k) { $this->E_ReadonlyStorage(); }

	final public function __isset($k)
	 {
		$d = $this->InitData();
		return ($d->RowIsNotEmpty($k, $row) && $row) || isset($d->data[$k]);
	 }

	final public function valid()
	 {
		$d = $this->InitData();
		while(null !== ($k = key($d->data)))
		 {
			if($d->RowIsNotEmpty($k)) return true;
			next($d->data);
		 }
	 }

	final public function jsonSerialize()
	 {
		if($filter = $this->GetOption('json_filter'))
		 {
			$r = [];
			$d = $this->InitData();
			foreach($d->data as $id => $p)
			 {
				if($d->RowIsNotEmpty($id, $row) && $row)
				 {
					$r[$id] = $row->ToStdClass();
					foreach($r[$id] as $k => $v) if(true !== $filter($k, $r[$id]->$k)) unset($r[$id]->$k);
				 }
				elseif(isset($d->data[$id]))
				 {
					$r[$id] = [];
					foreach($d->data[$id] as $k => $v) if(true === $filter($k, $v)) $r[$id][$k] = $v;
				 }
			 }
			return $r;
		 }
		else return parent::jsonSerialize();
	 }

	final public function __get($k)
	 {
		$d = $this->InitData();
		if($d->RowIsNotEmpty($k, $row) && $row) return $row->ToStdClass();
		elseif(isset($d->data[$k])) return (object)$d->data[$k];
	 }

	final public function Get(...$key) { return $this->{$this->impl_get}(...$key); }

	final protected function InitObject()
	 {
		$type = parent::InitObject();
		$this->impl_get = 'compound' === $type ? 'Get_Compound' : 'Get_Default';
	 }

	final private function Get_Init(...$key)
	 {
		$this->InitObject();
		return $this->{$this->impl_get}(...$key);
	 }

	final private function Get_Compound(...$key)
	 {
		$this->CheckKeyArgsCount($key, $this->pkey);
		$index = $this->InitData()->GetIndex($key, 'key');
		if(null !== $index) return $this->__get($index);
	 }

	final private function Get_Default(...$key)
	 {
		$this->CheckKeyArgsCount($key, 1);
		return $this->__get($key[0]);
	 }

	private $impl_get = 'Get_Init';
}
?>