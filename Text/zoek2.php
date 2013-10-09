<?php
//connect to db
include('connect.php');

echo("<h3>Gezocht op achternaam: </h3> <h2>" . $_POST['achternaam'] . "</h2><br />");

$sql = 'SELECT * 
		FROM leerlingen 
		WHERE achternaam 
		LIKE "%' . $_POST['achternaam'] . '%"
		ORDER BY achternaam, leerlingnummer';

$result = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($result) > 0){
	echo "<h3>Er zijn overeenkomstige resultaten gevonden.</h3><br />";
	
	echo "<table style='width: 92%;'>";
	echo"
		<tr>
			<th>Leerlingnummer</th>
			<th>Voornaam</th>
			<th>Achternaam</th>
			<th>Klas</th>
		</tr>
	";
	
	while($row = mysql_fetch_array($result)){
		$llnr = $row['leerlingnummer'];
		echo "<tr><td>";
		echo "<a href=resultaten.php?id=$llnr>$llnr</a>";
		echo "</td><td>";
		echo $row[2];
		echo "</td><td>";
		echo $row[1];
		echo "</td><td>";
		echo $row[6];
		echo "</td></tr>";
	}
	echo "</table><br />";
}
else{
	echo('<h3>Er zijn helaas geen resultaten gevonden.</h3>');
	echo('<h4><p><a href="../zoeken.php">Opnieuw zoeken...</a></p></h4>');
}
mysql_close($con);
?>