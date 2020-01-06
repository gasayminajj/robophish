	<?php session_start ();if (!empty ($_SESSION['admin'])){if ($_SESSION['admin']){?>
	<html><head>
	 <link rel="shortcut icon" href="admin.ico" >
	 <title>Админ панель</title>
	 <meta http-equiv="refresh" content="1;URL=admin.php" />
	</head>
	 <body>
	</body>
	</html>
	<?exit;}}$_SESSION['admin'] = false;
	include ('config.php');	
	function not_logged_in (){echo '<html><head>
	<title>Вход в админ панель</title>
	<link rel="shortcut icon" href="admin.ico" >
		<style>
		body {
		font-family: sans-serif;
		background: #00B3FE url("https://www.sunhome.ru/i/wallpapers/126/gradient.orig.jpg") center top no-repeat;
	}

		#slick-login {
	width: 220px;
	height: 155px;
	position: absolute;
	left: 50%;
	top: 50%;
	margin-left: -110px;
	margin-top: -75px;

	-webkit-animation: login 1s ease-in-out;
	-moz-animation: login 1s ease-in-out;
	-ms-animation: login 1s ease-in-out;
	-o-animation: login 1s ease-in-out;
	animation: login 1s ease-in-out;
}

#slick-login label {
	display: none;
}

.placeholder {
    color: #444;
}

#slick-login input[type="text"],#slick-login input[type="password"] {
	width: 100%;
	height: 40px;
	positon: relative;
	margin-top: 7px;
	font-size: 14px;
	color: #444;
	outline: none;
	border: 1px solid rgba(0, 0, 0, .49);

	padding-left: 20px;
	
	-webkit-background-clip: padding-box;
	-moz-background-clip: padding-box;
	background-clip: padding-box;
	border-radius: 6px;

	background-image: -webkit-linear-gradient(bottom, #FFFFFF 0%, #F2F2F2 100%);
	background-image: -moz-linear-gradient(bottom, #FFFFFF 0%, #F2F2F2 100%);
	background-image: -o-linear-gradient(bottom, #FFFFFF 0%, #F2F2F2 100%);
	background-image: -ms-linear-gradient(bottom, #FFFFFF 0%, #F2F2F2 100%);
	background-image: linear-gradient(bottom, #FFFFFF 0%, #F2F2F2 100%);

	-webkit-box-shadow: inset 0px 2px 0px #d9d9d9;
	box-shadow: inset 0px 2px 0px #d9d9d9;

	-webkit-transition: all .1s ease-in-out;
	-moz-transition: all .1s ease-in-out;
	-o-transition: all .1s ease-in-out;
	-ms-transition: all .1s ease-in-out;
	transition: all .1s ease-in-out;
}

#slick-login input[type="text"]:focus,#slick-login input[type="password"]:focus {
	-webkit-box-shadow: inset 0px 2px 0px #a7a7a7;
	box-shadow: inset 0px 2px 0px #a7a7a7;
}

#slick-login input:first-child {
	margin-top: 0px;
}

#slick-login input[type="submit"] {
	width: 100%;
	height: 50px;
	margin-top: 7px;
	color: #fff;
	font-size: 18px;
	font-weight: bold;
	text-shadow: 0px -1px 0px #5b6ddc;
	outline: none;
	border: 1px solid rgba(0, 0, 0, .49);

	-webkit-background-clip: padding-box;
	-moz-background-clip: padding-box;
	background-clip: padding-box;
	border-radius: 6px;

	background-color: #5466da;
	background-image: -webkit-linear-gradient(bottom, #5466da 0%, #768ee4 100%);
	background-image: -moz-linear-gradient(bottom, #5466da 0%, #768ee4 100%);
	background-image: -o-linear-gradient(bottom, #5466da 0%, #768ee4 100%);
	background-image: -ms-linear-gradient(bottom, #5466da 0%, #768ee4 100%);
	background-image: linear-gradient(bottom, #5466da 0%, #768ee4 100%);
	
	-webkit-box-shadow: inset 0px 1px 0px #9ab1ec;
	box-shadow: inset 0px 1px 0px #9ab1ec;
	
	cursor: pointer;

	-webkit-transition: all .1s ease-in-out;
	-moz-transition: all .1s ease-in-out;
	-o-transition: all .1s ease-in-out;
	-ms-transition: all .1s ease-in-out;
	transition: all .1s ease-in-out;
}

#slick-login input[type="submit"]:hover {
	background-color: #5f73e9;
	background-image: -webkit-linear-gradient(bottom, #5f73e9 0%, #859bef 100%);
	background-image: -moz-linear-gradient(bottom, #5f73e9 0%, #859bef 100%);
	background-image: -o-linear-gradient(bottom, #5f73e9 0%, #859bef 100%);
	background-image: -ms-linear-gradient(bottom, #5f73e9 0%, #859bef 100%);
	background-image: linear-gradient(bottom, #5f73e9 0%, #859bef 100%);

	-webkit-box-shadow: inset 0px 1px 0px #aab9f4;
	box-shadow: inset 0px 1px 0px #aab9f4;
	margin-top: 10px;
}

#slick-login input[type="submit"]:active {
	background-color: #7588e1;
	background-image: -webkit-linear-gradient(bottom, #7588e1 0%, #7184df 100%);
	background-image: -moz-linear-gradient(bottom, #7588e1 0%, #7184df 100%);
	background-image: -o-linear-gradient(bottom, #7588e1 0%, #7184df 100%);
	background-image: -ms-linear-gradient(bottom, #7588e1 0%, #7184df 100%);
	background-image: linear-gradient(bottom, #7588e1 0%, #7184df 100%);

	-webkit-box-shadow: inset 0px 1px 0px #93a9e9;
	box-shadow: inset 0px 1px 0px #93a9e9;
}

		</style>

	</head><body><center><br><br><br><br><br><br><br><br><br><table cellpadding=0 cellspacing=0 id=wrap><tr>
	<td align=center id=wraptd><table cellpadding=0 cellspacing=0><tr>
	</tr><tr>
	<td class=loginbox2 align=center>

	<form id="slick-login" action=index.php method=post>
	
	<label for="login">Логин:</label><input type="text" name="login" class="placeholder" placeholder="login">
	<label for="password">Пароль:</label><input type="password" name="password" class="placeholder" placeholder="password">
	<input type="submit" value="LOGIN">
		</form>
		
	</td></tr></table></td></tr></table></center></body></html>';
	exit;}
	if (!$_POST) not_logged_in ();
	if (!$_POST['login']) not_logged_in ();
	if (!$_POST['password']) not_logged_in ();
	if ($_POST['login']!= $adminlogin) not_logged_in ();
	if ($_POST['password']!= $adminpassw) not_logged_in ();
	$_SESSION['admin'] = true;?><html><head>
	<link rel="shortcut icon" href="admin.ico" >

	<title>Админ панель</title>
	<meta http-equiv="refresh" content="1;URL=admin.php" />
	</head><body></body></html>
	<? include admin.php ?>
						