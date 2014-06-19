<?php
if (@$_POST['nhlteam'] == NULL) {
	header("Location: http://www.labdb.org/tv-parse/");
}

// selectcontact.php

include("./header.php");

$nhlteam = $_POST['nhlteam'];
$nhlchannel = $_POST['nhlchannel'];

$i=0;
while ($i < count($nhlchannel)) {
	$nhlchannel[$i] = mysql_real_escape_string($nhlchannel[$i]);
	$i++;
}
$nhlchannel = implode("|",$nhlchannel);
$nhlchannel = $nhlchannel . "|";

$nhlteam = mysql_real_escape_string($nhlteam);

echo <<<EOM

		<div id="team-select">
			<form name="contacts" method="post" action="./submit.php">
			<input type="hidden" value="$nhlteam" name="nhlteam" />
			<input type="hidden" value="$nhlchannel" name="nhlchannel" />
			
			<table border=0>
					<tr><td>* E-Mail Address: </td><td><input name="email" type="text" size="24" /></td><td><span class="smallfont">You will receive television notifications to this email address. It is also used to login and make changes to this website.</span></td></tr>
					<tr><td>* Password: </td><td><input name="password" type="password" size="24" /></td><td></td></tr>
					<tr><td>* Password Again </td><td><input name="password2" type="password" size="24" /></td><td></td></tr>
					<tr><td>Cell Phone Number</td><td><input name="cellnumber" type="text" size="24" disabled /></td><td><span class="smallfont">You can enter your cell number if you would like to receive text message notifications.</td></tr>
					<tr><td>Cell Phone Provider</td><td><select name="cellprovider" disabled /><option value="att">AT&T</option><option value="sprint">Sprint</option><option value="tmobile">T-Mobile</option></select></td><td></td></tr>
			</table>
			Note: Cell phone notifications are not yet active.<br />					
			<br /><input type='submit' value='Submit' />
			</form>
		</div>
		<div id="team-select">&nbsp;</div>
EOM;

include("./footer.php");