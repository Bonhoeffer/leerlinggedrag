<?php
function curPageName(){
	return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

// ------- MENU --------------------------------------------------------------------------------------------------------------------------------------------//
echo "<ul>";

//BEOORDELEN
if (isset($_GET['id']) && curPageName() == "resultaten.php"){
	$id = $_GET['id'];
	
	//connect to db
	include('connect.php');
	
	$sql = 'SELECT leerjaar
			FROM leerlingen
			WHERE leerlingnummer = "' . $id . '"';

	$row = mysql_fetch_array(mysql_query($sql));
	$lj = $row['leerjaar'];
	
	echo "
		<li><a href=beoordelen.php?id=" . $id . "&lj=" . $lj . ">Beoordelen</a></li>
	";
}
if (isset($_GET['ln']) && curPageName() == "result.php"){
	$id = $_GET['ln'];
	
	//connect to db
	include('connect.php');
	
	$sql = 'SELECT leerjaar
			FROM leerlingen
			WHERE leerlingnummer = "' . $id . '"';

	$row = mysql_fetch_array(mysql_query($sql));
	$lj = $row['leerjaar'];
	
	echo "
		<li><a href=beoordelen.php?id=" . $id . "&lj=" . $lj . ">Beoordelen</a></li>
	";
}
if (!isset($_SESSION['sort']) && curPageName() == "beoordelen.php"){
	echo "
		<li><a href=oordeel.php>Beoordelen</a></li>
	";
}
if (isset($_SESSION['sort']) && curPageName() == "beoordelen.php"){
	echo "
		<li><a href=bezoek" . $_SESSION['sort'] . ".php>Beoordelen</a></li>
	";
	unset($_SESSION['sort']);
}
if (curPageName() != "resultaten.php" && curPageName() != "result.php" && curPageName() != "beoordelen.php"){
	echo "
		<li><a href=oordeel.php>Beoordelen</a></li>
	";
}


//RESULTATEN
if (isset($_GET['id']) && curPageName() == "beoordelen.php"){
	$id = $_GET['id'];
	echo "
		<li><a href=resultaten.php?id=$id>Resultaten</a></li>
		";
}
if (isset($_GET['ln']) && curPageName() == "result.php"){
	$id = $_GET['ln'];
	echo "
		<li><a href=resultaten.php?id=$id>Resultaten</a></li>
		";
}
if (curPageName() != "result.php" && curPageName() != "beoordelen.php"){
	echo "
		<li><a href=zoeken.php>Resultaten</a></li>
	";
}


//BEOORDEELD
echo "
	<li>
		<a href=beoordeeld.php>Leerlingen beoordeeld</a>
	</li>				
	";

echo "</ul>";
?>
