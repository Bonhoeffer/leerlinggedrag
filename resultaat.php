<?php include ('Text/loggedin.php'); ?>
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
				
				<h2> Resultaten </h2>
				<p> Hier kan je zoeken naar resultaten </p>
				<form action="zoek.php" method="post">
					<p><h3>Leerlingnummer</h3></p>
					<input type="text" name="leerlingnummer">
					<input name="zoek" class="button" value="zoek" type="submit" />
				</form>
				<br />
				<br />
				
				<form action="zoek2.php" method="post"> 
					<p><h3>Of Achternaam</h3></p>
					<input type="text" name="achternaam">
					<input name="zoek" class="button" value="zoek" type="submit" />
				</form>
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
