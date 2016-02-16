<?php

require_once("config.php");

if(isset($_COOKIE['match_token']) && isset($_GET['matchid']) && isset($_GET['relay']) && isset($_GET['firing_point'])) {
	$relay=$_GET['relay'];
	$firing_point=$_GET['firing_point'];
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


$sql="select * from registrants WHERE matchid = ? AND relay = ? AND firing_point = ?";
$db=connectDb();
$sth=$db->prepare($sql);
$res=$sth->execute(array($match,$relay,$firing_point));
errorChecker($res);
$row=$res->fetchRow(MDB2_FETCHMODE_ASSOC);
$tdata='';
foreach($row as $key=>$val) {
	$lbl=ucwords(preg_replace("/_/"," ",$key));
	switch($key) {
	case "reg_id":
	case "matchid":
		$tdata.="\t<tr><td>{$lbl}</td><td><input type=\"hidden\" name=\"{$key}\" value=\"{$val}\">{$val}</td></tr>\n";
		break;
	case "address_cont":
		$lbl.=".";
	default:
		$tdata.="\t<tr><td>{$lbl}</td><td><input type=\"text\" name=\"{$key}\" value=\"{$val}\"></td></tr>\n";
		break;
	}
}
$tdata.="\t<tr><td colspan=\"2\"><input type=\"submit\"></td></tr>\n";
?>
<html>
<head><title>Edit Registrant Entry</title>
</head>
<body>
<form method="post" action="editEntryAction.php">
<table cellpadding="5" cellspacing="0" border="1">
<?php echo $tdata; ?>
</table>
</form>
</body>
</html>
