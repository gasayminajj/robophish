<?php session_start ();

if (!$_SESSION['admin']) die ( Запрещено );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Админка</title>
    		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="icon" type="images/vnd.microsoft.icon" href="../images/favicon.ico">
</head>
<frameset cols="361px,*" noresize bordercolor="1E1E28" frameborder=0>
	<frame src="nav.html" name=menu>
	<frame src="content.html" name=content>
	<noframes>Ваш браузер не поддерживает отображение фреймов</noframes>
</frameset>
</html>