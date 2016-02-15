<?php

define("DBDSN","pgsql://paulo@localhost/matchreg");
require_once("PHPExcel/Classes/PHPExcel.php");
require_once("MDB2.php");


function connectDb() {
	$db=MDB2::singleton(DBDSN);
	return $db;
}

function makeMenu() {
	global $_COOKIE;
	if(isset($_COOKIE['match_token'])) {
		$isAdmin=checkAdmin($_COOKIE['match_token']);
	} else {
		$isAdmin=false;
	}
	if($isAdmin) {
		return "<a href=\"login.php\">Login</a> | <a href=\"cookie.php\">Cookie Debug</a>";
	} else {
		return "<a href=\"login.php\">Login</a>";
	}
}

function isAdminORMD($c,$match) {
	$db=connectDb();
	$sql="DELETE FROM sessions WHERE expires < now()";
	$sth=$db->prepare($sql);
	$sth->execute();
	if(!isset($c['match_token'])) {
		return;
	}
	$token=$c['match_token'];
	if(checkAdmin($token)) {
		return true;
	}
	$sql="SELECT match_director FROM matches WHERE matchid=?";
	$sth=$db->prepare($sql);
	$res=$sth->execute($match);
	$row=$res->fetchRow();
	$matchDirectorID=$row[0];
	$sql="SELECT userid FROM sessions WHERE token=?";
	$sth=$db->prepare($sql);
	$res=$sth->execute($token);
	$row=$res->fetchRow();
	if($row[0] == $matchDirectorID) {
		return true;
	} else {
		return false;
	}
}
function matchMenu($c,$match) {
	$showMenu=isAdminorMD($c,$match);
	$menu="<a href=\"editMatch.php?matchid={$match}\">Edit Match</a>";
	if($showMenu) {
		return $menu;
	} else {
		return;
	}
}
function delSession($token) {
	$db=connectDb();
	$sql="DELETE FROM sessions WHERE token=?";
	$sth=$db->prepare($sql);
	$sth->execute($token);
	return;
}
function checkAdmin($cookie) {
	$db=connectDb();
	$sql="SELECT userid FROM sessions WHERE token=?";
	$sth=$db->prepare($sql);
	$res=$sth->execute($cookie);
	$row=$res->fetchRow();
	$userid = $row[0];
	if(is_numeric($userid)) {
		$sql="SELECT is_admin FROM users WHERE userid=?";
		$sth=$db->prepare($sql);
		$res=$sth->execute($userid);
		$row=$res->fetchRow();
		if($row[0] == 't') {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function scramblePassword($pass) {
	return crypt($pass);
}

function makeToken($length=20) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function checkRegistrations($matchid,$relay,$fp) {
	if(!is_numeric($matchid)) {
		return 'INVALID';
	}
	if(!is_numeric($relay)) {
		return 'INVALID';
	}
	if(!is_numeric($fp)) {
		return 'INVALID';
	}
	$db=connectDb();
	$sql="SELECT count(*) FROM registrants WHERE matchid={$matchid} AND relay={$relay} AND firing_point={$fp}";
	$res=$db->query($sql);
	$row=$res->fetchRow();
	if($row[0] == 0) {
		return 'OK';
	} else {
		return 'DUPLICATE';
	}
}
function getMatchTitle($match) {
	if(!is_numeric($match)) {
		echo "Application Error: Potential SQL Injection rejected";
		exit();
	}
	$db=connectDb();
	$sql="SELECT match_name FROM matches WHERE matchid={$match}";
	$res=$db->query($sql);
	$row=$res->fetchRow();
	return $row[0];
}

function getFpCount($match) {
	if(!is_numeric($match)) {
		echo "Application Error: Potential SQL Injection rejected";
		exit();
	}
	$db=connectDb();
	$sql="SELECT num_fp FROM matches WHERE matchid={$match}";
	$res=$db->query($sql);
	$row=$res->fetchRow();
	return $row[0];
}

function fixDate($date) {
	$arr=preg_split("/ /",$date);
	$date=$arr[0];
	$time=$arr[1];
	$date=preg_split("/-/",$date);
	$time=preg_split("/:/",$time);
	$myDate=sprintf("%02d/%02d/%4d<br>%02d:%02d",$date[1],$date[2],$date[0],$time[0],$time[1]);
	return $myDate;
}
function getRelays($match) {
	if(!is_numeric($match)) {
		echo "Application Error: Potential SQL Injection rejected";
		exit();
	}
	$db=connectDb();
	$sql="SELECT relay1,relay2,relay3,relay4,relay5,relay6,relay7,relay8,relay9 FROM matches WHERE matchid={$match}";
	$res=$db->query($sql);
	$row=$res->fetchRow();
	$relays=array();
	foreach($row as $r) {
		if($r != NULL) {
			$r=fixDate($r);
			$relays[]=$r;
		}
	}
	return $relays;
}

function getUpcomingMatches($num=10) {
	if(!is_numeric($num)) {
		echo "Application Error: Potential SQL Injection rejected";
		exit();
	}
	$sql="SELECT matchid,match_name,match_start_date,match_end_date FROM matches WHERE match_start_date > date(now()) ORDER BY match_start_date,match_name ASC LIMIT {$num}";
	$db=connectDb();
	$res=$db->query($sql);
	if(PEAR::isError($res)) {
		print $res->getMessage();
		exit();
	}
	$tdata="<table cellpadding=\"5\" cellspacing=\"0\" border=\"1\">\n";
	$tdata.="\t<tr><td align=\"center\" colspan=\"3\">Upcoming Matches</td></tr>\n";
	$tdata.="\t<tr><td>Match Name</td><td>Start Date</td><td>End Date</td></tr>\n";
	while(($row=$res->fetchrow()) == true) {
		$link="<a href=\"showMatch.php?matchid={$row[0]}\">{$row[1]}</a>";
		$tdata.="\t<tr><td>{$link}</td><td>{$row[2]}</td><td>{$row[3]}</td>\n";
	}
	$tdata.="</table>\n";
	return $tdata;
}
