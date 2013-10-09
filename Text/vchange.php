<?php
session_start();
$_SESSION['queries'] = 0;

if (isset($_POST['domein']) && isset($_POST['nummer']) && isset($_POST['vraag']) && isset($_POST['soort']) && isset($_GET['id']) && isset($_POST['volg'])){
	//connect to db
	include('connect.php');
	
	$id = $_GET['id'];
	$newLet = strtoupper($_POST['domein']);
	$newNum = $_POST['nummer'];
	$newSoort = $_POST['soort'];
	
	//get old letter and number and kind
	$sql = "SELECT letter, nummer, soort
			FROM vragen
			WHERE id = $id";
	
	if (!mysql_query($sql, $con)){
		die('ErrorGetOld: ' . mysql_error());
	}
	$result = mysql_fetch_array(mysql_query($sql, $con));
	$_SESSION['queries'] += 1;
	
	$oldLet = $result['letter'];
	$oldNum = $result['nummer'];
	$oldSoort = $result['soort'];
	
	//check if $newNum is a whole number
	if (strpos($newNum, ".") != false || strpos($newNum, ",") != false){
		$newNum = $oldNum;
	}

// -------- CHANGE COLUMN beoordeling TABLE ----------------------------------------------------------------------------------------------------------------- //
	if ($newLet != "" || $newNum != ""){
		//fix issues
		if ($newLet == "") $newLet = $oldLet;
		if ($newNum == "") $newNum = $oldNum;
		
		if ($newLet != $oldLet || $newNum != $oldNum){
			//delete OLD column from beeordeling table
			$sql = "ALTER TABLE beoordeling
					DROP " . $oldLet . $oldNum;
			
			if (!mysql_query($sql, $con)){
				die('ErrorDEL: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			//check if new column exists
			$sql = "SELECT *
					FROM vragen
					WHERE letter = '$newLet'
						AND nummer = $newNum";
			
			if (!mysql_query($sql, $con)){
				die('ErrorTEST: ' . mysql_error());
			}
			$result = mysql_query($sql, $con);
			$_SESSION['queries'] += 1;
			//if NEW column exists
			if (mysql_num_rows($result) == 1){
				//delete NEW column
				$sql = "ALTER TABLE beoordeling
						DROP " . $newLet . $newNum;
				
				if (!mysql_query($sql, $con)){
					die('ErrorDEL1: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
				
				//create column
				switch ($oldSoort){
					case 0:
						$sql = "ALTER TABLE beoordeling
								ADD " . $oldLet . $oldNum . " int";
					break;
					
					case 1:
						$sql = "ALTER TABLE beoordeling
								ADD " . $oldLet . $oldNum . " text";
					break;
				}
				
				if (!mysql_query($sql, $con)){
					die('ErrorDEL2: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
				
				//update questions
				$sql = "UPDATE vragen
						SET letter = '$oldLet', 
							nummer = $oldNum
						WHERE letter = '$newLet' 
							AND nummer = $newNum";
							
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
			}
			
			//add to beoordeling table
			switch ($oldSoort){
				case 0:
					$sql = "ALTER TABLE beoordeling
							ADD " . $newLet . $newNum . " int";
				break;
				
				case 1:
					$sql = "ALTER TABLE beoordeling
							ADD " . $newLet . $newNum . " text";
				break;
			}
			
			if (!mysql_query($sql, $con)){
				die('ErrorADD: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
		}
	}
// ---------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	//fix issues
	if ($newLet == "") $newLet = $oldLet;
	if ($newNum == "") $newNum = $oldNum;
	
	//update domain if set
	if ($newLet != $oldLet){
		//check which char is entered
		if (strlen($newLet) == 1 && ord($newLet) > 64){
			if (array_key_exists($newLet, $_SESSION['domainArray'])){
				//get full domain from domain array
				$newDom = $_SESSION['domainArray'][$newLet];
				
				//update domain
				$sql = "UPDATE vragen
						SET domein = '$newDom'
						WHERE id = $id";
				
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
				
				$sql = "UPDATE vragen
						SET letter = '$newLet'
						WHERE id = $id";
				
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
			}
		}
	}
	
	//update number if set
	if ($newNum != $oldNum){
		//get id of the question you want to change to
		$sql = "SELECT id
				FROM vragen
				WHERE letter = '$newLet'
					AND nummer = $newNum";
		
		$res1 = mysql_query($sql, $con);
		$_SESSION['queries'] += 1;
		
		if (mysql_num_rows($res1)){
			$row = mysql_fetch_array($res1);
			$other = $row['id'];
		
			//put new id in memory
			$sql = "UPDATE vragen
					SET nummer = -1
					WHERE id = $other";
					
			if (!mysql_query($sql, $con)){
				die('Error1: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
		}
		
		//change chosen id
		$sql = "UPDATE vragen
				SET nummer = $newNum
				WHERE id = $id";
				
		if (!mysql_query($sql, $con)){
			die('Error2: ' . mysql_error());
		}
		$_SESSION['queries'] += 1;
		
		//restore memory id
		$sql = "UPDATE vragen
				SET nummer = $oldNum
				WHERE nummer = -1";
				
		if (!mysql_query($sql, $con)){
			die('Error3: ' . mysql_error());
		}
		$_SESSION['queries'] += 1;
	}
	
	//update vraag if set
	if ($_POST['vraag'] != ""){
		$sql = "UPDATE vragen
				SET vraag = '$_POST[vraag]'
				WHERE id = $id";
				
		if (!mysql_query($sql, $con)){
			die('Error: ' . mysql_error());
		}
		$_SESSION['queries'] += 1;
	}
	
	//update soort if different
	if ($oldSoort != $newSoort){
		//update vraag
		$sql = "UPDATE vragen
				SET soort = $newSoort
				WHERE id = $id";
				
		if (!mysql_query($sql, $con)){
			die('ErrorS1: ' . mysql_error());
		}
		$_SESSION['queries'] += 1;
		
		//update column
		$sql = "ALTER TABLE beoordeling
				DROP " . $newLet . $newNum;
				
		if (!mysql_query($sql, $con)){
			die('ErrorS2: ' . mysql_error());
		}
		$_SESSION['queries'] += 1;
		
		switch ($newSoort){
			case 0:
				$sql = "ALTER TABLE beoordeling
						ADD " . $newLet . $newNum . " int";
			break;
			
			case 1:
				$sql = "ALTER TABLE beoordeling
						ADD " . $newLet . $newNum . " text";
			break;
		}
		
		if (!mysql_query($sql, $con)){
			die('ErrorS3: ' . mysql_error());
		}
		$_SESSION['queries'] += 1;
	}
	
	//change order of questions
	if ($_POST['volg'] != ""){
		//count questions
		$sql = "SELECT *
				FROM vragen";
		
		$count = mysql_num_rows(mysql_query($sql, $con));
		$_SESSION['queries'] += 1;
		
		$volg = $_POST['volg'] - 1;
		
		//if id entered is bigger than biggest existing id
		if ($volg >= $count){
			$sql = "UPDATE vragen
					SET id = -1
					WHERE id = $id";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			$sql = "UPDATE vragen
					SET id = id - 1
					WHERE id > $id";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
			
			$sql = "UPDATE vragen
					SET id = $count - 1
					WHERE id = -1";
					
			if (!mysql_query($sql, $con)){
				die('Error: ' . mysql_error());
			}
			$_SESSION['queries'] += 1;
		}
		else{
			if ($volg > $id){
				$sql = "UPDATE vragen
						SET id = -1
						WHERE id = $id";
						
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
				
				$sql = "UPDATE vragen
						SET id = id - 1
						WHERE id > $id
							AND id <= $volg";
						
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
				
				$sql = "UPDATE vragen
						SET id = $volg
						WHERE id = -1";
						
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				$_SESSION['queries'] += 1;
			}
			else{
				if ($volg != $id){
					$sql = "UPDATE vragen
							SET id = -1
							WHERE id = $id";
							
					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
					$_SESSION['queries'] += 1;
					
					$sql = "UPDATE vragen
							SET id = id + 1
							WHERE id >= $volg
								AND id < $id";
							
					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
					$_SESSION['queries'] += 1;
					
					$sql = "UPDATE vragen
							SET id = $volg
							WHERE id = -1";
							
					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
					$_SESSION['queries'] += 1;
				}
			}
		}
	}
	
	//close connection
	mysql_close($con);

	//redirect
	$_SESSION['QFailed'] = 7;
	header('Location: ../vwijzig.php');
}
else{
	//redirect
	header('Location: ../vwijzig.php');
}
?>