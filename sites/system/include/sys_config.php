<?php
error_reporting(E_ALL);

function __autoload($class_name)
{
	\MaxieSystems\Config::Autoload($class_name);
}

require_once(MSSE_LIB_DIR.'/config.php');
require_once(MSSE_LIB_DIR.'/msexceptionizer.php');
new MSExceptionizer();
set_exception_handler(['\MaxieSystems\Config', 'HandleException']);
register_shutdown_function(['\MaxieSystems\Config', 'OnShutDown']);
require_once(dirname(__FILE__).'/global_config.php');
?>