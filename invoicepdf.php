<?php 
	require 'vendor/autoload.php';
	require 'connect.php';
	session_start();
	class PDF extends FPDF {
		// Load data
		function LoadData($file)
		{
			// Read file lines
			$lines = file($file);
			$data = array();
			foreach($lines as $line)
				$data[] = explode(';',trim($line));

			return $data;
		}

		// Simple table
		function BasicTable($header, $data)
		{			
			// Header
			foreach($header as $col){
				if ($col == 'Item Name') {
					$this->Cell(150,10,$col,1);
				}
				else if ($col == 'Quantity') {
					$this->Cell(20,10,$col,1);
				}
				else {
					$this->Cell(15,10,$col,1);
				}
				
			}
			$this->Ln();

			// Data
			foreach($data as $row)
			{					
				$this->Cell(150,10,$row[0],1);			
				$this->Cell(20,10,$row[1],1);
				$this->Cell(15,10,'$'.$row[2],1);		
				$this->Ln();				
			}
		}

		function getTotal($data){
			$total = 0;
			foreach ($data as $value) {
				$total += $value[2]; 
			}
			return $total + (13 / 100 * $total); 
		}

		// Better table
		function ImprovedTable($header, $data)
		{
			// Column widths
			$w = array(40, 35, 40, 45);
			// Header
			for($i=0;$i<count($header);$i++)
				$this->Cell($w[$i],7,$header[$i],1,0,'C');
			$this->Ln();
			// Data
			foreach($data as $row)
			{
				$this->Cell($w[0],6,$row[0],'LR');
				$this->Cell($w[1],6,$row[1],'LR');
				$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
				// $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
				$this->Ln();
			}
			// Closing line
			$this->Cell(array_sum($w),0,'','T');
		}

		// Colored table
		function FancyTable($header, $data)
		{
			// Colors, line width and bold font
			$this->SetFillColor(200,0,0);
			$this->SetTextColor(255);
			$this->SetDrawColor(128,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			// Header
			$w = array(40, 35, 40, 45);
			for($i=0;$i<count($header);$i++)
				$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
			$this->Ln();
			// Color and font restoration
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('');
			// Data
			$fill = false;
			foreach($data as $row)
			{
				$this->Cell($w[0],6,$row[0],'LR',0,'L', $fill);
				$this->Cell($w[1],6,$row[1],'LR',0,'L', $fill);
				$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R', $fill);
				// $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
				$this->Ln();
				$fill = !$fill;
			}
			// Closing line
			$this->Cell(array_sum($w),0,'','T');
		}

		function loadtoDatabase(){
			 
		}
	}

	$pdf = new PDF();
	$pdf->SetTitle('Mobile Cafe: Invoice ('.date('M d h:i:sa').')', true);
	$total = $pdf->getTotal($_SESSION['orders']);

	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);	
	$pdf->Cell(40,10,'Your Electronic Invoice');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	date_default_timezone_set('Asia/Katmandu'); 
	$pdf->Cell(40,10,'Generated at: '.date('M d h:i:sa'));
	$pdf->Ln();
	$pdf->Ln();

	// Column headings
	$header = array('Item Name', 'Quantity', 'Price');
	// Data loading
	$pdf->SetFont('Arial','',14);
	$pdf->BasicTable($header,$_SESSION['orders']);
	$pdf->SetFont('Arial', 'B', 15); 
	$pdf->Cell(40,10,'Total (13% VAT) = $'. $total);

	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1", "©").' All rights reserved.',0,1,'C',0);
	$pdf->Output('Invoice.pdf','I');

	
	// Save invoice to local directory 
	$pdf->Output('Invoices/Invoice-Session'.$_SESSION['user_code'].'.pdf', 'F');

	// Link invoice to database
	$query = "MATCH (user:USER{name:'session".$_SESSION['user_code']."'}) MERGE (user)-[:GENERATED_INVOICE]->(invoice:INVOICE{filename:'Invoice-Session".$_SESSION['user_code'].".pdf'})";
	$client->run($query);



	


	


?>