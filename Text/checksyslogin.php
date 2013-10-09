<?php
session_start();

//md5 encryption
ob_start();
include('schoolinfo/253c1ad6dd5825f1f5ba10adea7e42a7.txt');
$salt = ob_get_clean();
ob_start();
include('schoolinfo/cbfc3317abb7e081ab1d3c13f12df96c.txt');
$salt2 = ob_get_clean();

//connect to db
include('connect.php');

//to protect from injection 
$gebruikersnaam = mysql_real_escape_string(stripslashes($_POST['gebruikersnaam']));
$wachtwoord = md5(md5(mysql_real_escape_string(stripslashes($_POST['wachtwoord'])) . $salt) . $salt2);

//get info from user who logged on
$sql = "SELECT * 
		FROM systeembeheer
		WHERE gebruikersnaam = '$gebruikersnaam'
			AND wachtwoord = '$wachtwoord'";
		
$count = mysql_num_rows(mysql_query($sql, $con));

//if result matched $gebruikersnaam and $wachtwoord, table row must be 1 row, 0 if result didn't match
if ($count == 1){
	$_SESSION['gb'] = $gebruikersnaam;
	$_SESSION['wachtwoord'] = $wachtwoord;
	
	//update logboek
	$sql = "INSERT INTO syslogboek (
				`gebruikersnaam` ,
				`datum` ,
				`tijd`
			)
			VALUES (
				'$gebruikersnaam',
				CURDATE( ),
				NOW( )
			)";
	
	if (!mysql_query($sql, $con)){
		die('Error: ' . mysql_error());
	}
	
	//redirect
	header('Location: ../accounts.php');
}
else{
	//redirect
	$_SESSION['sLogin'] = 0;
	header('Location: ../slogin.php');
}
?>

