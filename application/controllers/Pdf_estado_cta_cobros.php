<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_estado_cta_cobros extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('pdf_estado_cta_cobros_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
		$this->load->model('cliente_model');
        $this->load->model('factura_model');
	}


	 public function index($id,$opc_id,$tipo,$fec_2){
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
	    $cliente = $this->cliente_model->lista_un_cliente_cedula($id);
        $cns = $this->pdf_estado_cta_cobros_model->lista_facturas_cliente_2($cliente->cli_id,$rst_cja->emp_id,$fec_2);
        $fec1='2000-01-01';
        $fec2=date('Y-m-d');
       
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
        $pdf->Cell(190, 5, utf8_decode("TELÉFONO: " ). $emisor->emp_telefono, 0, 0, 'L');
        $pdf->SetX(0);
        $pdf->Cell(190, 5, $pdf->Image('./imagenes/'.$emisor->emp_logo, 175, 4, 25), 0, 0, 'R');
        $pdf->setY(30);

        $pdf->setY(25); 
        $pdf->SetFont('Calibri-bold', '', 14);
        $pdf->Cell(200, 5, "CUENTAS POR COBRAR ", 0, 0, 'C');
        $pdf->Ln(); 
        $pdf->SetFont('Calibri-light', '', 13);   
        $pdf->Cell(200, 5, "Estado de cuenta cliente al " . $fec_2, 0, 0, 'C');
        $pdf->Ln();
        

        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(20, 5, "CLIENTE: ", 0, 0, 'L');
        $pdf->SetFont('Calibri-light', '', 11);
        $pdf->Cell(62, 5, utf8_decode($cliente->cli_raz_social), 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(20, 5, "RUC: " , 0, 0, 'L');
        $pdf->SetFont('Calibri-light', '', 11);
        $pdf->Cell(50, 5, $cliente->cli_ced_ruc, 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(20, 5, utf8_decode("TELÉFONO:"), 0, 0, 'L');
        $pdf->SetFont('Calibri-light', '', 11);
        $pdf->Cell(62, 5,  $cliente->cli_telefono, 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(20, 5, utf8_decode("DIRECCIÓN: ") , 0, 0, 'L');
        $pdf->SetFont('Calibri-light', '', 11);
        $pdf->Cell(62, 5,utf8_decode($cliente->cli_calle_prin), 0, 0, 'L');
        $pdf->Ln();

        $pdf->SetFont('Calibri-bold', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();
        $pdf->Cell(10, 5, utf8_decode("N°"), 1, 0, 'C');
        $pdf->Cell(30, 5, "FACTURA", 1, 0, 'C');
        $pdf->Cell(20, 5, "F.EMISION", 1, 0, 'C');
        $pdf->Cell(20, 5, "VALOR", 1, 0, 'C');
        $pdf->Cell(20, 5, "V.ABONADO", 1, 0, 'C');
        $pdf->Cell(20, 5, "SALDO", 1, 0, 'C');
        $pdf->Cell(20, 5, "PLAZO", 1, 0, 'C');
        $pdf->Cell(25, 5, "VENCIMIENTO", 1, 0, 'C');
        $pdf->Cell(25, 5, "ESTADO", 1, 0, 'C');
        $pdf->Ln();

        $n=0;
        $sal=0;
        $tot=0;
        $t_general=0;
        $t_abonado=0;
        foreach ($cns as $rst) {
            $saldo_fac=round($rst->fac_total_valor,$dec)-round($rst->credito,$dec);

            if($saldo_fac>0){
                $n++;
                
                $d_plazo = (strtotime($rst->pag_fecha_v) - strtotime($rst->fac_fecha_emision)) / 86400;
                    $d_plazo = abs($d_plazo);
                    $d_plazo = floor($d_plazo);
                    if($d_plazo > 120){
                        $d_plazo = 'mas de 120';
                    }
                $sal=round($rst->fac_total_valor,$dec)-round($rst->credito,$dec);    
                $exp=explode('-',$rst->fac_numero);
                $num=round($exp[0]).'-'.round($exp[1]).'-'.round($exp[2]);    
                $pdf->SetFont('Calibri-bold', '', 10);
                $pdf->Cell(10, 5, $n, 1, 0, 'C');
                $pdf->Cell(30, 5, $num, 1, 0, 'C');
                $pdf->Cell(20, 5, $rst->fac_fecha_emision, 1, 0, 'C');
                $pdf->Cell(20, 5, number_format($rst->fac_total_valor, $dec), 1, 0, 'R');
                $pdf->Cell(20, 5, number_format($rst->credito, $dec), 1, 0, 'R');
                $pdf->Cell(20, 5, number_format($sal, $dec), 1, 0, 'R');
                $pdf->Cell(20, 5, "", 1, 0, 'C');
                $pdf->Cell(25, 5, "", 1, 0, 'C');
                $pdf->Cell(25, 5, "", 1, 0, 'C');
                $pdf->Ln();
                $t_abonado+=round($rst->credito, $dec);
                $pdf->SetFont('Calibri-bold', '', 10);
                $cns_pagos=$this->factura_model->lista_pagos_factura($rst->fac_id); 
                $m=0;
                $total=0;
                $abonado=0;
                $fec_act=date('Y-m-d');
                $tot=$rst->credito;
                $cobro=0;
                $sald=0;

                foreach ($cns_pagos as $rst_doc1) {
                    $m++;
                    $cnt=round($rst_doc1->pag_cant,$dec);
                    $d_plazo = (strtotime($rst_doc1->pag_fecha_v) - strtotime($rst->fac_fecha_emision)) / 86400;
                    $d_plazo = abs($d_plazo);
                    $d_plazo = floor($d_plazo);
                    if($d_plazo > 120){
                        $d_plazo = 'mas de 120';
                    }


                    if(round($tot,$dec)>=round($rst_doc1->pag_cant,$dec)){
                        $cobro=$rst_doc1->pag_cant;
                        $sald=0;
                        $tot=round($tot,$dec)-round($rst_doc1->pag_cant,$dec);
                    }else{
                        $cobro=round($tot,$dec);
                        $sald=round($rst_doc1->pag_cant,$dec)-round($tot,$dec);
                        $tot=0;
                    }

                    if ($sald != 0) {
                        if($rst_doc1->pag_fecha_v>$fec_act){
                            $estado='CORRIENTE';
                        }else{
                            $estado='VENCIDO';
                        }
                        $pdf->SetFont('Calibri-light', '', 10);
                        $pdf->Cell(10, 5, "", 1, 0, 'C');
                        $pdf->Cell(30, 5, utf8_decode("PAGO $m"), 1, 0, 'C');
                        $pdf->Cell(20, 5, "", 1, 0, 'C');
                        $pdf->Cell(20, 5, number_format($cnt, $dec), 1, 0, 'R');
                        $pdf->Cell(20, 5, number_format($cobro, $dec), 1, 0, 'R');
                        $pdf->Cell(20, 5, number_format($sald, $dec), 1, 0, 'R');
                        $pdf->Cell(20, 5, $d_plazo, 1, 0, 'C');
                        $pdf->Cell(25, 5, $rst_doc1->pag_fecha_v, 1, 0, 'C');
                        $pdf->Cell(25, 5, $estado, 1, 0, 'C');
                        $pdf->Ln();
                        $t_general+=round($sald,$dec);
                        // $t_abonado+=round($cobro,$dec);

                    }
                }
              
                
            }
        }

        $pdf->Ln();
        $pdf->SetFont('Calibri-bold', '', 10);
        $pdf->Cell(80, 5, 'TOTAL GENERAL ', 1, 0, 'L');
        $pdf->Cell(20, 5, number_format($t_abonado, $dec), 1, 0, 'R');
        $pdf->Cell(20, 5, number_format($t_general, $dec), 1, 0, 'R');
        $pdf->Cell(70, 5, '', 1, 0, 'R');

        


        $nombre_fichero = 'pdfs/estados/estado_cta_cobros_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';

        if (file_exists($nombre_fichero)) {       
        }else{
             $pdf->Output($nombre_fichero , $tipo);      
        }

        


    } 

    

}
