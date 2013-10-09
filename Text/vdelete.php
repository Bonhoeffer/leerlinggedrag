<?php
session_start();
$_SESSION['queries'] = 0;

if (isset($_GET['id'])){
	//connect to db
	include('connect.php');
	
	//delete column
	$sql = "SELECT letter, nummer
			FROM vragen
			WHERE id = $_GET[id]";
	
	if (!mysql_query($sql, $con)){
		die('Error:' . mysql_error());
	}
	$_SESSION['queries'] += 1;
	
	$result = mysql_fetch_array(mysql_query($sql, $con));
	//domain
	$dom = $result['letter'];
	//number
	$num = $result['nummer'];
	
	$sql = "ALTER TABLE beoordeling
			DROP `" . $dom . $num . "`";
			
	if (!mysql_query($sql, $con)){
		die('Error:' . mysql_error());
	}
	$_SESSION['queries'] += 1;
	
	//delete question
	$sql = "DELETE
			FROM vragen
			WHERE id = $_GET[id]";
	
	if (!mysql_query($sql, $con)){
		die('Error:' . mysql_error());
	}
	$_SESSION['queries'] += 1;
	
	//adjust order	
	$sql = "UPDATE vragen
			SET id = id - 1
			WHERE id > $_GET[id]";
	
	if (!mysql_query($sql, $con)){
		die('Error:' . mysql_error());
	}
	$_SESSION['queries'] += 1;
	
	//close connection
	mysql_close($con);
	
	//redirect
	$_SESSION['QFailed'] = 2;
	header('Location: ../vwijzig.php');
}
else{
	//redirect
	header('Location: ../vwijzig.php');
}
?>