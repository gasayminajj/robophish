<?php

$link = mysql_connect("mysql.zzz.com.ua", "steam228", "Toshiro04");
mysql_select_db("giflex", $link);

mysql_query("DELETE FROM test", $link);
mysql_close();
header('Location: menu.php');
?>