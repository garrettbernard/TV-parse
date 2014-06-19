<?php
session_start();

if (@$_POST['nhlteam'] == NULL) {
	header("Location: http://www.labdb.org/tv-parse/");
}

$link = mysql_connect('localhost','','');
mysql_select_db('',$link) or die( "Unable to select database");


// submit.php

if (@$_SESSION['user_id'] != NULL) {
	$user_id = $_SESSION['user_id'];

	$i=0;
	$nhlteam = implode("|",$_POST['nhlteam']);
	$nhlteam = $nhlteam . "|";
	
	$nhlchannel = implode("|",$_POST['nhlchannel']);
	$nhlchannel = $nhlchannel . "|";
		

	$nhlteam = mysql_real_escape_string($nhlteam);
	$nhlchannel = mysql_real_escape_string($nhlchannel);
	$email = mysql_real_escape_string($_POST['email']);
	$password = mysql_real_escape_string($_POST['password']);
	$cellnumber = mysql_real_escape_string($_POST['cellnumber']);
	$cellprovider = mysql_real_escape_string($_POST['cellprovider']);


	$sql = "UPDATE users SET my_teams='$nhlteam',my_channels='$nhlchannel',cellnumber='$cellnumber',cellprovider='$cellprovider' WHERE user_id='$user_id'";
	// print($sql);
	mysql_query($sql) or die();

	header("Location: http://www.labdb.org/tv-parse/index.php");
	
	} else {
	

$nhlteam = mysql_real_escape_string($_POST['nhlteam']);
$nhlchannel = mysql_real_escape_string($_POST['nhlchannel']);
$email = mysql_real_escape_string($_POST['email']);
$password = mysql_real_escape_string($_POST['password']);
$cellnumber = mysql_real_escape_string($_POST['cellnumber']);
$cellprovider = mysql_real_escape_string($_POST['cellprovider']);


$sql = "INSERT INTO users (email_address,password,my_teams,my_channels,cellnumber,cellprovider) VALUES ('$email','$password','$nhlteam','$nhlchannel','$cellnumber','$cellprovider')";
mysql_query($sql) or die();

$sql = "SELECT user_id,email_address FROM users WHERE email_address = '$email'";
$result = mysql_query($sql) or die();
$row = mysql_fetch_assoc($result);

$_SESSION['user_id'] = $row['user_id'];
$_SESSION['email'] = $row['email_address'];

header("Location: http://www.labdb.org/tv-parse/index.php");
}