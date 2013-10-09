<?php
if (isset($_GET['id'])){
	//connect to db
	include('connect.php');
	
	//get lastname of teacher
	$sql = "SELECT achternaam
			FROM docenten
			WHERE gebruikersnaam = '$_GET[id]'";
			
	$array = mysql_fetch_array(mysql_query($sql, $con));

	echo "
		<h2>Logboek registraties van $array[achternaam]</h2>
		<br />
	";
	
	//get logboek of teacher
	$sql = "SELECT *
			FROM logboek
			WHERE gebruikersnaam = '$_GET[id]'
			ORDER BY datum DESC, tijd DESC";
			
	$result = mysql_query($sql, $con);
	
	//make a table
	$i = 1;
	echo "
		<table style='width: 92%;'>
			<tr>
				<th style='width: 21%;'>$_GET[id]</th>
				<th style='width: 37%;'>Datum</th>
				<th style='width: 37%;'>Tijd</th>
				<th style='width: 5%; padding-left: 5px; padding-right: 5px;'><a class='th' href='ldelete.php?d=$_GET[id]&all=1'><img src='images/delete-icon.png' alt='verwijderen' /></a></th>
			</tr>
	";
	while ($row = mysql_fetch_array($result)){
		echo "
			<tr>
				<td>$i.</td>
				<td>$row[datum]</td>
				<td>$row[tijd]</td>
				<td><a href='ldelete.php?d=$row[datum]&t=$row[tijd]&id=$row[gebruikersnaam]&idc=$_GET[id]'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
			</tr>
		";
	
		$i++;
	}
	echo "</table><br />";
}
else{
	//connect to db
	include('connect.php');
	
	$max = 3;
	
	echo "
		<h2>Logboek registraties</h2>
		
		<p>Hier staan alle registraties over het inloggen van een gebruikersaccount.</p>
		<p>Per gebruikersaccount staan de $max laatste inlog pogingen.
			Klik op een gebruikersnaam om alle inlog pogingen te bekijken.
			Klik op Verwijder om de registratie uit het logboek te verwijderen.</p>
		<br />
	";

	//get docenten logboek
	$sql = "SELECT *
			FROM logboek
			ORDER BY gebruikersnaam, datum DESC, tijd DESC";
			
	$result = mysql_query($sql, $con);

	if (mysql_num_rows($result) > 0){
		//make a table per teacher
		$i = 1;
		$teacher = "";
		while ($row = mysql_fetch_array($result)){
			//create next teacher table
			$docentGB = strtolower(str_replace(' ', '', $row['gebruikersnaam']));
			
			if ($docentGB != $teacher){
				if ($teacher != ""){
					echo "</table>";
				}
				
				//start counting on a new table
				$teacher = $docentGB;
				$i = 1;
				
				echo "
					<table style='width: 92%;'>
						<tr>
							<th style='width: 30%;'><a class='th' href='logboek.php?id=$docentGB'>$docentGB</a></th>
							<th style='width: 30%;'>Datum</th>
							<th style='width: 30%;'>Tijd</th>
							<th style='width: 5%;'></th>
						</tr>
				";
			}
			//count in same table
			if ($docentGB == $teacher && $i <= $max){
				echo "
					<tr>
						<td>$i.</td>
						<td>$row[datum]</td>
						<td>$row[tijd]</td>
						<td><a href='ldelete.php?d=$row[datum]&t=$row[tijd]&id=$docentGB'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
					</tr>
				";
				
				$i++;
			}
		}
		echo "</table><br /><br />";
	}
	else{
		echo "
			<div id='notice'>
				<br />
				<b><p>Geen logboek registraties.</p></b>
				<br />
			</div>
			<br /><br />
			<br /><br />
			<br /><br />
		";
	}

	mysql_close($con);
}
?>