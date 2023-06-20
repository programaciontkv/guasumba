<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_estado_cliente extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('pdf_estado_cliente_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
		$this->load->model('cliente_model');
	}

	

	 public function index($id,$opc_id,$fec2){
	 	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 	require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('L','A4',0);
	    set_time_limit(0);

	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
        
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);
	    $cliente = $this->cliente_model->lista_un_cliente_cedula($id);
        $fec1='2000-01-01';
        $cns = $this->pdf_estado_cliente_model->lista_estado_cuenta_cliente($id, $fec1, $fec2,$rst_cja->emp_id);
       	$rst_cta = $this->pdf_estado_cliente_model->lista_codigo_cuenta($cliente->cli_ced_ruc);
        $cod='';
        if ($rst_cta->pln_id != '') {
            $rst_pln = $this->plan_cuentas_model->listar_una_cuenta_id($rst_cta->pln_id);
            $cod = $rst_pln->pln_codigo;
        }
        $pdf->Image('./imagenes/'.$emisor->emp_logo, 6, 4, 35);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetTextColor(0, 160, 186);
        $pdf->Cell(270, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(270, 5, "RUC: " . $emisor->emp_identificacion, 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(270, 5, "CUENTAS POR COBRAR", 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(270, 5, "ESTADO DE CUENTAS CLIENTE", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        //$pdf->Cell(150, 5, "CODIGO CLIENTE: " . $cliente->cli_codigo, 0, 0, 'L');
        $pdf->Cell(150, 5, "RUC: " .utf8_decode($cliente->cli_ced_ruc), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(150, 5, "CLIENTE: " . utf8_decode($cliente->cli_raz_social), 0, 0, 'L');
        $pdf->Cell(150, 5, "DIRECCION: " . utf8_decode($cliente->cli_calle_prin), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(150, 5, "CODIGO CONTABLE: " . $cod, 0, 0, 'L');
        $pdf->Cell(150, 5, "TELEFONO: " . $cliente->cli_telefono, 0, 0, 'L');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(25, 5, "FECHA", 'TB', 0, 'L');
        $pdf->Cell(30, 5, "DOCUMENTO", 'TB', 0, 'C');
        $pdf->Cell(60, 5, "CONCEPTO", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "F.PAGO", 'TB', 0, 'C');
        $pdf->Cell(33, 5, "DEBE", 'TB', 0, 'C');
        $pdf->Cell(33, 5, "HABER", 'TB', 0, 'C');
        $pdf->Cell(33, 5, "SALDO", 'TB', 0, 'C');
        $pdf->Cell(35, 5, "SALDO VENCIDO", 'TB', 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $saldo1 = 0;
        $n = 0;
        $tdeb=0;
        $tcre=0;
        $tvc=0;

        foreach ($cns as $rst) {
            $n++;
            $debito1 = round($rst->total_valor,$dec);
            $credito1 = round($rst->haber,$dec);
            
            $saldo1 = round($saldo1,$dec) + $debito1 - $credito1;
            if ($rst->concepto == 'FACTURACION VENTA') {
                $res = $this->pdf_estado_cliente_model->suma_pagos1($rst->fac_id);
                $mto = round($res->monto,$dec); // suma pagos cta
                $pgo = round($res->pago,$dec);
                $rst_pag1 = $this->pdf_estado_cliente_model->lista_ultimo_pago($rst->fac_id);
                $fvencimiento = $rst_pag1->pag_fecha_v;
                if ($fvencimiento < date('Y-m-d')) {
                    $vencido1 = round($pgo,$dec) - round($mto,$dec);
                } else {
                    $vencido1 = 0;
                }
            } else {
                $vencido1 = 0;
            }
            
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 5, $rst->fac_fecha_emision, 0, 0, 'L');
            $pdf->Cell(30, 5, $rst->fac_numero, 0, 0, 'L');
            $pdf->Cell(60, 5, substr($rst->concepto, 0, 30), 0, 0, 'L');
            $pdf->Cell(30, 5, substr($rst->forma, 0, 30), 0, 0, 'L');
            $pdf->Cell(30, 5, number_format($debito1,  $dec), 0, 0, 'R');
            $pdf->Cell(30, 5, number_format($credito1,  $dec), 0, 0, 'R');
            $pdf->Cell(30, 5, number_format($saldo1,  $dec), 0, 0, 'R');
            $pdf->Cell(35, 5, number_format($vencido1,  $dec), 0, 0, 'R');
            $pdf->Ln();
            $tdeb+=round($debito1,$dec);
            $tcre+=round($credito1,$dec);
            $tvc+=round($vencido1,$dec);
        }
        $tsal = $tdeb - $tcre;


        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(110, 5, '', '', 0, 'R');
        $pdf->Cell(35, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(30, 5, number_format($tdeb,  $dec), 'T', 0, 'R');
        $pdf->Cell(30, 5, number_format($tcre,  $dec), 'T', 0, 'R');
        $pdf->Cell(30, 5, number_format($tsal,  $dec), 'T', 0, 'R');
        $pdf->Cell(35, 5, number_format($tvc,  $dec), 'T', 0, 'R');
        $pdf->Ln();

        $pdf->Output('estado_cliente_cxc.pdf' , 'I' ); 

    } 

    

}
