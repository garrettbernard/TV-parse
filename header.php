<?php
session_start();
$link = mysql_connect('localhost','','');
mysql_select_db('',$link) or die( "Unable to select database");
@$user_id = $_SESSION['user_id'];
// header.php
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>OOM Sports | Notifying You When Your Team's On TV!</title>
		<link rel="stylesheet" type="text/css" href="./main.css" />
		
		<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	</head>

	<body>
		<div id="container">
			<div id="header">
				<div id="left-header">
					<a href="./index.php"><img src="./images/oom-sports-headerlogo.png" /></a><br />
				</div>

<?php
if (@$_SESSION['user_id'] == NULL) {
echo <<<EOM
				<div id="right-header">
					<h3>Already using OOM Sports?</h3>
					<h4>Login to access your notifications.</h4>
					
					<form name="login" method="post" action="./login.php?a=login">
						<table border=0>
							<tr>
								<td>E-Mail:</td><td><input name="email" type="text" id="email" size="24" /></td><td></td>
							</tr>
							<tr>
								<td>Password:</td><td><input name="password" type="password" id="password" size="24" /></td><td><input type="submit" value="Submit" /></td>
							</tr>
						</table>
						
					</form>
					
				</div>

EOM;

} else {
echo <<<EOM
				<div id="right-header">
					<h4>You're logged in as {$_SESSION['email']}.</h4>
					<h6>( <a href="./login.php?a=logout">Click here</a> to logout. )</h6>					
				</div>
EOM;
}
?>
			</div>
