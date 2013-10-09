<?php
if (isset($_POST['leerlingnummer']) && isset($_GET['lj'])){
	//get leerjaar from url
	$lj = $_GET['lj'];
	
	//connect to db
	include('connect.php');
	
	//check if beoordeling already exists
	$sql = "SELECT *
			FROM beoordeling 
			WHERE leerlingnummer = $_POST[leerlingnummer]
				AND periode = $_POST[periode]
				AND docent = '$_SESSION[gb]'";
		
	$result = mysql_query($sql, $con);
	
	//if not, insert into
	if (mysql_num_rows($result) == 0){
		$exists = false;
	}
	else{
		$exists = true;
	}
		
	//get questions
	$sql = "SELECT *
			FROM vragen
			ORDER BY id";
	
	$result = mysql_query($sql, $con);
	
	//if questions exist
	if (mysql_num_rows($result) > 0){
		//start query
		if ($exists){
			$mysql = "UPDATE beoordeling SET ";
		}
		else{
			$mysql = "INSERT INTO beoordeling ( leerlingnummer, docent, periode, ";
		}
		
		//'read' questions
		while ($row = mysql_fetch_array($result)){
			//if questions in domain
			if ($row['nummer'] > 0){
				$name = $row['letter'] . $row['nummer'];
				
				//save value
				if (!isset($_POST[$name])){
					$array[$name] = 'null';
					
					//notice that not all fields were filled in
					$_SESSION['bNotice2'] = 1;
				}
				else{
					$array[$name] = $_POST[$name];
				}
				
				//add to query
				if ($exists){
					if ($array[$name] != 'null' && $array[$name] != ''){
						switch ($row['soort']){
							case 0:
								$mysql .= "`" . $name . "` = " . $array[$name] . ", ";
							break;
								
							case 1:
								$mysql .= "`" . $name . "` = '" . $array[$name] . "', ";
							break;
						}
					}
				}
				else{
					$mysql .= "`" . $name  . "`, ";
				}
			}
		}
		//save last choice of period
		$_SESSION['lastPeriod'] = $_POST['periode'];
		
		//get current date and treshhold
		$y = date('Y') - 2013;
		$m = date('m');
		$months = ($y * 12) + $m;
		
		//end query
		if ($exists){
			$mysql .= "datum = CURDATE(), drempel = $months
					WHERE leerlingnummer = $_POST[leerlingnummer]
						AND docent = '$_SESSION[gb]'
						AND periode = $_POST[periode]";
			
			//notice
			$_SESSION['bNotice'] = 2;
		}
		else{
			$mysql .= "datum, drempel ) VALUES (
					$_POST[leerlingnummer], '$_POST[docent]', $_POST[periode], ";
					
			//get questions
			$sql = "SELECT *
					FROM vragen
					ORDER BY id";
			
			$result = mysql_query($sql, $con);
			
			//if questions exist
			if (mysql_num_rows($result) > 0){			
				//'read' questions
				while ($row = mysql_fetch_array($result)){
					//if questions in domain
					if ($row['nummer'] > 0){
						$name = $row['letter'] . $row['nummer'];
						
						//add to query
						switch ($row['soort']){
							case 0:
								$mysql .= $array[$name] . ", ";
							break;
								
							case 1:
								$mysql .= "'" . $array[$name] . "', ";
							break;
						}
					}
				}
				$mysql .= "CURDATE(), $months )";
				
				//notice
				$_SESSION['bNotice'] = 1;
			}
		}
		//send
		if (!mysql_query($mysql, $con)){
			die('Error: ' . mysql_error());
		}
		
		//redirect
		header('Location: beoordelen.php?id=' . $_POST['leerlingnummer'] . '&lj='. $lj);
	}
	else{
		//redirect
		header('Location: oordeel.php');
	}
	//close connection
	mysql_close($con);
}
//if no questions exist
else{
	//redirect
	header('Location: oordeel.php');
}
?>