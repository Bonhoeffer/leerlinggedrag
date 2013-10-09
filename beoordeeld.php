<?php
include ('Text/loggedin.php');
?>
		<link rel="stylesheet" href="images/style.css" type="text/css" />
		<title>Basisvaardigheden</title>
	</head>
	<body>

		<div id="wrap">
		
			<div id="header">
			
			<?php
				include ('Text/header.php');
			?>
			
			</div>
			
			<div  id="menu">
			
			<?php
				include ('Text/menu.php');
			?>
			
			</div>	
			
			<div id="content-wrap">
			
				<div id="main">
				
				<?php
					if (isset($_GET['bvrwdrd'])){
						echo "
							<div id='notice'>
								<br />
								<b><p>Beoordeling succesvol verwijderd.</p></b>
								<br />
							</div>
						";
					}
				
				//amount of months
				$max = 1;
				
				echo "
					<h2>Leerlingen beoordeeld</h2>
				
					<p>Hier ziet u welke leerlingen u deze maand beoordeeld heeft.</p>
					<p>Klik op het leerlingnummer om de beoordeling te bekijken of te verwijderen.</p>
					
					<br /><hr style='width: 94%;' noshade /><br />
				";
				
					error_reporting(0);
					$gb = $_SESSION['gb'];
					
					function getTreshDate(){
						global $max;
						
						$y = date('Y') - 2013;
						$m = date('m');
						
						$months = ($y * 12) + $m;
						$d = $months - $max;
						return $d;
					};
					$t = getTreshDate();
					
// ---- CREATE PERIODE 1 TABLE -----------------------------------------------------------------------------------------------------------------------------//
					//connect to db
					include('Text/connect.php');
					
					echo "<h3>1e beoordeling van leerlingen in jaar 1</h3>";
					
					$sql3 ="SELECT *
							FROM leerlingen l, beoordeling b
							WHERE b.docent =  '$gb'
								AND b.leerlingnummer = l.leerlingnummer
								AND b.periode = 1
								AND b.drempel > '$t'
							GROUP BY b.leerlingnummer
							ORDER BY l.klas, l.leerlingnummer";	
					$result3 = mysql_query($sql3) or die(mysql_error());
					
					if (mysql_num_rows($result3) > 0){
						echo "
							<table style='width: 97%; margin-left: 6px;'>
								<tr>
									<th>Leerlingnummer</th>
									<th>Voornaam</th>
									<th>Achternaam</th>
									<th>Klas</th>
									<th>Datum</th>
								</tr>
						";
					
						while($antwoord3 = mysql_fetch_array($result3)){
							echo "
								<tr>
									<td><a href=result.php?&ln=$antwoord3[leerlingnummer]&per=1>$antwoord3[leerlingnummer]</a></td>
									<td>$antwoord3[voornaam]</td>
									<td>$antwoord3[achternaam]</td>
									<td>$antwoord3[klas]</td>
									<td>$antwoord3[datum]</td>
								</tr>
							";
						}
						echo "</table>"; 
					}
					else{
						echo "<p><b>Deze maand <i>geen</i> leerlingen beoordeeld.</b></p>";
					}

					echo "<br /><hr style='width: 94%;' noshade /><br />";

					mysql_close($con);
					
// ---- CREATE PERIODE 2 TABLE -----------------------------------------------------------------------------------------------------------------------------//
					//connect to db
					include('Text/connect.php');
			
					echo "<h3>2e beoordeling van leerlingen in jaar 1</h3>";
					
					$sql3 ="SELECT *
							FROM leerlingen l, beoordeling b
							WHERE b.docent =  '$gb'
								AND b.leerlingnummer = l.leerlingnummer
								AND b.periode = 2
								AND drempel > '$t'
							GROUP BY b.leerlingnummer
							ORDER BY l.klas, l.leerlingnummer";	
					$result3 = mysql_query($sql3) or die(mysql_error());
					
					if (mysql_num_rows($result3) > 0){		
						echo "
							<table style='width: 97%; margin-left: 6px;'>
								<tr>
									<th>Leerlingnummer</th>
									<th>Voornaam</th>
									<th>Achternaam</th>
									<th>Klas</th>
									<th>Datum</th>
								</tr>
						";
					
						while($antwoord3 = mysql_fetch_array($result3)){
							echo "
								<tr>
									<td><a href=result.php?&ln=$antwoord3[leerlingnummer]&per=2>$antwoord3[leerlingnummer]</a></td>
									<td>$antwoord3[voornaam]</td>
									<td>$antwoord3[achternaam]</td>
									<td>$antwoord3[klas]</td>
									<td>$antwoord3[datum]</td>
								</tr>
							";
						}
						echo "</table>"; 
					}
					else{
						echo "<p><b>Deze maand <i>geen</i> leerlingen beoordeeld.</b></p>";
					}
					
					echo "<br /><hr style='width: 94%;' noshade /><br />";
					
					mysql_close($con);
					
// ---- CREATE PERIODE 3 TABLE -----------------------------------------------------------------------------------------------------------------------------//
					//connect to db
					include('Text/connect.php');
		
					echo "<h3>1e beoordeling van leerlingen in jaar 2</h3>";
					
					$sql3 ="SELECT *
							FROM leerlingen l, beoordeling b
							WHERE b.docent =  '$gb'
								AND b.leerlingnummer = l.leerlingnummer
								AND b.periode = 3
								AND drempel > '$t'
							GROUP BY b.leerlingnummer
							ORDER BY l.klas, l.leerlingnummer";	
					$result3 = mysql_query($sql3) or die(mysql_error());
					
					if (mysql_num_rows($result3) > 0){		
						echo "
							<table style='width: 97%; margin-left: 6px;'>
								<tr>
									<th>Leerlingnummer</th>
									<th>Voornaam</th>
									<th>Achternaam</th>
									<th>Klas</th>
									<th>Datum</th>
								</tr>
						";
					
						while($antwoord3 = mysql_fetch_array($result3)){
							echo "
								<tr>
									<td><a href=result.php?&ln=$antwoord3[leerlingnummer]&per=3>$antwoord3[leerlingnummer]</a></td>
									<td>$antwoord3[voornaam]</td>
									<td>$antwoord3[achternaam]</td>
									<td>$antwoord3[klas]</td>
									<td>$antwoord3[datum]</td>
								</tr>
							";
						}
						echo "</table>";  
					}
					else{
						echo "<p><b>Deze maand <i>geen</i> leerlingen beoordeeld.</b></p>";
					}

					echo "<br /><hr style='width: 94%;' noshade /><br />";

					mysql_close($con);
					
// ---- CREATE PERIODE 4 TABLE -----------------------------------------------------------------------------------------------------------------------------//
					//connect to db
					include('Text/connect.php');
		
					echo "<h3>2e beoordeling van leerlingen in jaar 2</h3>";
						
					$sql3 = "SELECT *
							FROM leerlingen l, beoordeling b
							WHERE b.docent =  '$gb'
								AND b.leerlingnummer = l.leerlingnummer
								AND b.periode = 4
								AND drempel > '$t'
							GROUP BY b.leerlingnummer
							ORDER BY l.klas, l.leerlingnummer";	
					$result3 = mysql_query($sql3) or die(mysql_error());
					
					if (mysql_num_rows($result3) > 0){		
						echo "
							<table style='width: 97%; margin-left: 6px;'>
								<tr>
									<th>Leerlingnummer</th>
									<th>Voornaam</th>
									<th>Achternaam</th>
									<th>Klas</th>
									<th>Datum</th>
								</tr>
						";
					
						while($antwoord3 = mysql_fetch_array($result3)){
							echo "
								<tr>
									<td><a href=result.php?&ln=$antwoord3[leerlingnummer]&per=4>$antwoord3[leerlingnummer]</a></td>
									<td>$antwoord3[voornaam]</td>
									<td>$antwoord3[achternaam]</td>
									<td>$antwoord3[klas]</td>
									<td>$antwoord3[datum]</td>
								</tr>
							";
						}
						echo "</table>";  
					}
					else{
						echo "<p><b>Deze maand <i>geen</i> leerlingen beoordeeld.</b></p>";
					}
						
					echo "<br />";
					
					mysql_close($con);		
				?>

				</div>
				
				<div id="sidebar">
					
				<?php
					include ('Text/sidebar.php');
				?>	
				
				</div>

			</div>

			<div id="footer">
			
			<?php
				include ('Text/footer.php');
			?>
			
			</div>	

		</div>

	</body>
</html>
