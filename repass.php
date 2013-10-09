<?php
if (isset($_GET['id']) && isset($_POST['newPass'])){
	//start session to send notices
	session_start();
	
	//get gebruikersnaam
	$docentID = $_GET['id'];
	
	if ($_POST['newPass'] != ""){
		//md5 encryption
		ob_start();
		include('Text/schoolinfo/253c1ad6dd5825f1f5ba10adea7e42a7.txt');
		$salt = ob_get_clean();
		ob_start();
		include('Text/schoolinfo/cbfc3317abb7e081ab1d3c13f12df96c.txt');
		$salt2 = ob_get_clean();
		
		$newPass = md5(md5($_POST['newPass'] . $salt) . $salt2);
		
		//connect to db
		include('Text/connect.php');
		
		//change pass
		$sql = "UPDATE docenten
				SET wachtwoord = '$newPass'
				WHERE gebruikersnaam = '$docentID'";
				
		if (!mysql_query($sql, $con)){
			die('Error: ' . mysql_error());
		}
		
		//close connection
		mysql_close($con);
		
		//succes
		$_SESSION['repassed'] = 0;
		header("Location: logon.php?id=$docentID");
	}
	else{
		//empty pass entered
		$_SESSION['repassed'] = 1;
		header("Location: logon.php?id=$docentID");
	}
}
else{
	header('Location: accounts.php');
}
?>