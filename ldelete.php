<?php
if (isset($_GET['all'])){
	if (isset($_GET['d'])){
		$d = $_GET['d'];
		
		//connect to db
		include('Text/connect.php');
		
		//delete all
		$sql = "DELETE
				FROM logboek
				WHERE gebruikersnaam = '$d'";
				
		if (!mysql_query($sql, $con)){
			die('Error: ' . mysql_error());
		}
		
		//get teacher's name
		$sql = "SELECT achternaam
				FROM docenten
				WHERE gebruikersnaam = '$d'";
				
		$result = mysql_fetch_array(mysql_query($sql, $con));
		
		header("Location: logboek.php?all=$result[achternaam]");
		
		mysql_close($con);
	}
	else{
		header('Location: logboek.php');
	}
}
else{
	if (isset($_GET['d']) && isset($_GET['t']) && isset($_GET['id'])){
		$date = $_GET['d'];
		$time = $_GET['t'];
		$id = $_GET['id'];
		
		//connect to db
		include('Text/connect.php');
		
		$sql = "DELETE
				FROM logboek
				WHERE gebruikersnaam = '$id'
					AND datum = '$date'
					AND tijd = '$time'";
		
		if (!mysql_query($sql, $con)){
			die('Error: ' . mysql_error());
		}
		
		if (isset($_GET['idc'])){
			header('Location: logboek.php?dltd=1&id=' . $_GET['idc']);
		}
		else{
			header('Location: logboek.php?dltd=1');
		}
		
		mysql_close($con);
	}
	else{
		header('Location: logboek.php');
	}
}
?>