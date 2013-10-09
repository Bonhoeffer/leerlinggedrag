<?php
if (isset($_GET['id']) && isset($_POST['fromWho'])){
	session_start();
	
	//gebruikersnaam TO
	$docentID = $_GET['id'];
	
	if ($_POST['fromWho'] != ""){
		//gebruikersnaam FROM
		$from = $_POST['fromWho'];
		
		//connect to db
		include('Text/connect.php');
		
		//get beoordelingen from FROM teacher
		$sql = "SELECT leerlingnummer, periode
				FROM beoordeling
				WHERE docent = '$from'";
				
		$result = mysql_query($sql, $con);
		
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_array($result)){
				//select beoordeling from TO teacher with certain student in certain periode
				$qry = "SELECT *
						FROM beoordeling
						WHERE docent = '$docentID'
							AND leerlingnummer = '$row[leerlingnummer]'
							AND periode = '$row[periode]'
						";
				
				$res = mysql_query($qry, $con);
				
				//check if beoordeling requested above exists, if not...
				if (mysql_num_rows($res) == 0){
					$res = mysql_fetch_array($res);
					
					//migrate certain beoordeling
					$sql = "UPDATE beoordeling
							SET docent = '$docentID'
							WHERE docent = '$from'
								AND leerlingnummer = '$row[leerlingnummer]'
								AND periode = '$row[periode]'
							";
					
					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
				}
			}
			
			$_SESSION['bMigrate'] = 2;
			header("Location: logon.php?id=$docentID");
		}
		else{
			$_SESSION['bMigrate'] = 1;
			header("Location: logon.php?id=$docentID");
		}
		
		//close connection
		mysql_close($con);
	}
	else{
		$_SESSION['bMigrate'] = 0;
		header("Location: logon.php?id=$docentID");
	}
}
else{
	header('Location: accounts.php');
}
?>