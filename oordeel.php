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
					echo"
						<h2>Beoordelen</h2>
						
						<p>Zoek hieronder een leerling om hem/haar te beoordelen:</p>

						<form action='bezoek.php' method='post'> 
							<p><h3>Leerlingnummer:</h3></p> <input type='text' style='margin-left: 10px;' name='value' />
							<input name='zoek' class='button' value='zoek' type='submit' />
						</form>

						<br>
						<br>
						<form action='bezoek2.php' method='post'> 
							<p><h3>Achternaam:</h3></p> <input type='text' style='margin-left: 10px;' name='value' />
							<input name='zoek' class='button' value='zoek' type='submit' />
						</form>

						<br>
						<br>
						<form action='bezoek3.php' method='post'> 
							<p><h3>Klas:</h3></p> <input type='text' style='margin-left: 10px;' name='value' />
							<input name='zoek' class='button' value='zoek' type='submit' />
						</form>
						";
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