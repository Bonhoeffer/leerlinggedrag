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

$allaccess = 0;
$allaccess2 = 0;


//to protect from injection
$gebruikersnaam = mysql_real_escape_string(stripslashes($_POST['gebruikersnaam']));
$wachtwoord = md5(md5(mysql_real_escape_string(stripslashes($_POST['wachtwoord'])) . $salt) . $salt2);

//get info from user who logged on
$sql = "SELECT * 
		FROM docenten
		WHERE gebruikersnaam = '$gebruikersnaam' 
			AND wachtwoord = '$wachtwoord'";

$count = mysql_num_rows(mysql_query($sql, $con));

//get all-access account info
$sql = "SELECT * 
		FROM systeembeheer 
		WHERE gebruikersnaam = '$allaccess' 
			AND wachtwoord = '$allaccess2'";
			
$count2 = mysql_num_rows(mysql_query($sql, $con));

if ($count == 1 || $count2 == 1){
	$_SESSION['gb'] = $gebruikersnaam;
	
	//register lastname
	$sql = "SELECT achternaam
			FROM docenten
			WHERE gebruikersnaam = '$gebruikersnaam' 
				AND wachtwoord = '$wachtwoord'";
	
	$array = mysql_fetch_array(mysql_query($sql, $con));
	$_SESSION['lastName'] = $array['achternaam'];

	//update logboek
	$sql = "INSERT INTO logboek (
				`gebruikersnaam` ,
				`datum` ,
				`tijd`
			)
			VALUES (
				'$gebruikersnaam', 
				CURDATE( ), 
				NOW( )
			)";
	
	if (!mysql_query($sql)){
		die('Error: ' . mysql_error());
	}
	
	//redirect
	header('Location: ../oordeel.php');
}
else{
	//redirect
	$_SESSION['login'] = 0;
	header('Location: ../index.php');
}
?>
