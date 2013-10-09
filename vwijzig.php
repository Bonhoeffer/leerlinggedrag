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
					//notices/errors
					if (isset($_SESSION['QFailed'])){
						switch ($_SESSION['QFailed']){
							case 0:
								echo "
									<div id='notice'>
										<br />
										<b><p>Toevoegen mislukt. Eén of meerdere velden niet ingevuld.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 1:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag succesvol toegevoegd.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 2:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag verwijderd.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 3:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag één plek omhoog verplaatst.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 4:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag kan <i>niet</i> één plek omhoog verplaatst worden.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 5:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag kan <i>niet</i> één plek omlaag verplaatst worden.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 6:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag één plek omlaag verplaatst.</p></b>
										<br />
									</div>
									<br />
								";
							break;
							
							case 7:
								echo "
									<div id='notice'>
										<br />
										<b><p>Vraag succesvol gewijzigd.</p></b>
										<br />
									</div>
									<br />
								";
							break;
						}
						unset($_SESSION['QFailed']);
					}
					
					include ('Text/vwijzig.php');
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