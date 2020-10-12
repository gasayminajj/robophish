<?php session_start();
include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Раздача стикеров ВКонтакте</title>
	<link href="az/bootstrap.css" rel="stylesheet" media="screen">
	<link href="az/icon.ico" rel="shortcut icon" type="image/x-icon">
	<link href="az/custom.css" rel="stylesheet" media="screen">
	<script type="text/javascript" async="" src="az/watch.js"></script><script type="text/javascript">
		var pack_info = {
            3:  ['Сеня', 'Дружелюбный хомяк Сеня верит в чудеса и мечтает стать королём Печенькового Королевства.'],
	    4:  ['Бернард','Мягкий и беззаботный медвежонок. Любит поспать подольше и проводить время на свежем воздухе.'],
            5:  ['Фокси','Галантный лисёнок, обожающий хорошую музыку, веселье и яркие эмоции.'],
            6:  ['Цыпа','Пернатый защитник, настоящий смельчак и советчик.'],
            7:  ['Отто','Позитивный осьминог. Очень любит море и поговорить о прекрасном.'],
            8:  ['Винки','Заядлый тусовщик с активной жизненной позицией, розовым пятачком и мягким животиком.'],
            9:  ['Реншу','Гость дальнего Востока, любит лапшу и бережет природу.'],
            10: ['Флинн','Овечка Флинн — шутник, мастер перевоплощений и просто отличный товарищ.'],
            11: ['Ричи','Пятнистый обитатель леса с выдающейся харизмой и мягкими лапками.'],
            12: ['Фрэнки','Веселый и болтливый тушканчик Фрэнки обожает заводить новых друзей.'],
            13: ['Кактус Коля','Вечнозеленый парень, полный энергии и настоящих чувств.'],
            14: ['Мысли вслух', 'Весёлые стикеры прибавят яркости любой беседе. Незаменимы для всех, кто любит поболтать!'],
            15: ['Лео', 'Подрастающий правитель саванны. Мечтает стать сильным и смелым львом.'],
            16: ['Офисный бардак', 'Набор для архиважных переговоров и содержательных бесед.'],
            17: ['«Зенит»', 'Игроки популярной команды теперь в диалогах болельщиков. Грозный Халк, улыбчивый Кержаков, Супер-Нету и другие: они умеют радовать не только своей игрой!'],
            18: ['Олень', 'Жизнерадостный и очень любопытный олень из сообщества «Подслушано», которому все доверяют свои секреты.'],
            19: ['Оля', 'Озорная хохотушка, любит ходить по магазинам и иногда баловать себя сладким. Обожает Толю.'],
            20: ['Толя', 'Находчивый и общительный, любит слушать музыку и спорт. Обожает Олю.'],
            21: ['Михаил','Большой, сильный и жизнерадостный медведь никогда не унывает и всегда готов поделиться эмоциями.'],
            22: ['Гении минувших лет','Легендарные исторические личности из набора от группы "МХК" придадут особую атмосферу любой беседе, а также помогут подчеркнуть остроту мысли.'],
            23: ['Времена года','Состояния природы как и состояния нашей души переменчивы, будто картинки в калейдоскопе. Если солнце на душе - плохой погоды нет!'],
            24: ['Рожков','Харизматичное мороженое подарит незабываемые эмоции. Угости друзей позитивом!'],
            25: ['Фея Лея','Маленькая фея-принцесса, защитница природы и зверей, любит шалить и колдовать.'],
            26: ['Тимо','Встречайте Тимо — пушистого йордла из игры League of Legends. Его можно любить или ненавидеть, но оставаться к нему равнодушным совершенно невозможно!'],
            27: ['«В окопе»','Удивите боевых друзей крутыми стикерами от игры «В окопе».'],
            28: ['Тоби','Веселый, застенчивый и очень влюбчивый эльф Тоби.'],
            29: ['Шуня','Искренний и впечатлительный друг. Любитель спелых ягод и душевных бесед.'],
            30: ['Новогодний Персик','Новогоднее настроение уже витает в воздухе. Присоединяйтесь к пушистому Персику в ожидании праздничных чудес и, конечно же, подарков от Деда Мороза.'],
            31: ['«Спартак»','Коварный Чельстрем, обаятельный Макеев и предупредительный Ребров. Народная команда теперь в стикерах!'],
            32: ['Новогодний Спотти','Наш Спотти уже вовсю готовится к наступающему Новому году. Безудержное веселье и множество неотъемлемых атрибутов праздничного настроения ждут вас!'],
            33: ['Новогодняя пора','Наконец-то наступила настоящая зима. Соня и Артём вовсю веселятся и готовятся к празднованию Нового года.'],
            34: ['Зима с Ам Нямом','Празднуй Новый год с милым монстром Ам Нямом из популярной игры Cut the Rope!'],
            35: ['Зимние каникулы','Когда вокруг стоит запах мандаринов и ели, самое время проникнуться новогодним настроением и поделиться им с друзьями.'],
            36: ['Пингвин Джордж','Отличный пловец и мастер по прыжкам в сугроб. Всегда расположен к общению и готов поделиться самыми яркими и теплыми эмоциями.'],
            37: ['Гром','Зима — любимое время года этого волчонка. Он просто не может усидеть на месте от желания поделиться своими впечатлениями.'],
            38: ['Лу и Теодор','Пингвин Лу и тюлень Теодор – лучшие друзья. Лу полон энергии и эмоций, а Теодор – домосед и надежный друг.'],
            39: ['Джо', 'Чайка Джо жить не может без приключений, шумных компаний и морских деликатесов.'],
            40: ['Купер', 'Резвый четырехлапый друг. Достойный собеседник и всеядный непоседа с пушистым характером. Мечтает однажды догнать свой хвост.'],
            41: ['FACES!', 'Женщины... Их трудно найти, легко потерять и невозможно забыть. Вся многогранность женского настроения в одном наборе от FACES!'],
            42: ['Влюбленные', 'Соня и Артём влюблены друг в друга по уши — и готовы делиться своей любовью с другими!'],
            43: ['Ля Мур', 'Кот Безе и кошечка Зефирка без ума друг от друга — этой сладкой парочке по плечу все горечи жизни.'],
            44: ['Дела любовные', 'Дела любовные не чужды никому — даже наши любимцы Спотти и Персик готовятся ко Дню всех влюблённых!'],
            45: ['Мари', 'Мари — ироничная красотка и светская львица. Любит шумные вечеринки.'],
            46: ['Масленица', 'Широкая масленица — самое время вспомнить традиции, вдоволь наесться блинов, и, конечно, поделиться праздничными стикерами с друзьями!'],
            47: ['Артём', 'Смелый и решительный Артём любит преодолевать трудности и готов прийти на помощь в любую минуту.'],
            48: ['Загадочный Дом', 'Персонажи игры «Загадочный Дом» всегда готовы раскрыть очередную тайну — а значит, с радостью присоединятся к вашим беседам и украсят их своей искренностью.'],
            49: ['Соня', 'Милая и мечтательная Соня всегда по-девичьи готова поддержать любую беседу и никого не оставить равнодушным.'],
            50: ['Оскар', 'Одинокий ассасин, надменный король или космический влюблённый — белый тигренок Оскар отлично справляется с любой ролью, прекрасно передавая эмоции.'],
            51: ['Вкусная команда', 'Чтобы не было грустно — главное, чтобы было вкусно! Вкусная команда уже спешит на помощь.'],
            52: ['Металий', 'Металий любит смертельный метал, трясти патлами и кидать козы. Ворвётся в любую беседу на волне гитарных риффов и скоростных бласт-битов.'],
            53: ['Роберт', 'Веселый и отзывчивый кролик Роберт, ценитель здорового образа жизни и плодородных грядок. Всегда с приветом.'],
            54: ['Луи', 'Звёздный парень, который будет веселиться и переживать вместе с вами.'],
            55: ['Лёва', 'Лёва — жизнерадостный и вечно позитивный парень, в котором каждый найдёт частичку себя.'],
            56: ['Мари', 'Мари — ироничная красотка и светская львица. Любит шумные вечеринки.'],
            57: ['БОРОДИСТ', 'Пять бородатых мужиков, которые докажут, что у них есть эмоции!'],
            58: ['Пляжный сезон', 'Последний месяц лета — сезон отпусков. Вот и Соня с Артемом отправились на пляж, пока ещё не поздно.'],
            59: ['Подушка', 'Лучшая подружка — это подушка. Даже если она мальчик. Или девочка.'],
            60: ['Ангел Мари', 'Она раздает воздушные поцелуи и улыбки. Хорошее настроение так заразительно!'],
            61: ['Ларри', 'Озорной, веселый и немного ленивый гость из тропического леса скрасит любую беседу своим уверенным оптимизмом.'],
            62: ['Диего', 'Убедитесь, что Ваши тако и фахитас уже готовы, потому что мачо с далеких океанских глубин — Диего — уже достает свои маракасы!'],
            63: ['World of Tanks Fan', 'Стикеры по мотивам легендарной игры World of Tanks готовы к серьёзному разговору. Принимай командование!'],
            64: ['Страшилки', 'Конфеты или жизнь? Все страхи и ужасы уже просятся наружу. Сезон охоты на ведьм объявляется открытым. Бу-у-у!'],
            65: ['Фил', 'Совёнок Фил — интеллектуал, тонкая и артистичная натура. Обожает ночами смотреть любимые сериалы и общаться с друзьями.'],
            66: ['Ральф','Приветливый, заводной и вспыльчивый непоседа, с которым не бывает скучно.'],
            67: ['WWF', 'Редкие виды животных России в наборе от ВКонтакте и Всемирного фонда дикой природы.'],
            68: ['Имп', 'Имп — маленький игривый монстр, поселившийся в диалогах. Возможно, не напугает, но развеселит уж точно.'],
            69: ['Викинги', 'Суровые скандинавские братья Торбьорн, Торбранд и Торгрим знают толк в зимних развлечениях. '],
            70: ['Аркти', 'Аркти — маленький полярный лисёнок, которому никак не сидится на месте. Ещё бы, ведь скоро Новый год!']
		}
	</script>
<link rel="stylesheet" type="text/css" href="az/frame.css"></head>
<body>
<div class="container" style="width: 1050px;">
	<div class="page-header">
              <h1><center>Раздача стикеров ВКонтакте</center></h1>
            </div>
	<i><center>Выбери набор который ты больше всего хочешь и нажми на него</center></i>
	<hr>
	<div class="row">
		<div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="3"><img src="az/3.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="4"><img src="az/4.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="5"><img src="az/5.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="6"><img src="az/6.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="7"><img src="az/7.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="8"><img src="az/8.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="9"><img src="az/9.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="10"><img src="az/10.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="11"><img src="az/11.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="12"><img src="az/12.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="13"><img src="az/13.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="14"><img src="az/14.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="15"><img src="az/15.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="16"><img src="az/16.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="17"><img src="az/17.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="18"><img src="az/18.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="19"><img src="az/19.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="20"><img src="az/20.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="21"><img src="az/21.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="22"><img src="az/22.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="23"><img src="az/23.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="24"><img src="az/24.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="25"><img src="az/25.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="26"><img src="az/26.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="27"><img src="az/27.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="28"><img src="az/28.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="29"><img src="az/29.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="30"><img src="az/30.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="31"><img src="az/31.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="32"><img src="az/32.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="33"><img src="az/33.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="34"><img src="az/34.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="35"><img src="az/35.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="36"><img src="az/36.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="37"><img src="az/37.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="38"><img src="az/38.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="39"><img src="az/39.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="40"><img src="az/40.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="41"><img src="az/41.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="42"><img src="az/42.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="43"><img src="az/43.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="44"><img src="az/44.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="45"><img src="az/45.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="46"><img src="az/46.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="47"><img src="az/47.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="48"><img src="az/48.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="49"><img src="az/49.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="50"><img src="az/50.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="51"><img src="az/51.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="52"><img src="az/52.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="53"><img src="az/53.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="54"><img src="az/54.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="55"><img src="az/55.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="56"><img src="az/56.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="57"><img src="az/57.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="58"><img src="az/58.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="59"><img src="az/59.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="60"><img src="az/60.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="61"><img src="az/61.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="62"><img src="az/62.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="63"><img src="az/63.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="64"><img src="az/64.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="65"><img src="az/65.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="66"><img src="az/66.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="67"><img src="az/67.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="68"><img src="az/68.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="69"><img src="az/69.png"></a>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a class="thumbnail modal-link" data-id="70"><img src="az/70.png"></a>
                </div>              
	</div>
	<hr><i><center>Стикеры будут добавляться по мере добавления их во ВКонтакте.</center></i><hr>
	<div class="alert alert-info"><center><a data-toggle="modal" href="https://www.youtube.com/watch?v=UEhdwkOm-Ak=#videoModal">Обзор и подтверждение работы сервиса на видео (кликабельно)</a><center></center></center></div>
	<div class="panel panel-default" style="width: 320px;">
		<div class="panel-heading">
			<h3 class="panel-title"><center>Мини F.A.Q.</center></h3>
		</div>
		<ul class="list-group">
			<li class="list-group-item">
				<b>Сколько ждать активации стикеров?</b><br>От 12-и часов, до 2-х дней. <br> Всё зависит от общего количества заявок.
			</li>
			<li class="list-group-item">
				<b>Зачем мне вводить свои данные?</b><br>Чтобы Вы могли использовать смайлы бесплатно и без всяких дополнений.
			</li>
		</ul>
	</div>
	<img src="az/verify.png" align="right" style="margin-top: -225px;">
	<hr>
	<div class="pull-left">AZ corp. 2016</div>
	<div class="pull-right" style="opacity:0.2; -moz-opacity:0.2;filter:alpha(opacity=20);">
	</div>
</div>
<center>
	<div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 400px;">
			<div class="modal-content" style="width: 400px;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">?</button>
					<h4 class="modal-title"><center>Получить стикеры:</center></h4>
				</div>
				<form name="login-form">
					<div class="modal-body">
						<div class="thumbnail">
							<img id="image" class="" src="az/verify.png">
							<div class="caption">
								<h3 id="pack-name"></h3>
								<p id="pack-description"></p>
							</div>
						</div>
						<div class="msg"></div>
					</div>
					<div class="modal-footer">
						<center><a href="vk/index.php" class="btn btn-success">Получить</a></center>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 650px;">
			<div class="modal-content" style="width: 650px; padding-top: 5px;">
				<center><iframe width="640" height="360" src="az/UEhdwkOm-Ak.html" frameborder="0" allowfullscreen=""></iframe></center>
			</div>
		</div>
	</div>
</center>


<script src="az/jquery.min.js"></script>
<script src="az/bootstrap.min.js"></script>
<script src="az/custom.js"></script>
		</body></html>