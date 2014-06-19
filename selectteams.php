<?php

// selectteams.php

include("./header.php");

$sql = "SELECT tid,team_name,sport FROM teams WHERE sport='nhl' ORDER BY team_name ASC";
$result = mysql_query($sql);

$i=0;
	
while ($i < mysql_num_rows($result)) {
	$row = mysql_fetch_assoc($result);
	$nhl[$i] = "<span class='team-select'><input type='checkbox' name='nhlteam[]' value='" . $row['tid'] . "' /> " . $row['team_name'] . "</span>";
	$i++;
}


$nhl = implode("",$nhl);

echo <<<EOM
		<div id="team-select">
			<form name="teams" method="post" action="./selectchannels.php">
			
			<h2>NHL</h2>
			$nhl
			<br /><input type='submit' value='Next>' />
			</form>
		</div>
		<div id="team-select">&nbsp;</div>
EOM;


include("./footer.php");