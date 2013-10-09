<?php
session_start();
$_SESSION['queries'] = 0;

if (isset($_POST['domein']) && isset($_POST['nummer']) && isset($_POST['vraag']) && isset($_POST['soort']) && $_POST['domein'] != "" && $_POST['nummer'] != "" && $_POST['vraag'] != ""){
	//connect to db
	include('connect.php');
	
	//domains
	$domein = $_POST['domein'];
	//check if char is entered
	if (strlen($domein) == 1 && ord(strtoupper($domein)) > 64 && ord(strtoupper($domein)) < 91){
		//get full domain from domain array if exists
		if (array_key_exists(strtoupper($domein), $_SESSION['domainArray'])){
			$domein = $_SESSION['domainArray'][strtoupper($_POST['domein'])];
			
			//letter is letter entered
			$letter = $_POST['domein'];
		}
	}
	else{
		//get a letter assigned
		$sql = "SELECT *
				FROM vragen
				GROUP BY domein";
		
		$letter = chr(65 + mysql_num_rows(mysql_query($sql, $con)));
		$_SESSION['queries'] += 1;
	}
	
	//get amount of questions
	$sql = "SELECT *
			FROM vragen
			ORDER BY id";
	
	$result = mysql_query($sql, $con);
	$_SESSION['queries'] += 1;
	$count = mysql_num_rows($result);
	
	$nummer = $_POST['nummer'];
	//check if column already exists
	while ($row = mysql_fetch_array($result)){
		if ($row['domein'] == $domein){
			if ($nummer == $row['nummer']){
				$sql = "SELECT *
						FROM vragen
						WHERE domein = '$domein'";
			
				$nummer = mysql_num_rows(mysql_query($sql, $con)) + 1;
				$_SESSION['queries'] += 1;
				break;
			}
		}
	}
	
	//add question add the end
	$sql = "INSERT INTO vragen (
				`id`, `letter`, `domein`, `nummer`, `vraag`, `soort`
			)
			VALUES (
				$count, '$letter', '$domein', $nummer, '$_POST[vraag]', $_POST[soort]
			);";
	
	
	if (!mysql_query($sql, $con)){
		die('Error1: ' . mysql_error());
	}
	$_SESSION['queries'] += 1;
	
	//add to beoordeling table
	switch ($_POST['soort']){
		case 0:
			$sql = "ALTER TABLE beoordeling
					ADD `" . $letter . $nummer . "` int";
		break;
		
		case 1:
			$sql = "ALTER TABLE beoordeling
					ADD `" . $letter . $nummer . "` text";
		break;
	}
	
	//send
	if (!mysql_query($sql, $con)){
		die('Error2: ' . mysql_error());
	}
	$_SESSION['queries'] += 1;

// ---------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	//close connection
	mysql_close($con);
	
	//redirect
	$_SESSION['QFailed'] = 1;
	header('Location: ../vwijzig.php');
}
else{
	//redirect
	$_SESSION['QFailed'] = 0;
	header('Location: ../vwijzig.php');
}
?>