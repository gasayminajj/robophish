<?php session_start ();if (!empty ($_SESSION['admin'])){if ($_SESSION['admin']){?><html><head>

<title>Административная панель</title>
<meta http-equiv="refresh" content="1;URL=menu.php" />
</head><body></body></html><?exit;}}$_SESSION['admin'] = false;include ('config.php');function not_logged_in (){echo '<html><head><title>Админка</title>
<style>
    @font-face {

font-family: \'ProximaNova\';

src: url(\'../assets/fonts/ProximaNova-Regular.eot\');

src: url(\'../assets/fonts/ProximaNova-Regular.eot?iefix\') format(\'eot\'),

url(\'../assets/fonts/ProximaNova-Regular.woff\') format(\'woff\'),

url(\'../assets/fonts/ProximaNova-Regular.ttf\') format(\'truetype\'),

url(\'../assets/fonts/ProximaNova-Regular.svg#webfont\') format(\'svg\');

font-weight: normal;

font-style: normal;

}
    body {
    font-family: ProximaNova;
    background: #272632;
    }
            .knopka {
    font-weight: normal;
    border: none;
    font-size: 18px;
    color: white;
    background-color: #d03352;
    border-radius: 25px;
    padding: 6px 175px;
    text-decoration: none;
    box-shadow:0px 0px 3px 1px black;
    margin:5px 5px;
    margin-bottom:10px;
    }
    .knopka:hover {
     background-color: #D93656;
    }
.loginbox1 {
padding-top:5px;
padding-bottom:20px;
         font-size:25px;
         color:white;
}
input[type="text"] {
text-align:center;
padding: 6px 0;
  margin-bottom:12px;
  resize: none;
  height:auto;
  max-width:400px;
  color:#17171F;
  font-size:18px;
  font-family:\'Ubuntu\', Helvetica, Arial, sans-serif;
  width:100%;
  background:#FEB856;
  border-radius:23px;
  line-height:1.3em;
  border:none;
  box-shadow:0px 0px 5px 1px black;
  -webkit-transition: height 2s ease;
-moz-transition: height 2s ease;
-ms-transition: height 2s ease;
-o-transition: height 2s ease;
transition: height 2s ease;
}
</style>

</head><body><center><table cellpadding=0 cellspacing=0 id=wrap><tr><td align=center id=wraptd><table cellpadding=0 cellspacing=0><tr><td class=loginbox1 align=center>Вход в админку</td></tr><tr><td class=loginbox2 align=center><form action=index.php method=post><input type=text class=textin name=login value=Логин onclick=this.value=""><br><input type=text class=textin name=password value=Пароль onclick=this.value=""><br><input type=submit class=knopka value=Войти></form></td></tr></table></td></tr></table></center></body></html>';exit;}if (!$_POST) not_logged_in ();if (!$_POST['login']) not_logged_in ();if (!$_POST['password']) not_logged_in ();if ($_POST['login']!= $adminlogin) not_logged_in ();if ($_POST['password']!= $adminpassw) not_logged_in ();$_SESSION['admin'] = true;?><html><head>

<title>Административная панель</title>
<meta http-equiv="refresh" content="1;URL=menu.php" />
</head><body></body></html>
                    