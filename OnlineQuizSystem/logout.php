<?php

session_start();
session_unset();
setcookie("userId", "", time() - 3600);
setcookie("type", "", time() - 3600);

session_destroy();

header('location:index.php');
exit();


?>
