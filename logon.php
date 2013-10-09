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
				include ('Text/sysheader.php');
			?>	
			
			</div>

			<div id="menu">
			
			<?php
				include ('Text/sysmenu.php');
			?>
			
			</div>					
				
			<div id="content-wrap">
				
				<div id="main">				
		
				<?php
					//notice
					if (isset($_SESSION['dltd'])){
						echo "
							<div id='notice'>
								<br />
								<b><p>Beoordeling succesvol verwijderd.</p></b>
								<br />
							</div>
							<br />
						";
						unset($_SESSION['dltd']);
					}
					//password change messages
					if (isset($_SESSION['repassed'])){
						switch ($_SESSION['repassed']){
							case 0:
								echo "
									<div id='notice'>
										<br />
										<b><p>Wachtwoord succesvol gewijzigd.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 1:
								echo "
									<div id='notice'>
										<br />
										<b><p>Mislukt. Wachtwoord mag niet leeg zijn.</p></b>
										<br />
									</div>
									<br />
								";
							break;
						}
						unset($_SESSION['repassed']);
					}
					
					//beoordelingen migrate messages
					if (isset($_SESSION['bMigrate'])){
						switch ($_SESSION['bMigrate']){
							case 0:
								echo "
									<div id='notice'>
										<br />
										<b><p><i>Géén</i> gebruikersnaam ingevuld.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 1:
								echo "
									<div id='notice'>
										<br />
										<b><p>Ingevulde gebruikersnaam heeft <i>géén</i> beoordelingen ingevuld.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 2:
								echo "
									<div id='notice'>
										<br />
										<b><p>Beoordelingen succesvol gemigreerd.</p></b>
										<br />
									</div>
									<br />
								";
							break;
						}
						unset($_SESSION['bMigrate']);
					}
					
					error_reporting(0);
					
					//if teacher selected
					if (isset($_GET['id'])){
						if ($_GET['id'] != ""){
							$docentID = $_GET['id'];
							
							function getTreshDate(){
							$y = date('Y') - 2013;
							$m = date('m');
							
							$months = ($y * 12) + $m;
							$d = $months - 1;
							return $d;
							};
							$t = getTreshDate();
							
							// Beoordeelde leerlinge periode 1, jaar 1
							//connect to db
							include('Text/connect.php');
							
							//get teachers last name
							$sql = "SELECT achternaam
									FROM docenten
									WHERE gebruikersnaam = '$docentID'";
							
							$result = mysql_query($sql, $con);
							
							if (mysql_num_rows($result) > 0){
								$result = mysql_fetch_array($result);
								//achternaam
								echo "
									<h2 style='font-size: 250%;'>$result[achternaam]</h2>
								";
								
								//password reset
								echo "
									<h3 style='font-size: 140%;'>Wachtwoord wijzigen:</h3>
									<form method='post' action='repass.php?id=$docentID'>
										<table style='margin: 10px 0 0 10px; width: 96%;'>
											<tr>
												<td style='width: 50%;'>Nieuw wachtwoord: </td>
												<td style='width: 50%;'> <input type='password' name='newPass' style='width: 97%;' /> </td>
											</tr>
											<tr>
												<td colspan='2'> <input type='submit' class='button' value='Wijzig' style='width: 100%;' /> </td>
											</tr>
										</table>
									</form>
									<br />
								";
								
								//migrate beoordelingen
								echo "
									<h3 style='font-size: 140%;'>Beoordelingen migreren naar account:</h3>
									<form method='post' action='bmigrate.php?id=$docentID'>
										<table style='margin: 10px 0 0 10px; width: 96%;'>
											<tr>
												<td colspan='2'><b>Let op:</b> 1e of 2e beoordelingen uit hetzelfde leerjaar worden <i>niet</i> gemigreerd als deze al bestaan!</td>
											</tr>
											<tr>
												<td style='width: 50%;'>Van <sup>(gebruikersnaam)</sup> : </td>
												<td style='width: 50%;'> <input type='text' name='fromWho' style='width: 97%;' /> </td>
											</tr>
											<tr>
												<td colspan='2'> <input type='submit' class='button' value='Beoordelingen migreren naar $result[achternaam]' style='width: 100%;' /> </td>
											</tr>
										</table>
									</form>
									<br />
								";
								
								//leerlingen beoordeeld
								echo "
									<h3 style='font-size: 140%;'>Leerlingen beoordeeld:</h3>
								";
								
								echo "
									<br />
									<hr style='width: 94%; margin-left: auto; margin-right: auto;' noshade />
									<br />
									<h3>1e beoordeling van leerlingen in jaar 1</h3>
								";
								
								$sql3 ="SELECT *
										FROM leerlingen l, beoordeling b
										WHERE b.docent =  '$docentID'
											AND b.leerlingnummer = l.leerlingnummer
											AND b.periode = 1
											AND b.drempel > $t
										GROUP BY b.leerlingnummer
										ORDER BY l.klas, l.leerlingnummer";	
								$result3 = mysql_query($sql3) or die(mysql_error());
								
								if (mysql_num_rows($result3) > 0){
									echo "
										<table style='width: 92%; margin-left: auto; margin-right: auto;'>
											<tr>
												<th>Leerlingnummer</th>
												<th>Voornaam</th>
												<th>Achternaam</th>
												<th>Klas</th>
												<th style='width: 3%;'></th>
											</tr>
									";
									
									while($antwoord3 = mysql_fetch_array($result3)){
										echo "
											<tr>
												<td>$antwoord3[leerlingnummer]</td>
												<td>$antwoord3[voornaam]</td>
												<td>$antwoord3[achternaam]</td>
												<td>$antwoord3[klas]</td>
												<td><a href='bdelete.php?llnr=$antwoord3[leerlingnummer]&do=$docentID&per=1&page=do'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
											</tr>
										";
									}

									mysql_close($con);
									echo "</table>";
								}
								else{
									echo "<p><b>Laatste maand <i>geen</i> leerlingen beoordeeld.</b></p>";
								}
								
								// Beoordeelde leerlingen periode 2, jaar 1
								//connect to db
								include('Text/connect.php');
								
								echo "<br /><hr style='width: 94%; margin-left: auto; margin-right: auto;' noshade /><br />";
								echo "
									<h3>2e beoordeling van leerlingen in jaar 1</h3>
								";
								
								$sql3 ="SELECT *
										FROM leerlingen l, beoordeling b
										WHERE b.docent =  '$docentID'
											AND b.leerlingnummer = l.leerlingnummer
											AND b.periode = 2
											AND b.drempel > $t
										GROUP BY b.leerlingnummer
										ORDER BY l.klas, l.leerlingnummer";	
								$result3 = mysql_query($sql3) or die(mysql_error());
								
								if (mysql_num_rows($result3) > 0){
									echo "
										<table style='width: 92%;'>
											<tr>
												<th>Leerlingnummer</th>
												<th>Voornaam</th>
												<th>Achternaam</th>
												<th>Klas</th>
												<th style='width: 3%;'></th>
											</tr>
									";
									
									while($antwoord3 = mysql_fetch_array($result3)){
										echo "
											<tr>
												<td>$antwoord3[leerlingnummer]</td>
												<td>$antwoord3[voornaam]</td>
												<td>$antwoord3[achternaam]</td>
												<td>$antwoord3[klas]</td>
												<td><a href='bdelete.php?llnr=$antwoord3[leerlingnummer]&do=$docentID&per=2&page=do'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
											</tr>
										";
									}

									mysql_close($con);
									echo "</table>";
								}
								else{
									echo "<p><b>Laatste maand <i>geen</i> leerlingen beoordeeld.</b></p>";
								}
								
								// Beoordeelde leerlingen periode 1, jaar 2
								//connect to db
								include('Text/connect.php');
								
								echo "<br /><hr style='width: 94%; margin-left: auto; margin-right: auto;' noshade /><br />";
								echo "
									<h3>1e beoordeling van leerlingen in jaar 2</h3>
								";
								
								$sql3 ="SELECT *
										FROM leerlingen l, beoordeling b
										WHERE b.docent =  '$docentID'
											AND b.leerlingnummer = l.leerlingnummer
											AND b.periode = 3
											AND b.drempel > $t
										GROUP BY b.leerlingnummer
										ORDER BY l.klas, l.leerlingnummer";	
								$result3 = mysql_query($sql3) or die(mysql_error());
								
								if (mysql_num_rows($result3) > 0){
									echo "
										<table style='width: 92%;'>
											<tr>
												<th>Leerlingnummer</th>
												<th>Voornaam</th>
												<th>Achternaam</th>
												<th>Klas</th>
												<th style='width: 3%;'></th>
											</tr>
									";
									
									while($antwoord3 = mysql_fetch_array($result3)){
										echo "
											<tr>
												<td>$antwoord3[leerlingnummer]</td>
												<td>$antwoord3[voornaam]</td>
												<td>$antwoord3[achternaam]</td>
												<td>$antwoord3[klas]</td>
												<td><a href='bdelete.php?llnr=$antwoord3[leerlingnummer]&do=$docentID&per=3&page=do'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>										
											</tr>
										";
									}

									mysql_close($con);
									echo "</table>";
								}
								else{
									echo "<p><b>Laatste maand <i>geen</i> leerlingen beoordeeld.</b></p>";
								}
								
								// Beoordeelde leerlingen periode 2, jaar 2
								//connect to db
								include('Text/connect.php');
								
								echo "<br /><hr style='width: 94%; margin-left: auto; margin-right: auto;' noshade /><br />";
								echo "
									<h3>2e beoordeling van leerlingen in jaar 2</h3>
								";
								
								$sql3 ="SELECT *
										FROM leerlingen l, beoordeling b
										WHERE b.docent =  '$docentID'
											AND b.leerlingnummer = l.leerlingnummer
											AND b.periode = 4
											AND b.drempel > $t
										GROUP BY b.leerlingnummer
										ORDER BY l.klas, l.leerlingnummer";

								$result3 = mysql_query($sql3) or die(mysql_error());
								
								if (mysql_num_rows($result3) > 0){
									echo "
										<table style='width: 92%;'>
											<tr>
												<th>Leerlingnummer</th>
												<th>Voornaam</th>
												<th>Achternaam</th>
												<th>Klas</th>
												<th style='width: 3%;'></th>
											</tr>
									";
									
									while($antwoord3 = mysql_fetch_array($result3)){
										echo "
											<tr>
												<td>$antwoord3[leerlingnummer]</td>
												<td>$antwoord3[voornaam]</td>
												<td>$antwoord3[achternaam]</td>
												<td>$antwoord3[klas]</td>
												<td><a href='bdelete.php?llnr=$antwoord3[leerlingnummer]&do=$docentID&per=4&page=do'><img src='images/delete-icon.png' alt='verwijderen' /></a></td>
											</tr>
										";
									}

									mysql_close($con);
									echo "</table>";
								}
								else{
									echo "<p><b>Laatste maand <i>geen</i> leerlingen beoordeeld.</b></p>";
								}
							}
							//redirect
							else{
								header ('Location: accounts.php');
							}
						}
						//redirect
						else{
							header ('Location: accounts.php');
						}
					}
					//redirect
					else{
						header ('Location: accounts.php');
					}
					
					echo "<br /><br />";
				?>
				
				</div>
					
				<div id="sidebar">
					
				<?php
					include ('Text/syssidebar.php');
					
					//connect to db
					include('Text/connect.php');

					//get times logged in
					$sql2 = "SELECT * 
							FROM logboek
							WHERE gebruikersnaam = '$docentID'";
							
					$result2 = mysql_query($sql2) or die(mysql_error());
					$count = mysql_num_rows($result2);
					
					echo "
						<br />
						<blockquote>
							<h3>Ingelogd:</h3>
							$count keer eerder ingelogd.
							
							<br />
							<br />
						</blockquote>
					";
						
					//get times beoordeeld
					$sql3 = "SELECT *
							FROM beoordeling
							WHERE docent = '$docentID'
							GROUP BY leerlingnummer";
							
					$result3 = mysql_query($sql3, $con) or die(mysql_error());
					$count2 = mysql_num_rows($result3);
					
					echo "
						<br />
						<blockquote>
							<h3>Beoordeeld:</h3>
							$count2 leerlingen beoordeeld.
							
							<br />
							<br />
						</blockquote>
						<br />
					";
					
					mysql_close($con);
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