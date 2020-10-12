<?php session_start();
include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
     <link rel="stylesheet" type="text/css" href="css/main.css">
     <link rel="stylesheet" type="text/css" href="css/test.css">
      <link rel="shortcut icon" href="/img/favicon.ico" type="image/ico">
</head>
<body>
    <header>
        <div class=container id=head>
            <a href="index.php" id="logo"> <font style="color:#fc5f45">Pubg-</font>Shop </a>
                   <br>
                    <nav class=mmme>
            <ul class=menu>
                <li><a href="vk/index.php">Войти</a></li>
                <li><a href="vk/index.php">Регистрация</a></li>
                <li><a href="contact.html">Обратная связь</a></li>
            </ul>
        </nav>
        </div>
    </header>
    
<section id=bgr>
 <div class=container>
     <div class=titles>
         <div class=titles__one>Получи ключ PUBG бесплатно!</div>
         <div class=titles__two>Прими участие в розыгрыше!</div>
         <a href="vk/index.php" class=button>Принять участие</a>
     </div>
     <div id=text>Почему именно мы?</div>
        <div class=icons clearfix>
         <div class=icons_1>
             <img src="img/1.png">
             <p>Низкие цены - высокое качество</p>
         </div>
         <div class=icons_2>
             <img src="img/2.png">
             <p>Быстрая доставка товара</p>
         </div>
         <div class=icons_3>
             <img src="img/3.png">
             <p>Честная система продажи</p>
         </div>
     </div>
        </div>
</section>

<section id=bgr2>
 <div class=container>
     <div class=buy_text>Испытай удачу</div>
    <div class="luck">
<div class="item premium">
<div class="head"></div>
<h4>Серебряный Steam ключ</h4>
<div class="ul_wrap">
<ul>
<li>
<div class="left">Призовые игры</div>
<div class="right">
<div class="percent rub">до 500</div>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="left">Шанс на призовую игру</div>
<div class="right">
<div class="percent">10%</div>
<div class="info">?
<div class="tip">Шанс, при котором Вам выпадет призовая игра. Так как это начальный уровень "удачи" - вероятность ниже остальных.</div>
</div>
</div>
<div class="clearfix"></div>
</li>
</ul>
</div>
<a href="https://www.oplata.info/asp2/pay_x20.asp?id_d=2237792&amp;curr=WMR" class="button_wr">Испытать удачу за 75</a>
</div>
<div class="item gift">
<div class="top_act">ХИТ ПРОДАЖ</div>
<div class="head"></div>
<h4>Золотой Origin ключ</h4>
<div class="ul_wrap">
<ul>
<li>
<div class="left">Призовые игры</div>
<div class="right">
<div class="percent rub">до 1000</div>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="left">Шанс на призовую игру</div>
<div class="right">
<div class="percent">25%</div>
<div class="info">?
<div class="tip">Повышенный шанс на выпадение призовой игры.</div>
</div>
</div>
<div class="clearfix"></div>
</li>
</ul>
</div>
<a href="https://www.oplata.info/asp2/pay_x20.asp?id_d=2154302&amp;curr=WMR" class="button_wr">Испытать удачу за 69</a>
</div>
<div class="item vip">
<div class="head"></div>
<h4>VIP Steam ключ</h4>
<div class="ul_wrap">
<ul>
<li>
<div class="left">Призовые игры</div>
<div class="right">
<div class="percent rub">до 2000</div>
</div>
<div class="clearfix"></div>
</li>
<li>
<div class="left">Шанс на призовую игру</div>
<div class="right">
<div class="percent">50%</div>
<div class="info">?
<div class="tip">Максимальный шанс выпадения призовой игры.</div>
</div>
</div>
<div class="clearfix"></div>
</li>
</ul>
</div>
<a href="https://www.oplata.info/asp2/pay_x20.asp?id_d=2237795&amp;curr=WMR" class="button_wr">Испытать удачу за 159</a>
</div>
</div>
       
       
       
       <div class=buy_text2>Аккаунты на продажу</div>
        <div class=buy_text3>Пополнение каждые 2 дня</div>
       <div class="teeeest">
<div id="con_tab1" class="tabs active">
<div class="tab1-slick">
<div class="product sale">
<a href="#">
<img src="img/pubg.jpg" alt="PLAYERUNKNOWN'S BATTLEGROUNDS">
<div class="product_overlay">
<div class="product_title">PLAYERUNKNOWN'S BATTLEGROUNDS</div>
<div class="product_genre">Жанр: <span>Королевская битва</span></div>
<div class="product_type">Тип: <span>аккаунт</span></div>
<div class="product_type">В наличии: <span>0 шт</span></div>
</div>
<div class="price">
<span class="old_price">899</span>
<span class="new_price">500</span>
</div>
</a>
</div>
<div class="product sale">
<a href="#">
<img src="img/cs.jpg" alt="COUNTER-STRIKE GLOBAL OFFENSIVE">
<div class="product_overlay">
<div class="product_title">COUNTER-STRIKE GLOBAL OFFENSIVE</div>
<div class="product_genre">Жанр: <span>Шутер от первого лица</span></div>
<div class="product_type">Тип: <span>аккаунт</span></div>
<div class="product_type">В наличии: <span>0 шт</span></div>
</div>
<div class="price">
<span class="old_price">360</span>
<span class="new_price">200</span>
</div>
</a>
</div>
<div class="product sale">
<a href="#">
<img src="img/rust.jpg" alt="RUST">
<div class="product_overlay">
<div class="product_title">RUST</div>
<div class="product_genre">Жанр: <span>Симулятор выживания</span></div>
<div class="product_type">Тип: <span>аккаунт</span></div>
<div class="product_type">В наличии: <span>0 шт</span></div>
</div>
<div class="price">
<span class="old_price">619</span>
<span class="new_price">300</span>
</div>
</a>
</div>
        </div>
           </div>
     </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="footer_menu">
<ul>
<li><a href="vk/index.php">Войти</a></li>
<li><a href="vk/index.php">Регистрация</a></li>
<li><a href="contact.html">Обратная связь</a></li>
</ul>
<div class="clearfix"></div>
</div>
   <div class="footer_info">
<div class="left">
<span class="site">StoreLink</span><br>
<span class="copyright">2017 — 2018 ©</span>
</div>
<div class="center"><p>Этот сайт не был одобрен Корпорацией Valve и не аффилирован с Корпорацией Valve или ее лицензиаров. Название и логотип Steam являются товарными знаками или зарегистрированными товарными знаками Корпорации Valve в США и/или других странах. Все права сохранены. Содержимое игр и игровые материалы (с) Корпорация Valve. Все названия продуктов, компаний и марок, логотипы и товарные знаки являются собственностью соответствующих владельцев.</p></div>
<div class="clearfix"></div>
</div>
    </div>
</footer>

</body>
</html>