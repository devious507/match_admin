<?php

require_once("config.php");
$cookie=$_COOKIE['match_token'];
delSession($cookie);
setcookie("match_token",'',-1);
header("Location: cookie.php");
?>
