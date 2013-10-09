<?php
//check if fields are filled in
if ($_POST['gebruikersnaam'] != "" && $_POST['wachtwoord'] != "" && $_POST['achternaam'] != ""){
	//md5 encryption
	ob_start();
	include('Text/schoolinfo/253c1ad6dd5825f1f5ba10adea7e42a7.txt');
	$salt = ob_get_clean();
	ob_start();
	include('Text/schoolinfo/cbfc3317abb7e081ab1d3c13f12df96c.txt');
	$salt2 = ob_get_clean();

	//connect to db
	include('Text/connect.php');
	
	$gb = mysql_real_escape_string(stripslashes($_POST['gebruikersnaam']));
	$achternaam = mysql_real_escape_string(stripslashes($_POST['achternaam']));
	$password = md5(md5($_POST['wachtwoord'] . $salt) . $salt2);
	
	//check if account already exists
	$qry = "SELECT *
			FROM docenten
			WHERE gebruikersnaam = '$gb'";
	
	if (mysql_num_rows(mysql_query($qry, $con)) > 0){
		$_SESSION['insrtd'] = 0;
		header('Location: atoevoegen.php');
	}
	else{
		$sql = "INSERT INTO docenten (
					gebruikersnaam,
					wachtwoord,
					achternaam
				)
				VALUES(
					'$gb',
					'$password',
					'$achternaam'
				)";

		if (!mysql_query($sql, $con)){
			die('Error: ' . mysql_error());
		}
			
		$_SESSION['insrtd'] = 1;
		header('Location: atoevoegen.php');
	}

	mysql_close($con);
}
else{
	$_SESSION['insrtd'] = 2;
	header('Location: atoevoegen.php');
}
?>