<?php
$id = $_GET['id'];

if (isset($_POST['order'])){
	//connect to db
	include('Text/connect.php');
	
	//get student's class
	$sql = "SELECT klas 
			FROM leerlingen
			WHERE leerlingnummer = '$id'";
			
	$result = mysql_fetch_array(mysql_query($sql, $con));
	$klas = $result['klas'];
	
	if (isset($_POST['prev'])){
		//get students from that class
		$sql = "SELECT leerlingnummer, leerjaar
				FROM leerlingen
				WHERE klas = '$klas'
				ORDER BY leerlingnummer DESC";
				
		$result = mysql_query($sql, $con);
		
		//previous student in line
		while ($row = mysql_fetch_array($result)){
			if ($row['leerlingnummer'] < $id){
				header('Location: beoordelen.php?id=' . $row['leerlingnummer'] . '&lj=' . $row['leerjaar']);
				break;
			}
		}
	}
	else{
		//get students from that class
		$sql = "SELECT leerlingnummer, leerjaar
				FROM leerlingen
				WHERE klas = '$klas'
				ORDER BY leerlingnummer ASC";
				
		$result = mysql_query($sql, $con);
		
		//previous student in line
		while ($row = mysql_fetch_array($result)){
			if ($row['leerlingnummer'] > $id){
				header('Location: beoordelen.php?id=' . $row['leerlingnummer'] . '&lj=' . $row['leerjaar']);
				break;
			}
		}
	}
	
	mysql_close($con);
}
else{
	header('Location: oordeel.php');
}
?>