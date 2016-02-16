<?php

require_once("config.php");

if(isset($_COOKIE['match_token']) && isset($_GET['matchid'])) {
	$c=$_COOKIE;
	$match=$_GET['matchid'];
	$isAdmin= isAdminORMD($c,$match);
} else {
	header("Location: login.php");
	exit();
}


if(!$isAdmin) {
	header("Location: index.php");
	exit();
} 
// Whew, time to actually bring up the edit screen now
?>
