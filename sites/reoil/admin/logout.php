<?php session_start ();
if (!$_SESSION['admin']) die (Запрещено );
session_destroy ();
?>
<html>
<head>
<meta http-equiv="refresh" content="1;URL=../index.html" />
    </head>
    </html>