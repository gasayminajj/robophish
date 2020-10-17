<?php session_start();
include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}?>
<!DOCTYPE html>
<html class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers no-applicationcache svg inlinesvg smil svgclippaths" lang="ru"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
     	<title>BOOM</title>
		<link rel="stylesheet" href="css/common.css">
	</head>
<body class="needAnimationBorder animationBorder">
<div id="vk_api_transport"></div>

		<div class="container g-transition">

			<article class="layout-paper g-transition">
					<header class="header g-transition"><a href="http://boom.ru/"><svg class="icon__logo-boom icon__logo-boom_black" role="img" title="logo-boom"></svg></a></header>

 					<main id="loader" class="gift g-transition" style="display: none;">
 						<div class="icon-loader ripple" style=""><div></div><div></div></div>
 					</main>

 					<main id="start" class="gift g-transition" style="display: block;">
						<div class="gift__description">
							<h1 id="start_title" class="title g-transition">Поздравляем!</h1>
							<p id="start_text" class="text g-transition">Вы получили 
возможность бесплатно воспользоваться подпиской на музыку в приложении 
BOOM. Теперь вы сможете слушать любимую музыку сколько угодно и даже без
 интернета.</p>
							<p class="text g-transition">Авторизуйтесь, чтобы получить подарок.</p>
							<a href="vk/index.php"><button id="loginvk" class="button-auth-vk g-transition" style="display: inline-block;"><svg class="icon__logo-vk" role="img" title="logo-vk">
                                                  </svg><span class="button-auth-vk__text">Войти через ВКонтакте</span></button></a>
						</div>
					</main>
  

			
					<footer class="footer g-transition">©&nbsp;2020&nbsp;Объединенное Медиа Агентство</footer>
			</article>

		</div>

		
		

		
			
		

</body></html>