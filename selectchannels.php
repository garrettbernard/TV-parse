<?php
if (@$_POST['nhlteam'] == NULL) {
	header("Location: http://www.labdb.org/tv-parse/");
}

// selectchannels.php

include("./header.php");

$nhlteam = $_POST['nhlteam'];

$i=0;
while ($i < count($nhlteam)) {
	$nhlteam[$i] = mysql_real_escape_string($nhlteam[$i]);
	$i++;
}
$nhlteam = implode("|",$nhlteam);
$nhlteam = $nhlteam . "|";
$sql = "SELECT cid,short_name,sport FROM channels WHERE sport='nhl' ORDER BY short_name ASC";
$result = mysql_query($sql);

$i=0;
	
while ($i < mysql_num_rows($result)) {
	$row = mysql_fetch_assoc($result);
	$nhl[$i] = "<span class='team-select'><input type='checkbox' name='nhlchannel[]' value='" . $row['cid'] . "' /> " . $row['short_name'] . "</span>";
	$i++;
}
$nhl = implode("",$nhl);

echo <<<EOM

		<div id="team-select">
			<form name="channels" method="post" action="./selectcontact.php">
			<input type="hidden" value="$nhlteam" name="nhlteam" />
			<h2>NHL</h2>
			$nhl
			<br /><input type='submit' value='Next>' />
			</form>
		</div>
		<div id="team-select">&nbsp;</div>
EOM;

include("./footer.php");