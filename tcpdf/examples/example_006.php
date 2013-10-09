<?php
//connect to db
include('../../Text/connect.php');

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
	
	//============================================================+
	// File name   : example_006.php
	// Begin       : 2008-03-04
	// Last Update : 2013-03-16
	//
	// Description : Example 006 for TCPDF class
	//               WriteHTML and RTL support
	//
	// Author: Nicola Asuni
	//
	// (c) Copyright:
	//               Nicola Asuni
	//               Tecnick.com LTD
	//               www.tecnick.com
	//               info@tecnick.com
	//============================================================+

	/**
	 * Creates an example PDF TEST document using TCPDF
	 * @package com.tecnick.tcpdf
	 * @abstract TCPDF - Example: WriteHTML and RTL support
	 * @author Nicola Asuni
	 * @since 2008-03-04
	 */

	require_once('../config/lang/eng.php');
	require_once('../tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('BC-Enschede');
	$pdf->SetTitle("Beoordeling - $SData[voornaam] $SData[achternaam] ($id)");
	$pdf->SetSubject('Leerling beoordeling');
	$pdf->SetKeywords('bc-enschede, basisvaardigheden, beoordeling, rapport');

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);

	// ---------------------------------------------------------

	// set font
	$pdf->SetFont('Helvetica', '', 10);

	// add a page
	$pdf->AddPage();

	// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
	// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

	$html = "
		<h1>Resultaten van: </h1>
		<h2>$SData[voornaam] $SData[achternaam] ($id), $SData[klas]</h2>
		
	";

	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '', true);

	// reset pointer to the last page
	$pdf->lastPage();

	//Close and output PDF document
	$pdf->Output("Beoordeling - $SData[voornaam] $SData[achternaam] ($id).pdf", 'I');

	//============================================================+
	// END OF FILE                                                
	//============================================================+
}