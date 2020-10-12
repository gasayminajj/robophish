<?php
use \MaxieSystems as MS;
use \MaxieSystems\SimpleConfig as Conf;
MS\Config::DisplayErrors(false);
MS\Config::ErrorTracking(false, E_STRICT, E_CORE_WARNING, E_DEPRECATED);
MS\Config::RegisterClasses('arrayresult', 'colconf', 'db', 'dbregval', 'dbtable', 'document.ui', 'dropdown', 'dropdownlist', 'events', 'file', 'filesystemstorage', 'fileuploader', 'filter', 'form', 'format', 'formatdate', 'html', 'http', 'idna_convert', 'imageprocessor', 'imageuploader', 'imageuploaderurl', 'imserrorstream', 'mk', 'ms', 'ms4xxlog', 'msauthenticator', 'msbanners', 'msbreadcrumbs', 'mscache', 'mscfg', 'mschangepassword', 'mscontactinfo', 'msdataloader', 'msdebuginfo', 'msdberrorstream', 'msdbtable', 'msdownloadproxy', 'mseditpairs', 'msemailerrorstream', 'msemailtpl', 'mserrorstream', 'msfaq', 'msfbuttons', 'msfieldset', 'msfiles', 'msgqueue', 'msicons', 'msimages', 'msmail', 'msmaps', 'msmessagefieldset', 'msnotifications', 'msnotificationsviewer', 'msoauth2', 'msoptions', 'mspagenav', 'mspassword', 'msphpinfo', 'mssearch', 'mssedomains', 'mssimplelist', 'mssmusers', 'mstable', 'mstableorder', 'msvideos', 'mswatermark', 'page', 'pagetreeaction', 'queue', 'radio', 'registry', 'searchselect', 'select', 'smprofile', 'sqlexpr', 'streamuploader', 'sunder', 'timeleft', 'timemeter', 'pagetree', 'unifiedresult', 'uploader', 'url', 'watermark');
MS\Config::RequireFile('traits', 'containers', 'l10n');
MS\Config::SetErrorStreams(new MSErrorStream());
$locale = 'ru_RU';
setlocale(LC_ALL, "$locale.utf8");
setlocale(LC_NUMERIC, 'en_US');
date_default_timezone_set('Europe/Moscow');

interface IConst
{
	const JQUERY = 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js';
	const YMAPS = 'https://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU';
	const MSAPIS = 'https://msapis.com';
	const AUTH_SESS_LEN = 60;
}

MS\Config::AddAutoload(function($lower_class_name){
	$fname = MSSE_INC_DIR."/class.$lower_class_name.php";
	if(file_exists($fname))
	 {
		require_once($fname);
		return true;
	 }
});

require_once(DOCUMENT_ROOT.'/lib/Settings.php');
new MS\L10N\Storage(Conf::Instance('main')->language ?: 'ru', ['root' => DOCUMENT_ROOT, 'dir' => '/languages']);
function l10n($dir = '') { return '' === $dir ? MS\L10N\Storage::Instance() : MS\L10N\Storage::Instance()->__invoke($dir); }
class MSConfig extends MS\Config {}
?>