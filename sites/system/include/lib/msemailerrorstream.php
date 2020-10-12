<?php
\MaxieSystems\Config::RequireFile('msmail', 'idna_convert');

class MSEmailErrorStream implements IMSErrorStream
{
	final public function __construct($email_from, $email_to, $url)
	 {
		$this->email_from = $email_from;
		$this->email_to = $email_to;
		$this->url = $url;
	 }

	final public function InsertError(array $error)
	 {
		$text = '';
		foreach($error as $key => $value) $text .= $key.': '.$value.PHP_EOL;
		$this->SendMail($text);
	 }

	final public function InsertException(Exception $e)
	 {
		$this->SendMail('File: '.$e->getFile().PHP_EOL.'Line: '.$e->getLine().PHP_EOL.'Code: '.$e->getCode().PHP_EOL.'Class: '.get_class($e).PHP_EOL.PHP_EOL.$e->getMessage());
	 }

	final public function GetExceptionById($id) {}
	final public function GetErrorById($id) {}

	final protected function GetDecodedHost()
	 {
		if(empty($_SERVER['HTTP_HOST'])) return 'undefined host';
		$idn = new idna_convert();
		return $idn->decode($_SERVER['HTTP_HOST']);
	 }

	final protected function SendMail($text)
	 {
		$mail = new MSMail();
		$mail->SetTo($this->email_to);
		if($this->email_from) $mail->SetFrom($this->email_from);
		$host = $this->GetDecodedHost();
		$mail->SetSubject('MSSE error notification — '.$host);
		if(empty($_SERVER['REMOTE_ADDR'])) $ip_line = '$_SERVER["REMOTE_ADDR"] is empty.';
		elseif($ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) $ip_line = "REMOTE_ADDR: $ip";
		else $ip_line = '$_SERVER["REMOTE_ADDR"] is invalid';
		$text = 'Host: '.$host.PHP_EOL.'Request URI: '.@$_SERVER['REQUEST_URI'].PHP_EOL.(empty($_SERVER['HTTP_REFERER']) ? '' : 'HTTP Referer: '.$_SERVER['HTTP_REFERER'].PHP_EOL).$ip_line.PHP_EOL.date('Y-m-d H:i:s').PHP_EOL.$text;
		if(!empty($_SERVER['HTTP_HOST']))
		 {
			$host = 'http'.(empty($_SERVER['HTTPS']) || 'off' == $_SERVER['HTTPS'] ? '' : 's').'://'.$_SERVER['HTTP_HOST'];
			$text .= PHP_EOL.PHP_EOL.$host.@$_SERVER['REQUEST_URI'];
			if($this->url) $text .= PHP_EOL.'You can view detailed information here: '.$host.$this->url;
		 }
		$mail->SetText($text)->Send();
	 }

	private $email_from;
	private $email_to;
	private $url;
}
?>