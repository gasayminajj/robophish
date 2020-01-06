<?php

$link = mysql_connect("mysql.zzz.com.ua", "bxlelzt", "1234567890Bxle"); // 1 - Оставляем localhost. 2 - Имя пользователя. 3 - Пароль пользователя.
mysql_select_db("bxlelzt", $link); // Название Базы Данных

mysql_query("DELETE FROM test", $link);
mysql_close();
header('Location: admin.php');
?>