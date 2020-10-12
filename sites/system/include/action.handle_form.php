<?php
use \MaxieSystems as MS;
MSConfig::RequireFile('traits', 'containers', 'filesystemstorage');
$conf = new MS\FileSystemStorage('storage/fs_config.php', ['readonly' => true, 'root' => MSSE_INC_DIR]);
foreach($conf as $id => $fs_conf)
 {
	$fs = new DollyForms("fs_$id", ['log_exception' => false, 'show_e_msg' => true, 'field_omission_allowed' => true]);
	$has_err = false;
	foreach($fs_conf->fields as $i => $f_args)
	 {
		$f_args[0] = $i;
		$f = $fs->AddField(...$f_args);
		if($f->HasErrMsg()) $has_err = true;
	 }
	if(!empty($_POST['__redirect'])) $_SESSION['__redirect'] = $_POST['__redirect'];// check url!!!
	$fs->SetRedirect('/index.php?__dolly_action=handle_form');
	$d = $fs->GetData();
	if($has_err || $d->status_type)
	 {
		if($d->status_type)
		 {
			$type = $d->status_type;
			$msg = $d->status_msg ?: ('success' === $type ? ($fs_conf->msg_success ?: l10n()->message_sent) : l10n()->error_sending_message);
		 }
		else
		 {
			$type = 'error';
			$msg = 'Поля не прошли проверку!';
			$i = 0;
			foreach($fs as $n => $f)
			 {
				++$i;
				if($f->msg)
				 {
					$t = $f->title ?: "Поле №$i";
					$msg .= "<div>$t: $f->msg</div>";
				 }
			 }
		 }
?><!DOCTYPE html>
<html lang='<?=l10n()->GetLang()?>' prefix='og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#'>
<head>
<meta charset='utf-8' />
<title><?=l10n()->feedback_form?></title>
<meta name="robots" content="noindex,nofollow" />
<link href="/dolly_templates/css/handle_form.css" rel="stylesheet" />
</head>
<body data-action='handle_form'>
<div class='body'>
	<div class='status_msg' data-status='<?=$type?>'><?=$msg?></div>
	<div class='bottom'><a href='<?=empty($_SESSION['__redirect']) ? '/' : $_SESSION['__redirect']?>' class='close_messages'><?=l10n()->return_to_website?></a></div>
</div>
</body>
</html><?php
		die;
	 }
 }
MSFieldSet::Handle();
if(empty($_SESSION['__redirect'])) $redirect = '/';
else
 {
	$redirect = $_SESSION['__redirect'];
	unset($_SESSION['__redirect']);
 }
MS\HTTP::Redirect($redirect);
?>