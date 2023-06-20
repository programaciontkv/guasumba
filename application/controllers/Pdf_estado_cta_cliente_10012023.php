<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_estado_cta_cliente extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('pdf_estado_cta_cliente_model');
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
	    $pdf->AddPage('P','A4',0);
	    set_time_limit(0);

	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
        
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);
	    $cliente = $this->cliente_model->lista_un_cliente_cedula($id);
        $fec1='2000-01-01';
        $fec2=date('Y-m-d');
       
        
        $pdf->Image('./imagenes/'.$emisor->emp_logo, 6, 4, 35);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetTextColor(0, 160, 186);
        $pdf->Cell(180, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(180, 5, "RUC: " . $emisor->emp_identificacion, 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(180, 5, "CUENTAS POR COBRAR", 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(180, 5, "ESTADO DE CUENTAS CLIENTE AL ". $fec2 , 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);
       
        $pdf->Cell(150, 5, "CLIENTE: " . utf8_decode($cliente->cli_raz_social), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(150, 5, "RUC: " .utf8_decode($cliente->cli_ced_ruc), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(150, 5, "TELEFONO: " . $cliente->cli_telefono, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(150, 5, "DIRECCION: " . utf8_decode($cliente->cli_calle_prin), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(165, 5, "VENCIDOS", 'T', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(35, 5, "FACTURA", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "FECHA", 'TB', 0, 'C');
        $pdf->Cell(20, 5, "VALOR", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "PLAZO", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "VENCIMIENTO", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "DIAS VENCIDOS", 'TB', 0, 'C');
        $pdf->Ln();
        
        $fec_act = date("Y-m-d");
        $fec_inicial = date("2015-01-01");
        $dias = 30;

        $vencidos = $this->pdf_estado_cta_cliente_model->lista_pagos_vencidos($cliente->cli_id, $fec_inicial, $fec_act,$rst_cja->emp_id);

        $por_vencer = $this->pdf_estado_cta_cliente_model->lista_pag_porvencer($cliente->cli_id, $fec_act,$rst_cja->emp_id); // por vencer

        $n = 0;
        $tot1=0;
        $totmora=0;
        $tot6=0;
        $tot_ven_30t=0;
        $tot_ven_60t=0;
        $tot_ven_90t=0;
        $tot_ven_120t=0;
        $tot_ven_m120t=0;
        $grup="";

        foreach($vencidos as $rst_doc ) {
            $n++;
             if($grup==$rst_doc->fac_id){
               if(round($tot_ven_30,$dec)>round($rst_doc->pag_cant,$dec)){
                    $saldo_v=0;
                    $tot_ven_30=round($tot_ven_30,$dec)-round($rst_doc->pag_cant,$dec);
               }else{
                   $saldo_v=round($rst_doc->pag_cant,$dec)-round($tot_ven_30,$dec);
                   $tot_ven_30=0;
               }    
               
            }else{
                $rst_fac=$this->pdf_estado_cta_cliente_model->suma_pagos1($rst_doc->fac_id, $fec_inicial, $fec_act);
                $tot_ven_30=round($rst_fac->credito,$dec);
                if(round($rst_fac->credito,$dec)>=round($rst_doc->pag_cant,$dec)){
                    $saldo_v=0;
                    $tot_ven_30=round($tot_ven_30,$dec)-round($rst_doc->pag_cant,$dec);
                }else{
                    $saldo_v=round($rst_doc->pag_cant,$dec)-round($rst_fac->credito,$dec);
                    $tot_ven_30=0;
                }
            }

            

            if (round($saldo_v,$dec) != 0) {
                $plazo=(strtotime($fec_act)-strtotime($rst_doc->pag_fecha_v))/86400;
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(35, 5, utf8_decode($rst_doc->fac_numero), 0, 0, 'C');
                $pdf->Cell(30, 5, $rst_doc->fac_fecha_emision, 0, 0, 'C');
                $pdf->Cell(20, 5, number_format($saldo_v, $dec), 0, 0, 'R');
                $pdf->Cell(25, 5, (strtotime($rst_doc->pag_fecha_v) - strtotime($rst_doc->fac_fecha_emision)) / 86400, 0, 0, 'C');
                $pdf->Cell(30, 5, $rst_doc->pag_fecha_v, 0, 0, 'C');
                $pdf->Cell(25, 5, $plazo, 0, 0, 'C');
                $pdf->Ln();
                $tot1+=round($saldo_v,$dec);
                
                if($plazo>120){
                    $tot_ven_m120t+=round($saldo_v,$dec);  
                }else if($plazo>90 && $plazo<=120){
                    $tot_ven_120t+=round($saldo_v,$dec);
                }else if($plazo>60 && $plazo<=90){
                    $tot_ven_90t+=round($saldo_v,$dec);
                }else if($plazo>30 && $plazo<=60){
                    $tot_ven_60t+=round($saldo_v,$dec);
                }else if($plazo>=0 && $plazo<=30){
                    $tot_ven_30t+=round($saldo_v,$dec);
                }

                $saldo_v = 0;

            }

            $grup=$rst_doc->fac_id;
        }

        $total = $tot1 ;
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(35, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(20, 5, number_format($total, $dec), 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
        $pdf->Cell(30, 5, '', 'T', 0, 'R');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(165, 5, "CORRIENTE", 'T', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(35, 5, "FACTURA", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "FECHA", 'TB', 0, 'C');
        $pdf->Cell(20, 5, "VALOR", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "PLAZO", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "VENCIMIENTO", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "DIAS POR VENCER", 'TB', 0, 'C');
        $pdf->Ln();
        $tot_por_vencert=0;
        $grup5='';
        foreach($por_vencer as $rst_doc5) {
            
             if($grup5==$rst_doc5->fac_id){
               if(round($tot_por_vencert,$dec)>round($rst_doc5->pag_cant,$dec)){
                    $saldo_pv=0;
                    $tot_por_vencert=round($tot_por_vencert,$dec)-round($rst_doc5->pag_cant,$dec);
               }else{
                   $saldo_pv=round($rst_doc5->pag_cant,$dec)-round($tot_por_vencert,$dec);
                   $tot_por_vencert=0;
               }    
               
            }else{
                $rst_fac5=$this->pdf_estado_cta_cliente_model->suma_pagos1($rst_doc5->fac_id, $fec_inicial, $fec_act);
                $tot_por_vencert=round($rst_fac5->credito,$dec);
                if(round($rst_fac5->credito,$dec)>=round($rst_doc5->pag_cant,$dec)){
                    $saldo_pv=0;
                    $tot_por_vencert=round($tot_por_vencert,$dec)-round($rst_doc5->pag_cant,$dec);
                }else{
                    $saldo_pv=round($rst_doc5->pag_cant,$dec)-round($rst_fac5->credito,$dec);
                    $tot_por_vencert=0;
                }
            }

            $d_plazo = (strtotime($rst_doc5->pag_fecha_v) - strtotime($rst_doc5->fac_fecha_emision)) / 86400;
            $d_plazo = abs($d_plazo);
            $d_plazo = floor($d_plazo);
            if ($d_plazo > 120) {
                $d_plazo = 'mas de 120';
            }
            if (round($saldo_pv,$dec) != 0) {
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(35, 5, utf8_decode($rst_doc5->fac_numero), 0, 0, 'C');
                $pdf->Cell(30, 5, $rst_doc5->fac_fecha_emision, 0, 0, 'C');
                $pdf->Cell(20, 5, number_format($saldo_pv, $dec), 0, 0, 'R');
                $pdf->Cell(30, 5, $d_plazo, 0, 0, 'C');
                $pdf->Cell(25, 5, $rst_doc5->pag_fecha_v, 0, 0, 'C');
                $pdf->Cell(25, 5,  (strtotime($rst_doc5->pag_fecha_v) - strtotime($fec_act)) / 86400, 0, 0, 'C');
                 
                $pdf->Ln();
                $tot6+=round($saldo_pv,$dec);
                $saldo_pv = 0;
            }
            $grup5=$rst_doc5->fac_id;
        }
        $totalc = $tot6;
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(35, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(20, 5, number_format($totalc, $dec), 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
        $pdf->Cell(30, 5, '', 'T', 0, 'R');
        $pdf->Ln();
        $pdf->Ln();
        
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'CORRIENTE', 'LT', 0, 'L');
        $pdf->Cell(25, 5, number_format($totalc, $dec), 'TR', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'VENCIDO 1 A 30 DIAS', 'L', 0, 'L');
        $pdf->Cell(25, 5, number_format($tot_ven_30t, $dec), 'R', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'VENCIDO 31 A 60 DIAS', 'L', 0, 'L');
        $pdf->Cell(25, 5, number_format($tot_ven_60t, $dec), 'R', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'VENCIDO 61 A 90 DIAS', 'L', 0, 'L');
        $pdf->Cell(25, 5, number_format($tot_ven_90t, $dec), 'R', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'VENCIDO 91 A 120 DIAS', 'L', 0, 'L');
        $pdf->Cell(25, 5, number_format($tot_ven_120t, $dec), 'R', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'MAS DE 120 DIAS', 'LB', 0, 'L');
        $pdf->Cell(25, 5, number_format($tot_ven_m120t, $dec), 'BR', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, ' TOTAL', 'BL', 0, 'L');
        $pdf->Cell(25, 5, number_format(($tot_ven_30t + $tot_ven_60t + $tot_ven_90t + $tot_ven_120t + $tot_ven_m120t + $totalc), $dec), 'BR', 0, 'R');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(187, 5, 'CHEQUES', 'LTR', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(50, 5, 'FACTURA', 'LTRB', 0, 'C');
        $pdf->Cell(50, 5, 'BANCO', 'TRB', 0, 'C');
        $pdf->Cell(21, 5, '# CHEQUE', 'TRB', 0, 'C');
        $pdf->Cell(23, 5, 'FECHA CHEQUE', 'TRB', 0, 'C');
        $pdf->Cell(21, 5, 'VALOR', 'TRB', 0, 'C');
        $pdf->Cell(22, 5, 'SALDO', 'TRB', 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 8);
        $cns_chq = $this->pdf_estado_cta_cliente_model->lista_cheques_cliente($cliente->cli_id,$rst_cja->emp_id);
        $c = 0;
        $t_mont=0;
        $ts=0;
        foreach ($cns_chq as $rst_chq) {
            $saldo = round($rst_chq->chq_monto,$dec) - round($rst_chq->chq_cobro,$dec);
            if (round($saldo,$dec) != 0) {
                $c++;
                $pdf->Cell(50, 5, $rst_chq->chq_concepto, 'LTRB', 0, 'L');
                $pdf->Cell(50, 5, $rst_chq->chq_banco, 'TRB', 0, 'C');
                $pdf->Cell(21, 5, $rst_chq->chq_numero, 'TRB', 0, 'C');
                $pdf->Cell(23, 5, $rst_chq->chq_fecha, 'TRB', 0, 'C');
                $pdf->Cell(21, 5, number_format($rst_chq->chq_monto, 2), 'TRB', 0, 'R');
                $pdf->Cell(22, 5, number_format($saldo, 2), 'TRB', 0, 'R');
                $pdf->Ln();
                $t_mont+= round($rst_chq->chq_monto,$dec);
                $ts+= round($saldo,$dec);
            }
        }
        
        $pdf->SetFont('helvetica', 'B',8);
        $pdf->Cell(121, 5, '', '', 0, 'R');
        $pdf->Cell(23, 5, 'TOTAL ', 'RBL', 0, 'R');
        $pdf->Cell(21, 5, number_format($t_mont, 2), 'RBL', 0, 'R');
        $pdf->Cell(22, 5, number_format($ts, 2), 'RBL', 0, 'R');


        $pdf->Output('estado_cta_cliente.pdf' , 'I' ); 

    } 

    

}
