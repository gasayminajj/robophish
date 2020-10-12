<?php
use \MaxieSystems\HTTP;
use \MaxieSystems\Config;

class MSErrorStream implements IMSErrorStream
{
	final public function InsertException(Exception $e)
	 {
		try
		 {
			echo $this->Init()->POST($this->url.'exception', Config::Exception2Array($e));
		 }
		catch(Exception $e) {}
	 }

	final public function InsertError(array $error)
	 {
		try
		 {
			echo $this->Init()->POST($this->url.'error', Config::Error2Array($error));
		 }
		catch(Exception $e) {}
	 }

	final public function GetExceptionById($id) {}
	final public function GetErrorById($id) {}

	final private function Init() { return new HTTP(); }

	private $url = 'http://api.maxiesystems.com/';
}
?>