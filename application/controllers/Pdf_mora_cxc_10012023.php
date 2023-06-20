<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_mora_cxc extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('pdf_mora_cxc_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
		$this->load->model('cliente_model');
	}

	

	 public function index($id,$opc_id){
	 	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 	require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','A4',0);
	    
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);
	    $cliente = $this->cliente_model->lista_un_cliente_cedula($id,$rst_cja->emp_id);
	    $rst_vnd = $this->pdf_mora_cxc_model->lista_ultimo_vendedor($cliente->cli_id,$rst_cja->emp_id);
        

        set_time_limit(0);
        
        $pdf->Image('./imagenes/'.$emisor->emp_logo, 6, 4, 35);
        $pdf->SetFont('helvetica', 'B', 14);
       	$pdf->SetTextColor(0, 160, 186);
      
        $pdf->Cell(190, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 5, "RUC: " . $emisor->emp_identificacion, 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 5, "CUENTAS POR COBRAR", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 5, "ESTADO DE CUENTA AL " . date('Y-m-d'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(62, 5, "CLIENTE: " . utf8_decode($cliente->cli_raz_social), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(50, 5, "RUC: " . $cliente->cli_ced_ruc, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(62, 5, "TELEFONO: " . $cliente->cli_telefono, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(62, 5, "DIRECCION: " . utf8_decode($cliente->cli_calle_prin), 0, 0, 'L');
        $pdf->Ln();
        
        $pdf->Cell(75, 5, "VENDEDOR: " . $rst_vnd->vnd_nombre, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(62, 5, "Estimado Cliente");
        $pdf->Ln();
        $pdf->Ln();
       	$pdf->Cell(62, 5, "Se adjunta el detalle de su cuenta");
        $pdf->Ln();
    
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 5, "", 'T', 0, 'C');
        $pdf->Cell(50, 5, "VENCIDOS", 'T', 0, 'C');
        $pdf->Cell(40, 5, "TASA 14%", 'T', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(35, 5, "FACTURA", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "FECHA", 'TB', 0, 'C');
        $pdf->Cell(20, 5, "VALOR", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "DIAS VENC.", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "INTERES POR MORA", 'TB', 0, 'C');
        $pdf->Ln();

        $fec_act = date("Y-m-d");
        $fec_inicial = date("2015-01-01");
        $dias = 30;
        
        $vencidos = $this->pdf_mora_cxc_model->suma_pagos_vencidos($cliente->cli_id, $fec_inicial, $fec_act,$rst_cja->emp_id);

        $por_vencer = $this->pdf_mora_cxc_model->lista_pag_porvencer($cliente->cli_id, $fec_act,$rst_cja->emp_id); // por vencer

        $n = 0;
        $tot1=0;
        $totmora=0;
        $tot6=0;
        $tot_ven_30t=0;
        $tot_ven_60t=0;
        $tot_ven_90t=0;
        $tot_ven_120t=0;
        $tot_ven_m120t=0;

        foreach($vencidos as $rst_doc ) {
            $n++;
            $credito=0;
            if(!empty($rst_doc->credito)){
            	$credito=$rst_doc->credito;
            }

            $tot_ven_30 = round($rst_doc->fac_total_valor,$dec) - round($credito,$dec);
            if ($tot_ven_30 != 0) {
                $dias_v=(strtotime($fec_act) - strtotime($rst_doc->fac_fecha_emision)) / 86400;

                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(35, 5, utf8_decode($rst_doc->fac_numero), 0, 0, 'C');
                $pdf->Cell(30, 5, $rst_doc->fac_fecha_emision, 0, 0, 'C');
                $pdf->Cell(20, 5, number_format($tot_ven_30, $dec), 0, 0, 'R');
                $pdf->Cell(25, 5, $dias_v, 0, 0, 'C');
                $pdf->Cell(25, 5, number_format(((strtotime($fec_act) - strtotime($rst_doc->fac_fecha_emision))/ 86400)*0.14* $tot_ven_30/360, $dec) , 0, 0, 'R');
                $pdf->Ln();
                $totmora+=((strtotime($fec_act) - strtotime($rst_doc->fac_fecha_emision))/ 86400)*0.14* $tot_ven_30/360;
                $tot1+=round($tot_ven_30,$dec);

                
                if($dias_v>120){
                    $tot_ven_m120t+=round($tot_ven_30,$dec);  
                }else if($dias_v>90 && $dias_v<=120){
                    $tot_ven_120t+=round($tot_ven_30,$dec);
                }else if($dias_v>60 && $dias_v<=90){
                    $tot_ven_90t+=round($tot_ven_30,$dec);
                }else if($dias_v>30 && $dias_v<=60){
                    $tot_ven_60t+=round($tot_ven_30,$dec);
                }else if($dias_v>=0 && $dias_v<=30){
                    $tot_ven_30t+=round($tot_ven_30,$dec);
                }

                $tot_ven_30 = 0;

            }
        }

        $total = $tot1 ;
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(35, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(20, 5, number_format($total, $dec), 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
        $pdf->Cell(25, 5, number_format($totmora, $dec), 'T', 0, 'R');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(135, 5, "CORRIENTE", 'T', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(35, 5, "FACTURA", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "FECHA", 'TB', 0, 'C');
        $pdf->Cell(20, 5, "VALOR", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "PLAZO", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "VENCIMIENTO", 'TB', 0, 'C');
        $pdf->Ln();
        foreach($por_vencer as $rst_doc5) {
            $n++;
            $cedito5=0;
            if(!empty($rst_doc5->credito)){
            	$cedito5=$rst_doc5->credito;
            }
            $tot_por_vencert = round($rst_doc5->fac_total_valor,$dec) - round($rst_doc5->credito,$dec);
            $d_plazo = (strtotime($rst_doc5->pag_fecha_v) - strtotime($rst_doc5->fac_fecha_emision)) / 86400;
            $d_plazo = abs($d_plazo);
            $d_plazo = floor($d_plazo);
            if ($d_plazo > 120) {
                $d_plazo = 'mas de 120';
            }
            if ($tot_por_vencert != 0) {
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(35, 5, utf8_decode($rst_doc5->fac_numero), 0, 0, 'C');
                $pdf->Cell(30, 5, $rst_doc5->fac_fecha_emision, 0, 0, 'C');
                $pdf->Cell(20, 5, number_format($tot_por_vencert, $dec), 0, 0, 'R');
                $pdf->Cell(25, 5, $d_plazo, 0, 0, 'C');
                $pdf->Cell(25, 5, $rst_doc5->pag_fecha_v, 0, 0, 'C');
                $pdf->Ln();
                $tot6+=round($tot_por_vencert,$dec);
                $tot_por_vencert = 0;
            }
        }
        $totalc = $tot6;
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(35, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(20, 5, number_format($totalc, $dec), 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
        $pdf->Cell(25, 5, '', 'T', 0, 'R');
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
        $pdf->Cell(30, 5, 'INTERES POR MORA', 'LB', 0, 'L');
        $pdf->Cell(25, 5, number_format($totmora, $dec), 'BR', 0, 'R');
        $pdf->Ln();
        $pdf->Cell(80, 5, '', '', 0, 'R');
        $pdf->Cell(30, 5, ' TOTAL', 'BL', 0, 'L');
        $pdf->Cell(25, 5, number_format(($tot_ven_30t + $tot_ven_60t + $tot_ven_90t + $tot_ven_120t + $tot_ven_m120t + $totalc+$totmora), $dec), 'BR', 0, 'R');
    	$pdf->Ln();
   		$pdf->Ln();
    	$pdf->Ln();
   		$pdf->Ln();
        $pdf->Cell(30, 5, 'RECIBIDO POR______________________ FIRMA   _______________________ FECHA___________', '', 0, '');

        $pdf->Output('mora_cxc.pdf' , 'I' ); 

    } 

    

}
