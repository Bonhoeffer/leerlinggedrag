<?php
include ('Text/loggedin.php');
?>
		<link rel="stylesheet" href="images/style.css" type="text/css" />
		<title>Basisvaardigheden</title>
	</head>
	<body>
	
		<div id="wrap">
		
			<div id="header">	
			
				<?php include ('Text/sysheader.php'); ?>
				
			</div>
			
			<div  id="menu">
			
			<?php
				include ('Text/sysmenu.php');
			?>
				
			</div>
			
			<div id="content-wrap">
			
				<div id="main">				
				
				<?php
					//error messages
					if (isset($_SESSION['insrtd'])){
						switch ($_SESSION['insrtd']){
							case 0:
								echo "
									<div id='notice'>
										<br />
										<b><p>Mislukt. Er bestaat al een account met deze gebruikersnaam.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 1:
								echo "
									<div id='notice'>
										<br />
										<b><p>Gebruikersaccount succesvol toegevoegd.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 2:
								echo "
									<div id='notice'>
										<br />
										<b><p>Mislukt. Eén of meerdere velden niet ingevuld.</p></b>
										<br />
									</div>
									<br />
								";
							break;
						}
						unset($_SESSION['insrtd']);
					}
				?>				
				
				<h2>Account toevoegen</h2>
				<p>Hier kunt u gebruikersaccounts toevoegen.</p>
				<form action="ainsert.php" method="post">
					<h3>
						<p>Achternaam: <sup>(Indien voorvoegsel, noteren als: Ham, ter)</sup></p> <input type="text" name="achternaam" />
						<p>Gebruikersnaam:</p> <input type="text" name="gebruikersnaam" />
						<p>Wachtwoord:</p> <input type="password" name="wachtwoord" />
						
						<p><input name="toevoegen" class="button" value="Toevoegen" type="submit" /></p>
					</h3>
				</form>
				</div>
					
				<div id="sidebar">
					<?php include ('Text/syssidebar.php'); ?>	
				</div>
			</div>
			
			<div id="footer">
				<?php include ('Text/footer.php'); ?>
			</div>
		</div>
	</body>
</html>