<?php
$gb = $_SESSION['gb'];

echo "<h2>Resultaten zoeken</h2>";
echo "<p>Hier kunt u een leerling zoeken om zijn/haar resultaten te bekijken:</p>";

// leerlingnummer
echo "
	<form action='zoek.php' method='post'> 
		<p><h3>Leerlingnummer:</h3></p> <input type='text' style='margin-left: 10px;' name='leerlingnummer' />
		<input type='hidden' name='gb' value='$gb' />
		<input name='zoek' class='button' value='zoek' type='submit' />
	</form>
";

// achternaam
echo "
	<br />
	<br />
	<form action='zoek2.php' method='post'> 
		<p><h3>Achternaam:</h3></p> <input type='text' style='margin-left: 10px;' name='achternaam' />
		<input type='hidden' name='gb' value='$gb' />
		<input name='zoek' class='button' value='zoek' type='submit' />
	</form>
";

//klas
echo "
	<br />
	<br />
	<form action='zoek3.php' method='post'> 
		<p><h3>Klas:</h3></p> <input type='text' style='margin-left: 10px;' name='klas' />
		<input type='hidden' name='gb' value='$gb' />
		<input name='zoek' class='button' value='zoek' type='submit' />
	</form>
";
?>