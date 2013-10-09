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
					include ('Text/insert.php');
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
