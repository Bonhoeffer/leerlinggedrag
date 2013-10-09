<?php
//functions
function gemiddelde($getal){
	if ($getal == 0) return "-";
	if ($getal > 0 && $getal <= 3) return "Onvoldoende";
	if ($getal > 3 && $getal <= 5) return "Matig";
	if ($getal > 5 && $getal <= 7) return "Voldoende";
	if ($getal > 7 && $getal <= 9) return "Ruim voldoende";
	if ($getal > 9 && $getal <= 10) return "Goed";
}

function tekst($getal){
	if ($getal == false) return "-";
	if ($getal == 2) return "Onvoldoende";
	if ($getal == 4) return "Matig";
	if ($getal == 6) return "Voldoende";
	if ($getal == 8) return "Ruim voldoende";
	if ($getal == 10) return "Goed";
}
	
//if a student is choosen
if (isset($_GET['id'])){
	$id = $_GET['id'];
	//notice
	if (isset($_GET['bvrwdrd'])){
		echo "
			<br />
			<div id='notice'>
				<br />
				<b><p>Beoordeling succesvol verwijderd.</p></b>
				<br />
			</div>
			<br />
		";
	}
	
	//connect to db
	include('connect.php');

	//Gegevens leerling opvragen
	$sql = "SELECT voornaam, achternaam, klas 
			FROM leerlingen 
			WHERE leerlingnummer = '$_GET[id]'";
			
	$antwoord = mysql_fetch_array(mysql_query($sql));
	
// ----------------------------------------------------------------------------------------------------------------------------------------------------- //	
	//who?
	echo "
		<h1>Beoordeling basisvaardigeden van: </h1>
		<h2>" . $antwoord['voornaam'] . " " . $antwoord['achternaam'] . " (" . $id . "), " . $antwoord['klas'] . "</h2>
		<br />
		
		<form method='post' action='nextInLine2.php?id=$id' style='height: 28px;'>
			<input type='hidden' name='order' value='true' />
	";

	//get previous students from that class
	$sql = "SELECT leerlingnummer, leerjaar
			FROM leerlingen
			WHERE klas = '$antwoord[klas]'
			ORDER BY leerlingnummer DESC";
		
	$result = mysql_query($sql, $con);
	
	while ($row = mysql_fetch_array($result)){
		if ($row['leerlingnummer'] < $id){
			echo "
				<input type='submit' name='prev' class='button' style='margin: 0 0 0 10px;' value='Vorige leerling uit $antwoord[klas]' />
			";
			break;
		}
	}
	
	//get next students from that class
	$sql = "SELECT leerlingnummer, leerjaar
			FROM leerlingen
			WHERE klas = '$antwoord[klas]'
			ORDER BY leerlingnummer ASC";
		
	$result = mysql_query($sql, $con);
	
	while ($row = mysql_fetch_array($result)){		
		if ($row['leerlingnummer'] > $id){
			echo "
				<input type='submit' name='next' class='button' style='margin: 0 10px 0 0; float: right;' value='Volgende leerling uit $antwoord[klas]' />
			";
			break;
		}
	}
	
	echo "
		</form>
	";
	
// --- GET AVERAGE RESULTS ARRAYS PER PERIOD ---------------------------------------------------------------------------------------------------------------- //
	
	//get questions from db
	$qry = "SELECT *
			FROM vragen
			ORDER BY id";
	
	$result = mysql_query($qry, $con);
	
	//if questions exist
	if (mysql_num_rows($result) > 0){
		$GO = true;
		
		//make 4 arrays
		for ($i = 0; $i < 4; $i++){
			//start query
			$sql = "SELECT ";
			$memory = "";
			
			//get questions from db
			$qry = "SELECT *
					FROM vragen
					ORDER BY id";

			$result = mysql_query($qry, $con);
			
			//'read' questions
			while ($row = mysql_fetch_array($result)){
				//if questions in domain
				if ($row['nummer'] != 0){
					if ($row['soort'] == 0){
						$name = $row['letter'] . $row['nummer'];
						
						//add to query
						$sql .= "avg($name), ";
					}				
				}
			}
			//get rid of last comma
			$sql = substr($sql, 0, strlen($sql) - 2);
			$sql .= "FROM beoordeling 
					WHERE periode = " . ($i + 1) . " 
					AND leerlingnummer = $id 
					GROUP BY leerlingnummer";
			
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			
			$periode[$i + 1] = mysql_fetch_array(mysql_query($sql, $con));
		}
	}
	//if no questions exist
	else{
		echo "
			<div id='notice'>
				<br />
				<b><p><i>Geen</i> vragen ingevuld door systeembeheer.</p></b>
				<br />
			</div>
		";
	}
	
//--------- SHOW AVERAGE RESULTS -------------------------------------------------------------------------------------------------------------------------- //
	
	//if questions exist
	if ($GO){
		//start table
		echo "
			<table>
				<tr>
					<th></th>
					<th colspan='2'>Jaar 1</th>
					<th colspan='2'>Jaar 2</th>
				</tr>
				<tr>
					<th>Vraag</th>
					<th style='width: 70px;'>b 1</th>
					<th style='width: 70px;'>b 2</th>
					<th style='width: 70px;'>b 1</th>
					<th style='width: 70px;'>b 2</th>
				</tr>
		";
		
		$memory = "";
			
		//get questions from db
		$qry = "SELECT *
				FROM vragen
				ORDER BY id";
		
		$result = mysql_query($qry, $con);
			
		//'read' questions
		while ($row = mysql_fetch_array($result)){
			//if same domain as previous 'read'
			if ($memory == $row['domein']){
				//if questions in domain
				if ($row['nummer'] != 0){
					if ($row['soort'] == 0){
						$name = $row['letter'] . $row['nummer'];
						echo "
							<tr>
								<td><h6>$row[vraag]</h6></td>
								<td>" . gemiddelde($periode[1]["avg($name)"]) . "</td>
								<td>" . gemiddelde($periode[2]["avg($name)"]) . "</td>
								<td>" . gemiddelde($periode[3]["avg($name)"]) . "</td>
								<td>" . gemiddelde($periode[4]["avg($name)"]) . "</td>
							</tr>
						";
					}				
				}
			}
			else{
				//set memory domain to current
				$memory = $row['domein'];
				
				//if questions in domain
				if ($row['nummer'] != 0){
					if ($row['soort'] == 0){
						$name = $row['letter'] . $row['nummer'];
						echo "
							<tr>
								<th class='light' colspan='5'><b style='font-size: 15px;'>$memory</b></th>
							</tr>
							<tr>
								<td><h6>$row[vraag]</h6></td>
								<td>" . gemiddelde($periode[1]["avg($name)"]) . "</td>
								<td>" . gemiddelde($periode[2]["avg($name)"]) . "</td>
								<td>" . gemiddelde($periode[3]["avg($name)"]) . "</td>
								<td>" . gemiddelde($periode[4]["avg($name)"]) . "</td>
							</tr>
						";
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
	
// --------- SHOW PERIOD RESULTS ---------------------------------------------------------------------------------------------------------------------------- //
	$gb = $_SESSION['gb'];
	
	//get beoordelingen of student ordered by period
	$sql2 = "SELECT * 
			FROM beoordeling
			WHERE leerlingnummer = $_GET[id]
			ORDER BY periode";
	
	if (!mysql_query($sql2, $con)){
		die('Error: ' . mysql_error());
	}
	
	$antw = mysql_query($sql2, $con);
	
	while($row = mysql_fetch_array($antw)){
		echo "<table>";
		
		//allowed to delete beoordeling: teacher who wrote beoordeling, user: pater, allacces account
		if ($gb == $row[2] || $gb == "pater" || $gb == "allacces"){
			echo "
				<tr>
					<th colspan='2'><h4 style='font-size: 15px; margin: 0;'><a href=verwijderen.php?idr=$row[0]&do=$row[2]&per=$row[3]&ln=$row[1]&page=resultaten><img src='images/delete-icon.png' alt='verwijderen' /></a></h4></th>
				</tr>
				<tr>
			";
		}
		
		//get teacher's lastname
		$sql = "SELECT achternaam
				FROM docenten
				WHERE gebruikersnaam = '$row[2]'";
				
		$array = mysql_fetch_array(mysql_query($sql, $con));
			
		switch ($row[3]){
			case 1:
				echo "<th style='width: 70%;'>Beoordeling 1 in jaar 1 door ";
				if ($array['achternaam'] != null){
					echo "$array[achternaam]";
				}
				else{
					echo $row[2] . "<sup>(verwijderd account)</sup>";
				}
			break;
			
			case 2:
				echo "<th style='width: 70%;'>Beoordeling 2 in jaar 1 door ";
				if ($array['achternaam'] != null){
					echo "$array[achternaam]";
				}
				else{
					echo $row[2] . "<sup>(verwijderd account)</sup>";
				}
			break;
			
			case 3:
				echo "<th style='width: 70%;'>Beoordeling 1 in jaar 2 door ";
				if ($array['achternaam'] != null){
					echo "$array[achternaam]";
				}
				else{
					echo $row[2] . "<sup>(verwijderd account)</sup>";
				}
			break;
			
			case 4:
				echo "<th style='width: 70%;'>Beoordeling 2 in jaar 2 door ";
				if ($array['achternaam'] != null){
					echo "$array[achternaam]";
				}
				else{
					echo $row[2] . "<sup>(verwijderd account)</sup>";
				}
			break;
		}
		echo "
				</th>
				<th style='width: 30%;'>Score</th>
			</tr>
		";
		
		//get questions from db
		$qry = "SELECT *
				FROM vragen
				ORDER BY id";
		
		$result = mysql_query($qry, $con);
		
		//'read' questions
		$domein = "";
		while ($anw = mysql_fetch_array($result)){
			//if same domain as previous 'read'
			if ($domein == $anw['domein']){
				//if questions in domain
				if ($anw['nummer'] != 0){
					switch ($anw['soort']){
						case 0:
							echo "
								<tr>
									<td><h6>$anw[vraag]</h6></td>
									<td>" . tekst($row[$anw['letter'] . $anw['nummer']]) . "</td>
								</tr>
							";
						break;
						
						case 1:
							echo "
								<tr>
									<td><h6>$anw[vraag]</h6></td>
									<td>" . $row[$anw['letter'] . $anw['nummer']] . "</td>
								</tr>
							";
						break;
					}			
				}
			}
			else{
				//set memory domain to current
				$domein = $anw['domein'];
				
				//if questions in domain
				if ($anw['nummer'] != 0){
					echo "
						<tr>
							<th class='light' colspan='2'><b style='font-size: 15px;'>$anw[domein]</b></th>
						</tr>
					";
					
					switch ($anw['soort']){
						case 0:
							echo "
								<tr>
									<td><h6>$anw[vraag]</h6></td>
									<td>" . tekst($row[$anw['letter'] . $anw['nummer']]) . "</td>
								</tr>
							";
						break;
						
						case 1:
							echo "
								<tr>
									<td><h6>$anw[vraag]</h6></td>
									<td>" . $row[$anw['letter'] . $anw['nummer']] . "</td>
								</tr>
							";
						break;
					}
				}
			}
		}
		echo "
			</table>
			<br />
			<br />
		";
	}
// -------------------------------------------------------------------------------------------------------------------------------------------------------- //
	mysql_close($con);
}
else{
	header('Location: zoeken.php');
}
?>