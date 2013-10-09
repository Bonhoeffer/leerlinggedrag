<!DOCTYPE html>
<?php
	//log out
	session_start();
	session_destroy();
	
	unset($_SESSION['gb']);
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" /> <!-- favicon -->
		<link rel="apple-touch-icon" href="images/ipad-icon.png" /> <!-- ipad icon -->
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" href="images/style.css" type="text/css" />
		<title>Basisvaardigheden</title>
	</head>
	
	<body>

		<div id="wrap">
		
			<div id="header">			
			
				<h1 id='logo-text'><a href='index.php'>Basisvaardigheden</a></h1>		
				<p id='slogan'><?php include('Text/schoolinfo/naam.txt'); echo " "; include('Text/schoolinfo/locatienaam.txt'); ?></p>			
	
			</div>

			<div id="menu">
			
				<ul>
					<li>
						<a href="index.php">Login</a>
					</li>
					<li id="current">
						<a href="slogin.php">Systeembeheer</a>
					</li>
				</ul>
			
			</div>					
			
			<div id="content-wrap">
				
				<div id="main">				
				
				<?php
					//login failed
					if (isset($_SESSION['sLogin'])){
						switch ($_SESSION['sLogin']){
							case 0:
								echo "
									<div id='notice'>
										<br />
										<p><b>Gebruikersnaam en/of wachtwoord onjuist.</b></p>
										<br />
									</div>
								";
							break;
						}
						unset($_SESSION['sLogin']);
					}
					//logged out
					if (isset($_GET['l'])){
						echo "
							<div id='notice'>
								<br />
								<p><b>Succesvol uitgelogd.</b></p>
								<br />
							</div>
						";
					}
					
					include ('Text/syslogin.php');
				?>
				
				</div>
			
				<div id="sidebar">
				
				<blockquote> 
					<h3>Datum</h3>
					<?php 
						echo date("d-m-Y");
					?>
					<br />
					<br />
				</blockquote>
				<br />
				
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
