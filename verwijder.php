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
					//get variables
					$do = $_GET['do'];
					$per = $_GET['per'];
					$ln = $_GET['ln'];
					
					//connect to db
					include('Text/connect.php');
					
					//delete beoordeling
					$sql = "DELETE FROM leerlingen.beoordeling 
							WHERE leerlingnummer = $ln
								AND docent = '$do' 
								AND periode = $per
							";
							
					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
					
					//return to last page
					$pagina = $_GET['page'];
					if ($pagina == "resultaten"){
						header('Location: resultaten.php?bvrwdrd=1&id=' . $ln);
					}
					elseif ($pagina == "archief"){
						$_SESSION['bvrwdrd'] = true;
						header('Location: archief.php');
					}
					else{
						header('Location: beoordeeld.php?bvrwdrd=1&id=' . $ln);
					}
					
					mysql_close($con);
				?>
				
				</div>

			</div>
			
			<div id="sidebar">
			
			<?php
				include ('Text/sidebar.php');
			?>	
			
			</div>

			<div id="footer">
			
			<?php
				include ('Text/footer.php');
			?>
			
			</div>
	
		</div>

	</body>
</html>
