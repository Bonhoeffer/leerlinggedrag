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

			<div id="menu">
			
			<?php
				include ('Text/menu.php');
			?>
			
			</div>					
				
			<div id="content-wrap">
				
				<div id="main">				

				<?php
					//connect to db
					include('Text/connect.php');
					
					if (isset($_POST['value'])){
						$_SESSION['value'] = $_POST['value'];
					}
					
					echo ("<h3>Gezocht op leerlingnummer: </h3> <h2>" . $_SESSION['value'] . "</h2><br />");

					$sql = 'SELECT * 
							FROM leerlingen
							WHERE leerlingnummer LIKE "%' . $_SESSION['value'] . '%"
							ORDER BY leerlingnummer';
					
					$_SESSION['sort'] = '';
					
					$result = mysql_query($sql) or die(mysql_error());
					if (mysql_num_rows($result) > 0){
						echo "<h3>Er zijn overeenkomstige resultaten gevonden.</h3><br />";
						
						echo "<table style='width: 92%;'>";
						echo "
							<tr>
								<th>Leerlingnummer</th>
								<th>Voornaam</th>
								<th>Achternaam</th>
								<th>Klas</th>
							</tr>
						";
						 
						while($row = mysql_fetch_array($result)){
							$llnr = $row['leerlingnummer'];
							$leerjaar = $row['leerjaar'];
							echo "<tr><td>";
							echo "<a href=beoordelen.php?id=$llnr&lj=$leerjaar>$llnr</a>";
							echo "</td><td>";
							echo $row[2];
							echo "</td><td>";
							echo $row[1];
							echo "</td><td>";
							echo $row[6];
							echo "</td></tr>";
						}
						echo "</table><br />";
					}
					else{
						echo('<h3>Er zijn helaas geen resultaten gevonden.</h3>');
						echo('<h4><p><a href="../oordeel.php">Opnieuw zoeken...</a></p></h4>');
					}

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
