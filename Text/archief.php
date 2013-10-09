<?php
	//connect to db
	include ('connect.php');
	
	//notices
	if (isset($_SESSION['failedFields'])){
		echo "
			<div id='notice'>
				<br />
				<p><b>Eén of meerdere velden niet correct ingevuld.</b></p>
				<br />
			</div>
			<br />
		";
		unset($_SESSION['failedFields']);
	}
	if (isset($_SESSION['bvrwdrd'])){
		echo "
			<div id='notice'>
				<br />
				<p><b>Beoordeling succesvol verwijderd.</b></p>
				<br />
			</div>
			<br />
		";
		unset($_SESSION['bvrwdrd']);
	}
	
	echo "
		<h2>Beoordelingen archief</h2>
		
		<p>Kies een leeraar om te zien welke leerlingen deze leeraar heeft beoordeeld in de gegeven periode.</p>
		<p>Als u alle leerlingen wilt zien laat dan de 'Ouder dan' en 'Jonger dan' velden leeg.</p>
	";
	
	//get teachers
	$sql = "SELECT achternaam, gebruikersnaam
			FROM docenten
			ORDER BY achternaam";
			
	$result = mysql_query($sql, $con);
	
	//put them in a dropdown menu
	echo "
		<form method='post' action=''>
			<table style='margin: 10px 0 0 10px; width: 96%;'>			
				<tr>
					<td colspan='2'><b>Beoordelingen</b></td>
				</tr>
				<tr>
					<td>Van: </td>
					<td>
						<select name='docent' style='width: 100%;'>
					";
	
					while ($row = mysql_fetch_array($result)){
						echo "<option value='$row[gebruikersnaam]'>$row[achternaam]</option>";
					}
	
				echo "
						</select>
					</td>
				</tr>
				<tr>
					<td style='width: 50%;'>Jonger dan <sup>(maanden)</sup> : </td>
					<td style='width: 50%;'><input name='jonger' type='text' style='width: 97%;' /></td>
				</tr>
				<tr>
					<td style='width: 50%;'>Ouder dan <sup>(maanden)</sup> : </td>
					<td style='width: 50%;'><input name='ouder' type='text' style='width: 97%;' /></td>
				</tr>
				<tr>
					<td colspan='2'><input type='submit' class='button' value='Beoordelingen bekijken' style='float: right; width: 100%;' /></td>
				</tr>
			</table>			
		</form>
		<br />
	";
	
	//if teacher choosen
	if (isset($_POST['docent'])){
		$docent = $_POST['docent'];
		$jonger = $_POST['jonger'];
		$ouder = $_POST['ouder'];
		$_SESSION['aDocent'] = $_POST['docent'];
		$_SESSION['aJonger'] = $_POST['jonger'];
		$_SESSION['aOuder'] = $_POST['ouder'];
		
		//check if valid
		if ($jonger != "" && $ouder != ""){
			if ($jonger <= $ouder){
				//redirect
				$_SESSION['failedFields'] = true;
				header('Location: archief.php');
			}
		}
		
		//get teacher's last name
		$sql = "SELECT achternaam
				FROM docenten
				WHERE gebruikersnaam = '$docent'";
		
		$antwoord = mysql_fetch_array(mysql_query($sql, $con));
		echo "<h2>$antwoord[achternaam]</h2>";
		
		//get current date and treshhold
		$y = date('Y') - 2013;
		$m = date('m');
			
		$nu = ($y * 12) + $m;
		
		function periode($p){
			global $docent;
			global $jonger;
			global $ouder;
			global $nu;
			global $con;
			global $done;
			
			//only older results
			if ($jonger == "" && $ouder != ""){
				$t = $nu - $ouder;
				
				if (!$done) echo "<h2>Beoordelingen ouder dan $ouder maand(en).</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND b.drempel < $t
							AND l.leerlingnummer = b.leerlingnummer
							
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			//only younger results
			if ($ouder == "" && $jonger != ""){
				$t = $nu - $jonger;
				
				if (!$done) echo "<h2>Beoordelingen jonger dan $jonger maand(en).</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND b.drempel > $t
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			//all results
			if ($ouder == "" && $jonger == ""){
				
				if (!$done) echo "<h2>Alle beoordelingen.</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			//results within certain range
			if ($ouder != "" && $jonger != ""){
				$t = $nu - $ouder;
				$t2 = $nu - $jonger;
				
				if (!$done) echo "<h2>Beoordelingen jonger dan $jonger maand(en), ouder dan $ouder maand(en).</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = $sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND b.drempel < $t
							AND b.drempel > $t2
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			
			$result = mysql_query($sql, $con);
				
			if (mysql_num_rows($result) > 0){
				echo "
					<table style='margin: 0 0 0 10px; width: 133%;'>
						<tr>
							<th>Leerlingnummer</th>
							<th>Voornaam</th>
							<th>Achternaam</th>
							<th>Klas</th>
							<th>Datum</th>
							<th style='width: 5%;'></th>
						</tr>
				";
				
				while ($row = mysql_fetch_array($result)){
					echo "
						<tr>
							<td>$row[leerlingnummer]</td>
							<td>$row[voornaam]</td>
							<td>$row[achternaam]</td>
							<td>$row[klas]</td>
							<td>$row[datum]</td>
							<td><a href='verwijder.php?idr=$row[0]&do=$docent&ln=$row[leerlingnummer]&per=$p&page=archief'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
						</tr>
					";
				}
				
				echo "</table><br /><br />";
			}
			else{
				echo "<b><p><i>Géén</i> leerlingen beoordeeld.</p></b><br />";
			}
		}
		
		//show results
		$done = false;
		periode(1);
		$done = true;
		echo "<hr style='width: 137%;' noshade /><br />";
		periode(2);
		echo "<hr style='width: 137%;' noshade /><br />";
		periode(3);
		echo "<hr style='width: 137%;' noshade /><br />";
		periode(4);
		echo "<hr style='width: 137%;' noshade /><br />";
	}
	elseif (isset($_SESSION['aDocent']) && isset($_SESSION['aJonger']) && isset($_SESSION['aOuder'])){
		$docent = $_SESSION['aDocent'];
		$jonger = $_SESSION['aJonger'];
		$ouder = $_SESSION['aOuder'];
		
		//check if valid
		if ($jonger != "" && $ouder != ""){
			if ($jonger <= $ouder){
				//redirect
				$_SESSION['failedFields'] = true;
				header('Location: archief.php');
			}
		}
		
		//get teacher's last name
		$sql = "SELECT achternaam
				FROM docenten
				WHERE gebruikersnaam = '$docent'";
		
		$antwoord = mysql_fetch_array(mysql_query($sql, $con));
		echo "<h2>$antwoord[achternaam]</h2>";
		
		//get current date and treshhold
		$y = date('Y') - 2013;
		$m = date('m');
			
		$nu = ($y * 12) + $m;
		
		function periode($p){
			global $docent;
			global $jonger;
			global $ouder;
			global $nu;
			global $con;
			global $done;
			
			//only older results
			if ($jonger == "" && $ouder != ""){
				$t = $nu - $ouder;
				
				if (!$done) echo "<h2>Beoordelingen ouder dan $ouder maand(en).</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND b.drempel < $t
							AND l.leerlingnummer = b.leerlingnummer
							
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			//only younger results
			if ($ouder == "" && $jonger != ""){
				$t = $nu - $jonger;
				
				if (!$done) echo "<h2>Beoordelingen jonger dan $jonger maand(en).</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND b.drempel > $t
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			//all results
			if ($ouder == "" && $jonger == ""){
				
				if (!$done) echo "<h2>Alle beoordelingen.</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			//results within certain range
			if ($ouder != "" && $jonger != ""){
				$t = $nu - $ouder;
				$t2 = $nu - $jonger;
				
				if (!$done) echo "<h2>Beoordelingen jonger dan $jonger maand(en), ouder dan $ouder maand(en).</h2> <br />";
				
				if ($p == 1) echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 2) echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
				if ($p == 3) echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
				if ($p == 4) echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
				
				$sql = $sql = "SELECT *
						FROM beoordeling b, leerlingen l
						WHERE b.docent = '$docent'
							AND b.drempel < $t
							AND b.drempel > $t2
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
			}
			
			$result = mysql_query($sql, $con);
				
			if (mysql_num_rows($result) > 0){
				echo "
					<table style='margin: 0 0 0 10px; width: 133%;'>
						<tr>
							<th>Leerlingnummer</th>
							<th>Voornaam</th>
							<th>Achternaam</th>
							<th>Klas</th>
							<th>Datum</th>
							<th style='width: 5%;'></th>
						</tr>
				";
				
				while ($row = mysql_fetch_array($result)){
					echo "
						<tr>
							<td>$row[leerlingnummer]</td>
							<td>$row[voornaam]</td>
							<td>$row[achternaam]</td>
							<td>$row[klas]</td>
							<td>$row[datum]</td>
							<td><a href='verwijder.php?idr=$row[0]&do=$docent&ln=$row[leerlingnummer]&per=$p&page=archief'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
						</tr>
					";
				}
				
				echo "</table><br /><br />";
			}
			else{
				echo "<b><p><i>Géén</i> leerlingen beoordeeld.</p></b><br />";
			}
		}
		
		//show results
		$done = false;
		periode(1);
		$done = true;
		echo "<hr style='width: 137%;' noshade /><br />";
		periode(2);
		echo "<hr style='width: 137%;' noshade /><br />";
		periode(3);
		echo "<hr style='width: 137%;' noshade /><br />";
		periode(4);
		echo "<hr style='width: 137%;' noshade /><br />";
	}
?>