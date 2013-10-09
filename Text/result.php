<?php
function tekst($getal){
	if ($getal == 0) return "-";
	if ($getal == 2) return "Onvoldoende";
	if ($getal == 4) return "Matig";
	if ($getal == 6) return "Voldoende";
	if ($getal == 8) return "Ruim voldoende";
	if ($getal == 10) return "Goed";
};

$gb = $_SESSION['gb'];
$ln = $_GET['ln'];
$per = $_GET['per'];
	
//connect to db
include('Text/connect.php');

//get beoordeling
$sql = "SELECT * 
		FROM beoordeling
		WHERE leerlingnummer = $ln
		AND periode = $per 
			AND docent = '$gb'";
	
//get student's data
$sql2 = "SELECT * 
		FROM `leerlingen`.`leerlingen`
			WHERE `leerlingnummer` = $ln";
		
$studentData = mysql_fetch_array(mysql_query($sql2));
	
//draw table
$result = mysql_query($sql) or die(mysql_error());
while ($row = mysql_fetch_array($result)){
	//start table
	echo "
		<br />
		<h1>Door u ingevulde beoordeling van:</h1>
		<h2>$studentData[voornaam] $studentData[achternaam] ($studentData[leerlingnummer])</h2>
		<br />
		
		<table>
			<tr>
				<th colspan='2'><a class='th' href='verwijderen.php?do=$row[2]&per=$row[3]&ln=$row[1]&page=enkel'><img src='images/delete-icon.png' alt='verwijderen' /></a></th>
			</tr>
			<tr>
	";
	
	//which beoordeling?
	if ($row[3] > 2){
		echo "
			<th style='width: 70%;'>Beoordeling " . ($row[3] - 2) . ", jaar 2</th>
		";
	}
	else{
		echo "
			<th style='width: 70%;'>Beoordeling " . $row[3] . ", jaar 2</th>
		";
	}
	
	echo "
			<th style='width: 30%;'>Score</th>
		</tr>
	";
	
	//questions
	$sql = "SELECT *
			FROM vragen
			ORDER BY id";
	
	$result2 = mysql_query($sql) or die(mysql_error());
	$memory = "";
	while ($anw = mysql_fetch_array($result2)){
		if ($memory == $anw['domein']){
			//if questions in domain
			if ($anw['nummer'] > 0){
				switch ($anw['soort']){
					case 0:
						echo "
							<tr>
								<td>$anw[vraag]</td>
								<td>" . tekst($row[$anw['letter'] . $anw['nummer']]) . "</td>
							</tr>
						";
					break;
					
					case 1:
						echo "
							<tr>
								<td>$anw[vraag]</td>
								<td>" . $row[$anw['letter'] . $anw['nummer']] . "</td>
							</tr>
						";
					break;
				}
			}
		}
		else{
			//save last domain in memory
			$memory = $anw['domein'];
			
			//if questions in domain
			if ($anw['nummer'] > 0){
				echo "
					<tr>
						<th class='light' colspan='2'><b style='font-size: 15px;'>$anw[domein]</b></th>
					</tr>
				";
				switch ($anw['soort']){
					case 0:
						echo "
							<tr>
								<td>$anw[vraag]</td>
								<td>" . tekst($row[$anw['letter'] . $anw['nummer']]) . "</td>
							</tr>
						";
					break;
					
					case 1:
						echo "
							<tr>
								<td>$anw[vraag]</td>
								<td>" . $row[$anw['letter'] . $anw['nummer']] . "</td>
							</tr>
						";
					break;
				}
			}
		}
	}
	
	//end table
	echo "
		</table>
		<br />
	";
}

//close connection
mysql_close($con);
?>