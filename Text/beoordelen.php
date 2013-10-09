<?php
$gb = $_SESSION['gb'];
$id = $_GET['id'];
$lj = $_GET['lj'];

//connect to db
include('Text/connect.php');

//get student's data
$sql = "SELECT * 
		FROM `leerlingen`.`leerlingen`
		WHERE `leerlingnummer` = '$id'";

$result = mysql_query($sql, $con);

//if student exists
if (mysql_num_rows($result) > 0){
	$result = mysql_fetch_array($result);

	//notice
	if (isset($_SESSION['bNotice'])){
		switch ($_SESSION['bNotice']){
			case 1:
				echo "
					<div id='notice'>
						<br />
						<b><p>Beoordeling succesvol verwerkt.</p></b>
						<br />
					</div>
				";
			break;
			
			case 2:
				echo "
					<div id='notice'>
						<br />
						<b><p>Beoordeling succesvol gewijzigd.</p></b>
						<br />
					</div>
				";
			break;
		}
		$a = true;
		unset($_SESSION['bNotice']);
	}
	if (isset($_SESSION['bNotice2'])){
		if ($a) echo "<br />";
		
		switch ($_SESSION['bNotice2']){
			case 1:
				echo "
					<div id='notice'>
						<br />
						<b><p>Niet <i>alle</i> velden waren ingevuld.</p></b>
						<br />
					</div>
				";
			break;
		}
		unset($_SESSION['bNotice2']);
	}

// ----------------------------------------------------------------------------------------------------------------------------------------------------- //	
	echo "
		<h2>Leerling beoordelen</h2>
		
		<form method='post' action='nextInLine.php?id=$id' style='height: 28px;'>
			<input type='hidden' name='order' value='true' />
	";
	
	//get student's class
	$sql = "SELECT klas 
			FROM leerlingen
			WHERE leerlingnummer = '$id'";
		
	$result = mysql_fetch_array(mysql_query($sql, $con));
	$klas = $result['klas'];

	//get previous students from that class
	$sql = "SELECT leerlingnummer, leerjaar
			FROM leerlingen
			WHERE klas = '$klas'
			ORDER BY leerlingnummer DESC";
		
	$result = mysql_query($sql, $con);
	
	//previous student in line
	while ($row = mysql_fetch_array($result)){
		if ($row['leerlingnummer'] < $id){
			echo "<input type='submit' name='prev' class='button' style='margin: 0 0 0 10px;' value='Vorige leerling uit $klas' />";
			break;
		}
	}
	
	//get next students from that class
	$sql = "SELECT leerlingnummer, leerjaar
			FROM leerlingen
			WHERE klas = '$klas'
			ORDER BY leerlingnummer ASC";
		
	$result = mysql_query($sql, $con);
	
	//next student in line
	while ($row = mysql_fetch_array($result)){		
		if ($row['leerlingnummer'] > $id){
			echo "<input type='submit' name='next' class='button' style='margin: 0 10px 0 0; float: right;' value='Volgende leerling uit $klas' />";
			break;
		}
	}
	
// -------------------------------------------------------------------------------------------------------------------------------------------------------- //
	//get student's data
	$sql = "SELECT * 
			FROM `leerlingen`.`leerlingen`
			WHERE `leerlingnummer` = '$id'";

	$result = mysql_fetch_array(mysql_query($sql, $con));

// ----------------------- SELECT PERIODE ----------------------------------------------------------------------------------------------------------------- //
	echo "
		</form>
		
		<form name='beoordelen' method='post' action='beoordeling.php?lj=$lj'>
			<h4>Leerling:</h4> <h3>$result[voornaam] $result[achternaam] ($id), $result[klas]</h3>
			<input type='hidden' name='leerlingnummer' value='$id'>
			<h4>Docent:</h4> <h3>$_SESSION[lastName]</h3> <input type='hidden' name='docent' value='$gb'>

			<h4>Beoordeling:</h4> <h3>Leerjaar $lj</h3>
			<select name='periode' style='margin-left: 5px;'>
	";
	
	if (isset($_SESSION['lastPeriod'])){
		switch ($_SESSION['lastPeriod']){
			case 1:
				echo "
					<option value='1' selected='selected'>Leerjaar 1, beoordeling 1</option>
					<option value='2'>Leerjaar 1, beoordeling 2</option>
					<option value='3'>Leerjaar 2, beoordeling 1</option>
					<option value='4'>Leerjaar 2, beoordeling 2</option>
				";
			break;
			
			case 2:
				echo "
					<option value='1'>Leerjaar 1, beoordeling 1</option>
					<option value='2' selected='selected'>Leerjaar 1, beoordeling 2</option>
					<option value='3'>Leerjaar 2, beoordeling 1</option>
					<option value='4'>Leerjaar 2, beoordeling 2</option>
				";
			break;
			
			case 3:
				echo "
					<option value='1'>Leerjaar 1, beoordeling 1</option>
					<option value='2'>Leerjaar 1, beoordeling 2</option>
					<option value='3' selected='selected'>Leerjaar 2, beoordeling 1</option>
					<option value='4'>Leerjaar 2, beoordeling 2</option>
				";
			break;
			
			case 4:
				echo "
					<option value='1'>Leerjaar 1, beoordeling 1</option>
					<option value='2'>Leerjaar 1, beoordeling 2</option>
					<option value='3'>Leerjaar 2, beoordeling 1</option>
					<option value='4' selected='selected'>Leerjaar 2, beoordeling 2</option>
				";
			break;
		}
	}
	else{
		echo "
			<option value='1'>Leerjaar 1, beoordeling 1</option>
			<option value='2'>Leerjaar 1, beoordeling 2</option>
			<option value='3'>Leerjaar 2, beoordeling 1</option>
			<option value='4'>Leerjaar 2, beoordeling 2</option>
		";
	}
	
	echo "
			</select>
			<br />
			<br />
			
			<div style='font-size: 90%; padding: 10px;'><i><b>NB:</b> <br /> U hoeft niet alle velden in te vullen om een leerling te beoordelen. <br /> Om een beoordeling te wijzigen, hoeft u niet alle velden opnieuw in te vullen. Slechts de velden die u wilt wijzigen. De niet ingevulde velden blijven dan hetzelfde.</i></div>
			
			<br />
	";
	
// ----------------------- DRAW QUESTIONS ----------------------------------------------------------------------------------------------------------------- //
	//get questions
	$sql = "SELECT *
			FROM vragen
			ORDER BY id";
	
	$result = mysql_query($sql, $con);
	
	//if questions exist
	if (mysql_num_rows($result) > 0){
		$memory = "";
		//'read' questions
		while ($row = mysql_fetch_array($result)){
			//if same domain as previous 'read'
			if ($memory == $row['domein']){
				$dom = $row['letter'] . $row['nummer'];
				//if questions in domain
				if ($row['nummer'] > 0){
					//draw question
					echo "
						<p>$row[nummer]. $row[vraag]
						<br />
					";
					
					switch ($row['soort']){
						//rating
						case 0:
							echo "									
									<input type='radio' name='$dom' value='2'/> O
									<input type='radio' name='$dom' value='4'/> M 
									<input type='radio' name='$dom' value='6'/> V 
									<input type='radio' name='$dom' value='8'/> RV 
									<input type='radio' name='$dom' value='10'/> G 
								</p>
								<br />
							";
						break;
						
						//textarea
						case 1:
							echo "
								<br />
								<textarea name='$dom' type='textbox' style='margin: 0 0 0 -10px; min-width: 480px; max-width: 480px; min-height: 100px; max-height: 300px;' ></textarea>
								</p>
							";
						break;
					}					
				}
			}
			else{
				//set memory domain to current
				$memory = $row['domein'];
				$dom = $row['letter'] . $row['nummer'];
				
				//draw domain
				echo "
					<h5 class='fancy'>$row[letter]: $row[domein]</h5>
				";
				
				//draw first question
				echo "
					<p>$row[nummer]. $row[vraag]
					<br />
				";
				
				//if questions in domain
				if ($row['nummer'] > 0){
					switch ($row['soort']){
						//rating
						case 0:
							echo "
									<input type='radio' name='$dom' value='2'/> O
									<input type='radio' name='$dom' value='4'/> M 
									<input type='radio' name='$dom' value='6'/> V 
									<input type='radio' name='$dom' value='8'/> RV 
									<input type='radio' name='$dom' value='10'/> G 
								</p>
								<br />
							";
						break;
						
						//textarea
						case 1:
							echo "
								<br />
								<textarea name='$dom' type='textbox' style='margin: 0 0 0 -10px; min-width: 480px; max-width: 480px; min-height: 100px; max-height: 300px;' ></textarea>
								</p>
							";
						break;
					}					
				}
			}
		}
	}
	//no questions at all
	else{
		echo "
			<div id='notice'>
				<br />
				<b><p><i>Geen</i> vragen ingevuld door systeembeheer.</p></b>
				<br />
			</div>
		";
	}
// -------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	echo "
			<br />
			<br />

			<p><input name='verstuur' class='button' value='Verstuur' type='submit' /></p>
		</form>
	";
}
else{
	echo "
		<br />
		<div id='notice'>
			<br />
			<p><b><i>Geen</i> leerling gevonden met id: $id</b></p>
			<br />
		</div>
		<br /><br />
		<br /><br />
		<br /><br />
		<br /><br />
		<br /><br />
		<br /><br />
	";
}

mysql_close($con);
?>