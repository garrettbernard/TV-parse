<?php

// edit.php
$user_id = $_SESSION['user_id'];

class edit {

	function my_teams() {
	global $html,$link,$user_id;

		$sql = "SELECT user_id,my_teams FROM users WHERE user_id='$user_id'";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$my_teams = explode("|",$row['my_teams']);
		$my_teams = array_filter($my_teams);
		$my_teams = array_values($my_teams);

		$sql = "SELECT tid,team_name,sport FROM teams WHERE sport='nhl' ORDER BY team_name ASC";
		$result = mysql_query($sql);
		print_r($my_teams);
		$i=0;
		while ($i < mysql_num_rows($result)) {
			$row = mysql_fetch_assoc($result);
	
			if (in_array($row['tid'],$my_teams)) {
				$nhl[$i] = "<span class='team-select'><input type='checkbox' name='nhlteam[]' value='" . $row['tid'] . "' checked /> " . $row['team_name'] . "</span>\n";
			} else {
				$nhl[$i] = "<span class='team-select'><input type='checkbox' name='nhlteam[]' value='" . $row['tid'] . "' /> " . $row['team_name'] . "</span>\n";
			}
			$i++;
		}

		$nhl = implode("",$nhl);

echo <<<EOM
<h2>My Teams</h2>\n
<p>$nhl</p>\n
EOM;
	}
	
	function my_channels() {
	global $html,$link,$user_id;
	
		$sql = "SELECT user_id,my_channels FROM users WHERE user_id='$user_id'";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$my_channels = explode("|",$row['my_channels']);
		$my_channels = array_filter($my_channels);
		$my_channels = array_values($my_channels);
	
		$i=0;

		$sql = "SELECT cid,short_name,sport FROM channels WHERE sport='nhl' ORDER BY short_name ASC";
		$result = mysql_query($sql);

		$i=0;
			
		while ($i < mysql_num_rows($result)) {
			$row = mysql_fetch_assoc($result);
			
			if (in_array($row['cid'],$my_channels)) {
				$nhl[$i] = "<span class='team-select'><input type='checkbox' name='nhlchannel[]' value='" . $row['cid'] . "' checked /> " . $row['short_name'] . "</span>\n";
			} else {
				$nhl[$i] = "<span class='team-select'><input type='checkbox' name='nhlchannel[]' value='" . $row['cid'] . "' /> " . $row['short_name'] . "</span>\n";
			}
			$i++;
		}
		$nhl = implode("",$nhl);

echo <<<EOM
<br />
<h2>My Channels</h2>\n
$nhl\n
<br />
EOM;
	}
	
	function my_notifications() {
	global $html,$link,$user_id;
	
		$sql = "SELECT user_id,cellnumber,cellprovider,notify_email,notify_cell FROM users WHERE user_id='$user_id'";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$cellnumber = $row['cellnumber'];
		$cellprovider = $row['cellprovider'];
		$notify_email = explode("|",$row['notify_email'],-1);
		$notify_cell = explode("|",$row['notify_cell'],-1);

/*
		$i=0;
		while ($i < count($notify_email)) {
			if ($notify_email[$i] == '1') {
				$checked = 'checked';
				$value = '1';
			} else {
				$checked = '';
				$value = '0';
			}
			if ($i=0) {
				$notify_email[$i] = "<span class='team-select'><input type='checkbox' name='notifyemail[0]' value='" . $value . "' $checked />The night before</span>\n";
			} else if ($i=1) {
				$notify_email[$i] = "<span class='team-select'><input type='checkbox' name='notifyemail[1]' value='" . $value . "' $checked />The morning of</span>\n";
			} else if ($i=2) {
				$notify_email[$i] = "<span class='team-select'><input type='checkbox' name='notifyemail[2]' value='" . $value . "' $checked />A few hours before</span>\n";
			}
		$i++;
		}
		*/
		// $notify_email = implode("",$implode);
		
		// $target = explode("|",$target);
		print_r($target);
		
		$sql = "SELECT cpid,name,email_ending FROM cell_provider ORDER BY name ASC";
		$result = mysql_query($sql);

		$i=0;
		while ($i < mysql_num_rows($result)) {
			$row = mysql_fetch_assoc($result);
			
			if ($row['cpid'] == $cellprovider) {
				$cell_company_list[$i] = "<option value='" . $row['cpid'] . "' selected='selected'>" . $row['name'] . "</option>";
			} else {
				$cell_company_list[$i] = "<option value='" . $row['cpid'] . "'>" . $row['name'] . "</option>";
			}
			$i++;
		}
		


			
		
		$cell_company_list = implode("",$cell_company_list);
		
		
	
echo <<<EOM
<br />
			<table border=0>
					<tr><td>Cell Phone Number</td><td><input name="cellnumber" type="text" size="24" value="$cellnumber" /></td><td><span class="smallfont">You can enter your cell number if you would like to receive text message notifications.</td></tr>
					<tr><td>Cell Phone Provider</td><td><select name="cellprovider" value="$cellprovider" />$cell_company_list</td><td></td></tr>
					<tr><td colspan='2'><h5>Set Where You'll Be Notified</h5></td></tr>
					<!-- <tr><td>Email Message</td><td>$notify_email</td></tr> -->
			</table>
			Note: Cell phone notifications are not yet active. However, should you input your cell phone number, you will be notified when the option becomes available.<br />					

EOM;

	}
	
	function display_form() {
		global $html;

		$html = '';
echo <<<EOM
			<div id="team-select">
				<form name="contacts" method="post" action="./submit.php">
EOM;
					print("<div id='anchor'>");
					$this->my_teams();
					print("</div>");
					print("<div id='anchor'>");
					$this->my_channels();
					print("</div>");
					print("<div id='anchor'>");
					$this->my_notifications();
					print("</div>");
echo <<<EOM
					<br /><input type='submit' value='Submit' />
				</form>
			</div>
			<div id="team-select">&nbsp;</div>
EOM;


	}
}




$edit = new edit;


include("./header.php");
$edit->display_form();



	include("./footer.php");

?>