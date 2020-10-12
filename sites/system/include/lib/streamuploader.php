<?php
class StreamUploader extends Uploader
{
	final public function Action() { return array('data' => file_get_contents($this->GetFileTmpName()), 'mime' => $this->GetFileMIME(), 'name' => $this->GetFileName()); }

	protected function DBAction(&$data, $file_name, $rel_name, $id, array $options = null) { throw new Exception('not implemented yet'); }

	/* public function LoadFiles($callback = null)
	 {
		$list = func_get_args();
		if(!isset($_FILES[$this->input_name]))
		 if($this->e_no_file) throw new EUploaderNoFile(UPLOADER_ERR_NO_FILE_MSG);
		 else return null;
		$files = array();
		foreach($_FILES[$this->input_name]['error'] as $key => $err)
		 {
			if($file = $this->Load($err, $_FILES[$this->input_name]['name'][$key], $_FILES[$this->input_name]['tmp_name'][$key], $_FILES[$this->input_name]['type'][$key]))
			 {
				if($callback)
				 {
					$list[0] = $key;
					$file['properties'] = call_user_func_array($callback, $list);
				 }
				$files[] = $file;
			 }
		 }
		return $files;
	 } */
}
?>