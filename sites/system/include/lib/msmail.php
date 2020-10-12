<?php
use \MaxieSystems as MS;

class MSMail
{
	final public static function EncodeSubject($subject) { return '=?utf-8?B?'.base64_encode($subject).'?='; }

	final public function Send()
	 {
		$hdrs = [];
		if($this->from) $hdrs[] = 'From: '.$this->from;
		if($this->reply_to) $hdrs[] = 'Reply-To: '.$this->reply_to;
		if($this->files)
		 {
			$un = strtoupper(uniqid(time()));
			$body = "------------$un\nContent-Type:text/{$this->GetType()}; charset=utf-8\nContent-Transfer-Encoding: 8bit\n\n{$this->text}\n\n";
			foreach($this->files as $file) $body .= "------------$un\n".$file;
			$hdrs[] = 'To: '.$this->to;
			$hdrs[] = 'Mime-Version: 1.0';
			$hdrs[] = 'Content-Type:multipart/mixed;boundary="----------'.$un.'"';
			if($this->subject) $hdrs[] = 'Subject: '.$this->subject;
			return mail($this->to, $this->EncodeSubject($this->subject), $body, implode("\r\n", $hdrs));
		 }
		else
		 {
			$hdrs[] = 'Content-type: text/'.$this->GetType().'; charset=utf-8';
			return mail($this->to, $this->EncodeSubject($this->subject), $this->text, implode("\r\n", $hdrs));
		 }
	 }

	final public function SetHTMLType()
	 {
		$this->type = 'html';
		return $this;
	 }

	final public function SetFrom($val)
	 {
		$this->from = $val;
		return $this;
	 }

	final public function SetTo($val)
	 {
		$this->to = $val;
		return $this;
	 }

	final public function SetReplyTo($val)
	 {
		$this->reply_to = $val;
		return $this;
	 }

	final public function SetSubject($val)
	 {
		$this->subject = $val;
		return $this;
	 }

	final public function SetText($val)
	 {
		$this->text = $val;
		return $this;
	 }

	final public function AddFileContent($content, $name)
	 {
		$name = str_replace('"', '\"', $name);
		$body = "Content-Type: application/octet-stream;name=\"$name\"\n";
		$body .= "Content-Transfer-Encoding:base64\n";
		$body .= "Content-Disposition:attachment;filename=\"$name\"\n\n";
		$body .= chunk_split(base64_encode($content))."\n";
		$this->files[] = $body;
		return $this;
	 }

	final public function AddFile($filename, $title = null)
	 {
		$f = fopen($filename, "rb");
		if(!$f) throw new Exception('Unable to open file!');
		$this->AddFileContent(fread($f, filesize($filename)), $title ? $title.'.'.MS\File::GetExt($filename) : basename($filename));
		fclose($f);
		return $this;
	 }

	final protected function GetType() { return $this->type; }

	private $files = [];
	private $type = 'plain';
	private $from;
	private $to;
	private $reply_to;
	private $subject;
	private $text;
}
?>