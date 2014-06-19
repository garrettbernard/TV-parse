<?php
session_start();
$link = mysql_connect('localhost','','');
mysql_select_db('',$link) or die( "Unable to select database");
// login.php

if (@$_GET['a'] == 'logout') {
		$_SESSION = array();

		//if (isset($_COOKIE[session_name()])) {
		//	    setcookie(session_name(), '', time()-42000, '/');
		//}
		
		$_SESSION['user_id'] = NULL;
		session_destroy();
		
			header("Location: http://www.labdb.org/tv-parse/index.php");
			exit;

	} else if (@$_GET['a'] == 'login') {
	
			$password = @md5(mysql_real_escape_string($_POST['password']));
			// $password = mysql_real_escape_string($_POST['password']);
		// print($password);
		$email = mysql_real_escape_string($_POST['email']);
		
/*
		$sql    = "SELECT * FROM users WHERE uid='1'";

		$result = mysql_query($sql, $link); 
			if ($result) {
			echo "Database Error";
			echo 'MySQL Error: ' . mysql_error();
			// exit;
		}
*/

$sql = "SELECT user_id,password,email_address FROM users WHERE email_address = '$email'";
$result = mysql_query($sql) or die();
$row = mysql_fetch_assoc($result);

		
		if ($row['password'] == $password AND $row['email_address'] == $email) {
			$_SESSION['email'] = $email;
			$_SESSION['user_id'] = $row['user_id'];


			header('Location: http://www.labdb.org/tv-parse/');
			// print("Correct Possword!");
			exit;
			
		} else {
			print("Incorrect password.");
		}

	}
	
