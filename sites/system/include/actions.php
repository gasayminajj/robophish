<?php
$actions = [
	'editor' => [
		'auth' => true,
		'sys_inc' => ['action.editor'],
	],
	'forms' => [
		'auth' => true,
		'sys_inc' => ['fs.save_form', 'action.forms'],
	],
	'handle_form' => [
		'auth' => false,
		'sys_inc' => ['fs.dollyforms', 'action.handle_form'],
	],
];
if(isset($actions[$_GET['__dolly_action']]))
 {
	$a = $actions[$_GET['__dolly_action']];
	if(false === $a['auth'] || Controllers::isAdmin())
	 {
		foreach($a['sys_inc'] as $fname) require_once(MSSE_INC_DIR."/$fname.php");
		die;
	 }
 }
?>