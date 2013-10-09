<?php include ('Text/loggedin.php'); ?>
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
		
			<div  id="menu">
				
			<?php
				include ('Text/sysmenu.php');
			?>	
			
			</div>					
			
			<div id="content-wrap">
			
				<div id="main">				
			
				<?php
					//notice
					if (isset($_GET['dltd'])){
						echo "
							<div id='notice'>
								<br />
								<b><p>Gebruikersaccount succesvol verwijderd.</p></b>
								<br />
							</div>
							<br />
						";
					}
					
					//connect to db
					include('Text/connect.php');
					
					//select all teachers from fb
					$sql = 'SELECT * 
							FROM docenten
							ORDER BY achternaam';

					$result = mysql_query($sql) or die(mysql_error());
					
					//if accounts found
					if (mysql_num_rows($result) > 0){
						echo "
							<h2>Accounts</h2>
							
							<p>Hier kunt u geregistreerde gebruikersaccounts en hun beoordelingen bekijken en verwijderen.</p>
							<p>Klik op de gebruikersnaam om zijn/haar beoordelingen te bekijken of te verwijderen.</p>
						";
						
						echo "
							<br />
							<table style='table-layout: fixed; width: 92%'>
								<tr>
									<th style='width: 43%;'>Gebruikersnaam</th>
									<th style='width: 43%;'>Achternaam</th>
									<th style='width: 3%;'></th>
								</tr>
						";
						
						//create table
						while($row = mysql_fetch_array($result)){
							echo "
								<tr>
									<td style='word-wrap: break-word; vertical-align: top;'>
										<a href=logon.php?id=$row[gebruikersnaam]>$row[gebruikersnaam]</a>
									</td>
									<td style='word-wrap: break-word; vertical-align: top;'>
										$row[achternaam]
									</td>
									<td>
										<a href=adelete.php?gb=$row[gebruikersnaam]><img src='images/delete-icon.png' alt='verwijderen' /></a>
									</td>
								</tr>
							";
						}
						echo("</table><br /><br />");
					}
					//if no accounts found, say so
					else{
						echo "
							<div id='notice'>
								<br />
								<b><p>Er zijn nog <i>geen</i> gebruikersaccounts.</p></b>
								<br />
							</div>
							
							<br /><br />							
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
				
				</div>
				
				<div id="sidebar">
					
				<?php
					include ('Text/syssidebar.php');
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
