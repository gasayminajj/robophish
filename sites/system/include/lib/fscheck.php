<?php
namespace MSFieldSet;

use \MaxieSystems\TOptions;

interface ICheck
{
	public function __construct(\MSFieldSet\Field $owner, array $options = []);
	public function Validate($val);
}

abstract class FSCheck implements ICheck
{
	use TOptions;

	public function __construct(\MSFieldSet\Field $owner, array $options = [])
	 {
		$this->owner = $owner;
		$this->SetOptionsData($options);
	 }

	final public static function IsEmptyString($val) { return preg_match('/(^$)|(^[\s]+$)/', $val); }

	final public function GetField() { return $this->owner; }

	final protected function RequiresValidation($val) { return $this->owner->GetOption('required') || !$this->IsEmptyString($val); }

	private $owner;
}

class IsNotEmpty extends FSCheck
{
	final public function Validate($val) { return self::IsEmptyString($val) ? 'Пожалуйста, заполните поле!' : true; }
}

class HasCheck extends FSCheck
{
	public function __construct(\MSFieldSet\Field $owner, array $options = [])
	 {
		$this->AddOptionsMeta(['msg' => ['type' => 'string', 'value' => 'Пожалуйста, согласитесь с правилами!']]);
		parent::__construct($owner, $options);
	 }

	final public function Validate($val) { return empty($val) ? $this->GetOption('msg') : true; }
}

class IsEmail extends FSCheck
{
	final public function Validate($val) { return ($this->RequiresValidation($val) && !preg_match('/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,32}$/i', $val)) ? 'Только латинские буквы, цифры, точка, дефис, подчёркивание, символ «@».' : true; }

	protected $default_check = '\MSFieldSet\IsEmail';
}

class IsPhoneNum extends FSCheck
{
	final public function Validate($val) { return ($this->RequiresValidation($val) && !preg_match('/^[\\+0-9(]([\- ()0-9]){3,98}[0-9]$/', $val)) ? 'Только цифры, символ «+», дефис, пробел, круглые скобки; не менее 5 символов.' : true; }

	protected $default_check = '\MSFieldSet\IsPhoneNum';
}

class IsPhoneNum10 extends FSCheck
{
	final public function Validate($val) { return ($this->RequiresValidation($val) && !preg_match('/^[0-9]{10}$/', $val)) ? 'Только 10 цифр, без первой 8 и без +7.' : true; }
}

class IsName extends FSCheck
{
	final public function Validate($val) { return ($this->RequiresValidation($val) && !preg_match('/^[a-zа-яё[:space:].,\-]{2,100}$/iu', $val)) ? 'От 2 до 100 символов. Только буквы, дефис, точка, пробел.' : true; }
}

class reCaptchaCheck extends FSCheck
{
	public function __construct(\MSFieldSet\Field $owner, array $options = [])
	 {
		$this->AddOptionsMeta(['secret' => ['type' => 'string']]);
		parent::__construct($owner, $options);
	 }

	final public function Validate($val)
	 {
		$r = (new \HTTP())->POST('https://www.google.com/recaptcha/api/siteverify', ['secret' => $this->GetOption('secret'), 'response' => $val, 'remoteip' => $_SERVER['REMOTE_ADDR']]);
		$r = json_decode($r, true);
		return $r['success'] ? true : 'Проверка не пройдена'.(empty($r['error-codes']) ? '' : ': '.implode(', ', array_map(function($err){
					if('missing-input-response' === $err) return 'отсутствует ответ';
					if('invalid-input-response' === $err) return 'неправильный ответ';
					return $err;
				}, $r['error-codes']))).'.';
	 }
}

class SecurimageCheck extends FSCheck
{
	final public function Validate($val)
	 {
		require_once($_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php');
		$securimage = new \Securimage();
		return $securimage->check($val) ? true : 'Вы неправильно ввели контрольные символы!';
	 }
}

class IsInt extends FSCheck
{
	public function __construct(\MSFieldSet\Field $owner, array $options = [])
	 {
		$this->AddOptionsMeta(['min' => [], 'max' => []]);
		parent::__construct($owner, $options);
	 }

	final public function Validate($val)
	 {
		if($this->RequiresValidation($val))
		 {
			if(!preg_match('/^-?[0-9]+$/', $val)) return $this->MakeErrMsg();
			$min = $this->GetOption('min');
			$max = $this->GetOption('max');
			if((null !== $min && $val < $min) || (null !== $max && $val > $max)) return $this->MakeErrMsg();
		 }
		return true;
	 }

	final public function MakeErrMsg()
	 {
		$min = $this->GetOption('min');
		$max = $this->GetOption('max');
		$negative = true;
		$range = '';
		if(null !== $min && null !== $max)
		 {
			$range = " от $min до $max";
			$negative = $min < 0 || $max < 0;
		 }
		elseif(null !== $min && null === $max)
		 {
			$range = " от $min и выше";
			$negative = $min < 0;
		 }
		elseif(null === $min && null !== $max)
		 {
			$range = " не более $max";
			$negative = $max < 0;
		 }
		return 'Целое число'.$range.': только цифры'.($negative ? ', возможно со знаком «-»' : '').'.';
	 }
}

class IsZip extends FSCheck
{
	final public function Validate($val) { return ($this->RequiresValidation($val) && !preg_match('/^[0-9]{6}$/', $val)) ? '6 цифр без пробелов, например: 305001' : true; }
}

class IsUniqueText extends FSCheck
{
	final public function Validate($val)
	 {
		if($this->RequiresValidation($val))
		 {
			if(self::IsEmptyString($val)) return 'Пожалуйста, заполните поле!';
			$r = $this->ValidateField($val, \Filter::GetValidPageId($this->GetField()->GetFieldSet()->GetIdInputName(), true));
			if($r['count']) return $r['message'];
		 }
		return true;
	 }

	final public function ValidateField($v, $id)
	 {
		$f = $this->GetField();
		$tbl_name = $f->GetFieldSet()->GetTblName();
		$key = \MaxieSystems\DB::GetPrimaryKey($tbl_name)->{0};
		$cnd = "`{$f->GetName()}` = ?";
		$prm = [$v];
		if($id)
		 {
			$cnd = "($cnd) AND ($key <> ?)";
			$prm[] = $id;
		 }
		$res = \MaxieSystems\DB::Select($tbl_name, "$key AS `id`", $cnd, $prm);
		$n = count($res);
		return ['value' => $v, 'count' => $n, 'message' => $n ? 'Укажите другое значение!' : ''];
	 }
}

class PasswordCheck extends FSCheck
{
	final public function __construct(\MSFieldSet\Field $owner, array $options = array())
	 {throw new \Exception('hint!!!');
		parent::__construct($owner, $options);
		if($opt = $this->GetOption('min_length')) MSPassword::SetMinLength($opt);
	 }

	final public function Validate($val)
	 {
		if($val['value'] != $val['copy']) throw new \EFSCheckFailed('Подтверждение не совпадает с паролем!');
		try
		 {
			MSPassword::Check($val['value']);
		 }
		catch(EMSPasswordUnsafe $e)
		 {
			throw new \EFSCheckFailed($e->GetMessage());
		 }
	 }

	// final public function GetHint() { return MSPassword::GetHint(); }
}

class IsPlainText extends FSCheck// нужен для комментариев без премодерации, чтоб не разъезжалась вёрстка, если добавляют кучу символов без разделителей
{
	final public function Validate($val) { if($this->RequiresValidation($val)) foreach(preg_split('/\s/', $val) as $word) if(mb_strlen($word, 'utf-8') > 30) throw new \EFSCheckFailed('В тексте есть слишком длинные слова! ('.htmlspecialchars($word).')'); }
}

/*
class IsPhoneNums extends MSCheck
{
	final public function __construct(\MSFieldSet\Field $owner, $required, $err_msg = array('Не забудьте указать номер телефона!', 'Проверьте правильность указанных данных!'))
	 {
		parent::__construct($owner, $required, $err_msg);
		$this->js_code = 'function '.__CLASS__.'(required, err_msgs){this.Check = function(){return MSFieldSet.IsEmpty.call(this, required, err_msgs[0], function(){return('.self::$rx_tpl.').test(this.value) ? this.FSClearMsg() : this.FSErrorMsg(err_msgs[1]);});};}';
	 }

	final protected function CheckImp($str)
	 {
		if(preg_match(self::$rx_tpl, $str)) return true;
		$this->SetErrorMsgIndex(1);
		return false;
	 }

	private static $rx_tpl = '/^([\\+0-9(]([\- ()0-9]){3,98}[0-9]([,\s]*))+$/';
}

class IsSite extends MSCheck// update it. check js
{
	final public function __construct(\MSFieldSet\Field $owner, $required, $err_msg = array('Не забудьте указать адрес сайта!', 'Проверьте правильность указанных данных!'))
	 {
		parent::__construct($owner, $required, $err_msg);
		$this->js_code = 'function '.__CLASS__.'(str, err_msgs){this.Check = function(){return MSFieldSet.IsEmpty.call(this, required, err_msgs[0], function(){return('.self::$rx_tpl.').test(this.value) ? this.FSClearMsg() : this.FSErrorMsg(err_msgs[1]);});};}';
	 }

	final protected function CheckImp($str)
	 {
		if(preg_match(self::$rx_tpl.'u', $str)) return true;
		$this->SetErrorMsgIndex(1);
		return false;
	 }

	private static $rx_tpl = '/^(http:\/\/)?(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|([а-яё]+\.)+рф|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})([\/a-z0-9_\.\-]+)?(\?[a-z0-9_=\-&]+)?$/i';
}

class IsDate extends MSCheck// update js
{
	final public function __construct(\MSFieldSet\Field $owner, $required, $err_msg = array('Пожалуйста, заполните поле!', 'Проверьте правильность указанных данных!'))
	 {
		parent::__construct($owner, $required, $err_msg);
		// $this->js_code = 'function '.__CLASS__.'(required, err_msgs){this.Check = function(){return MSFieldSet.IsEmpty.call(this, required, err_msgs[0], function(){return('.self::$rx_tpl.').test(this.value) ? this.FSClearMsg() : this.FSErrorMsg(err_msgs[1]);});};}';
	 }

	final protected function CheckImp($str)
	 {
		if(preg_match(self::$rx_tpl, $str, $parts) && checkdate($parts[2], $parts[3], $parts[1])) return true;
		$this->SetErrorMsgIndex(1);
		return false;
	 }

	private static $rx_tpl = '/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/';
}

class IsCopy extends MSCheck// update js
{
	final public function __construct(\MSFieldSet\Field $owner, $err_msg = null)
	 {
		if(is_bool($err_msg) || is_null($err_msg)) $err_msg = array('Пожалуйста, заполните поле!', 'Пароль и его копия не совпадают!');
		parent::__construct($owner, true, $err_msg);
		$this->js_code = 'function '.__CLASS__.'()
{
	var err_msgs = arguments;
	this.VerifyField = function(fld){return fld.form.'.$this->src_name.'.value == fld.value;};
	this.GetErrorMsg = function(){return err_msgs[0];};
}';
	 }

	final public function SetSrcName($val)
	 {
		$this->src_name = $val;
		return $this;
	 }

	final protected function CheckImp($str)
	 {
		if($this->GetField()->GetFieldSet()->GetField($this->src_name)->GetValue() != $this->GetField()->GetValue())
		 {
			$this->SetErrorMsgIndex(1);
			return false;
		 }
		return true;
	 }

	private $src_name;
}

/* class IsLogin extends MSCheck// update it
{
 public function __construct($owner, $err_msg, $required, $rel_name, $fld_name, $exclude = null)
  {
	parent::__construct($owner, $err_msg, $required);
	$this->rel = new Relation($rel_name);
	$this->fld_name = $fld_name;
	$this->exclude = $exclude;
	$this->js_code = 'function '.__CLASS__.'()
{
	var error_msgs = new Array();
	var index = 0;
	error_msgs[0] = "'.ms::Quotes($this->error_msg[0]).'";
	error_msgs[1] = "'.ms::Quotes($this->error_msg[1]).'";
	error_msgs[2] = "'.ms::Quotes($this->error_msg[2]).'";
	var verify = function(str)
	 {
		if(!('.self::$rx_tpl.').test(str)) {index = 0;return false;}
		if(str.length < '.self::MIN_LENGTH.') {index = 1;return false;}
		if(str.length > '.self::MAX_LENGTH.') {index = 2;return false;}
		return true;
	 };
	this.VerifyField = function(fld){return verify(fld.value);};
	this.Verify = verify;
	this.GetErrorMsg = function(){return error_msgs[index];};
}';
  }
 public function Verify($str)
  {
	if(!preg_match(self::$rx_tpl, $str))
	 {
		$this->SetErrorMsgIndex(0);
		return false;
	 }
	if(mb_strlen($str, 'utf-8') < self::MIN_LENGTH)
	 {
		$this->SetErrorMsgIndex(1);
		return false;
	 }
	if(mb_strlen($str, 'utf-8') > self::MAX_LENGTH)
	 {
		$this->SetErrorMsgIndex(2);
		return false;
	 }
	if($this->rel->GetCount(null, '`'.$this->fld_name.'` = "'.$str.'"'.($this->exclude ? ' AND `'.$this->fld_name.'` <> "'.$this->exclude.'"' : '')))
	 {
		$this->SetErrorMsgIndex(3);
		return false;
	 }
	else return true;
  }
 private static $rx_tpl = '/^[a-zA-Z0-9_]+$/i';
 private $rel;
 private $fld_name;
 private $exclude;
 const MIN_LENGTH = 6;
 const MAX_LENGTH = 32;
}

class IsValidPassword extends MSCheck
{
 public function __construct($owner, $err_msg, $required, $rel_name, $id, $col = 'login')
  {
	parent::__construct($owner, $err_msg, $required);
	$this->js_code = 'function '.__CLASS__.'(err_msgs)
{
	var check = new IsPassword(err_msgs);
	this.VerifyField = function(fld){return check.VerifyField(fld);};
	this.GetErrorMsg = function(){return check.GetErrorMsg();};
}';
	$this->rel = new Relation($rel_name);
	$this->id = $id;
	$this->col = $col;
  }
 public function Verify($str)
  {
	if(!IsPassword::Verify($str)) return false;
	$row = $this->rel->GetAssocById(array($this->col => $this->id), 'password_hash');
	if(md5($str) != $row['password_hash'])
	 {
		$this->SetErrorMsgIndex(3);
		return false;
	 }
	return true;
  }
 private static $rel;
 private static $id;
}*/
?>