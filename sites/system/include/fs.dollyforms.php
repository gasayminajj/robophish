<?php
use \MaxieSystems as MS;
class DollyForms extends MSFieldSet
{
	protected function Action(...$args)
	 {
		$conf = new MS\FileSystemStorage('storage/fs_config.php', ['readonly' => true, 'root' => MSSE_INC_DIR]);
		$id = str_replace('fs_', '', $this->GetId());
		if(isset($conf->$id)) $c = $conf->$id;
		else throw new EFSAction('Не настроена конфигурация.');
		$empty = true;
		$mail = new MSMail();
		$text = $this->ReplaceItem('__dolly_timestamp', date('Y.m.d H:i:s'), $c->template);
		$text = $this->ReplaceItem('__dolly_ip', MSConfig::GetIP(), $text);
		$i = 0;
		foreach($this as $n => $fld)
		 {
			if($fld->object instanceof \MSFieldSet\IFile)
			 {
				$upl = new StreamUploader($fld->input_name);
				if($file = $upl->LoadFile())
				 {
					$mail->AddFileContent($file['data'], $file['name']);
					$empty = false;
				 }
			 }
			else
			 {
				$v = trim($args[$i]);
				$text = $this->ReplaceItem($c->fields[$n][0], $v, $text);
				if('' !== $v) $empty = false;
			 }
			++$i;
		 }
		if($empty) throw new EFSAction('Нельзя отправить пустую форму! Заполните хотя бы одно поле.');
		$mail->SetFrom($c->from)->SetTo($c->email)->SetSubject($c->subject)->SetText($text)->Send();
		$orders = [];
		$fname = DOCUMENT_ROOT.'/orders.txt';
		if(file_exists($fname))
		 {
			$file = file_get_contents($fname);
			$orders = unserialize($file);
		 }
		$orders[] = array('date'    => date('Y-m-d'),
						  'content' => $text,
						  'status'  => 'in_work');
		file_put_contents($fname, serialize($orders));
		if($c->async_response || $c->async_headers)
		 {
			if($c->async_headers) foreach($c->async_headers as $hdr) header($hdr);
			die($c->async_response);
		 }
	 }

	private function ReplaceItem($n, $v, $text) { return str_replace('{'.$n.'}', $v, $text); }
}
?>