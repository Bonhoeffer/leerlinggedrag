<?php
//connect to db
include('Text/connect.php');

//if a student is choosen
if (isset($_GET['id'])){
	$id = $_GET['id'];
	
	// Periode 1 array maken
	$sql = "SELECT 	
				avg(a1), avg(a2), avg(a3), 
				avg(b1), avg(b2), avg(b3), 
				avg(c1), avg(c2), 
				avg(d1), 
				avg(e1), 
				avg(f1), avg(f2), 
				avg(g1), avg(g2), 
				avg(h1), avg(h2), 
				avg(i1), avg(i2) 
			FROM beoordeling
			WHERE periode = '1' 
				AND leerlingnummer = '$id'
			GROUP BY leerlingnummer";
	
	$periode1 = mysql_fetch_array(mysql_query($sql, $con));

	// Periode 2 array maken
	$sql = "SELECT 	
				avg(a1), avg(a2), avg(a3), 
				avg(b1), avg(b2), avg(b3), 
				avg(c1), avg(c2), 
				avg(d1), 
				avg(e1), 
				avg(f1), avg(f2), 
				avg(g1), avg(g2), 
				avg(h1), avg(h2), 
				avg(i1), avg(i2) 
			FROM beoordeling
			WHERE periode = '2'
				AND leerlingnummer = '$id'
			GROUP BY leerlingnummer";
				
	$periode2 = mysql_fetch_array(mysql_query($sql, $con));
	
	// Periode 3 array maken
	$sql = "SELECT 	
				avg(a1), avg(a2), avg(a3), 
				avg(b1), avg(b2), avg(b3), 
				avg(c1), avg(c2), 
				avg(d1), 
				avg(e1), 
				avg(f1), avg(f2), 
				avg(g1), avg(g2), 
				avg(h1), avg(h2), 
				avg(i1), avg(i2) 
			FROM beoordeling
			WHERE periode = '3'
				AND leerlingnummer = '$id'
			GROUP BY leerlingnummer";
				
	$periode3 = mysql_fetch_array(mysql_query($sql, $con));
	
	// Periode 4 array maken
	$sql = "SELECT 	
				avg(a1), avg(a2), avg(a3), 
				avg(b1), avg(b2), avg(b3), 
				avg(c1), avg(c2), 
				avg(d1), 
				avg(e1), 
				avg(f1), avg(f2), 
				avg(g1), avg(g2), 
				avg(h1), avg(h2), 
				avg(i1), avg(i2) 
			FROM beoordeling
			WHERE periode = '4'
				AND leerlingnummer = '$id'
			GROUP BY leerlingnummer";
				
	$periode4 = mysql_fetch_array(mysql_query($sql, $con));
				
	// Gegevens leerling opvragen
	$sql = "SELECT voornaam, achternaam, klas 
			FROM leerlingen 
			WHERE leerlingnummer = '$id'";
	
	$SData = mysql_fetch_array(mysql_query($sql));
	
//---------- SHOW AVERAGE RESULTS ------------------------------------------------------------------------------------------------------ //	
$html = <<<EOD
	<h1>Resultaten van: </h1>
	<h2>$SData[voornaam] $SData[achternaam] ($id), $SData[klas]</h2>
	
	<table>
		<tr>
			<th></th>
			<th colspan='2'>Jaar 1</th>
			<th colspan='2'>Jaar 2</th>
		</tr>
		<tr>
			<th>Vraag</th>
			<th style='width: 65px;'>b 1</th>
			<th style='width: 65px;'>b 2</th>
			<th style='width: 65px;'>b 1</th>
			<th style='width: 65px;'>b 2</th>
		</tr>
		<tr>
			<td colspan='5'><b style='font-size: 15px;'>Concentratie</b></td>
		</tr>
	</table>
	<br  />
EOD;

// -------------------------------------------------------------------------------------------------------------------------------------- //
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('BC-Enschede');
$pdf->SetTitle("Beoordeling - $SData[voornaam]-$SData[achternaam]-$id");
$pdf->SetSubject('Leerling beoordeling');
$pdf->SetKeywords('bc-enschede, basisvaardigheden, beoordeling, rapport');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 065', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('helvetica', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
$html = <<<EOD
<h1>Example of <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a> document in <span style="background-color:#99ccff;color:black;"> PDF/A-1b </span> mode.</h1>
<i>This document conforms to the standard <b>PDF/A-1b (ISO 19005-1:2005)</b>.</i>
<p>Please check the source code documentation and other examples for further information (<a href="http://www.tcpdf.org">http://www.tcpdf.org</a>).</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_065.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
