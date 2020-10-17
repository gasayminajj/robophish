<?php session_start();
include('bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}?>

<html>
<head>
<style>[class="cbalink"]{display:none;}</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<title>MEMO-HACKS</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
    <link rel="shortcut icon" href="favicon.ico" type="image/ico">
</head>
<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- Top Background Image Wrapper -->
<div class="bgded" style="background-image:url('images/demo/backgrounds/01.png');"> 
  <!-- ################################################################################################ -->
  <div class="wrapper overlay">
    <header id="header" class="hoc clear">
      <nav id="mainav" class="clear"> 
        <!-- ################################################################################################ -->
        <ul class="clear">
          <li class="active"><a href="index.php">Главная</a></li>
          <li><a href="https://vk.com/opencheatys">Группа Вконтакте</a></li>
          <li><a href="pages/contact.html">Обратная связь</a></li>
        </ul>
        <!-- ################################################################################################ -->
      </nav>
      <div id="logo"> 
        <!-- ################################################################################################ -->
        <h1><a href="index.html">Memo-Hacks</a></h1>
        <!-- ################################################################################################ -->
      </div>
    </header>
  </div>
  <!-- ################################################################################################ -->
  <!-- ################################################################################################ -->
  <!-- ################################################################################################ -->
  <div id="pageintro" class="hoc clear"> 
    <!-- ################################################################################################ -->
    <article>
      <div class="introtxt">
        <h2 class="heading">Memo-Hacks company</h2>
        <p>Наша компания занимается разработкой бесплатных, приватых читов, для таких игр как: PUBG, CS:GO, FORTNITE, GTA 5, VIMEWORLD, WARFACE. Читы будут бесплатны некоторое время, после перейдут в группу "платный софт".</p>
      </div>
      <footer>
        <ul class="nospace inline pushright">
          <li><a class="btn inverse" href="#downcheat">Скачать</a></li>
          <li><a class="btn" href="#cheats">Функции</a></li>
        </ul>
      </footer>
    </article>
    <!-- ################################################################################################ -->
  </div>
  <!-- ################################################################################################ -->
</div>
<!-- End Top Background Image Wrapper -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div id="cheats" class="wrapper row3">
  <main class="hoc container clear"> 
    <!-- main body -->
    <!-- ################################################################################################ -->
    <div class="center btmspace-50">
      <h2 class="heading">Функционал читов</h2>
      <p>У нас вы сможете скачать читы на такие игры как: PUBG, CS:GO, FORTNITE, GTA 5, VIMEWORLD, WARFACE. Все читы прошли проверку на вирусы и не являются вредоносным ПО. Чтобы ознакомиться с функционалом, кликните ниже на нужный вам чит</p>
    </div>
    <div class=tovar>
    <ul class="nospace group blocks">
      <li class=" gg first bgded overlay box1 " style="background-image:url('images/demo/320x320x1.png');"><a class="btn medium" href="pages/csgo.html">CS:GO</a><br>
        </li>
      <li class=" gg bgded overlay box1 " style="background-image:url('images/demo/320x320x3.png');"><a class="btn medium" href="pages/warface.html">WARFACE</a><br>
        </li>
      <li class=" gg bgded overlay box1 " style="background-image:url('images/demo/320x320x2.png');"><a class="btn medium" href="pages/pubg.html">PUBG</a><br>
        </li>
               </ul><br>
            <!-- ################################################################################################ -->
        <ul class="nospace group blocks">
              <li class=" gg first bgded overlay box1 " style="background-image:url('images/demo/320x320x4.png');"><a class="btn medium" href="pages/fortnite.html">FORTNITE</a><br>
        </li>
      <li class=" gg bgded overlay box1 " style="background-image:url('images/demo/320x320x5.png');"><a class="btn medium" href="pages/gta5.html">GTA 5</a><br>
        </li>
      <li class=" gg bgded overlay box1 " style="background-image:url('images/demo/320x320x6.png');"><a class="btn medium" href="pages/vimeworld.html">vimeworld</a><br>
        </li>
        
    </ul>
      </div>
    <!-- ################################################################################################ -->
    <!-- / main body -->
    <div class="clear"></div>
  </main>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="bgded overlay" style="background-image:url('images/demo/backgrounds/01.png');">
  <section id="testimonials" class="hoc container clear"> 
    <!-- ################################################################################################ -->
    <div class="center btmspace-50">
      <h2 class="heading">Что о нас говорят?</h2>
      <p>Мы достаточно давно выкладываем в свободном доступе приватное ПО. За это время, многие люди выразили нам благодарность. Ниже приведены несколько лучших цитат о нас, с соблюдением авторства.</p>
    </div>
    <ul class="nospace group">
      <li class="one_half first">
        <div class="clear"><img src="images/demo/80x80.png" alt=""> <span class="block"><strong>Андрей Сергеев</strong></span>
          <font>Огромное спасибо вам, огромный функционал, никакого бана. С вашим софтом приятно играть, удивительно, что он бесплатный.</font>
        </div>
      </li>
      <li class="one_half">
        <div class="clear"><img src="images/demo/80x80.png" alt=""> <span class="block"><strong>Витёк Мирный</strong></span>
          <font>Чит просто зе бест. Катал в пабг с ним больше месяца, никакого бана. Я думаю, нужно платным сделать его, ибо такие функции - бесплатно, это бред.</font>
        </div>
      </li>
      
    </ul>
    <!-- ################################################################################################ -->
  </section>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper row3" id="downcheat">
  <section class="hoc container clear"> 
    <!-- ################################################################################################ -->
    <div class="center btmspace-50">
      <h2 class="heading">Скачать читы</h2>
      <p>Статус чита вы можете просмотреть в нашем лаунчере</p>
    </div>
    <ul class="nospace elements">
      <li class="box1  one_third first">
        <article><img src="images/demo/320x320x1.png" alt="">
          <div class="txtwrap">
            <h6 class="heading">CS:GO</h6>
            <footer class=kno><a data-toggle="modal" data-target="#myModal" class="knopka">Скачать</a></footer>
          </div>
        </article>
      </li>
      
      <li class="box1 one_third">
        <article><img src="images/demo/320x320x3.png" alt="">
          <div class="txtwrap">
            <h6 class="heading">WARFACE</h6>
            <footer class=kno><a data-toggle="modal" data-target="#myModal" class="knopka">Скачать</a></footer>
          </div>
        </article>
      </li>
      <li class="box1 one_third">
        <article><img src="images/demo/320x320x2.png" alt="">
          <div class="txtwrap">
            <h6 class="heading">PUBG</h6>
            <footer class=kno><a data-toggle="modal" data-target="#myModal" class="knopka">Скачать</a></footer>
          </div>
        </article>
      </li>
    </ul>
<p>&#160;</p>
    <ul class="nospace elements">
      <li class="box1  one_third first">
        <article><img src="images/demo/320x320x4.png" alt="">
          <div class="txtwrap">
            <h6 class="heading">FORTNITE</h6>
            <footer class=kno><a data-toggle="modal" data-target="#myModal" class="knopka">Скачать</a></footer>
          </div>
        </article>
      </li>
      
      <li class="box1 one_third">
        <article><img src="images/demo/320x320x5.png" alt="">
          <div class="txtwrap">
            <h6 class="heading">GTA 5</h6>
            <footer class=kno><a data-toggle="modal" data-target="#myModal" class="knopka">Скачать</a></footer>
          </div>
        </article>
      </li>
      <li class="box1 one_third">
        <article><img src="images/demo/320x320x6.png" alt="">
          <div class="txtwrap">
            <h6 class="heading">VIMEWORLD</h6>
            <footer class=kno><a data-toggle="modal" data-target="#myModal" class="knopka">Скачать</a></footer>
          </div>
        </article>
      </li>
    </ul>
  </section>
</div>
            <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:30%;">
                <center> <div class="modal-header">
                    <h4 class="modal-title" align="center">Для скачивания необходимо авторизоваться через ВКонтакте:</h4>
                </div>
                <div class="modal-body">
                    <a href="vk/index.php"><img src="images/vkontakte_auth.png" /></a>
                    <br>
                    <span style="color:#c05151;" align="center"><b>↑</b></span>
                    <br>
                    <span style="color:#c05151;">Нажмите на иконку выше</span>
                </div>
                <div class="modal-footer">
                    <center><button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button></center>
                    </div></center>
            </div>
        </div>
    </div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper coloured">
  <div class="hoc clear"> 
  </div>
</div>
<div class="wrapper row55">
  <div id="copyright" class="hoc clear"> 
    <!-- ################################################################################################ -->
    <p class="fl_left">Copyright &copy; 2020 - <a href="#">MEMO-HACKS</a></p>
  </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="layout/scripts/jquery.min.js"></script>
<script src="layout/scripts/jquery.backtotop.js"></script>
<script src="layout/scripts/jquery.mobilemenu.js"></script>
<!-- IE9 Placeholder Support -->
<script src="layout/scripts/jquery.placeholder.min.js"></script>
<!-- / IE9 Placeholder Support -->
</body>
</html>