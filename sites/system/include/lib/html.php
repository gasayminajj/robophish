<?php
namespace MaxieSystems\HTML;

require_once(MSSE_LIB_DIR.'/traits.php');

class Attribute
{
	use \MaxieSystems\TOptions;

	public function __construct($name, $value = null, array $options = null)
	 {
		$this->SetOptionsData($options);
		$this->AddOptionsMeta(['readonly' => ['type' => 'bool', 'value' => false], 'no_empty' => ['type' => 'bool', 'value' => true]]);
		$this->name = $name;
		if(null !== $value) $this->value = $this->CheckValue($value);
	 }

	protected function CheckValue($value) { return $value; }
	protected function ToHTML($name, $value) { return " $name=\"$value\""; }

	final public static function ProcessValue($value) { return str_replace(['"', '<', '>'], ['&quot;', '&lt;', '&gt;'], $value); }

	final public function __toString() { return null === $this->value || ('' === "$this->value" && $this->GetOption('no_empty')) ? '' : $this->ToHTML($this->name, $this->value); }

	final public function __get($k)
	 {
		if('name' === $k) return $this->name;
		if('value' === $k) return $this->value;
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$k, 8);
	 }

	final public function __set($k, $v)
	 {
		if('value' === $k)
		 {
			if($this->GetOption('readonly')) throw new \Exception("Attribute `$this->name` is readonly!");
			$this->value = $this->CheckValue($v);
		 }
		else throw new \Exception('Undefined property: '.get_class($this).'::$'.$k, 8);
	 }

	final public function __debugInfo() { return ['name' => $this->name, 'value' => $this->value]; }

	private $name;
	private $value = null;
}

class TextAttribute extends Attribute
{
	final protected function ToHTML($name, $value) { return " $name=\"".($value ? $this->ProcessValue($value) : $value).'"'; }
}

class EnumAttribute extends Attribute
{
	final public function __construct($name, $value = null, array $options = null)
	 {
		$this->AddOptionsMeta(['allowed' => ['type' => 'array', 'value' => []]]);
		parent::__construct($name, $value, $options);
	 }

	protected function CheckValue($value)
	 {
		if(!in_array($value, $this->GetOption('allowed'))) throw new \Exception("Value '$value' is not allowed for attribute `{$this->GetName()}`!");
		return $value;
	 }
}

class IntAttribute extends Attribute
{
	final public function __construct($name, $value = null, array $options = null)
	 {
		$this->AddOptionsMeta(['min' => ['type' => 'int,null']]);
		parent::__construct($name, $value, $options);
	 }

	protected function CheckValue($value)
	 {
		$value = intval($value);
		if(null !== ($opt = $this->GetOption('min')) && $value < $opt) throw new \Exception("Value `$value` is less than minimum allowed for IntAttribute `{$this->GetName()}` ($value < $opt)!");
		return $value;
	 }
}

class BooleanAttribute extends Attribute
{
	final protected function ToHTML($name, $value) { return $value ? " $name=\"$name\"" : ''; }
}

class OnOffAttribute extends Attribute
{
	final protected function CheckValue($value)
	 {
		if('on' === $value || 'off' === $value) return $value;
		if(true === $value || false === $value || 1 === $value || 0 === $value) return $value ? 'on' : 'off';
		throw new \Exception("Invalid value `$value` for attribute `$this->name`! Must be true, false, 'on', 'off', 1 or 0.");
	 }
}

class ArrayAttributeValue
{
	public function __invoke($value)
	 {
		if(false === strpos($value, ' ')) $this->data["$value"] = $value;
		else foreach(array_filter(explode(' ', $value), function($v){return $v !== '';}) as $v) $this->data[$v] = $v;
	 }

	public function __toString() { return $this->data ? implode(' ', $this->data) : ''; }
	public function __debugInfo() { return $this->data; }
	public function reset() { $this->data = []; }

	private $data = [];
}

class ArrayAttribute extends Attribute
{
	protected function CheckValue($value)
	 {
		$v = $this->value ?: new ArrayAttributeValue();
		if(false === $value) $v->reset();
		elseif('' !== "$value") $v($value);
		return $v;
	 }
}

abstract class Tag implements \Iterator
{
	abstract public function GetName();

	public function __construct(...$args)
	 {
		if(!isset($this->attributes['class'])) $this->attributes['class'] = new ArrayAttribute('class');
		if(!isset($this->attributes['title'])) $this->attributes['title'] = new TextAttribute('title');
		$this->Set('SetAttribute', ...$args);
	 }

	protected function OnSet($k, &$v) {}
	
	final public function __get($name)
	 {
		if(isset($this->attributes[$name])) return false === $this->attributes[$name] ? '' : $this->attributes[$name]->value;
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final public function __set($name, $value) { $this->SetAttribute($name, $value); }

	final public function SetAttr(...$args) { return $this->Set('SetAttribute', ...$args); }

	final public function SetData(...$args) { return $this->Set('SetDataAttribute', ...$args); }

	final public function current()
	 {
		if(null === ($name = key($this->attributes))) return;
		if(isset($this->attributes[$name])) return false === $this->attributes[$name] ? '' : $this->attributes[$name]->value;
	 }

	final public function next() { next($this->attributes); }
	final public function key() { return key($this->attributes); }
	final public function valid() { return null !== key($this->attributes); }
	final public function rewind() { reset($this->attributes); }

	final public function IsEditable($name)
	 {
		if(isset($this->attributes[$name])) return false === $this->attributes[$name] || !$this->attributes[$name]->GetOption('readonly');
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final protected function AddAttributes(...$args)
	 {
		foreach($args as $a)
		 if(is_string($a)) $this->attributes[$a] = false;
		 else $this->attributes[$a->name] = $a;
	 }

	final protected function GetAttributesAsString()
	 {
		$ret_val = '';
		foreach($this->attributes as $a) $ret_val .= $a;
		foreach($this->data as $a => $v) if(null !== $v && false !== $v) $ret_val .= " $a=\"".($v ? Attribute::ProcessValue($v) : $v).'"';
		return $ret_val;
	 }

	final private function Set($method, ...$args)
	 {
		if(1 === count($args) && (is_array($args[0]) || ($args[0] instanceof \Traversable) || ($args[0] instanceof \stdClass)))
		 {
			foreach($args[0] as $k => $v) if(false !== $this->OnSet($k, $v)) $this->$method($k, $v);
		 }
		else
		 {
			$length = count($args);
			if($length % 2) throw new \Exception('Number of arguments must be even (attribute - value)!');
			for($i = 0; $i < $length; $i += 2) if(false !== $this->OnSet($args[$i], $args[$i + 1])) $this->$method($args[$i], $args[$i + 1]);
		 }
		return $this;
	 }

	final private function SetAttribute($name, $value)
	 {
		if(isset($this->attributes[$name]))
		 {
			if(null === $value) return;
			if($this->attributes[$name]) $this->attributes[$name]->value = $value;
			else $this->attributes[$name] = new Attribute($name, $value);
		 }
		else throw new \Exception("Attribute '$name' is not allowed for tag '{$this->GetName()}'!");
	 }

	final private function SetDataAttribute($name, $value) { $this->data["data-$name"] = $value; }

	private $attributes = ['id' => false, 'lang' => false, 'tabindex' => false];
	private $data = [];
}

abstract class VoidTag extends Tag
{
	final public function __toString() { return "<{$this->GetName()}{$this->GetAttributesAsString()} />"; }
}

abstract class NormalTag extends Tag
{
	final public function __toString()
	 {
		if($this->remove_if_empty && '' === $this->html && !$this->children) return '';
		$html = '';
		foreach($this->children as $tag) $html .= $tag;
		return "<{$this->GetName()}{$this->GetAttributesAsString()}>{$this->OnShow($this->html.$html)}</{$this->GetName()}>";
	 }

	final public function RemoveIfEmpty()
	 {
		$this->remove_if_empty = true;
		return $this;
	 }

	final public function SetHTML($html)
	 {
		$this->html = "$html";
		$this->children = [];
		return $this;
	 }

	final public function Append(Tag ...$tags)
	 {
		foreach($tags as $tag) $this->children[] = $tag;
		return $this;
	 }

	final public function GetHTML() { return $this->html; }

	protected function OnSet($k, &$v)
	 {
		if('innerHTML' === $k)
		 {
			$this->SetHTML($v);
			return false;
		 }
	 }

	protected function OnShow($html) { return $html; }

	private $html = '';
	private $children = [];
	private $remove_if_empty = false;
}

class Div extends NormalTag
{
	final public function GetName() { return 'div'; }
}

class Span extends NormalTag
{
	final public function GetName() { return 'span'; }
}

class A extends NormalTag
{
	final public function GetName() { return 'a'; }

	public function __construct(...$args)
	 {
		$this->AddAttributes(new TextAttribute('href'));
		parent::__construct(...$args);
	 }
}

class Label extends NormalTag
{
	final public function GetName() { return 'label'; }

	public function __construct(...$args)
	 {
		$this->AddAttributes(new TextAttribute('for'));
		parent::__construct(...$args);
	 }
}

class Form extends NormalTag
{
	final public function GetName() { return 'form'; }

	public function __construct(...$args)
	 {
		$this->AddAttributes(new TextAttribute('action'), new OnOffAttribute('autocomplete'), new EnumAttribute('enctype', null, ['allowed' => ['multipart/form-data']]), new EnumAttribute('method', 'post', ['allowed' => ['get', 'post']]), 'name', 'target');
		parent::__construct(...$args);
	 }
}

abstract class Input extends VoidTag
{
	final public function GetName() { return 'input'; }

	public function __construct(...$args)
	 {
		$this->AddAttributes(new BooleanAttribute('disabled'), 'name', new TextAttribute('value', null, ['no_empty' => false]));
		parent::__construct(...$args);
	 }
}

abstract class TextInput extends Input
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new IntAttribute('maxlength', null, ['min' => 1]), new BooleanAttribute('readonly'), 'size', new TextAttribute('placeholder'), new BooleanAttribute('required'), new OnOffAttribute('autocorrect'), new OnOffAttribute('autocomplete'), new OnOffAttribute('autocapitalize'));
		parent::__construct(...$args);
	 }
}

abstract class ButtonInput extends Input
{
	
}

class Button extends ButtonInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'button', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class Submit extends ButtonInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'submit', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class CheckBox extends ButtonInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'checkbox', ['readonly' => true]), new BooleanAttribute('checked'), new BooleanAttribute('required'));
		parent::__construct(...$args);
	 }
}

class Radio extends ButtonInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'radio', ['readonly' => true]), new BooleanAttribute('checked'));
		parent::__construct(...$args);
	 }
}

class Text extends TextInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new TextAttribute('list'), new TextAttribute('pattern'), new Attribute('type', 'text', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class Search extends TextInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new TextAttribute('pattern'), new Attribute('type', 'search', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class Password extends TextInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'password', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class Email extends TextInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'email', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class Number extends TextInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'number', ['readonly' => true]), new IntAttribute('max'), new IntAttribute('min'));
		parent::__construct(...$args);
	 }
}

class Tel extends TextInput
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'tel', ['readonly' => true]), new TextAttribute('pattern'));
		parent::__construct(...$args);
	 }
}

class Textarea extends NormalTag
{
	final public function GetName() { return 'textarea'; }

	public function __construct(...$args)
	 {
		$this->AddAttributes('cols', 'name', new TextAttribute('placeholder'), new BooleanAttribute('required'), 'rows');
		parent::__construct(...$args);
	 }

	protected function OnShow($html) { return htmlspecialchars($html); }
}

class Select extends NormalTag
{
	final public function GetName() { return 'select'; }

	public function __construct(...$args)
	 {
		$this->AddAttributes(new BooleanAttribute('disabled'), new BooleanAttribute('multiple'), 'name', 'size');
		parent::__construct(...$args);
	 }
}

class Hidden extends Input
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'hidden', ['readonly' => true]));
		parent::__construct(...$args);
	 }
}

class File extends Input
{
	public function __construct(...$args)
	 {
		$this->AddAttributes(new Attribute('type', 'file', ['readonly' => true]), new BooleanAttribute('multiple'), 'accept');
		parent::__construct(...$args);
	 }
}
?>