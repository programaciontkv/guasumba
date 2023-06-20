<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_saldo_clientes extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('pdf_saldo_clientes_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
		$this->load->model('cliente_model');
	}

	

	 public function index($opc_id,$fec2,$nm = ''){
	 	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 	require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','A4',0);
	    set_time_limit(0);

	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
        
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);
        $fec1='2000-01-01';
        $txt = "and (f.fac_numero LIKE '%$nm%' or f.fac_nombre like '%$nm%' or f.fac_identificacion like '%$nm%') and f.fac_fecha_emision between '$fec1' and '$fec2' and exists (select * from erp_pagos_factura p where p.com_id=f.fac_id)";
        $cns = $this->pdf_saldo_clientes_model->lista_documentos_ctas($txt,$rst_cja->emp_id);
        
        $pdf->Image('./imagenes/'.$emisor->emp_logo, 6, 4, 35);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetTextColor(0, 160, 186);
        $pdf->Cell(200, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(200, 5, "RUC: " . $emisor->emp_identificacion, 0, 0, 'C');
        $pdf->Ln();
        
        $pdf->Cell(200, 5, "CUENTAS POR COBRAR", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(200, 5, "SALDO POR CLIENTES AL " . $fec2, 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 5, "", '', 0, 'C');
        $pdf->Cell(150, 5, "CLIENTE", 'TB', 0, 'L');
        $pdf->Cell(25, 5, "SALDO", 'TB', 0, 'C');
        $pdf->Ln();

        $debito = 0;
        $credito = 0;
        $deudor = 0;
        $acreedor = 0;
        $tacr = 0;
        $tdeu = 0;
        $tdeb = 0;
        $thab = 0;
        $n = 0;
        $grup='';
        foreach ($cns as $rst_doc) {
            
            if ($grup != $rst_doc->fac_identificacion) {
                $rst_cli = $this->cliente_model->lista_un_cliente_cedula($rst_doc->fac_identificacion);
                
               $res = $this->pdf_saldo_clientes_model->suma_documentos_cliente($rst_doc->fac_identificacion,$fec2,$rst_cja->emp_id);
                $haber = $res->credito; // suma pagos cta
                $debe = $res->fac_total_valor;
                $saldo = round($debe,$dec) - round($haber,$dec);
                if ($saldo != 0) {
                    if ($debe > $haber) {
                        $deudor = $debe - $haber;
                    } else {
                        $acreedor = $debe - $haber;
                    }
                    if (round($debe, $dec) != 0) {
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Cell(10, 5, "", '', 0, 'C');
                        $pdf->Cell(150, 5, utf8_decode(substr($rst_doc->fac_nombre, 0, 45)), 0, 0, 'L');
                        $pdf->Cell(25, 5, number_format($deudor, $dec), 0, 0, 'R');
                        $pdf->Ln();
                    }
                    $tdeb+=round($debe,$dec);
                    $thab+=round($haber,$dec);
                    $tdeu+=round($deudor,$dec);
                    $tacr+=round($acreedor,$dec);
                    $debe = 0;
                    $haber = 0;
                    $deudor = 0;
                    $acreedor = 0;
                    $cant = 0;
                    $deb = 0;
                    $cre = 0;
                    $grup = $rst_doc->fac_identificacion;
                }
            }
            
        }
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(10, 5, "", '', 0, 'C');
        $pdf->Cell(60, 5, '', '', 0, 'R');
        $pdf->Cell(90, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(25, 5, number_format($tdeu, $dec), 'T', 0, 'R');
        $pdf->Ln();

        $pdf->Output('saldo_clientes.pdf' , 'I' ); 

    } 

    

}
