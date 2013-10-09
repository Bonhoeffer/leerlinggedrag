<?php
//connect to db
include('Text/connect.php');

echo "
	<h2>Vragen beheren</h2>
	
	<p>Op deze pagina kunt u de vragen in het beoordeling formulier beheren.</p>
	<p>Dit houdt in dat u de vragen en domeinen kunt wijzigen*, toevoegen en verwijderen*.</p>
";

//get questions	
$sql = "SELECT *
		FROM vragen
		ORDER BY id";
	
$result = mysql_query($sql, $con);

// -------- DRAW QUESTION --------------------------------------------------------------------------------------------------------------------------------- //
//kind of questions
echo "
	<h3 style='font-size: 110%;'>Type vragen:</h3>
	<p>
		<b>0</b>: Oordeel van 'Onvoldoende' tot 'Goed'<br />
		<b>1</b>: Tekstvak<br />
	</p>
	
";

if (mysql_num_rows($result) > 0){
	//draw domains
	echo "
		<h3 style='font-size: 110%;'>Domeinen:</h3>
		<p>
	";
	$_SESSION['domainArray'] = array();
	while ($row = mysql_fetch_array($result)){
		if (!array_key_exists($row['letter'], $_SESSION['domainArray'])){
			echo "<b>$row[letter]</b>: $row[domein]<br />";
			
			//save domain names in array
			$_SESSION['domainArray'][$row['letter']] = $row['domein'];
		}
	}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /
	echo "
		</p>
		<br />
		
		<p>
			<i style='font-size: 90%;'>* Als u een vraag verwijderd of een vraag, het nummer of het domein wijzigt, worden de beoordelingen bij die vraag verwijderd!</i>
		</p>
		<br />
		
		<h3 style='font-size: 140%;'>Vragen beheren</h3>
		
		<table style='width: 132%;'>
			<tr>
				<th>Volg.</th>
				<th>Domein</th>
				<th>#</th>
				<th style='width: 100%;'>Stelling / vraag</th>
				<th>Type</th>
				<th colspan='3'></th>
			</tr>
	";
	
	//get questions again
	$result = mysql_query($sql, $con);

// - - - - - - - QUESTIONS - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /
	$domein = "";
	while ($row = mysql_fetch_array($result)){		
		$domein = $row['domein'];
		
		echo "
			<form action='Text/vchange.php?id=$row[id]' method='post'>
				<tr>
					<td>" . ($row['id'] + 1) . "<sup>e</sup></td>
					<td>$row[letter]</td>
					<td>$row[nummer]</td>
					<td>$row[vraag]</td>
					<td>$row[soort]</td>
					<td><a href='Text/vmove.php?id=$row[id]&dir=up'><img src='images/up-icon.png' style='margin: 0; padding: 0;' /></a></td>
					<td><a href='Text/vmove.php?id=$row[id]&dir=down'><img src='images/down-icon.png' style='margin: 0; padding: 0;' /></a></td>
					<td><a href='Text/vdelete.php?id=$row[id]'><img src='images/delete-icon.png' style='margin: 0; padding: 0;' /></a></td>
				</tr>
				<tr>
					<td class='light'> <input style='width: 87%;' type='number' name='volg' maxlength='2' /> </td>
					<td class='light'> <input style='width: 90%;' type='text' name='domein' maxlength='1' /> </td>
					<td class='light'> <input style='width: 75%;' type='number' name='nummer' maxlength='2' /> </td>
					<td class='light'> <input style='width: 98%;' type='text' name='vraag' /> </td>
					<td class='light'> <select style='width: 100%;' name='soort'>
		";
		switch ($row['soort']){
			case 0:
				echo "
					<option selected='selected'>0</option><option>1</option>
				";
			break;
			
			case 1:
				echo "
					<option>0</option><option selected='selected'>1</option>
				";
			break;
		}
		echo "</select> </td>
					<td class='light' colspan='3'> <input type='submit' class='button' value='Wijzig' style='width: 100%;' /> </td>
				</tr>
			</form>
		";
	}
	echo "
		</table>
		<br />
	";
}
else{
	echo "
		<div id='notice'>
			<br />
			<b><p><i>Geen</i> vragen ingevuld door systeembeheer.</p></b>
			<br />
		</div>
		<br />
	";
}

// -------- ADD QUESTION ---------------------------------------------------------------------------------------------------------------------------------- //
echo "
	<h3 style='font-size: 140%;'>Vraag toevoegen</h3>
	
	<table style='width: 132%;'>
		<tr>
			<th style='width: 30%;'>Domein</th>
			<th>#</th>
			<th style='width: 70%;'>Stelling / vraag</th>
			<th>Type</th>
		</tr>
		<form action='Text/vadd.php' method='post'>
			<tr>
				<td class='light'> <input style='width: 96%;' type='text' name='domein' /> </td>
				<td class='light'> <input style='width: 75%;' type='text' name='nummer' maxlength='2' /> </td>
				<td class='light'> <input style='width: 98%;' type='text' name='vraag' /> </td>
				<td class='light'> <select style='width: 100%;' name='soort'> <option>0</option><option>1</option> </select> </td>
			</tr>
			<tr>
				<td colspan='4'> <input style='width: 100%;' type='submit' class='button' value='Toevoegen' /> </td>
			</tr>
		</form>
	</table>
";
// -------------------------------------------------------------------------------------------------------------------------------------------------------- //

mysql_close($con);
?>