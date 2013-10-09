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
					$do = $_GET['do'];
					$per = $_GET['per'];
					$ln = $_GET['ln'];
					$page = $_GET['page'];
					
					//connect to db
					include('Text/connect.php');

					$sql = "SELECT *
							FROM `leerlingen`
							WHERE leerlingnummer = '$ln'";

					$result = mysql_fetch_array(mysql_query($sql));
					$firstName = $result['voornaam'];
					$lastName = $result['achternaam'];

					function periode ($id1){
						if ($id1 == 1) return "beoordeling 1 uit jaar 1";
						if ($id1 == 2) return "beoordeling 1 uit jaar 2";
						if ($id1 == 3) return "beoordeling 2 uit jaar 1";
						if ($id1 == 4) return "beoordeling 2 uit jaar 2";
					}
					
					echo "
						<h2>Verwijderen</h2>
						<br />
						
						<h3>Weet u zeker dat u " . periode($per) . " van</h3>
						<h2>$firstName $lastName ($ln)</h2>
						<h3>wilt verwijderen?</h3>
						<br />
						
						<hr style='width: 96%; margin-left: auto; margin-right: auto;' noshade />
						<br />
						
						<h3><a href=verwijder.php?do=$do&per=$per&ln=$ln&page=$page>Ja</a> / <a href=# onclick='history.go(-1)'>Nee (terug naar vorige pagina)</a></h3>
						<br />
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
