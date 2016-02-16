<?php

require_once("config.php");
$expected=array('reg_id','matchid','last_name','first_name','middle_name',
	'birthdate','relay','firing_point','address','address_cont','city',
	'state','zip','email','phone');
foreach($expected as $ex) {
	if(!isset($_POST[$ex])) {
		print "<html><head><title>Action Item</title></head><body>";
		print "Missing Variable {$ex}\n";
		print "<br><a href=\"index.php\">Back</a>";
		print "</body></html>";
		exit();
	}
}
$reg_id=$_POST['reg_id'];
$matchid=$_POST['matchid'];
$relay=$_POST['relay'];
$firing_point=$_POST['firing_point'];

unset($_POST['reg_id']);
unset($_POST['matchid']);
foreach($_POST as $k=>$v) {
	$fields[]=$k." = ?";
	$vals[]=$v;
}
$vals[]=$matchid;
$vals[]=$reg_id;

$db=connectDb();
errorChecker($db);
$sql="SELECT reg_id FROM registrants WHERE matchid = ? AND relay = ? AND firing_point = ?";
$sth=$db->prepare($sql);
errorChecker($db);
$res=$sth->execute(array($matchid,$relay,$firing_point));
$row=$res->fetchRow(MDB2_FETCHMODE_ASSOC);
errorChecker($row);
if(($row['reg_id'] != $reg_id) && ($row['reg_id'] != NULL)) {
	// Error, relay slot already full
	print "<html><head><title>Unable</title></head><body>";
	print "Error, unable to move registrant to relay {$relay} firing point {$firing_point} due to a conflict.<br>\n";
	print "<a href=\"showMatch.php?matchid={$matchid}\">Back to Match</a>";
	print "</body></html>\n";
	exit();
}
$sql="SELECT relay{$relay} FROM matches WHERE matchid = ?";
$sth=$db->prepare($sql);
errorChecker($sth);
$res=$sth->execute($matchid);
errorChecker($res);
$row=$res->fetchRow();
if($row[0] == NULL) {
	// Error, relay slot does not exist
	print "<html><head><title>Unable</title></head><body>";
	print "Error, unable to move registrant to relay {$relay} firing point {$firing_point} due to a conflict. (RELAY NOEXIST)<br>\n";
	print "<a href=\"showMatch.php?matchid={$matchid}\">Back to Match</a>";
	print "</body></html>\n";
	exit();
}


$sql="UPDATE registrants SET ".implode(", ",$fields)." WHERE matchid = ? AND reg_id = ?";
$sth=$db->prepare($sql);
errorChecker($db);
$res=$sth->execute($vals);
errorChecker($res);
header("Location: showMatch.php?matchid={$matchid}");
?>
