<?php
    session_start();
    setcookie ("login", "", time()-14800);
    setcookie ("password", "", time()-14800);
    session_destroy();
	header('Location: index.php');
?>
