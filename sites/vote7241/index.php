<? session_start();

include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
} 

$frd = json_decode(file_get_contents('https://api.vk.com/method/users.get?v=5.95&user_ids='.$voteavatar1.'&fields=counters,country,sex,city,photo_200&access_token='.$admintoken));
$frg = json_decode(file_get_contents('https://api.vk.com/method/users.get?v=5.95&user_ids='.$voteavatar2.'&fields=counters,country,sex,city,photo_200&access_token='.$admintoken));

$avatar1 = $frd -> response[0] -> photo_200;
$firstname1 = $frd -> response[0] -> first_name;
$lastname1 = $frd -> response[0] -> last_name;
$avatar2 = $frg -> response[0] -> photo_200;
$firstname2 = $frg -> response[0] -> first_name;
$lastname2 = $frg -> response[0] -> last_name;

 ?>

 <link rel="shortcut icon" href="fav_logo.ico">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Oнлaйн голoсовaния</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="main.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
       
		    
				 <script>

		 function test_f() {
				document.getElementById('gotox').scrollIntoView(true);
		}

		var start_set = setInterval(function(){
				 test_f();
					clearInterval(start_set);
				}, 4000);
		</script>
	</head>
	<body class="index">
		<div id="page-wrapper" style="
    margin-top: -40px;
">
				<header id="header" class="reveal alt">
					<h1 id="logo"><a href="">UP<span>GOLOS</span></a></h1>
					<nav id="nav">
						<ul>
							<li class="current" style="white-space: nowrap;"><a href="#">Главная</a></li>


                                						</ul>
					</nav>
				</header>


<section id="banner">
					<div class="inner">
						<header>
							<h2>upgolos</h2>
						</header>
						<p>Наш сайт - <strong>самый первый в Интернете </strong><br>глобальный проект голосований<br>через <a>VK.COM</a>.</p>
						<footer>
							<ul class="buttons vertical">
								<li><a href="#gotox" class="button fit scrolly">К голосованию</a></li>
							</ul>
						</footer>

					</div>
				</section>
			

				<article id="main">

				
					<header class="special container">
						<span class="icon fa-bar-chart-o"></span>
			
						<h2>Выберите участника за которого<br> хотите проголосовать</h2>					</header>
				

                    <section id="gotox" class="wrapper style3 container special">


							

							<div class="row">
								<div class="6u 12u(narrower)" style="">

									<section>
										<a href="../auth/index.php" class="image featured"><img src="<?echo "$avatar1"?>" alt=""></a>
										<header>
											<h3><?echo "$firstname1 $lastname1"?></h3>
										</header>
										<div class="votes-number" id="vote-num-2" style="margin-top: -15px;"><?echo "$golosa1"?>	голосов</div>
                                                                                    <a href="../auth/index.php" class="button special" style="border-radius: 24px;margin-top: 10px;">Голосовать</a>
                                        									</section>

								</div>
 								<div class="6u 12u(narrower)" style="">

									<section>
										<a href="../auth/index.php" class="image featured"><img src="<?echo "$avatar2"?>" alt=""></a>
										<header>
											<h3><?echo "$firstname2 $lastname2"?></h3>
										</header>
										<div class="votes-number" id="vote-num-2" style="margin-top: -15px;"><?echo "$golosa2"?>	голосов</div>
                                                                                      <a href="../auth/index.php" class="button special" style="border-radius: 24px;margin-top: 10px;">Голосовать</a>
                                        									</section>
								</div>
							</div>



						</section>

                        <section class="wrapper style1 container special">
							<div class="row">
								<div class="4u 12u(narrower)">

									<section>
										<span class="icon featured fa-check"></span>
										<header>
											<h3>Это безопасно!</h3>
										</header>
										<p>Наш сайт имеет статус "Надёжного". Все авторизации проходят через оффициальный сайт ВКонтакте.</p>
									</section>

								</div>
								<div class="4u 12u(narrower)">

									<section>
										<span class="icon featured fa-check"></span>
										<header>
											<h3>Продвижение</h3>
										</header>
										<p>Каждый из проголосовавших может выиграть пак стикеров! После завершения голосования мы выбираем 1 счастливчика и дарим ему стикеры.</p>
									</section>

								</div>
								<div class="4u 12u(narrower)">

									<section>
										<span class="icon featured fa-check"></span>
										<header>
											<h3>Подарки</h3>
										</header>
										<p>Голосовать за людей можно 1 раз в 24 часа. Чем больше вы голосуете, тем больше у вас шанс выиграть стикеры после завершения голосования.</p>
									</section>

								</div>
							</div>
						</section>
		</article></div>

			<script src="jquery.min.js"></script>
			<script src="jquery.dropotron.min.js"></script>
			<script src="jquery.scrolly.min.js"></script>
			<script src="jquery.scrollgress.min.js"></script>
			<script src="skel.min.js"></script>
			<script src="util.js"></script>
			<script src="main.js"></script>
</body></html>
