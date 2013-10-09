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
			
			<!----------------------------------- Printing or saving as .pdf ------------------------------------>
			<?php echo "<form method='post' action='pdf-result.php?id=$_GET[id]'>" ?>
				<input type="submit" name='type' class="button" value="Persoonlijk resultaat printen of opslaan als .pdf" style="margin: 0 0 0 10px;" />
				<input style='float: right; margin-right: 10px;' type="submit" name='type' class="button" value="Klassen resultaat printen of opslaan als .pdf" style="margin: 0 0 0 10px;" />
			</form>
			<!--------------------------------------------------------------------------------------------------->

			<?php include('Text/resultaten.php'); ?>
			
			<div id="footer">
			
			<?php
				include('Text/footer.php');
			?>
			
			</div>
			
		</div>
		
	</body>
</html>