<?php

$link = mysql_connect('localhost','','');
mysql_select_db('',$link) or die( "Unable to select database");

$sql = "SELECT nhl_lastupdated FROM admin";
$result = mysql_query($sql);
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
$row = mysql_fetch_array($result);
$database_time = ($row['nhl_lastupdated']);

$now = (time() + (60*60*24));
$database_time = ($now - $database_time);
// $database_time = time();
$database_time = 1;

/*
if ($database_time < (60*60*23)) {
	print("This script can only be ran once every 24 hours.");
} else {
*/

$lines = file_get_contents('http://espn.go.com/nhl/schedule');

$firstline = stripos($lines,'<div class="col-main" id="my-teams-table">');



$lines = str_replace(substr($lines,0,$firstline),'',$lines);
$lastline = stripos($lines,'<!-- begin sponsored links -->');
$strendline = strrpos($lines,'</html>');
$lines = str_replace(substr($lines,$lastline,$strendline),'',$lines);

$findstrRESULTS = stripos($lines,'<td width="35%">RESULT</td>');
$lines = str_replace(substr($lines,0,$findstrRESULTS),'',$lines);

$numberofresult = substr_count($lines,'<td width="35%">RESULT</td>');

// print('<p><strong>' . $numberofresult . '</strong></p>');
$i = 1;
while ($i <= $numberofresult) {
$findstrRESULTS = stripos($lines,'<td width="35%">RESULT</td>');
	if ($findstrRESULTS =! NULL) {
		$findstrTABLE = stripos($lines,'</table>');
		$findstrTABLE = ($findstrTABLE + 8);
		$lines = str_replace(substr($lines,0,$findstrTABLE),'',$lines);
	}
	$i++;
}


$lines = str_replace('</a> at <a href','</a></td><td><a href',$lines);
$lines = str_replace('<td width="35%">TEAMS</td>','<td width="13%">TEAM A</td><td width="13%">TEAM B</td>',$lines);

// print($lines);

$numberoftables = substr_count($lines,'</table>');
$select_table = array();
$date = array();
	include 'tableExtractor.class.php'; 
$i = 1;
while($i <= $numberoftables) {
	$locatetable_start = stripos($lines,'<table');
	$locatetable_end = stripos($lines,'</table>');
	$printtable = substr($lines,$locatetable_start,$locatetable_end + 8);
	$date_start = stripos($printtable,'<tr class="stathead"><td colspan="6">');
	$date_end = strrpos($printtable,'</td></tr><tr class="colhead"><td width="13%">');
	$date = substr($printtable,$date_start + 37,$date_end - $date_start - 37);
	$printtable = str_replace(substr($printtable,$date_start,$date_end - $date_start + 10),'',$printtable);
	// $select_table[$i][0] = $date[$i];
	$lines = str_replace(substr($lines,$locatetable_start,$locatetable_end + 8 - $locatetable_start),'',$lines);
	$tx = new tableExtractor; 
	$tx->source = $printtable;
	$tx->anchor = '';
//	$tx->startCol = 0;
	$tableArray = $tx->extractTable(); 

	$select_table[$i] = $tableArray;
	
	$numberofrows = substr_count($printtable,'</tr>');
	
	$j = 1;
	while ($j <= $numberofrows - 1) {
		$time = strip_tags($select_table[$i][$j]['TIME(ET)']);
		$dates = ($date . ' ' . $time);
		$dates = strtotime($dates);
	
		$teama = preg_replace('~([a-z])([A-Z])~', '\\1 \\2', $select_table[$i][$j]['TEAMA']);
		$teamb = preg_replace('~([a-z])([A-Z])~', '\\1 \\2', $select_table[$i][$j]['TEAMB']);

		$teama = str_replace('NY','NY ',$teama);
		$teamb = str_replace('NY','NY ',$teamb);
		$teama = str_replace('.','. ',$teama);
		$teamb = str_replace('.','. ',$teamb);
			

		$inserted = array($teama,$teamb,$dates,$select_table[$i][$j]['AWAYTV'],$select_table[$i][$j]['HOMETV'],$select_table[$i][$j]['NATTV']);
		$inserted = implode("','",$inserted);
		// $inserted = ('"' . $inserted . '"');
		// print($inserted . '<br />');
		
		$inserted = strip_tags($inserted);
		
		$check_if_inserted = md5($inserted);
		
		$result = mysql_query("SELECT data_id,check_if_inserted  FROM nhl WHERE check_if_inserted='" . $check_if_inserted . "' LIMIT 1");
		
		$num_rows = mysql_num_rows($result);
		
		if ($num_rows == '0') {

			$sql = "INSERT INTO nhl (teama,teamb,time,away_tv,home_tv,nat_tv,check_if_inserted) VALUES ('$inserted','$check_if_inserted')";		
			$result = mysql_query($sql);
			// print("<br />Your query was: " . $sql);
			
			if (!$result)
  {
  die('Could not connect: ' . mysql_error());
  }
  
			print("Entry updated. \r\n");
			
		} else {
			print("Duplicate Entry - Skipped \r\n");
		}

		$j++;
	}
	
	$i++;
	}

	$sql = 'UPDATE admin SET nhl_lastupdated = ' . $now . '';	
	$result = mysql_query($sql);
	
	
	
	print('Updated.');
	
	$sql = "DELETE FROM nhl WHERE time<'" . $now . "'";
	mysql_query($sql);
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}

// Update available teams (table 'teams')

$i=0;
	$sql = "SELECT teama,teamb FROM nhl";
	$result = mysql_query($sql);
	
	$i = 0;
	
	while ($i < mysql_num_rows($result)) {
		$row = mysql_fetch_assoc($result);
		$teama_arr[$i] = $row['teama'];
		$teamb_arr[$i] = $row['teamb'];
		$i++;
	}
	
	$teams = array_merge($teama_arr,$teamb_arr);
	$teams = array_unique($teams);
	$teams = array_values($teams);
	


	$sql = "SELECT team_name,sport FROM teams WHERE sport='nhl'";
	$result = mysql_query($sql);
	
	$i=0;
	
	while ($i < mysql_num_rows($result)) {
		$row = mysql_fetch_assoc($result);
		$stored_teams[$i] = $row['team_name'];
		$i++;
	}

	@$stored_teams = array_values($stored_teams);
		
	
	if (is_array($stored_teams)) {
		$teams = array_diff($teams,$stored_teams);
	}

		// print_r($teams);

		if (is_array($teams)) {
				$i=0;
				while ($i < count($teams)) {
					$sql = "INSERT INTO teams (team_name,sport) VALUES ('$teams[$i]','nhl')";
					mysql_query($sql);
				$i++;
				}
		}
	
// Update available channels (table 'channels')

$i=0;
	$sql = "SELECT away_tv,home_tv,nat_tv FROM nhl";
	$result = mysql_query($sql);
	
	$i = 0;
	
	while ($i < mysql_num_rows($result)) {
		$row = mysql_fetch_assoc($result);
		$away_arr[$i] = $row['away_tv'];
		$home_arr[$i] = $row['home_tv'];
		$nat_arr[$i] = $row['nat_tv'];
		$i++;
	}
	
	$channels = array_merge($away_arr,$home_arr);
	$channels = array_merge($channels,$nat_arr);
	$channels = array_unique($channels);
	$channels = array_values($channels);
	


	$sql = "SELECT short_name,sport FROM channels WHERE sport='nhl'";
	$result = mysql_query($sql);
	
	$i=0;
	
	while ($i < mysql_num_rows($result)) {
		$row = mysql_fetch_assoc($result);
		$stored_channels[$i] = $row['short_name'];
		$i++;
	}

	@$stored_channels = array_values($stored_channels);
		
	
	if (is_array($stored_channels)) {
		$channels = array_diff($channels,$stored_channels);
	}
		$channels = array_filter($channels);
		$channels = array_values($channels);
		
		// print_r($channels);

		if (is_array($channels)) {
				$i=0;
				while ($i < count($channels)) {
					$sql = "INSERT INTO channels (short_name,sport) VALUES ('$channels[$i]','nhl')";
					mysql_query($sql);
				$i++;
				}
		}	
	
	

// }
?>