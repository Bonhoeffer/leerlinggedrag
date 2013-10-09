<blockquote> 
	<h3>Datum</h3>
	<?php 
		echo date("d-m-Y");
	?>
	<br />
	<br />
</blockquote>
<br />

<blockquote>
	<h3>Account</h3>
	<?php
		if (isset($_SESSION['lastName'])){
			echo("<h5>Ingelogd als: </h5>  <h4>" . $_SESSION['lastName'] . "</h4>");
		}
		else{
			session_destroy();
			header('Location: index.php');
		}
	?>
	<br />
</blockquote> 
