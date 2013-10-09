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

			<div  id="menu">
			
			<?php
				include ('Text/sysmenu.php');
			?>
			
			</div>					
		
			<div id="content-wrap">
				
				<div id="main">
			
				<?php
					echo "
					<h2>Leerling toevoegen</h2>
					<p>Hier kunt u een leerling toevoegen.</p>
					";

					if (isset($_SESSION['leerlingInsertFailed'])){
						$err = $_SESSION['leerlingInsertFailed'];
						
						echo "<div id='notice'>";
						
						// Error messages
						if ($err == "invalidFields"){
							echo "<br /><p><b>Toevoegen mislukt. Eén of meerdere velden zijn niet ingevuld.</b></p><br />";
						}
						if ($err == "notNumeric"){
							echo "<br /><p><b>Toevoegen mislukt. Leerlingnummer niet correct ingevuld.</b></p><br />";
						}
						if ($err == "invalidBirtdayLength"){
							echo "<br /><p><b>Toevoegen mislukt. Geboortedatum niet correct ingevuld.</b></p><br />";
						}
						if ($err == "invalidLeerlLength"){
							echo "<br /><p><b>Toevoegen mislukt. Leerlingnummer niet correct ingevuld.</b></p><br />";
						}
						
						echo "</div>";
						unset($_SESSION['leerlingInsertFailed']);
					}
					
					echo "
					<form action='toegevoegd.php' method='post'>
						<h3>
							<p>Leerlingnummer: <sup>(alleen cijfers)</sup> </p>
								<input type='text' name='leerlingnummer' />
							<p>Voornaam: </p>
								<input type='text' name='voornaam' />
							<p>Achternaam: <sup>(Indien voorvoegsel, noteren als: Ham, ter)</sup> </p>
								<input type='text' name='achternaam' />
							<p>Geboortedatum: <sup>(dd-mm-jjjj)</sup> </p>
								<input type='text' name='geboortedatum' />
							<p>Niveau: </p>
								<input type='text' name='opleiding' />
							<p>Leerjaar: </p>
								<input type='text' name='leerjaar' />
							<p>Klas: <sup>(bv. W2K1)</sup> </p>
								<input type='text' name='klas' />
							<p><input name='toevoegen' class='button' value='Toevoegen' type='submit' /></p>
						</h3>
					</form>
					";
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
