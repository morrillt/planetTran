<?php
require('fpdf.php');

class Receipt extends FPDF {

	function Header() {
		$this->setFont('Arial', 'B', 15);
		$this->Cell(0);
		//$this->Image('PT_logo.png', 10, 10, 21.5);
		$this->Image('images/planettran_logo_new.jpg', 10, 10, 40);
		$this->Cell(80);
		$this->Cell(30,10,'PlanetTran, LLC',0,1,'C');
		$this->Cell(80);
		$this->Cell(30,10,'PlanetTran, LLC',0,1,'C');
		$this->Cell(80);
		$this->Cell(30,10,'38 Sidney Street, Suite 230',0,1,'C');
		$this->Cell(80);
		$this->Cell(30,10,'Cambridge, MA 02138',0, 1,'C');
		$this->Cell(80);
		$this->Cell(30,10,'1-888-PLNT-TRN',0, 1,'C');
		$this->Ln(20);
	}
	
}	
