<?php

require_once("config.php");

print "<html><head><title>Match Self Registration</title></head><body>\n";
print "<form method=\"post\" action=\"selfRegisterAction.php\">\n";
print "<table cellpadding=\"5\" cellspacing=\"0\" border=\"1\">\n";
print makeEntryLine("Match ID",'matchid',$_GET['matchid'],'hidden');
print makeEntryLine("Last Name",'last_name',NULL);
print makeEntryLine("First Name",'first_name',NULL);
print makeEntryLine("Middle Name",'middle_name',NULL);
print makeEntryLine("Birthdate",'birthdate',NULL);
print makeEntryLine("Relay",'relay',$_GET['relay'],'hidden');
print makeEntryLine("Firing Point",'firing_point',$_GET['fp'],'hidden');
print makeEntryLine("Address",'address',NULL);
print makeEntryLine("Address Cont.",'address_cont',NULL);
print makeEntryLine('City','city',NULL);
print makeEntryLine('State','state',NULL);
print makeEntryLine('Zip','zip',NULL);
print makeEntryLine('Email','email',NULL);
print makeEntryLine('Phone','phone',NULL);
print "<tr><td colspan=\"2\"><input type=\"submit\"></td></tr>\n";
print "</table>\n";
print "</form>\n";
print "</body></html>\n";

function makeEntryLine($lbl,$name,$value,$type="text") {
	if($type == "hidden") {
		$line="\t<tr><td>{$lbl}</td><td><input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\">{$value}</td></tr>\n";
	} else {
		$line="\t<tr><td>{$lbl}</td><td><input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\"></td></tr>\n";
	}
	return $line;
}
?>
