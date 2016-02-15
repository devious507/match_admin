<?php

require_once("config.php");
$isOK=checkRegistrations($_POST['matchid'],$_POST['relay'],$_POST['firing_point']);
$vals[]='default';
if($isOK == 'OK') {
	foreach($_POST as $key=>$val) {
		$vals[]="'".$val."'";
	}
	$sql="INSERT INTO registrants VALUES (".implode(",",$vals).")";
	$db=connectDb();
	$res=$db->query($sql);
	header("Location: index.php");
	exit();
} else {
	print $isOK;
	//header("Content-type: text/plain");var_dump($_POST);
}
?>
