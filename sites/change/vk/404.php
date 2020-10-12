<html>
	<head>
	<meta charset="Utf-8"/>
	<title>Ошибка 404</title>
		<style>
			* {
				margin: 0px;
				padding: 0px;
			}
			
			#header {
				margin: 0px auto;
				width: 100%;
				min-width: 500px;
				max-width: 1800px;
				height: 50px;
				background-color: #83BDC6;
				border-radius: 0px 0px 15px 15px;
				-webkit-border-radius: 0px 0px 15px 15px;
				-moz-border-radius: 0px 0px 15px 15px;
			}
			
			#wrapperHeader {
				margin: 0px auto;
				width: 500px;
				height: 50px;
			}
			
			.button {
				float: left;
				width: 49.5%;
				height: 50px;
				text-align: center;
				line-height: 3;
				font-weight: bold;
				color: #FFF;
				font-size: 1.1em;
				font-family: Arial;
				cursor: pointer;
			}
				/*Автор: vk.com/publik13*/
			.vk {
				background-color: rgba(50,64,140,.7);
				margin-right: .5%;
			}
			
			.youtube {
				background-color: rgba(213,35,67,.7);
			}
			
			.vk:hover {
				transition: .5s;
				background-color: rgba(50,64,140,1);
			}
			
			.youtube:hover {
				transition: .5s;
				background-color: rgba(213,35,67,1);
			}
			
			#wrapperContent {
				margin: 20px auto 10px;
				width: 700px;
				min-height: 77%;
				max-height: 78%;
				text-align: center;
				font-family: Arial;
				line-height: 1.2;
				border: 1px solid transparent;
			}
			
			#wrapperContent h2 {
				margin-top: 100px;
				margin-bottom: 10px;
				color: #7D0000;
			}
			
			#footer {
				width: 100%;
				height: 72px;
				background-color: #FFF9F6;
				min-width: 500px;
				max-width: 1800px;
				text-align: center;
			}
			
			.main {
				margin-left: .25%;
				margin-right: .5%;
				background-color: rgba(84,71,78,.7);
			}
			
			.main:hover {
				transition: .5s;
				background-color: rgba(84,71,78,1);
			}
			
			.search {
				background-color: rgba(38,50,72,.7);
			}
			
			.search:hover {
				transition: .5s;
				background-color: rgba(38,50,72,1);
			}
			
			.clear {
				clear: both;
			}
			
			#date {
				margin: 3px auto 0px;
				width: 99.5%;
				background-color: #AC9CA2;
				color: #131112;
				font-weight: bold;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<div id="wrapperHeader">
				<div class="button vk" onClick="location='http://vk.com/id0?21'">Vkontakte</div>
				<div class="button youtube" onClick="location='http://youtube.com'">Youtube</div>
			</div>
		</div>
	
		<div id="wrapperContent">
			<h2>Ошибка 404</h2>
				<p>Почему так произошло?<br/>
					<li>Была допущена ошибка при запросе;</li>
						<li>Страница была удалена или перемещена;</li>
					<li>Страница еще не была создана;</li>
				<li>Вы пытаетесь найти то, чего нет.</li>
			</p>
		</div>
			<div id="footer">
				<div class="button main" onClick="location='/'">Главная</div>
					<div class="button search" onClick="location='https://yandex.ua/search/?text=ошибка%20404&lr=960&clid=2270515&win=238'">Заяндексить 404</div>
					<div class="clear"></div>
				<div id="date"><?php echo date("Y"); ?> &copy;</div>
			</div>
	</body>
</html>