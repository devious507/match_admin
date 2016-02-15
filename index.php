<?php

require_once("config.php");

$body=getUpcomingMatches();
$menu=makeMenu();
?>
<html>
<head><title>Upcoming Matches</title></head>
<body>
<?php echo $body; ?>
<hr>
<?php echo $menu; ?>
</body>
</html>
