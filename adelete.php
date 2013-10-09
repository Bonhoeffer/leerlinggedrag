<?php
//connect to db
include('Text/connect.php');

//delete account
$sql = "DELETE FROM docenten
		WHERE gebruikersnaam = '$_GET[gb]'";

//check if query failed
if (!mysql_query($sql, $con)){
	die('Error: ' . mysql_error());
}

//delete logboek entries
$sql = "DELETE FROM logboek
		WHERE gebruikersnaam = '$_GET[gb]'";
		
//check if query failed
if (!mysql_query($sql, $con)){
	die('Error: ' . mysql_error());
}

//redirect
header('Location: accounts.php?dltd=1');

mysql_close($con);
?>