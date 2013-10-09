<?php
session_start();
$_SESSION['queries'] = 0;

if (isset($_GET['dir']) && isset($_GET['id'])){
	//connect to db
	include('connect.php');
	
	$id = $_GET['id'];
	//go up
	if ($_GET['dir'] == "up"){
		//check if id can go up
		if ($id > 0){
			//store upper id in memory
			$sql = "UPDATE vragen
					SET id = -1
					WHERE id = $id - 1";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//get id 1 up
			$sql = "UPDATE vragen
					SET id = id - 1
					WHERE id = $id";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//restore id in memory
			$sql = "UPDATE vragen
					SET id = $id
					WHERE id = -1";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//redirect
			$_SESSION['QFailed'] = 3;
			header('Location: ../vwijzig.php');
		}
		else{
			//redirect
			$_SESSION['QFailed'] = 4;
			header('Location: ../vwijzig.php');
		}
	}
	//go down
	else{
		//check if id can go down
		$sql = "SELECT *
				FROM vragen";
		
		if (!mysql_query($sql, $con)){
			die('Error: ' . mysql_error());
		}
		$result = mysql_query($sql, $con);
		$_SESSION['queries'] += 1;
		
		if (mysql_num_rows($result) > $id + 1){
			//store lower id in memory
			$sql = "UPDATE vragen
					SET id = -1
					WHERE id = $id + 1";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//get id 1 down
			$sql = "UPDATE vragen
					SET id = id + 1
					WHERE id = $id";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//restore id in memory
			$sql = "UPDATE vragen
					SET id = $id
					WHERE id = -1";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//redirect
			$_SESSION['QFailed'] = 6;
			header('Location: ../vwijzig.php');
		}
		else{
			//redirect
			$_SESSION['QFailed'] = 5;
			header('Location: ../vwijzig.php');
		}
	}
	
	//close connection
	mysql_close($con);
}
else{
	//redirect
	header('Location: ../vwijzig.php');
}
?>