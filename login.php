<?php

require_once("config.php");

if(isset($_POST['username']) && isset($_POST['password'])) {
	$sql="SELECT userid,password,is_admin FROM users WHERE username = ?";
	$db=connectDb();
	$sth=$db->prepare($sql);
	if(PEAR::isError($sth)) {
		print $res->getMessage();
		exit();
	}
	$res=$sth->execute($_POST['username']);
	if(PEAR::isError($res)) {
		print $res->getMessage();
		exit();
	}
	$row=$res->fetchRow();
	$cryptDbPw=$row[1];
	$dbSalt=substr($cryptDbPw,0,12);
	$cryptSubmittedPw=crypt($_POST['password'],$dbSalt);
	if($cryptDbPw == $cryptSubmittedPw) {
		$userid=$row[0];
		$token=crypt(makeToken(20));
		$sql="DELETE FROM sessions WHERE expires < now()";
		$db=connectDb();
		$res=$db->query($sql);
		$sql="INSERT INTO sessions (userid, token) VALUES (?, ?)";
		$sth=$db->prepare($sql);
		if(PEAR::isError($sth)) {
			print "STH:  ";
			print $sth->getMessage();
			exit();
		}
		$insertArr=array($userid,$token);
		$res=$sth->execute($insertArr);
		if(PEAR::isError($res)) {
			print "RES:  ";
			print $res->getMessage();
			print "<pre>"; var_dump($res); print "</pre>";
			exit();
		}
		setcookie("match_token",$token,mktime().time()+60*60*24);
		header("Location: index.php");
	} else {
		header("Location: login.php");
	}
	exit();
}
?>
<html>
<head><title>System Login</title></head>
<body>
<form method="post" action="login.php">
<table cellpadding="5" cellspacing="0" border="1">
<tr><td>Login</td><td><input type="text" name="username" size="10"></td></tr>
<tr><td>Password</td><td><input type="password" name="password" size="10"></td></tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
</form>
</body>
</html>
