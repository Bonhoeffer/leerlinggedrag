<?php
//functions
function gemiddelde($getal){
	if ($getal == 0) return "-";
	if ($getal > 0 && $getal <= 3) return "Onvoldoende";
	if ($getal > 3 && $getal <= 5) return "Matig";
	if ($getal > 5 && $getal <= 7) return "Voldoende";
	if ($getal > 7 && $getal <= 9) return "Ruim voldoende";
	if ($getal > 9 && $getal <= 10) return "Goed";
}

//if a student is choosen
if (isset($_GET['id'])){
	$id = $_GET['id'];
	
	//connect to db
	include('Text/connect.php');
	
	//get students data
	$sql = "SELECT voornaam, achternaam, klas 
			FROM leerlingen 
			WHERE leerlingnummer = $id";
	
	$SData = mysql_fetch_array(mysql_query($sql, $con));
	
	//persoonlijke pagina
	if ($_POST['type'] == "Persoonlijk resultaat printen of opslaan als .pdf"){

		// ------------------------ GET AVERAGE RESULTS ARRAYS PER PERIOD ------------------------------------------------------------------------------------------- //
		
		//get questions from db
		$qry = "SELECT *
				FROM vragen
				ORDER BY id";
		
		$result = mysql_query($qry, $con);
		
		//if questions exist
		if (mysql_num_rows($result) > 0){
			$GO = true;
			
			//make 4 arrays
			for ($i = 0; $i < 4; $i++){
				//start query
				$sql = "SELECT ";
				
				//'read' questions
				$result = mysql_query($qry, $con);
				while ($row = mysql_fetch_array($result)){
					//if questions in domain
					if ($row['nummer'] != 0){
						if ($row['soort'] == 0){
							$name = $row['letter'] . $row['nummer'];
							//add to query
							$sql .= "avg($name), ";
						}				
					}
				}
				//get rid of last comma
				$sql = substr($sql, 0, strlen($sql) - 2);
				//end query
				$sql .= " FROM beoordeling 
						WHERE periode = " . ($i + 1) . " 
							AND leerlingnummer = $id 
						GROUP BY leerlingnummer";
				
				if (!mysql_query($sql, $con)){
					die('Error: ' . mysql_error());
				}
				
				$periode[$i + 1] = mysql_fetch_array(mysql_query($sql, $con));
			}
		}
		
	//--------- SHOW AVERAGE RESULTS --------------------------------------------------------------------------------------------------------------------------------------------------------- //

	//start table
	$html = <<<EOF
		<style>
			body {
				color: black;
				font-size: 107%;
			}
			
			td, th {
				border: 1px solid #DEDEDD;
			}
			
			h1 {
				font-size: 170%;
			}
		</style>
		
		<body>
			<h1>Beoordeling basisvaardigheden van:</h1>
			<h2><span style="background-color: #999999;"> <span style="background-color: #9FA8E0;"> $SData[voornaam] $SData[achternaam] </span> </span> <span style="background-color: #999999;"> <span style="background-color: #9FA8E0;"> $id </span> </span> <span style="background-color: #999999;"> <span style="background-color: #9FA8E0;"> $SData[klas] </span> </span></h2>
		
			<table style="width: 100%;">
				<tr>
					<th style="width: 44%; background-color: #9FA8E0; border: none;" rowspan="2"></th>
					<th colspan="2" style="width: 28%; background-color: #9FA8E0; border: none;">Leerjaar 1</th>
					<th colspan="2" style="width: 28%; background-color: #9FA8E0; border: none;">Leerjaar 2</th>
				</tr>
				<tr>
					<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 1</th>
					<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 2</th>
					<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 1</th>
					<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 2</th>
				</tr>
EOF;

		//if questions exist
		if ($GO){
			$memory = "";
			
			//get questions from db
			$qry = "SELECT *
					FROM vragen
					ORDER BY id";
			
			$result = mysql_query($qry, $con);
			
			//'read' questions
			while ($row = mysql_fetch_array($result)){
				//if same domain as previous 'read'
				if ($memory == $row['domein']){
					//if questions in domain
					if ($row['nummer'] != 0){
						if ($row['soort'] == 0){
							$name = $row['letter'] . $row['nummer'];
							$html .= "
								<tr>
									<td>$row[vraag]</td>
									<td>" . gemiddelde($periode[1]["avg($name)"]) . "</td>
									<td>" . gemiddelde($periode[2]["avg($name)"]) . "</td>
									<td>" . gemiddelde($periode[3]["avg($name)"]) . "</td>
									<td>" . gemiddelde($periode[4]["avg($name)"]) . "</td>
								</tr>
							";
						}				
					}
				}
				else{
					//set memory domain to current
					$memory = $row['domein'];
					
					//if questions in domain
					if ($row['nummer'] != 0){
						if ($row['soort'] == 0){
							$name = $row['letter'] . $row['nummer'];
							$html .= '
								<tr>
									<td colspan="5" style="background-color: #AEB7F5; height: 25px; font-size: 115%;">' . $memory . '</td>
								</tr>
								<tr>
									<td>'. $row['vraag'] . '</td>
									<td>' . gemiddelde($periode[1]["avg($name)"]) . '</td>
									<td>' . gemiddelde($periode[2]["avg($name)"]) . '</td>
									<td>' . gemiddelde($periode[3]["avg($name)"]) . '</td>
									<td>' . gemiddelde($periode[4]["avg($name)"]) . '</td>
								</tr>
							';
						}
					}
				}
			}
			//end table
			$html .= "
					</table>
					<br />
				</body>
			";
		}
		
		//include the main TCPDF library.
		require_once('tcpdf/tcpdf.php');

		//create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);

		//set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("BC-Enschede");
		$pdf->SetTitle("$SData[voornaam] $SData[achternaam] ($id)");
		$pdf->SetSubject("Beoordeling $SData[voornaam] $SData[achternaam] ($id)");
		$pdf->SetKeywords("BC-Enschede, basisvaardigheden, beoordeling");
		
		//set margins
		$pdf->SetMargins(10, -1, 10, false);
		
		//set cell padding
		$pdf->setCellPaddings(1);
		
		//set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set auto page breaks
		$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set font
		$pdf->SetFont('helvetica', '', 10);
		
		//add a page
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage();
		
		//output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		//reset pointer to the last page
		$pdf->lastPage();

		//close and output PDF document
		ob_clean();
		$pdf->Output("$SData[voornaam]-$SData[achternaam]-$id.pdf", "I");
	}
	//klassenresultaat
	else{
		//include the main TCPDF library.
		require_once('tcpdf/tcpdf.php');

		//create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);

		//set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("BC-Enschede");
		$pdf->SetTitle("Klassenbeoordeling - $SData[klas]");
		$pdf->SetSubject("Klassenbeoordeling - $SData[klas]");
		$pdf->SetKeywords("BC-Enschede, basisvaardigheden, beoordeling");
		
		//set margins
		$pdf->SetMargins(10, -1, 10, false);
		
		//set cell padding
		$pdf->setCellPaddings(1);
		
		//set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set auto page breaks
		$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set font
		$pdf->SetFont('helvetica', '', 10);
		
	//--- GET PERSONAL RATINGS FROM EVERY STUDENT IN CLASS ---------------------------------------------------------------------------------------------------------------------------------
		
		//get students in class
		$sql = "SELECT leerlingnummer
				FROM leerlingen
				WHERE klas = '$SData[klas]'
				ORDER BY leerlingnummer ASC;";
				
		$result = mysql_query($sql, $con);
		if (!$result){
			die('Error: ' . mysql_error());
		}
		
		//create pdf per student
		while ($row = mysql_fetch_array($result)){
			//TEMP
			echo "$row[leerlingnummer]";
			
			//get questions from db
			$qry = "SELECT *
					FROM vragen
					ORDER BY id";
			
			$result2 = mysql_query($qry, $con);
			
			//if questions exist
			if (mysql_num_rows($result2) > 0){
				$GO = true;
				
				//make 4 arrays
				for ($i = 0; $i < 4; $i++){
					//start query
					$sql = "SELECT ";
					
					//'read' questions
					$result2 = mysql_query($qry, $con);
					while ($row3 = mysql_fetch_array($result2)){
						//if questions in domain
						if ($row3['nummer'] != 0){
							if ($row3['soort'] == 0){
								$name = $row3['letter'] . $row3['nummer'];
								//add to query
								$sql .= "avg($name), ";
							}				
						}
					}
					//get rid of last comma
					$sql = substr($sql, 0, strlen($sql) - 2);
					
					//end query
					$sql .= " FROM beoordeling
							WHERE periode = " . ($i + 1) . "
								AND leerlingnummer = $row[leerlingnummer] 
							GROUP BY leerlingnummer;";
					
					if (!mysql_query($sql, $con)){
						die('Error: ' . mysql_error());
					}
					
					$periode[$i + 1] = mysql_fetch_array(mysql_query($sql, $con));
				}
			}
			
		//--------- AVERAGE RESULTS --------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
		//get students data
		$sql = "SELECT voornaam, achternaam, klas 
			FROM leerlingen 
			WHERE leerlingnummer = $row[leerlingnummer]";
	
		$SData2 = mysql_fetch_array(mysql_query($sql, $con));
		
		//start table
		$html = <<<EOF
			<style>
				body {
					color: black;
					font-size: 107%;
				}
				
				td, th {
					border: 1px solid #DEDEDD;
				}
				
				h1 {
					font-size: 170%;
				}
			</style>
			
			<body>
				<h1>Beoordeling basisvaardigheden van:</h1>
				<h2><span style="background-color: #999999;"> <span style="background-color: #9FA8E0;"> $SData2[voornaam] $SData2[achternaam] </span> </span> <span style="background-color: #999999;"> <span style="background-color: #9FA8E0;"> $row[leerlingnummer] </span> </span> <span style="background-color: #999999;"> <span style="background-color: #9FA8E0;"> $SData[klas] </span> </span></h2>
			
				<table style="width: 100%;">
					<tr>
						<th style="width: 44%; background-color: #9FA8E0; border: none;" rowspan="2"></th>
						<th colspan="2" style="width: 28%; background-color: #9FA8E0; border: none;">Leerjaar 1</th>
						<th colspan="2" style="width: 28%; background-color: #9FA8E0; border: none;">Leerjaar 2</th>
					</tr>
					<tr>
						<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 1</th>
						<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 2</th>
						<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 1</th>
						<th style="width: 14%; background-color: #9FA8E0; border: none;">beoordeling 2</th>
					</tr>
EOF;

			//if questions exist
			if ($GO){
				$memory = "";
				
				//get questions from db
				$qry = "SELECT *
						FROM vragen
						ORDER BY id";
				
				$result3 = mysql_query($qry, $con);
				
				//'read' questions
				while ($row2 = mysql_fetch_array($result3)){
					//if same domain as previous 'read'
					if ($memory == $row2['domein']){
						//if questions in domain
						if ($row2['nummer'] != 0){
							if ($row2['soort'] == 0){
								$name = $row2['letter'] . $row2['nummer'];
								$html .= "
									<tr>
										<td>$row2[vraag]</td>
										<td>" . gemiddelde($periode[1]["avg($name)"]) . "</td>
										<td>" . gemiddelde($periode[2]["avg($name)"]) . "</td>
										<td>" . gemiddelde($periode[3]["avg($name)"]) . "</td>
										<td>" . gemiddelde($periode[4]["avg($name)"]) . "</td>
									</tr>
								";
							}				
						}
					}
					else{
						//set memory domain to current
						$memory = $row2['domein'];
						
						//if questions in domain
						if ($row2['nummer'] != 0){
							if ($row2['soort'] == 0){
								$name = $row2['letter'] . $row2['nummer'];
								$html .= '
									<tr>
										<td colspan="5" style="background-color: #AEB7F5; height: 25px; font-size: 115%;">' . $memory . '</td>
									</tr>
									<tr>
										<td>'. $row2['vraag'] . '</td>
										<td>' . gemiddelde($periode[1]["avg($name)"]) . '</td>
										<td>' . gemiddelde($periode[2]["avg($name)"]) . '</td>
										<td>' . gemiddelde($periode[3]["avg($name)"]) . '</td>
										<td>' . gemiddelde($periode[4]["avg($name)"]) . '</td>
									</tr>
								';
							}
						}
					}
				}
				//end table
				$html .= "
						</table>
						<br />
					</body>
				";
			}
			
			//add a page
			$pdf->SetPrintHeader(false);
			$pdf->SetPrintFooter(false);
			$pdf->AddPage();
			
			//output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			//reset pointer to the last page
			$pdf->lastPage();
		}
		
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		//close and output PDF document
		ob_clean();
		$pdf->Output("Klassenbeoordeling - $SData[klas].pdf", "I");
	}
}
else{
	//redirect
	header('Location: zoeken.php');
}
?>