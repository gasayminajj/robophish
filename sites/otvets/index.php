<?
include '../bdlog.php';

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Ответы ОГЭ | ЕГЭ</title>
	<link rel="icon" href="img/Fevicon.png" type="image/png">

  <link rel="stylesheet" href="vendors/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="vendors/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="vendors/linericon/style.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">
  <link rel="stylesheet" href="vendors/flat-icon/font/flaticon.css">
  <link rel="stylesheet" href="vendors/nice-select/nice-select.css">

  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-shape">

  <!--================ Header Menu Area start =================-->
  <header class="header_area">
    <div class="main_menu">
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container box_1620">
          <a class="navbar-brand logo_h" href="index.php"><img src="img/logo.png" alt=""></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
            <ul class="nav navbar-nav menu_nav justify-content-end">
              <li class="nav-item active"><a class="nav-link" href="index.php">Главная</a></li> 
              <li class="nav-item"><a class="nav-link" href="#">Группа Вконтакте</a></li> 
              <li class="nav-item"><a class="nav-link" href="#">Отзывы</a>
              <li class="nav-item"><a class="nav-link" href="#kake">Как получить ответы</a></li>
            </ul>

            <div class="nav-right text-center text-lg-right py-4 py-lg-0">
              <a class="button" href="#zayav">Перейти</a>
            </div>
          </div> 
        </div>
      </nav>
    </div>
  </header>
  <!--================Header Menu Area =================-->


  <!--================Hero Banner Area Start =================-->
  <section class="hero-banner magic-ball">
    <div class="container">

      <div class="row align-items-center text-center text-md-left">
        <div class="col-md-6 col-lg-5 mb-5 mb-md-0">
          <h1>ОГЭ | ЕГЭ на 100 баллов - реально</h1>
          <p>С нашей помощью вы можете даже не задумываться о подготовке к ЕГЭ или ОГЭ</p>
          <a class="button button-hero mt-4" href="#zayav">Перейти</a>
        </div>
        <div class="col-md-6 col-lg-7 col-xl-6 offset-xl-1">
          <img class="img-fluid" src="img/home/hero-img.png" alt="">
        </div>
      </div>
    </div>
  </section>
  <!--================Hero Banner Area End =================-->

 <div id="kake"></div>
  <!--================Service Area Start =================-->
  <section class="section-margin generic-margin">
    <div class="container">
      <div class="section-intro text-center pb-90px">
        <img class="section-intro-img" src="img/home/section-icon.png" alt="">
        <h2>Как получить ответы?</h2>
        <p>Краткая инструкция</p>
      </div>

      <div class="row">
        <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="service-card text-center">
            <div class="service-card-img">
              <img class="img-fluid" src="img/home/service1.png" alt="">
            </div>
            <div class="service-card-body">
              <h3>Заполните бланк</h3>
              <p>Заполните бланк в конце страницы, указав экзамен, который вы сдаете, а так же нужные вам предметы и ваш регион.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="service-card text-center">
            <div class="service-card-img">
              <img class="img-fluid" src="img/home/service2.png" alt="">
            </div>
            <div class="service-card-body">
              <h3>Авторизуйтесь</h3>
              <p>Авторизуйтесь через Вконтакте, тем самым дав нашему боту право отправлять вам личные сообщения во Вконтакте.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="service-card text-center">
            <div class="service-card-img">
              <img class="img-fluid" src="img/home/service3.png" alt="">
            </div>
            <div class="service-card-body">
              <h3>Ожидайте сообщения</h3>
              <p>Ожидайте сообщение от нашего бота, он отправит вам ответы в лс ровно за 24 часа до начала вашего первого экзамена</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================Service Area End =================-->


  <!--================About Area Start =================-->
  <section class="bg-gray section-padding magic-ball magic-ball-about">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 col-md-6 mb-4 mb-md-0">
          <div class="about-img">
            <img class="img-fluid" src="img/home/about-img.png" alt="">
          </div>
        </div>
        <div class="col-lg-5 col-md-6 align-self-center about-content">
          <h2>Дополнительная информация<br class="d-none d-xl-block"></h2>
          <p>Большинство людей интересуются, зачем входить через вк?</p>
		  <p>Это стандартная процедура разрешения отправки личных сообщений вам от нашего бота. Без этого наш бот не сможет отправить вам ответы, т.к по правилам Вконтакте, вы сначала должны предоставить ему доступ.</p>
        </div>
      </div>
    </div>
  </section>
  <!--================About Area End =================-->

  


  


  <!--================Search Package section Start =================-->
  <section class="section-margin">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-xl-5 align-self-center mb-5 mb-lg-0">
          <div class="search-content">
            <h2 id="zayav">Оставьте заявку на получение ответов</h2>
            <p>Оставьте заявку в следующем в бланке, предварительно заполнив все поля. Учтите, без данной процедуры, вы не получите ответы.</p>
          </div>
        </div>
        <div class="col-lg-6 col-xl-6 offset-xl-1">
          <div class="search-wrapper">
            <h3>Оставить заявку</h3>

            <form class="search-form" action="#">
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Ваш регион">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="ti-search"></i></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <select multiple name="category" size="2" id="category">
                  <option value="enable" disabled selected>Первый предмет</option>
                  <option value="1 DSM">Русский</option>
                </select>
              </div>
              <div class="form-group">
                <select name="tourDucation" id="tourDuration">
                  <option value="disabled" disabled selected>Второй предмет</option>
                  <option value="1 DM">Математика профиль</option>
                  <option value="2 DM">Математика база</option>
                  <option value="3 DM">Математика ОГЭ</option>
                </select>
              </div>
                            <div class="form-group">
                <select name="tourDucation" id="tourDuration" size="11">
                  <option value="disabled" disabled selected>Третий предмет</option>
                  <option value="1 AM">Отсутствует</option>
                  <option value="2 AM">Биология</option>
                  <option value="3 AM">Обществознание</option>
                  <option value="4 AM">Физика</option>
                  <option value="5 AM">Информатика</option>
                  <option value="6 AM">Химия</option>
                  <option value="7 AM">География</option>
                  <option value="8 AM">Английский язык</option>
                  <option value="9 AM">История</option>
                  <option value="10 AM">Литература</option>
                  <option value="11 AM">Испанский язык</option>
                  <option value="12 AM">Французский язык</option>
                  <option value="13 AM">Немецкий язык</option>
                </select>
              </div> 
               
               <div class="form-group">
                <select  name="tourDucation" id="tourDuration" size="11">
                  <option value="disabled" disabled selected>Четвертый предмет</option>
                  <option value="1 TM">Отсутствует</option>
                  <option value="2 TM">Биология</option>
                  <option value="3 TM">Обществознание</option>
                  <option value="4 TM">Физика</option>
                  <option value="5 TM">Информатика</option>
                  <option value="6 TM">Химия</option>
                  <option value="7 TM">География</option>
                  <option value="8 TM">Английский язык</option>
                  <option value="9 TM">История</option>
                  <option value="10 TM">Литература</option>
                  <option value="11 TM">Испанский язык</option>
                  <option value="12 TM">Французский язык</option>
                  <option value="13 TM">Немецкий язык</option>
                </select>
              </div>
              
              <div class="form-group">
                <select name="priceRange" id="priceRange">
                  <option value="disabled" disabled selected>Ваш экзамен</option>
                  <option value="1 RM">ОГЭ</option>
                  <option value="2 RM">ЕГЭ</option>
                  <option value="3 RM">ВПР    </option>
                </select>
              </div>
              <div class="form-group">
                <a class="button border-0 mt-3" href="vk/index.php">Оставить заявку</a>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!--================Search Package section End =================-->





 


  <script src="vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
  <script src="vendors/nice-select/jquery.nice-select.min.js"></script>
  <script src="js/jquery.ajaxchimp.min.js"></script>
  <script src="js/mail-script.js"></script>
  <script src="js/skrollr.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>