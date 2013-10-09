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
					//connect to db
					include('Text/connect.php');

					$sql = "DELETE FROM leerlingen.beoordeling 
							WHERE leerlingnummer = '$_GET[llnr]' 
								AND docent = '$_GET[do]' 
								AND periode = '$_GET[per]'";

					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
					
					$_SESSION['dltd'] = 0;
					if (isset($_GET['page'])){
						header('Location: logon.php?id=' . $_GET['do']);
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



 