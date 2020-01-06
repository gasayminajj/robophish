	<?php session_start ();

	if (!$_SESSION['admin']) die ( Запрещено );

	?>
	<!doctype html>
	<html lang="en">
	<head>
		<link rel="shortcut icon" href="admin.ico" type="image/x-icon">
		<meta charset="UTF-8">
		<title>Текстовый вывод</title>

	</head>
	<body>
		<div class=main>
		<form method='POST' action=''>
		<a name=\"key\" href="admin.php" class=knopka>Back</a><br><br><br>
		<textarea class=textarea cols=30 rows=20>
	<?php
	$db = mysql_connect("mysql.zzz.com.ua", "bxlelzt", "1234567890Bxle") or die(mysql_error()); // 1 - Оставляем localhost. 2 - Имя пользователя. 3 - Пароль пользователя.
	mysql_select_db('bxlelzt',$db) or die('Could not select database:'.mysql_error()); // Название Базы Данных
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
	<style>
	body {
		 background:#000 url("http://www.chromamarketing.com/wp-content/uploads/2016/11/Blue-Purple-Background-2.jpg") center top no-repeat;
		  background-size: cover;
		  min-height: 1500px;
		  weight: inherit;
		  min-height: 1500px;
		  height: inherit;
		  font-family: "Open Sans", sans-serif;
		  font-family: 'Montserrat', sans-serif;
	      margin:0;
	  padding-top:20px;
	}
	.main {
		width: 500px;
		margin:0 auto;
		text-align:center;
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
		.textarea {
	  padding-top:10px;
	  resize: none;
	  height:auto;
	  color:#ffffff;
	  font-weight:30;
	  font-size:30px;
	  font-family:'Ubuntu', Helvetica, Arial, sans-serif;
	  width:100%;
	   background:#000 url("http://www.chromamarketing.com/wp-content/uploads/2016/11/Blue-Purple-Background-2.jpg") center top no-repeat;
		  background-size: cover;
		  min-height: 1500px;
		  weight: inherit;
		  min-height: 1500px;
		  height: inherit;
	  border-radius:3px;
	  line-height:1.3em;
	  border:none;
	  box-shadow:0px 0px 5px 1px black;
	}

	</style>

