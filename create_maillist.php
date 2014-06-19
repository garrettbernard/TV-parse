<?php

$link = mysql_connect('localhost','USER','PASSWORD');
mysql_select_db('',$link) or die( "Unable to select database");

$z=0;

$sqlgo = "SELECT user_id FROM users";
$resultgo = mysql_query($sqlgo);

$a=0;
while ($a < mysql_num_rows($resultgo)) {
	$rowgo = mysql_fetch_array($resultgo);
	$user_id[$a] = $rowgo['user_id'];
	// print($user_id[$a] . "<br />");
	$a++;
}

foreach ($user_id as $key => $value) {

print("<h1>" . $value . "</h1>");
$sql = "SELECT user_id,my_teams,my_channels FROM users WHERE user_id='$value'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$teams = explode("|",$row['my_teams'],-1);
$channels = explode("|",$row['my_channels'],-1);
print_r($teams);
print_r($channels);

$i=0;
while ($i < count($teams)) {
	$sql = "SELECT tid,team_name,sport FROM teams WHERE tid='$teams[$i]'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$team_name[$i] = $row['team_name'];
	print($team_name[$i]);

	$i++;
}
	print($i . "<br />");
$team_name = array_filter($team_name);
$team_name = array_values($team_name);
// print_r($team_name);

$i=0;
while ($i < count($channels)) {
	$sql = "SELECT cid,short_name,sport FROM channels WHERE cid='$channels[$i]'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$channel_name[$i] = $row['short_name'];
	$i++;
}

$channel_name = array_filter($channel_name);
$channel_name = array_values($channel_name);


// Get data_id of all teams the user has

$i=0;
$n=0;
$k=0;
while ($i < count($team_name)) {
	$sqla = "SELECT data_id,teama FROM nhl WHERE teama = '$team_name[$i]'";
	$sqlb = "SELECT data_id,teamb FROM nhl WHERE teamb = '$team_name[$i]'";

	$resulta = mysql_query($sqla);
	$resultb = mysql_query($sqlb);

	$m=0;
	while ($m < mysql_num_rows($resulta)) {
		$row = mysql_fetch_assoc($resulta);
		$teams_data_id[$n] = $row['data_id'];
		$m++;
		$n++;
	}


	$j=0;
	while ($j < mysql_num_rows($resultb)) {
		$row = mysql_fetch_assoc($resultb);
		$teams_data_id[$n] = $row['data_id'];
		$j++;
		$n++;
	}
	
$i++;
}

$teams_data_id = array_unique($teams_data_id);
$teams_data_id = array_filter($teams_data_id);
$teams_data_id = array_values($teams_data_id);


// Get data_id of all TV channels the user has

$i=0;
$n=0;
$k=0;
$x=0;
while ($i < count($channel_name)) {
	$sql_away = "SELECT data_id,away_tv FROM nhl WHERE away_tv = '$channel_name[$i]'";
	$sql_home = "SELECT data_id,home_tv FROM nhl WHERE home_tv = '$channel_name[$i]'";
	$sql_nat = "SELECT data_id,nat_tv FROM nhl WHERE nat_tv = '$channel_name[$i]'";
	$result_away = mysql_query($sql_away);
	$result_home = mysql_query($sql_home);
	$result_nat = mysql_query($sql_nat);
	
	$m=0;
	while ($m < mysql_num_rows($result_away)) {
		$row = mysql_fetch_assoc($result_away);
		$tv_did[$n] = $row['data_id'];
		$m++;
		$n++;
	}
	
	$j=0;
	while ($j < mysql_num_rows($result_home)) {
		$row = mysql_fetch_assoc($result_home);
		$tv_did[$n] = $row['data_id'];
		$j++;
		$n++;
	}
	
	$y=0;
	while ($y < mysql_num_rows($result_nat)) {
		$row = mysql_fetch_assoc($result_nat);
		$tv_did[$n] = $row['data_id'];
		$y++;
		$n++;
	}
$i++;
}

$tv_did = array_unique($tv_did);
$tv_did = array_filter($tv_did);
$tv_did = array_values($tv_did);


# make array of values found in both television and teams

$did = array_intersect($teams_data_id,$tv_did);
$did = array_unique($did);
$did = array_filter($did);
$did = array_values($did);


$count_did = count($did);

if ($count_did != '0') {
$i=0;

while ($i < $count_did) {
	$sql = "SELECT * FROM nhl WHERE data_id='$did[$i]'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$day = date('D, M j',$row['time']);
	$time = date('g:i T',$row['time']);
	
	$networks = array($row['away_tv'],$row['home_tv'],$row['nat_tv']);
	$networks = array_values(array_filter($networks));
	
	$network_count = count($networks);
	$networks[$network_count - 1] = "and " . $networks[$network_count - 1];
	
	if ($network_count == '3') {
		$networks[0] = $networks[0] . ",";
	}
	
	$networks = implode(" ",$networks);
	
	$did_text[$z] = "<li>" . $row['teama'] . " vs " . $row['teamb'] . " on " . $day . " at " . $time . " on " . $networks . ".</li>";
	$z++;
	$i++;
}
$did_text .= implode("",$did_text);
} else {
$did_text .= '';
}
print("<h3>" . $value . "</h3>");
print($did_text);

}
