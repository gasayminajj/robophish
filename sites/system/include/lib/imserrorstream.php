<?php
interface IMSErrorStream
{
	public function InsertException(Exception $e);
	public function InsertError(array $error);
	public function GetExceptionById($id);
	public function GetErrorById($id);
}
?>