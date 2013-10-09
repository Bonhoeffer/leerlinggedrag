<?php 
//connect to db
include('connect.php');

if (isset($_POST['leerlingnummer']) && isset($_POST['achternaam']) && isset($_POST['voornaam']) && 
	isset($_POST['geboortedatum']) && isset($_POST['opleiding']) && isset($_POST['leerjaar']) && isset($_POST['klas'])){
	//controle
	$leerlingnummer = $_POST['leerlingnummer'];
	$achternaam = $_POST['achternaam'];
	$voornaam = $_POST['voornaam'];
	$geboortedatum = $_POST['geboortedatum'];
	$opleiding = $_POST['opleiding'];
	$leerjaar = $_POST['leerjaar'];
	$klas = $_POST['klas'];

	if (is_numeric($leerlingnummer)){
		if (strlen($geboortedatum) == 10){
			if (strlen($leerlingnummer) == 6){
				$sql = "INSERT INTO  `leerlingen`.`leerlingen` (
					`leerlingnummer` ,
					`achternaam` ,
					`voornaam` ,
					`geboortedatum` ,
					`opleiding` ,
					`leerjaar` ,
					`klas`
				)
				VALUES(
					'$leerlingnummer', 
					'$achternaam', 
					'$voornaam', 
					'$geboortedatum', 
					'$opleiding', 
					'$leerjaar', 
					'$klas'
				)";

				if (!mysql_query($sql, $con)){
					if (mysql_error() == "Duplicate entry '" . $leerlingnummer . "' for key 'PRIMARY'"){
						// Leerlingnummer already known to database, update student data
						
						$sql = "UPDATE  `leerlingen`.`leerlingen`
								SET `achternaam` = '$achternaam',
									`voornaam` = '$voornaam',
									`geboortedatum` = '$geboortedatum',
									`opleiding` = '$opleiding',
									`leerjaar` = '$leerjaar',
									`klas` = '$klas'
								WHERE leerlingnummer = '$leerlingnummer'";
									
						if (!mysql_query($sql, $con)){
							die('Error:' . mysql_error());
						}
						
						echo "
							<div id='notice'>
								<br />
								<b><p>Leerlingnummer al bekend. Leerling gegevens succesvol gewijzigd.</p></b>
								<br />
							</div>
							<br />
						";
						
						echo "
							<table style='width: 92%;'>
								<tr>
									<th colspan='2'></th>
								</tr>
								<tr>
									<td>Leerlingnummer: </td>
									<td><b>" . $leerlingnummer . "</b></td>
								</tr>
								<tr>
									<td>Naam: </td>
									<td><b>" . $voornaam . " " . $achternaam . "</b></td>
								</tr>
								<tr>
									<td>Klas: </td>
									<td><b>" . $klas . "</b></td>
								</tr>
								<tr>
									<td>Opleiding: </td>
									<td><b>" . $opleiding . "</b></td>
								</tr>
								<tr>
									<td>Leerjaar: </td>
									<td><b>" . $leerjaar . "</b></td>
								</tr>
								<tr>
									<td>Geboortedatum: </td>
									<td><b>" . $geboortedatum . "</b></td>
								</tr>
							</table>
						";
						echo "<br /><br /><h4><p><a href=toevoegen.php>Klik om terug te gaan.</a></p></h4><br />";
					}
				}
				else{
					echo "
							<div id='notice'>
								<br />
								<b><p>Leerling succesvol toegevoegd.</p></b>
								<br />
							</div>
							<br />
						";
						
						echo "
							<table style='width: 92%;'>
								<tr>
									<th colspan='2'></th>
								</tr>
								<tr>
									<td>Leerlingnummer: </td>
									<td><b>" . $leerlingnummer . "</b></td>
								</tr>
								<tr>
									<td>Naam: </td>
									<td><b>" . $voornaam . " " . $achternaam . "</b></td>
								</tr>
								<tr>
									<td>Klas: </td>
									<td><b>" . $klas . "</b></td>
								</tr>
								<tr>
									<td>Opleiding: </td>
									<td><b>" . $opleiding . "</b></td>
								</tr>
								<tr>
									<td>Leerjaar: </td>
									<td><b>" . $leerjaar . "</b></td>
								</tr>
								<tr>
									<td>Geboortedatum: </td>
									<td><b>" . $geboortedatum . "</b></td>
								</tr>
							</table>
						";
						echo "<br /><br /><h4><p><a href=toevoegen.php>Klik om terug te gaan.</a></p></h4><br />";
				}
			}
			else{
				// Invalid leerlingnummer length
				$_SESSION['leerlingInsertFailed'] = "invalidLeerlLength";
				header('Location: toevoegen.php');
			}
		}
		else{
			// Invalid birthday length
			$_SESSION['leerlingInsertFailed'] = "invalidBirtdayLength";
			header('Location: toevoegen.php');
		}
	}
	else{
		// Not numeric
		$_SESSION['leerlingInsertFailed'] = "notNumeric";
		header('Location: toevoegen.php');
	}
}
else{
	// One or more field not set
	$_SESSION['leerlingInsertFailed'] = "invalidFields";
	header('Location: toevoegen.php');
}

mysql_close($con);
?>