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
					include ('Text/beoordelen.php');
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
