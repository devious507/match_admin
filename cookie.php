<?php

require_once("config.php");
if(isset($_COOKIE['match_token'])) {
	$cookieText=$_COOKIE['match_token'];
	$link="<a href=\"eatCookie.php\">Delete Cookies</a><br>";
	$link.="<a href=\"index.php\">Back to Index</a>";
	$isAdmin=checkAdmin($_COOKIE['match_token']);
	$cookieText.="<br>Is Admin? {$isAdmin}";
} else {
	$cookieText='No Cookie Found';
	$link="<a href=\"index.php\">Back to Index</a>";
}
?>
<html>
<head><title>Cookie Monster</title></head>
<body>
<?php echo $cookieText; ?><br>
<?php echo $link; ?><br>
</body>
</html>
