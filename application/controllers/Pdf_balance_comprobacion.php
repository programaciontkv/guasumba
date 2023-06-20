<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_balance_comprobacion extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('rep_contables_model');
		$this->load->model('pdf_balance_comprobacion_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}

	

	public function index($desde,$hasta,$niv,$opc_id){
	 	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 	require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','A4',0);
        $pdf->AliasNbPages();
        $pdf->AddFont('Calibri-light','');//$pdf->SetFont('Calibri-Light', '', 9);
        $pdf->AddFont('Calibri-bold','');//$pdf->SetFont('Calibri-bold', '', 9);
	    
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        

        set_time_limit(0);
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

        $pdf->SetFont('Calibri-bold', '', 14);
        $pdf->Cell(200, 5, "BALANCE DE COMPROBACION", 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 13);
        $pdf->Cell(200, 5, "DE  " . $desde . '  AL ' . $hasta, 0, 0, 'C');
        $pdf->Ln();
        

        $pdf->Cell(162, 5, "");
		$pdf->SetFont('Calibri-bold', '', 10);
		$pdf->Cell(40, 5, "SALDO", 'TBLR', 0, 'C');
		$pdf->Ln();
		$pdf->Cell(20, 5, "CODIGO", 'TBLR', 0, 'C');
		$pdf->Cell(102, 5, "CUENTA", 'TBLR', 0, 'C');
		$pdf->Cell(20, 5, "DEBE", 'TBLR', 0, 'C');
		$pdf->Cell(20, 5, "HABER", 'TBLR', 0, 'C');
		$pdf->Cell(20, 5, "DEUDOR", 'TBLR', 0, 'C');
		$pdf->Cell(20, 5, "ACREEDOR", 'TBR', 0, 'C');
		$pdf->Ln();
        

        
        $cns=$this->pdf_balance_comprobacion_model->lista_cuentas_fecha($desde, $hasta,$rst_cja->emp_id);

        $n = 0;
        $g = 0;
        $g2 = 0;
        $g3 = 0;
        $g4 = 0;
        $g5 = 0;
        $d2 = '';
        $d3 = '';
        $d4 = '';
        $d5 = '';
        $deudor = 0;
        $acreedor = 0;
        $total_debe = 0;
        $total_haber = 0;
        $total_deudor = 0;
        $total_acreedor = 0;
        foreach ($cns as $rst) {
            $pdf->SetFont('Calibri-light', '', 9);
        	$d = explode('.', $rst->con_concepto_debe);
            $d1 = $d[0] . '.';
            $d11 = $d[0];
            if ($niv == 1) {
                if ($g != $d1) {
                    $rst1 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d1, $d11);
                    $sm = $this->pdf_balance_comprobacion_model->lista_balance_general1($d11, $desde, $hasta, $rst_cja->emp_id);
                    $pdf->Cell(20, 5, $rst1->pln_codigo, 'LR', 0, 'L');
                    $pdf->SetFont('Calibri-light', '', 10);
                    $pdf->Cell(102, 5, $rst1->pln_descripcion, 'LR', 0, 'L');
                    $pdf->Cell(20, 5, number_format($sm->debe, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($sm->haber, $dec), 'LR', 0, 'R');
                    $debe = round($sm->debe,$dec);
                    $haber = round($sm->haber,$dec);
                    $total = round($debe,$dec) - round($haber,$dec);
                    if (round($total,$dec) > 0) {
                        $deudor = round($total,$dec);
                    }
                    if (round($total,$dec) < 0) {
                        $acreedor = round($total,$dec);
                    }
                    $pdf->Cell(20, 5, number_format($deudor, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($acreedor, $dec), 'LR', 0, 'R');
                    $total_debe+= round($debe,$dec);
                    $total_haber+= round($haber,$dec);
                    $total_deudor+= round($deudor,$dec);
                    $total_acreedor+= round($acreedor,$dec);
                    $pdf->Ln();
                    $deudor = 0;
                    $acreedor = 0;
                }
            }
            if ($niv == 2) {
                $d2 = $d[0] . '.' . $d[1] . '.';
                $d21 = $d[0] . '.' . $d[1];
                if ($g2 != $d2) {
                    $rst2 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d2, $d21);
                    $sm2 = $this->pdf_balance_comprobacion_model->lista_balance_general1($d21, $desde, $hasta, $rst_cja->emp_id);
                    $pdf->Cell(20, 5, $rst2->pln_codigo, 'LR', 0, 'L');
                    $pdf->SetFont('Calibri-light', '', 10);
                    $pdf->Cell(102, 5, $rst2->pln_descripcion, 'LR', 0, 'L');
                    $pdf->Cell(20, 5, number_format($sm2->debe, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($sm2->haber, $dec), 'LR', 0, 'R');
                    $debe = round($sm2->debe, $dec);
                    $haber = round($sm2->haber, $dec);
                    $total = round($debe, $dec) - round($haber, $dec);
                    if ($total > 0) {
                        $deudor = round($total, $dec);
                    }
                    if ($total < 0) {
                        $acreedor = round($total, $dec);
                    }
                    $pdf->Cell(20, 5, number_format($deudor, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($acreedor, $dec), 'LR', 0, 'R');
                    $total_debe+= round($debe, $dec);
                    $total_haber+= round($haber, $dec);
                    $total_deudor+= round($deudor, $dec);
                    $total_acreedor+= round($acreedor, $dec);
                    $pdf->Ln();
                    $deudor = 0;
                    $acreedor = 0;
                }
            }
            if ($niv == 3) {
                $d3 = $d[0] . '.' . $d[1] . '.' . $d[2] . '.';
                $d31 = $d[0] . '.' . $d[1] . '.' . $d[2];
                if ($g3 != $d3) {
                    $rst3 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d3, $d31);
                    $sm3 = $this->pdf_balance_comprobacion_model->lista_balance_general1($d31, $desde, $hasta, $rst_cja->emp_id);
                    $pdf->Cell(20, 5, $rst3->pln_codigo, 'LR', 0, 'L');
                    $pdf->SetFont('Calibri-light', '', 10);
                    $pdf->Cell(102, 5, $rst3->pln_descripcion, 'LR', 0, 'L');
                    $pdf->Cell(20, 5, number_format($sm3->debe, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($sm3->haber, $dec), 'LR', 0, 'R');
                    $debe = round($sm3->debe, $dec);
                    $haber = round($sm3->haber, $dec);
                    $total = round($debe, $dec) - round($haber, $dec);
                    if ($total > 0) {
                        $deudor = round($total, $dec);
                    }
                    if ($total < 0) {
                        $acreedor = round($total, $dec);
                    }
                    $pdf->Cell(20, 5, number_format($deudor, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($acreedor, $dec), 'LR', 0, 'R');
                    $total_debe+= round($debe, $dec);
                    $total_haber+= round($haber, $dec);
                    $total_deudor+= round($deudor, $dec);
                    $total_acreedor+= round($acreedor, $dec);
                    $pdf->Ln();
                    $deudor = 0;
                    $acreedor = 0;
                }
            }
            if ($niv == 4) {
                $d4 = $d[0] . '.' . $d[1] . '.' . $d[2] . '.' . $d[3] . '.';
                $d41 = $d[0] . '.' . $d[1] . '.' . $d[2] . '.' . $d[3];
                if ($g4 != $d4) {
                    $rst4 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d4,$d41);
                    $sm4 = $this->pdf_balance_comprobacion_model->lista_balance_general1($d4, $desde, $hasta,$rst_cja->emp_id);
                    $pdf->Cell(20, 5, $rst4->pln_codigo, 'LR', 0, 'L');
                    $pdf->SetFont('Calibri-light', '', 10);
                    $pdf->Cell(102, 5, $rst4->pln_descripcion, 'LR', 0, 'L');
                    $pdf->Cell(20, 5, number_format($sm4->debe, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($sm4->haber, $dec), 'LR', 0, 'R');
                    $debe = round($sm4->debe, $dec);
                    $haber = round($sm4->haber, $dec);
                    $total = round($debe, $dec) - round($haber, $dec);
                    if ($total > 0) {
                        $deudor = round($total, $dec);
                    }
                    if ($total < 0) {
                        $acreedor = round($total, $dec);
                    }
                    $pdf->Cell(20, 5, number_format($deudor, $dec), 'LR', 0, 'R');
                    $pdf->Cell(20, 5, number_format($acreedor, $dec), 'LR', 0, 'R');
                    $total_debe+= round($debe, $dec);
                    $total_haber+= round($haber, $dec);
                    $total_deudor+= round($deudor, $dec);
                    $total_acreedor+= round($acreedor, $dec);
                    $pdf->Ln();
                    $deudor = 0;
                    $acreedor = 0;
                }

            }

            if ($niv == 5) {
                $rst_suma = $this->pdf_balance_comprobacion_model->lista_suma_cuentas($rst->con_concepto_debe, $desde, $hasta,$rst_cja->emp_id);
                $rst_cue = $this->pdf_balance_comprobacion_model->lista_una_cuenta($rst->con_concepto_debe,$rst->con_concepto_debe);
                $pdf->Cell(20, 5, $rst->con_concepto_debe, 'LR', 0, 'L');
                $pdf->SetFont('Calibri-light', '', 10);     
                $pdf->Cell(102, 5,"". $rst_cue->pln_descripcion, 'LR', 0, 'L');
                $pdf->Cell(20, 5, number_format($rst_suma->debe, $dec), 'LR', 0, 'R');
                $pdf->Cell(20, 5, number_format($rst_suma->haber, $dec), 'LR', 0, 'R');
                $debe = round($rst_suma->debe, $dec);
                $haber = round($rst_suma->haber, $dec);
                $total = round($debe, $dec) - round($haber, $dec);
                if ($total > 0) {
                    $deudor = round($total, $dec);
                }
                if ($total < 0) {
                    $acreedor = round($total, $dec);
                }
                $pdf->Cell(20, 5, number_format($deudor, $dec), 'LR', 0, 'R');
                $pdf->Cell(20, 5, number_format($acreedor, $dec), 'LR', 0, 'R');
                $total_debe+= round($debe, $dec);
                $total_haber+= round($haber, $dec);
                $total_deudor+= round($deudor, $dec);
                $total_acreedor+= round($acreedor, $dec);
                $pdf->Ln();
            }

            $n++;
            $g = $d1;
	        $g2 = $d2;
	        $g3 = $d3;
	        $g4 = $d4;
	        $total = 0;
            $deudor = 0;
            $acreedor = 0;
		}

		$pdf->SetFont('Calibri-bold', '', 9);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(25, 5, '', 'TBL', 0, 'R', true);
        $pdf->Cell(97, 5, 'SUMA TOTAL ', 'TBR', 0, 'C', true);
        if ($niv == 1 || $niv == 2 || $niv == 3 || $niv == 4 || $niv == 5) {
            $pdf->Cell(20, 5, number_format($total_debe, $dec), 'TBR', 0, 'R', true);
            $pdf->Cell(20, 5, number_format($total_haber, $dec), 'TBR', 0, 'R', true);
            $pdf->Cell(20, 5, number_format($total_deudor, $dec), 'TBR', 0, 'R', true);
            $pdf->Cell(20, 5, number_format($total_acreedor, $dec), 'TBR', 0, 'R', true);
            $pdf->Ln();
        }

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'PREPARADO', 'T', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'REVISADO', 'T', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'AUTORIZADO', 'T', 0, 'C');

        $pdf->Output('balance_comprobacion.pdf' , 'I' ); 

    }

    public function excel($desde,$hasta,$niv,$opc_id){
        $rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
        $rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
        
        $dc=$this->configuracion_model->lista_una_configuracion('2');
        $dec=$dc->con_valor;
        $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        set_time_limit(0);
        
        $data="<table><tr><td>";
        $data.="</td></tr>";
        $data.="<tr><td colspan='8' align='center'><strong>BALANCE DE COMPROBACION</strong></td></tr>";
        $data.="<tr><td>";
        
        $data.="<tr><td colspan='8'>PERIODO:  $desde AL $hasta</td></tr>";

        

        $data.="<tr>
                    <td colspan='4'>
                    <td colspan='2'><strong>SALDO</strong></td>
                </tr>   
                <tr>
                    <td><strong>CODIGO</strong></td>
                    <td><strong>CUENTA</strong></td>
                    <td><strong>DEBE</strong></td>
                    <td><strong>HABER</strong></td>
                    <td><strong>DEUDOR</strong></td>
                    <td><strong>ACREEDOR</strong></td>
                </tr>";

        $cns=$this->pdf_balance_comprobacion_model->lista_cuentas_fecha($desde, $hasta,$rst_cja->emp_id);

        $n = 0;
        $g = 0;
        $g2 = 0;
        $g3 = 0;
        $g4 = 0;
        $g5 = 0;
        $d2 = '';
        $d3 = '';
        $d4 = '';
        $d5 = '';
        $deudor = 0;
        $acreedor = 0;
        $total_debe = 0;
        $total_haber = 0;
        $total_deudor = 0;
        $total_acreedor = 0;
        foreach ($cns as $rst) {
            $d = explode('.', $rst->con_concepto_debe);
            $d1 = $d[0] . '.';
            $d11 = $d[0];
            if ($niv == 1) {
                if ($g != $d1) {
                    $rst1 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d1, $d11);
                    $sm = $this->pdf_balance_comprobacion_model->lista_balance_general1($d11, $desde, $hasta, $rst_cja->emp_id);
                    $data.="<tr>
                                <td>$rst1->pln_codigo</td>
                                <td>$rst1->pln_descripcion</td>
                                <td>".number_format($sm->debe, $dec)."</td>
                                <td>".number_format($sm->haber, $dec)."</td>";
                    $debe = round($sm->debe,$dec);
                    $haber = round($sm->haber,$dec);
                    $total = round($debe,$dec) - round($haber,$dec);
                    if (round($total,$dec) > 0) {
                        $deudor = round($total,$dec);
                    }
                    if (round($total,$dec) < 0) {
                        $acreedor = round($total,$dec);
                    }
                    $data.="<td>".number_format($deudor, $dec)."</td>
                            <td>".number_format($acreedor, $dec)."</td>
                            </tr>";
                    $total_debe+= round($debe,$dec);
                    $total_haber+= round($haber,$dec);
                    $total_deudor+= round($deudor,$dec);
                    $total_acreedor+= round($acreedor,$dec);
                    $deudor = 0;
                    $acreedor = 0;
                }
            }
            if ($niv == 2) {
                $d2 = $d[0] . '.' . $d[1] . '.';
                $d21 = $d[0] . '.' . $d[1];
                if ($g2 != $d2) {
                    $rst2 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d2, $d21);
                    $sm2 = $this->pdf_balance_comprobacion_model->lista_balance_general1($d21, $desde, $hasta, $rst_cja->emp_id);
                    $data.="<tr>
                                <td>$rst2->pln_codigo</td>
                                <td>$rst2->pln_descripcion</td>
                                <td>".number_format($sm2->debe, $dec)."</td>
                                <td>".number_format($sm2->haber, $dec)."</td>";
                    $debe = round($sm2->debe, $dec);
                    $haber = round($sm2->haber, $dec);
                    $total = round($debe, $dec) - round($haber, $dec);
                    if ($total > 0) {
                        $deudor = round($total, $dec);
                    }
                    if ($total < 0) {
                        $acreedor = round($total, $dec);
                    }
                    $data.="<td>".number_format($deudor, $dec)."</td>
                            <td>".number_format($acreedor, $dec)."</td>
                            </tr>";
                    $total_debe+= round($debe, $dec);
                    $total_haber+= round($haber, $dec);
                    $total_deudor+= round($deudor, $dec);
                    $total_acreedor+= round($acreedor, $dec);
                    $deudor = 0;
                    $acreedor = 0;
                }
            }
            if ($niv == 3) {
                $d3 = $d[0] . '.' . $d[1] . '.' . $d[2] . '.';
                $d31 = $d[0] . '.' . $d[1] . '.' . $d[2];
                if ($g3 != $d3) {
                    $rst3 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d3, $d31);
                    $sm3 = $this->pdf_balance_comprobacion_model->lista_balance_general1($d31, $desde, $hasta, $rst_cja->emp_id);
                    $data.="<tr>
                                <td>$rst3->pln_codigo</td>
                                <td>$rst3->pln_descripcion</td>
                                <td>".number_format($sm3->debe, $dec)."</td>
                                <td>".number_format($sm3->haber, $dec)."</td>";
                    $debe = round($sm3->debe, $dec);
                    $haber = round($sm3->haber, $dec);
                    $total = round($debe, $dec) - round($haber, $dec);
                    if ($total > 0) {
                        $deudor = round($total, $dec);
                    }
                    if ($total < 0) {
                        $acreedor = round($total, $dec);
                    }
                    $data.="<td>".number_format($deudor, $dec)."</td>
                            <td>".number_format($acreedor, $dec)."</td>
                            </tr>";

                    $total_debe+= round($debe, $dec);
                    $total_haber+= round($haber, $dec);
                    $total_deudor+= round($deudor, $dec);
                    $total_acreedor+= round($acreedor, $dec);
                    $deudor = 0;
                    $acreedor = 0;
                }
            }
            if ($niv == 4) {
                $d4 = $d[0] . '.' . $d[1] . '.' . $d[2] . '.' . $d[3] . '.';
                $d41 = $d[0] . '.' . $d[1] . '.' . $d[2] . '.' . $d[3];
                if ($g4 != $d4) {
                    $rst4 = $this->pdf_balance_comprobacion_model->lista_una_cuenta($d4,$d41);
                    $sm4 = $this->pdf_balance_comprobacion_model->lista_balance_general1($d4, $desde, $hasta,$rst_cja->emp_id);
                    $data.="<tr>
                                <td>$rst4->pln_codigo</td>
                                <td>$rst4->pln_descripcion</td>
                                <td>".number_format($sm4->debe, $dec)."</td>
                                <td>".number_format($sm4->haber, $dec)."</td>";
                    $debe = round($sm4->debe, $dec);
                    $haber = round($sm4->haber, $dec);
                    $total = round($debe, $dec) - round($haber, $dec);
                    if ($total > 0) {
                        $deudor = round($total, $dec);
                    }
                    if ($total < 0) {
                        $acreedor = round($total, $dec);
                    }
                    $data.="<td>".number_format($deudor, $dec)."</td>
                            <td>".number_format($acreedor, $dec)."</td>
                            </tr>";
                    $total_debe+= round($debe, $dec);
                    $total_haber+= round($haber, $dec);
                    $total_deudor+= round($deudor, $dec);
                    $total_acreedor+= round($acreedor, $dec);
                    $deudor = 0;
                    $acreedor = 0;
                }

            }

            if ($niv == 5) {
                $rst_suma = $this->pdf_balance_comprobacion_model->lista_suma_cuentas($rst->con_concepto_debe, $desde, $hasta,$rst_cja->emp_id);
                $rst_cue = $this->pdf_balance_comprobacion_model->lista_una_cuenta($rst->con_concepto_debe,$rst->con_concepto_debe);
                $data.="<tr>
                            <td>$rst->con_concepto_debe</td>
                            <td>$rst_cue->pln_descripcion</td>
                            <td>".number_format($rst_suma->debe, $dec)."</td>
                            <td>".number_format($rst_suma->haber, $dec)."</td>";
                $debe = round($rst_suma->debe, $dec);
                $haber = round($rst_suma->haber, $dec);
                $total = round($debe, $dec) - round($haber, $dec);
                if ($total > 0) {
                    $deudor = round($total, $dec);
                }
                if ($total < 0) {
                    $acreedor = round($total, $dec);
                }
                $data.="<td>".number_format($deudor, $dec)."</td>
                        <td>".number_format($acreedor, $dec)."</td>
                        </tr>";
                $total_debe+= round($debe, $dec);
                $total_haber+= round($haber, $dec);
                $total_deudor+= round($deudor, $dec);
                $total_acreedor+= round($acreedor, $dec);
            }

            $n++;
            $g = $d1;
            $g2 = $d2;
            $g3 = $d3;
            $g4 = $d4;
            $total = 0;
            $deudor = 0;
            $acreedor = 0;
        }

        
        if ($niv == 1 || $niv == 2 || $niv == 3 || $niv == 4 || $niv == 5) {
        $data.="<tr>
                    <td colspan='2'><strong>SUMA TOTAL</strong></td>
                    <td><strong>".number_format($total_debe, $dec)."</strong></td>
                    <td><strong>".number_format($total_haber, $dec)."</strong></td>
                    <td><strong>".number_format($total_deudor, $dec)."</strong></td>
                    <td><strong>".number_format($total_acreedor, $dec)."</strong></td>
                </tr>";
        }

        $data.="<tr><td><br><br><br></td></tr>";
        $data.="<tr>
                    <td></td>
                    <td>PREPARADO</td>
                    <td></td>
                    <td>REVISADO</td>
                    <td></td>
                    <td>AUTORIZADO</td>
                </tr>";
        
        $titulo='';
        $file="balance_comprobacion".date('Ymd');
        $this->export_excel->to_excel2($data,$file,$titulo);

    } 

}
