<?php
namespace MSFieldSet;

use \MaxieSystems\TOptions;
use \MaxieSystems\HTML;

interface IField
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null);
	public function MakeInput();
	public function GetValue();
	public function Omitted();
}

interface IFieldAsync
{
	public function GetData();
}

interface IFile {}
interface IIgnoreValue {}

abstract class Field implements IField
{
	use TOptions;

	const OPTIONS_META = [
		'class' => ['type' => 'string', 'value' => ''],
		'disabled' => ['type' => 'bool', 'value' => false],
		'readonly' => ['type' => 'bool', 'value' => false],
		'required' => ['type' => 'bool', 'value' => false],
		'value' => ['set' => true],
	];

	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->owner = $owner;
		$this->name = $name;
		$this->title = $title;
		$m = self::OPTIONS_META;
		$m['__data'] = ['set' => true];
		$m['__field'] = ['type' => 'string', 'value' => ''];
		$m['__label_class'] = ['set' => true, 'type' => 'string,false', 'value' => ''];
		$m['__row_class'] = ['set' => true, 'type' => 'string,false', 'value' => ''];
		$this->AddOptionsMeta($m);
		$this->SetOptionsData($options);
	 }

	public function __debugInfo()
	 {
		return ['name' => $this->name, 'input_name' => $this->GetInputName(), 'field_set' => $this->owner->GetID()];
	 }

	final public function __get($name)
	 {
		if('id' === $name) return $this->GetID();
		elseif('name' === $name) return $this->name;
		elseif('title' === $name) return $this->title;
		elseif('input_name' === $name) return $this->GetInputName();
		elseif('type' === $name) return $this->GetType();
		elseif('fs' === $name) return $this->owner;
		throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8);
	 }

	final public function __set($name, $value) { throw new \Exception('Undefined property: '.get_class($this).'::$'.$name, 8); }

	final public function GetErrMsg()
	 {
		if($this->HasErrMsg($val))
		 {
			unset($_SESSION[$this->GetFieldSet()->GetSessionID()]['error'][$this->GetName()]);
			return $val;
		 }
	 }

	final public function HasErrMsg(&$msg = null)
	 {
		$sess_id = $this->GetFieldSet()->GetSessionID();
		$msg = ($r = isset($_SESSION[$sess_id]['error'][$this->GetName()])) ? $_SESSION[$sess_id]['error'][$this->GetName()] : null;
		return $r;
	 }

	final public function SetCheck($ch_name, array $options = [])
	 {
		if($this->check) throw new \EFSCheckIsSet('Проверка для поля `'.$this->GetFieldSet()->GetID().'`.`'.$this->GetName().'` уже установлена ('.get_class($this->check).').');
		if('\\' !== $ch_name[0]) $ch_name = "\MSFieldSet\\$ch_name";
		return $this->InitCheck(new $ch_name($this, $options));
	 }

	final public function GetType()
	 {
		if(null === $this->type)
		 {
			$c = get_class($this);
			$this->type = strtolower(false === ($pos = strrpos($c, '\\')) ? $c : substr($c, $pos + 1));
		 }
		return $this->type;
	 }

	final public function GetFieldSet() { return $this->owner; }
	final public function GetName() { return $this->name; }
	final public function GetTitle() { return $this->title; }
	final public function GetInputName() { return $this->GetFieldSet()->GetID().'_'.$this->GetName(); }
	final public function GetID() { return $this->GetFieldSet()->GetID().'_'.$this->GetName(); }

	final public function GetCheck()
	 {
		if(null === $this->check)
		 {
			$this->check = $c = false;
			if($this->GetOption('required')) $c = $this->default_check ?: '\\MSFieldSet\\IsNotEmpty';
			elseif($this->default_check) $c = $this->default_check;
			if($c) $this->InitCheck(new $c($this));
		 }
		return $this->check;
	 }

	final protected function SetErrMsg($val) { $_SESSION[$this->GetFieldSet()->GetSessionID()]['error'][$this->GetName()] = $val; }

	final protected function Validate($val)
	 {
		if($check = $this->GetCheck())
		 {
			$status = $check->Validate($val);
			if(true === $status);
			else
			 {
				$e = is_array($status) ? new \EFSCheckFailed(...$status) : new \EFSCheckFailed("$status");
				$this->SetErrMsg($e->GetMessage());
				throw $e;
			 }
		 }
	 }

	final private function InitCheck(\MSFieldSet\FSCheck $check) { return $this->check = $check; }

	protected $default_check = null;

	private $owner;
	private $name;
	private $title;
	private $check = null;
	private $type = null;
}

abstract class POSTField extends Field
{
	const OPTIONS_META = ['default' => ['set' => true], 'null' => ['type' => 'bool', 'value' => false], 'post_process' => ['type' => 'callable,null'], 'pre_process' => ['type' => 'callable,null'], 'filter' => ['type' => 'callable,null']];

	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->AddOptionsMeta(self::OPTIONS_META);
		parent::__construct($owner, $name, $title, $options);
	 }

	final public function GetValue($validate = false)
	 {
		$fs = $this->GetFieldSet();
		$name = $this->GetName();
		$is_running = $fs->IsRunning();
		if($this->OptionIsSet('value')) $val = $this->GetOption('value');
		elseif($fs->GetDataSource()->ValueExists($name, $val));
		elseif($is_running)
		 {
			if($this->Omitted())
			 {
				if($fs->FieldOmissionAllowed($name)) return new \FSVoidValue();
				else
				 {
					$e = new \ЕFSFieldOmitted("Для поля `$name` нет данных!");
					$this->SetErrMsg($e->GetMessage());
					throw $e;
				 }
			 }
		 }
		elseif($this->OptionIsSet('default')) $val = $this->GetOption('default');
		else $val = null;
		$val = $this->PreProcess($val, $is_running);
		if($validate) $this->Validate($val);
		else $val = $this->Filter($val, $is_running);
		return $this->PostProcess($val, $is_running);
	 }

	public function Omitted() { return !$this->GetFieldSet()->GetDataSource()->KeyExists($this->GetName()); }

	protected function PreProcess($value, $is_running) { return ($callback = $this->GetOption('pre_process')) ? call_user_func($callback, $value, $is_running, $this) : $value; }
	protected function PostProcess($value, $is_running) { return ($callback = $this->GetOption('post_process')) ? call_user_func($callback, $value, $is_running, $this) : $value; }
	protected function Filter($value, $is_running) { return ($callback = $this->GetOption('filter')) ? call_user_func($callback, $value, $is_running, $this) : $value; }
}

trait TRenderableInput
{
	public function MakeInput() { return (string)$this->MakeInputObject(); }

	protected function ConfAttrHandlers()
	 {
		$this->attr_handlers = [
			'class' => function(&$v, $n){
				if($this->required__classes)
				 {
					$c = $this->required__classes;
					if('' !== $v)
					 {
						if(false === strpos($v, ' ')) $c[$v] = $v;
						else foreach(array_filter(explode(' ', $v)) as $k) $c[$k] = $k;
					 }
					$v = implode(' ', $c);
				 }
			},
			'placeholder' => function(&$v, $n){if(true === $v) $v = $this->GetTitle();},
		];
	 }

	protected function MakeInputObject($type = null)
	 {
		$c = '\MaxieSystems\HTML\\'.($type ?: $this->GetType());
		return $this->ConfInputObject(new $c($this->obj_default_options));
	 }

	final protected function ConfInputObject($obj)
	 {
		foreach($obj as $k => $v)
		 {
			if('id' === $k) $obj->$k = $this->GetID();
			elseif('name' === $k) $obj->$k = $this->GetInputName();
			elseif('value' === $k) $obj->$k = $this->GetValue();
			elseif($this->OptionExists($k, $v) && $obj->IsEditable($k))
			 {
				if(isset($this->attr_handlers[$k])) $this->attr_handlers[$k]($v, $k);
				if(null !== $v) $obj->$k = $v;
			 }
		 }
		if($opt = $this->GetOption('data_x')) $obj->SetData($opt);
		return $obj;
	 }

	protected $required__classes = [];
	protected $attr_handlers = [];
	protected $obj_default_options = [];
}

abstract class RenderableInput extends POSTField
{
	use TRenderableInput;

	const OPTIONS_META = ['autocomplete' => ['type' => 'bool,string,null'], 'data_x' => ['set' => true, 'type' => 'array', 'value' => []], 'init' => ['set' => true, 'type' => 'string,bool', 'value' => 'auto'], 'placeholder' => ['type' => 'string,true', 'value' => ''], 'on_create' => ['type' => 'callable,null'], 'on_show' => ['type' => 'callable,null']];

	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->ConfAttrHandlers();
		$this->AddOptionsMeta(self::OPTIONS_META);
		parent::__construct($owner, $name, $title, $options);
		if($callback = $this->GetOption('on_create')) call_user_func($callback, $this);
	 }
}

class AdapterInput extends RenderableInput
{
	public function MakeInput() { if($callback = $this->GetOption('on_show')) return call_user_func($callback, $this); }
	public function Omitted() { return false; }
}

class Text extends RenderableInput
{
	const OPTIONS_META = ['list' => ['type' => 'string,null'], 'maxlength' => ['type' => 'int,string,null'], 'pattern' => ['type' => 'string', 'value' => '']];

	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->AddOptionsMeta(self::OPTIONS_META);
		$this->ChangeOptionsMeta('class', ['value' => 'form__input_text']);
		parent::__construct($owner, $name, $title, $options);
	 }

	protected function PreProcess($value, $is_running) { return parent::PreProcess(trim($value), $is_running); }
}

class Email extends Text
{
}

class Tel extends Text
{
	protected $default_check = '\\MSFieldSet\\IsPhoneNum';
}

class Number extends Text
{
	const OPTIONS_META = ['max' => ['type' => 'int,string,null'], 'min' => ['type' => 'int,string,null']];

	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->AddOptionsMeta(self::OPTIONS_META);
		parent::__construct($owner, $name, $title, $options);
	 }

	public function PreProcess($value, $is_running)
	 {
		$value = parent::PreProcess($value, $is_running);
		return is_numeric($value) ? $value : ($this->GetOption('null') ? null : 0);
	 }
}

class Hidden extends POSTField
{
	public function MakeInput() { return (new HTML\Hidden('id', $this->GetID(), 'name', $this->GetInputName(), 'value', $this->GetValue()))->SetData('name', $this->GetName())->__toString(); }
}

class HiddenIgnored extends Hidden implements IIgnoreValue {}

class Textarea extends Text
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->AddOptionsMeta(['cols' => ['type' => 'int,string,null'], 'rows' => ['type' => 'int,string,null']]);
		$this->ChangeOptionsMeta('class', ['value' => 'form__textarea']);
		parent::__construct($owner, $name, $title, $options);
	 }

	public function MakeInput() { return (string)$this->MakeInputObject()->SetHTML($this->GetValue()); }
}

abstract class Captcha extends Field implements IIgnoreValue
{
	final public function Omitted() { return false; }
}

class reCaptcha extends Captcha
{
	final public function __construct(\MSFieldSet $owner, $name, $title, array $o = null)
	 {
		$this->AddOptionsMeta(['secret_key' => [], 'site_key' => []]);
		foreach(['secret_key', 'site_key'] as $key)// заменить инициализирующимися опциями!
		 {
			if(empty($o[$key]))
			 {
				$o[$key] = \Registry::GetValue('recaptcha', $key);
				if(!$o[$key]) throw new \Exception("Не указан `$key`!");
			 }
		 }
		parent::__construct($owner, $name, $title, $o);
		$this->SetCheck('reCaptchaCheck', ['secret' => $o['secret_key']]);
	 }

	final public function GetValue()
	 {
		$this->Validate($_POST['g-recaptcha-response']);
	 }

	final public function MakeInput()
	 {
		\Page::AddJSLink('https://www.google.com/recaptcha/api.js');
		return "<div class='g-recaptcha' data-sitekey='{$this->GetOption('site_key')}' id='{$this->GetID()}'></div>";
	 }
}

class Securimage extends Captcha
{
	final public function __construct(\MSFieldSet $owner, $name, $title, array $o = null)
	 {
		parent::__construct($owner, $name, $title, $o);
		$this->SetCheck('SecurimageCheck');
	 }

	final public function MakeInput()
	 {
		$id = $this->GetID();
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/securimage/audio'))//$this->GetOption('use_audio'))
		 {
			$swf_url = '/securimage/securimage_play.swf?audio_file=/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000';
			$obj = '<object class="securimage_captcha__button _audio" type="application/x-shockwave-flash" data="'.$swf_url.'" width="24" height="24"><param name="movie" value="'.$swf_url.'" /></object>';
		 }
		else $obj = '';
		return '<div class="securimage_captcha">
	<img id="'.$id.'" src="/securimage/securimage_show.php" alt="CAPTCHA Image" class="securimage_captcha__image" width="215" height="80" />
	<input type="text" name="'.$this->GetInputName().'" maxlength="6" class="securimage_captcha__value" autocomplete="off" />
	<div class="securimage_captcha__buttons"><input type="button" class="securimage_captcha__button _reload" onclick="document.getElementById(\''.$id.'\').src = \'/securimage/securimage_show.php?\' + Math.random();" value="" title="Другое изображение" />'.$obj.'</div>
</div>';
	 }

	final public function GetValue()
	 {
		$val = @$_POST[$this->GetInputName()];
		$this->Validate($val);
		return $val;
	 }
}

class CheckBox extends POSTField
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->default_check = '\\MSFieldSet\\HasCheck';
		$this->AddOptionsMeta(['label' => ['value' => true]]);
		parent::__construct($owner, $name, $title, $options);
	 }

	public function MakeInput()
	 {
		$html = (new HTML\Hidden('name', $this->GetInputName(), 'value', 0)).(new HTML\CheckBox('id', $this->GetID(), 'name', $this->GetInputName(), 'value', 1, 'checked', $this->GetValue(), 'required', $this->GetOption('required')));
		if($l = $this->GetOption('label'))
		 {
			if(true === $l) $l = $this->GetTitle();
			return "<label>$html $l</label>";
		 }
		else return "$html";
	 }
}

class CheckBoxIgnored extends CheckBox implements IIgnoreValue {}

abstract class SelectInput extends RenderableInput
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->AddOptionsMeta(['data' => [], 'i_data_x' => ['type' => 'array', 'value' => []], 'default_option' => ['type' => 'array,string', 'value' => ''], 'f_value' => ['type' => 'string', 'value' => 'id'], 'f_title' => ['type' => 'string', 'value' => 'title']]);
		parent::__construct($owner, $name, $title, $options);
	 }
}

class Select extends SelectInput
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->AddOptionsMeta(['multiple' => ['type' => 'bool', 'value' => false]]);
		$this->ChangeOptionsMeta('class', ['value' => $this->default_css_class]);
		parent::__construct($owner, $name, $title, $options);
	 }

	public function MakeInput()
	 {
		if($func = $this->GetOption('on_show')) call_user_func($func);
		$o = $this->GetOptions('class', 'f_value', 'f_title', 'i_data_x');
		$o['name'] = $this->GetInputName();
		$o['id'] = $this->GetID();
		if($this->GetOption('multiple'))
		 {
			$o['class'] .= ' _multiple';
			$o['name'] .= '[]';
			unset($o['id']);
		 }
		$obj = $this->GetInputObject($this->GetOption('data'), $o);
		if($default = $this->GetOption('default_option'))
		 {
			if(!is_array($default)) $default = ['', $default];
			$obj->SetDefaultOption(...$default);
		 }
		$html = '';
		if($is_m = $this->GetOption('multiple'))
		 {
			$c = $this->GetOption('class');
			$wr_c = ($c ? $c.'_m_wr' : 'multiple_select_wr').' _'.$this->GetName();
			if('auto' === $this->GetOption('init')) $wr_c .= ' _autoinit';
			if(($values = $this->GetValue()) && is_array($values))
			 foreach(array_filter($values) as $v)
			  {
				$html .= $obj->SetSelected($v)->Make();
				if(is_object($obj->GetData()))
				 {
					if($r = $this->GetOption('reset')) throw new \Exception('Not implemented yet!');
					else $obj->GetData()->Rewind();
				 }
			  }
			$v = $default[0];
		 }
		else $v = $this->GetValue();
		$html .= $obj->SetSelected($v)->Make();
		return $is_m ? "<div id='{$this->GetID()}' class='$wr_c'>$html</div>" : $html;
	 }

	protected function GetInputObject($data, array $o)
	 {
		return new \Select($data, $o);
	 }

	protected $default_css_class = 'form__select';
}

class DBSelect extends Select
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$this->ChangeOptionsMeta('data', ['type' => 'array']);
		parent::__construct($owner, $name, $title, $options);
	 }

	protected function GetInputObject($data, array $o)
	 {
		return new \Select(\MaxieSystems\DB::Select(...$data), $o);
	 }
}

trait TFile
{
	public function MakeInput() { return (new HTML\File('id', $this->GetID(), 'name', $this->GetInputName(), 'accept', $this->GetOption('accept')))->__toString(); }
	public function GetValue() {}
}

class File extends Field implements IFile
{
	use TFile;

	const OPTIONS_META = ['accept' => ['type' => 'string', 'value' => '']];

	public function __construct(\MSFieldSet $owner, $name, $title, array $o = null)
	 {
		$this->AddOptionsMeta(self::OPTIONS_META);
		parent::__construct($owner, $name, $title, $o);
	 }

	public function Omitted() { return false; }// это заглушка! можно определить, отправлялся ли файл.
}

class NewPassword extends Field
{
	final public function __construct(\MSFieldSet $owner, $name, $title, array $o = null)
	 {
		parent::__construct($owner, $name, $title, $o);
		$this->SetCheck('PasswordCheck');
	 }

	final public function MakeInput()
	 {
		return '<div class="form__new_password">
	<input type="password" id="'.$this->GetID().'" name="'.$this->GetInputName().'[value]" autocomplete="off" required="required" class="form__input_password" maxlength="48" />
	<input type="button" value="Показать пароль" class="form__new_password_show _hidden" />
	<div class="form__new_password_bar _hidden"><div class="form__new_password_bar_area"></div></div>
	<div class="form__new_password_strength"></div>
	<input type="password" id="'.$this->GetID().'_copy" name="'.$this->GetInputName().'[copy]" autocomplete="off" class="form__input_password" maxlength="48" placeholder="повторите пароль, чтоб не ошибиться" />
	<div class="form__new_password_not_eq"></div>
</div>';
	 }

	final public function GetValue()
	 {
		$val = @$_POST[$this->GetInputName()];
		$this->Validate($val);
		return $val['value'];
	 }

	final public function Omitted() { return false; }
}

class Radio extends SelectInput
{
	public function MakeInput()
	 {
		$d = $this->GetOption('data');
		$obj = (new \Radio($d, $this->GetOption('f_value') ?: (Select::IsDataCallable($d) ? 'id' : null), $this->GetOption('f_title') ?: (Select::IsDataCallable($d) ? 'title' : null), $this->GetOption('i_data_x')))->SetId($this->GetID())->SetName($this->GetInputName());
		if($v = $this->GetOption('class')) $obj->SetClassName($v);
		return $obj->SetSelected($this->GetValue())->Make();
	 }
}

class Password extends POSTField
{
	public function __construct(\MSFieldSet $owner, $name, $title, array $o = null)
	 {
		parent::__construct($owner, $name, $title, $o);
		$this->SetCheck();
	 }

	public function MakeInput()
	 {
		$ac = $this->GetOption('autocomplete');
		return '<input class="form__input_password" type="password" id="'.$this->GetID().'" required="required" name="'.$this->GetInputName().'" maxlength="48"'.(false === $ac || 'off' === $ac ? ' autocomplete="off"' : (true === $ac || 'on' === $ac ? ' autocomplete="on"' : '')).' />';
	 }
}

class Date extends RenderableInput
{
	const OPTIONS_META = ['max' => ['type' => 'string,null'], 'min' => ['type' => 'string,null'], 'null_label' => ['type' => 'string', 'value' => 'Не указывать дату'], 'null_name' => ['type' => 'string', 'value' => ''], 'null_title' => ['type' => 'string', 'value' => ''], 'time' => ['type' => 'bool', 'value' => false]];

	public function __construct(\MSFieldSet $owner, $name, $title, array $options = null)
	 {
		$m = self::OPTIONS_META;
		$m['null_name']['value'] = $name.'__null';
		$this->AddOptionsMeta($m);
		parent::__construct($owner, $name, $title, $options);
	 }

	public function MakeInput()
	 {
		if($func = $this->GetOption('on_show')) call_user_func($func);
		return '<input id="'.$this->GetID().'" type="date" name="'.$this->GetInputName().'" value="'.$this->GetValue().'" />';
	 }
}
?>