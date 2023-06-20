<?php
require_once('tcpdf.php');
	    
class MYPDF extends TCPDF {


    public function Footer() {
        // Position at 15 mm from bottom
         $this->SetY(-15);
        // // Set font
        $this->SetFont('calibril', '', 9);
        $this->Cell(0, 10, 'SISTEMA DE FACTURACIÓN ELECTRÓNICA TIKVASYST', 0,false, 'L', 0, '', 0, false, 'T', 'M');
        $this->setY(-12.5);
        $this->setX(110);
        $this->Cell(30, 5, " PÁGINA. ". $this->PageNo().' de '.$this->getAliasNbPages(), '', 0, '');
        $this->setY(-15);
        $this->setX(-38);
        $this->Cell(0,10, date("Y-m-d H:i:s"),0,0,'J');
    }
}
?>