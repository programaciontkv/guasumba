<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_vencidos_cxc extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('pdf_vencidos_cxc_model');
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
        $pdf->AliasNbPages();
        $pdf->AddFont('Calibri-light','');//$pdf->SetFont('Calibri-Light', '', 9);
        $pdf->AddFont('Calibri-bold','');//$pdf->SetFont('Calibri-bold', '', 9);

	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
        
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);
        $fec1='2000-01-01';
        $txt = " and (c.fac_numero LIKE '%$nm%' or c.fac_nombre like '%$nm%' or c.fac_identificacion like '%$nm%')";
        $cns = $this->pdf_vencidos_cxc_model->buscar_documentos_vencidos(date('Y-m-d'), $fec1, $fec2, $txt,$rst_cja->emp_id);
        
        // $pdf->Image('./imagenes/'.$emisor->emp_logo, 6, 4, 35);

        $pdf->SetX(50);
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, $emisor->emp_identificacion, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, $emisor->emp_ciudad."-".$emisor->emp_pais, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, "TELEFONO: " . $emisor->emp_telefono, 0, 0, 'L');
        $pdf->SetX(0);
        $pdf->Cell(190, 5, $pdf->Image('./imagenes/'.$emisor->emp_logo, 175, 4, 25), 0, 0, 'R');
        $pdf->setY(30);

        $pdf->SetFont('Calibri-bold', '', 14);
        $pdf->Ln();
        $pdf->Cell(200, 5, "CUENTAS POR COBRAR", 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 13);
        $pdf->Cell(200, 5, "Vencimientos al " . $fec2, 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetTextColor(0, 0, 0);
        $tot = 0;
        $ts = 0;
        $grup = '';
        $n = 0;
        foreach ($cns as $rst2) {
            $n++;

            $rst = $this->pdf_vencidos_cxc_model->lista_ultimo_pago($rst2->fac_id);
            $pag_fecha_v = $rst->pag_fecha_v;
            $res = $this->pdf_vencidos_cxc_model->suma_pagos1($rst2->fac_id);
            $mto = $res->monto; // suma pagos cta
            $pgo = $res->pago; //suma pagos
            $saldo = round($pgo,$dec) - round($mto, $dec); // saldo

            
            if ($rst->pag_fecha_v < date('Y-m-d')) {
                $vencido = $saldo;
            } else {
                $vencido = 0;
            }
            if (!empty($vencido)) {
                if ($n > 1 && $grup != $rst2->fac_identificacion && !empty($tot)) {
                    $pdf->SetFont('Calibri-bold', '', 10);
                    $pdf->Cell(75, 5, '', '', 0, 'R');
                    $pdf->Cell(45, 5, 'TOTAL', '', 0, 'C');
                    $pdf->Cell(30, 5, number_format($tot, $dec), 'T', 0, 'R');
                    $pdf->Ln();
                    $pdf->Ln();
                    $tot = 0;
                }
                if ($grup != $rst2->fac_identificacion) {
                    $datos = $pdf->getY();
					if ($datos > 250) {
						$pdf->AddPage();
					}
                   $pdf->SetFont('Calibri-bold', '', 10);
                    $rst_cli = $this->cliente_model->lista_un_cliente_cedula($rst2->fac_identificacion);
                    $pdf->Cell(200, 5, "IDENTIFICACION: " . $rst_cli->cli_ced_ruc, 0, 0, 'L');
                    $pdf->Ln();
                    $pdf->Cell(200, 5, "CLIENTE: " . utf8_decode($rst2->fac_nombre), 0, 0, 'L');
                    $pdf->Ln();
                    $pdf->Ln();
                    $pdf->SetFont('Calibri-light', '', 10);
                    $pdf->Cell(35, 5, "FACTURA", 'TB', 0, 'L');
                    $pdf->Cell(30, 5, "F.EMISION", 'TB', 0, 'L');
                    $pdf->Cell(30, 5, "F.VENCIMIENTO", 'TB', 0, 'L');
                    $pdf->Cell(25, 5, "DIAS VENCIDOS", 'TB', 0, 'L');
                    $pdf->Cell(30, 5, "VALOR", 'TB', 0, 'C');
                    $pdf->Ln();
                }
                $fec = strtotime(date('Y-m-d')) - strtotime($rst->pag_fecha_v);
                $dias = floor($fec / 86400);
                $pdf->SetFont('Calibri-light', '', 10);
                $pdf->Cell(35, 5, $rst2->fac_numero, 0, 0, 'L');
                $pdf->Cell(30, 5, $rst2->fac_fecha_emision, 0, 0, 'L');
                $pdf->Cell(30, 5, $rst->pag_fecha_v, 0, 0, 'L');
                $pdf->Cell(25, 5, $dias, 0, 0, 'C');
                $pdf->Cell(30, 5, number_format($vencido, $dec), 0, 0, 'R');
                $pdf->Ln();
                $grup = $rst2->fac_identificacion;
                $tot+=$vencido;
                $ts+=$vencido;
            }
        }

            $pdf->SetFont('Calibri-bold', '', 10);
            $pdf->Cell(75, 5, '', '', 0, 'R');
            $pdf->Cell(45, 5, 'TOTAL', '', 0, 'C');
            $pdf->Cell(30, 5, number_format($tot, $dec), 'T', 0, 'R');
            $pdf->Ln();
            $pdf->Ln();

            $pdf->SetFont('Calibri-bold', '', 10);
            $pdf->Cell(25, 5, '', '', 0, 'R');
            $pdf->Cell(45, 5, 'TOTALES ', '', 0, 'C');
            $pdf->Cell(80, 5, number_format($ts, $dec), 'T', 0, 'R');
            $pdf->Ln();
            $pdf->Ln();

       
    

        $pdf->Output('cartera_vencidad_cxc.pdf' , 'I' ); 

    } 

    

}
