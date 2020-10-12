<?php
// Этот файл обязательно должен работать на PHP 5.2

class EPreInstallCheckFailed extends Exception {}

abstract class MSPreInstallCheck
{
	abstract public function Run();
}

abstract class MSPreInstallCheckManager extends MSPreInstallCheck
{
	final public function SetData($check_id, $arg)
	 {
		if($this->checks[$check_id]['object']) throw new Exception("Duplicate id: $check_id.");
		$args = func_get_args();
		array_shift($args);
		$c = new ReflectionClass($this->checks[$check_id]['class']);
		$this->checks[$check_id]['object'] = $c->newInstanceArgs($args);
	 }

	final public function Run()
	 {
		$ret_val = array();
		foreach($this->checks as $id => $check)
		 if($check['object'])
		  {
			$r = $check['object']->Run();
			if(true === $r);
			elseif(true === $this->OnFail($id, $r)) $ret_val[] = $r;
			else throw new EPreInstallCheckFailed(isset($r->message) ? $r->message : "$id: check failed.");
		  }
		 else throw new Exception("Pre install check '$id' is not initialized.");
		return $ret_val ? $ret_val : true;
	 }

	abstract protected function OnFail($id, stdClass $r);

	final protected function SetChecksMeta(array $meta) { foreach($meta as $k => $c) $this->checks[$k] = array('class' => $c, 'object' => null); }

	private $checks = array();
}

class PHPVersionCheck extends MSPreInstallCheck
{
	final public function __construct($min_ver, $max_ver)
	 {
		if(version_compare($min_ver, $max_ver) > 0) throw new Exception('min ver '.$min_ver.' is greater than max ver '.$max_ver);
		$this->min_ver = $min_ver;
		$this->max_ver = $max_ver;
	 }

	final public function Run()
	 {
		if(version_compare(PHP_VERSION, $this->min_ver) < 0) return (object)array('min' => $this->min_ver, 'val' => PHP_VERSION, 'result' => -1);
		if(version_compare(PHP_VERSION, $this->max_ver) >= 0) return (object)array('max' => $this->max_ver, 'val' => PHP_VERSION, 'result' => 1);
		return true;
	 }

	private $min_ver;
	private $max_ver;
}

class PHPExtensionsCheck extends MSPreInstallCheck
{
	final public function __construct(array $required)
	 {
		$this->required = $required;
	 }

	final public function Run()
	 {
		$m = array();
		foreach($this->required as $ext) if(!extension_loaded($ext)) $m[] = $ext;
		return $m ? (object)array('items' => $m, 'message' => 'PHP extensions missing: '.implode(', ', $m)) : true;
	 }

	private $required;
}

// class PHPConfigCheck extends MSPreInstallCheck
// {
	// final public function __construct()
	// {
		
	// }
// }

class ApacheModulesCheck extends MSPreInstallCheck
{
	final public function __construct(array $required)
	 {
		$this->required = $required;
	 }

	final public function Run()
	 {
		return ($r = array_diff($this->required, apache_get_modules())) ? (object)array('items' => $r, 'message' => 'Apache modules missing: '.implode(', ', $r)) : true;
	 }

	private $required;
}

class MySQLConnectionCheck extends MSPreInstallCheck
{
	final public function __construct($host, $user, $password, $db_name)
	 {
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->db_name = $db_name;
		// if(!$data['user']) $err_msgs[] = Lang::EmptyDBUser();
		// if(!$data['password']) $err_msgs[] = Lang::EmptyDBPassword();
		// if(!$data['name']) $err_msgs[] = Lang::EmptyDBName();
		// if(!$data['host']) $err_msgs[] = Lang::EmptyDBHost();
	 }

	final public function Run()
	 {
		// $err_msgs = array();
		// if($err_msgs) throw new EError(implode('<br/>', $err_msgs));
		try
		 {
			$pdo = new PDO("mysql:host=$this->host", $this->user, $this->password, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));//;dbname=$this->db_name
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$res = $pdo->query('SHOW DATABASES');
			$dbs = array();
			while($row = $res->fetch(PDO::FETCH_ASSOC)) $dbs[$row['Database']] = $row['Database'];
			// var_dump($dbs);
			// $res = Installer::$pdo->query('SHOW ENGINES');
			// $engines = array('InnoDB' => false);
			// while($row = $res->fetch(PDO::FETCH_ASSOC)) if(isset($engines[$row['Engine']]) && $row['Support'] != 'NO') $engines[$row['Engine']] = true;
			// if(in_array(false, $engines, true))
			 // {
				// $err_msgs = array();
				// foreach($engines as $engine => $available) if(!$available) $err_msgs[] = "Таблицы `$engine` недоступны.";
				// throw new EImpossible(implode('<br/>', $err_msgs));
			 // }
			return true;
		 }
		catch(PDOException $e)
		 {
			return (object)array('message' => $e->getMessage(), 'code' => $e->getCode(), 'type' => 'PDOException');
		 }
	 }

	private $host;
	private $user;
	private $password;
	private $db_name;
}
?>