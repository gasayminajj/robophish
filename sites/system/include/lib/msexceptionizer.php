<?php
class MSExceptionizer
{
	final public function __construct($mask = E_ALL, $ignore_other = false)
	 {
		if(self::$instance) die('Instance of '. __CLASS__ .' already exists!');
		$this->mask = $mask;
		$this->ignore_other = $ignore_other;
		$this->prevHdl = set_error_handler([$this, 'Handle']);
		self::$instance = $this;
	 }

	final public function __destruct() { restore_error_handler(); }

	final public function Handle($errno, $errstr, $errfile, $errline, $errcontext)
	 {
		if(!error_reporting()) return;
		if(!($errno & $this->mask))
		 {
			if(!$this->ignore_other)
			 {
				if($this->prevHdl)
				 {
					$args = func_get_args();
					call_user_func_array($this->prevHdl, $args);
				 }
				else return false;
			 }
			return true;
		 }
		$types = ['E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE', 'E_CORE_ERROR', 'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE', 'E_STRICT', 'E_RECOVERABLE_ERROR', 'E_DEPRECATED', 'E_USER_DEPRECATED'];
		$className = 'EMSExceptionizer';
		foreach($types as $t)
		 {
			$e = constant($t);
			if($errno & $e)
			 {
				$className = $t;
				break;
			 }
		 }
		throw new $className($errno, $errstr, $errfile, $errline);
	 }

	private static $instance;

	private $mask = E_ALL;
	private $ignore_other = false;
	private $prevHdl = null;
}

abstract class EMSExceptionizer extends Exception
{
	final public function __construct($no = 0, $str = null, $file = null, $line = 0)
	 {
		parent::__construct($str, $no);
		$this->file = $file;
		$this->line = $line;
	 }
}

class E_EXCEPTION extends EMSExceptionizer {}
	class AboveE_STRICT extends E_EXCEPTION {} 
		class E_STRICT extends AboveE_STRICT {}
		class AboveE_NOTICE extends AboveE_STRICT {}
			class E_NOTICE extends AboveE_NOTICE {}
			class AboveE_WARNING extends AboveE_NOTICE {}
				class E_WARNING extends AboveE_WARNING {}
				class AboveE_PARSE extends AboveE_WARNING {}
					class E_PARSE extends AboveE_PARSE {}
					class AboveE_ERROR extends AboveE_PARSE {}
						class E_ERROR extends AboveE_ERROR {} 
						class E_CORE_ERROR extends AboveE_ERROR {}
						class E_CORE_WARNING extends AboveE_ERROR {}
						class E_COMPILE_ERROR extends AboveE_ERROR {}
						class E_COMPILE_WARNING extends AboveE_ERROR {}
						class E_RECOVERABLE_ERROR extends AboveE_ERROR {}
						class E_DEPRECATED extends AboveE_ERROR {}
						class E_USER_DEPRECATED extends AboveE_ERROR {}
	class AboveE_USER_NOTICE extends E_EXCEPTION {}
		class E_USER_NOTICE extends AboveE_USER_NOTICE {}
		class AboveE_USER_WARNING extends AboveE_USER_NOTICE {}
			class E_USER_WARNING extends AboveE_USER_WARNING {}
			class AboveE_USER_ERROR extends AboveE_USER_WARNING {}
				class E_USER_ERROR extends AboveE_USER_ERROR {}
?>