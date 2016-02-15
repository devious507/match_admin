<?php

require_once("config.php");
if(!isset($_GET['matchid'])) {
	header("Location: index.php");
	exit();
}

$fpCount=getFpCount($_GET['matchid']);
$relays=getRelays($_GET['matchid']);
$matchTitle=getMatchTitle($_GET['matchid']);
$relayCount=count($relays);
$topline="";
foreach($relays as $r) {
	$topline.="<td>{$r}</td>";
}

for($i=1; $i <= $relayCount; $i++) {
	for($j=1; $j<=$fpCount; $j++) {
		$arr[$i][$j]="<a href=\"selfRegister.php?matchid={$_GET['matchid']}&relay={$i}&fp={$j}\">Available</a>";
	}
}
$sql="SELECT last_name,first_name,relay,firing_point,city,state FROM registrants WHERE matchid={$_GET['matchid']}";
$db=connectDb();
$res=$db->query($sql);
while(($row=$res->fetchRow())==true) {
	$obscure=isAdminorMD($_COOKIE,$_GET['matchid']);
	if(!$obscure) {
		$firstLetterLastName=substr($row[0],0,1);
		$firstLetterFirstName=substr($row[1],0,1);
		$obscuredText="{$firstLetterLastName}***, {$firstLetterFirstName}*** ({$row[4]})";
		$arr[$row[2]][$row[3]]=$obscuredText;
	} else {
		$txt=$row[0].", ".$row[1]." (".$row[4].")";
		$link="<a href=\"editEntry.php?matchid={$_GET['matchid']}&relay={$row[2]}&firing_point={$row[3]}\">{$txt}</a>";
		$arr[$row[2]][$row[3]]=$link;
	}
}
$relayDetails="";
for($i=1; $i<=$fpCount; $i++) {
	$relayDetails.="\n<tr><td align=\"center\">{$i}";
	for($j=1; $j<=$relayCount; $j++) {
		$relayDetails.="<td>{$arr[$j][$i]}</td>";
	}
	$relayDetails.="</tr>\n";
}
?>
<html>
<head><title><?php echo $matchName?></title></head>
<body>
<table cellpadding="5" cellspacing="0" border="1">
<tr><td colspan="<?php echo $relayCount+1;?>"><?php echo $matchTitle;?></td></tr>
<tr><td>&nbsp;</td><td colspan="<?php echo $relayCount;?>">Relay Times</td></tr>
<tr><td>Firing Point</td><?php echo $topline; ?></tr>
<?php echo $relayDetails; ?>
</table>
<?php echo matchMenu($_COOKIE,$_GET['matchid']); ?>
</body>
</html>
