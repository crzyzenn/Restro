<?php
require 'pdfmyurl.php';

try {
	// first fill in the license that you received upon sign-up
	$pdf = new PDFmyURL ('your license here');
	
	// now set the options, so for example when we want to have a page in A4 in orientation portrait
	$pdf->SetPageSize('A4');
	$pdf->SetPageOrientation('Portrait');
	
	// then do the conversion - this is how you convert Google to PDF and display the PDF to the user
	$pdf->CreateFromURL ('www.google.com');
	$pdf->Display();
	
}  catch (Exception $error) {
	   echo $error->getMessage();
	   echo $error->getCode();
}
