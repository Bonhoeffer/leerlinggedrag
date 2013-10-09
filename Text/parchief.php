<?php
	$gb = $_SESSION['gb'];
	
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
	
	echo "
		<h2>Beoordelingen archief</h2>
		
		<p>Als u alle beoordelingen wilt zien laat dan de 'Ouder dan' en 'Jonger dan' velden leeg.</p>
	";
	
	//select period menu
	echo "
		<form method='post' action=''>
			<table style='margin: 10px 0 0 10px; width: 96%;'>			
				<tr>
					<td colspan='2'><b>Beoordelingen</b></td>
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
	
	//if period submitted
	if (isset($_POST['ouder']) && isset($_POST['jonger'])){
		$jonger = $_POST['jonger'];
		$ouder = $_POST['ouder'];
		
		//check if valid
		if ($jonger != "" && $ouder != ""){
			if ($jonger <= $ouder){
				//redirect
				$_SESSION['failedFields'] = true;
				header('Location: parchief.php');
			}
		}
		
		echo "<h2>$_SESSION[lastName]</h2>";
		
		//get current date and treshhold
		$y = date('Y') - 2013;
		$m = date('m');
			
		$nu = ($y * 12) + $m;
		
		$done = false;
		function periode($p){
			global $gb;
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
						WHERE b.docent = '$gb'
							AND b.drempel < $t
							AND l.leerlingnummer = b.leerlingnummer
							
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
						
				$result = mysql_query($sql, $con);
				
				if (mysql_num_rows($result) > 0){
					echo "
						<table style='margin: 0 0 0 10px; width: 97%;'>
							<tr>
								<th>Leerlingnummer</th>
								<th>Voornaam</th>
								<th>Achternaam</th>
								<th>Klas</th>
								<th>Datum</th>
							</tr>
					";
					
					while ($row = mysql_fetch_array($result)){
						echo "
							<tr>
								<td><a href='result.php?ln=$row[leerlingnummer]&per=$p'>$row[leerlingnummer]</a></td>
								<td>$row[voornaam]</td>
								<td>$row[achternaam]</td>
								<td>$row[klas]</td>
								<td>$row[datum]</td>
							</tr>
						";
					}
					
					echo "</table><br /><br />";
				}
				else{
					echo "<b><p><i>Géén</i> leerlingen beoordeeld.</p></b><br />";
				}
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
						WHERE b.docent = '$gb'
							AND b.drempel > $t
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
						
				$result = mysql_query($sql, $con);
				
				if (mysql_num_rows($result) > 0){
					echo "
						<table style='margin: 0 0 0 10px; width: 96%;'>
							<tr>
								<th>Leerlingnummer</th>
								<th>Voornaam</th>
								<th>Achternaam</th>
								<th>Klas</th>
								<th>Datum</th>
							</tr>
					";
					
					while ($row = mysql_fetch_array($result)){
						echo "
							<tr>
								<td><a href='result.php?ln=$row[leerlingnummer]&per=$p'>$row[leerlingnummer]</a></td>
								<td>$row[voornaam]</td>
								<td>$row[achternaam]</td>
								<td>$row[klas]</td>
								<td>$row[datum]</td>
							</tr>
						";
					}
					
					echo "</table><br /><br />";
				}
				else{
					echo "<b><p><i>Géén</i> leerlingen beoordeeld.</p></b><br />";
				}
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
						WHERE b.docent = '$gb'
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
						
				$result = mysql_query($sql, $con);
				
				if (mysql_num_rows($result) > 0){
					echo "
						<table style='margin: 0 0 0 10px; width: 96%;'>
							<tr>
								<th>Leerlingnummer</th>
								<th>Voornaam</th>
								<th>Achternaam</th>
								<th>Klas</th>
								<th>Datum</th>
							</tr>
					";
					
					while ($row = mysql_fetch_array($result)){
						echo "
							<tr>
								<td><a href='result.php?ln=$row[leerlingnummer]&per=$p'>$row[leerlingnummer]</a></td>
								<td>$row[voornaam]</td>
								<td>$row[achternaam]</td>
								<td>$row[klas]</td>
								<td>$row[datum]</td>
							</tr>
						";
					}
					
					echo "</table><br /><br />";
				}
				else{
					echo "<b><p><i>Géén</i> leerlingen beoordeeld.</p></b><br />";
				}
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
						WHERE b.docent = '$gb'
							AND b.drempel < $t
							AND b.drempel > $t2
							AND l.leerlingnummer = b.leerlingnummer
							AND b.periode = $p
						ORDER BY b.datum DESC, l.klas, l.achternaam, l.leerlingnummer";
						
				$result = mysql_query($sql, $con);
				
				if (mysql_num_rows($result) > 0){
					echo "
						<table style='margin: 0 0 0 10px; width: 96%;'>
							<tr>
								<th>Leerlingnummer</th>
								<th>Voornaam</th>
								<th>Achternaam</th>
								<th>Klas</th>
								<th>Datum</th>
							</tr>
					";
					
					while ($row = mysql_fetch_array($result)){
						echo "
							<tr>
								<td><a href='result.php?ln=$row[leerlingnummer]&per=$p'>$row[leerlingnummer]</a></td>
								<td>$row[voornaam]</td>
								<td>$row[achternaam]</td>
								<td>$row[klas]</td>
								<td>$row[datum]</td>
							</tr>
						";
					}
					
					echo "</table><br /><br />";
				}
				else{
					echo "<b><p><i>Géén</i> leerlingen beoordeeld.</p></b><br />";
				}
			}
		}
		
		//show results
		periode(1);
		$done = true;
		echo "<hr style='width: 94%;' noshade /><br />";
		periode(2);
		echo "<hr style='width: 94%;' noshade /><br />";
		periode(3);
		echo "<hr style='width: 94%;' noshade /><br />";
		periode(4);
		echo "<hr style='width: 94%;' noshade /><br />";
	}
?>