<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_epyg extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('rep_contables_model');
		$this->load->model('pdf_epyg_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}

	

	 public function index($desde,$hasta,$niv,$anio,$mes,$opc_id){

        $periodo="del $desde al $hasta";

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
        $emp_id=$rst_cja->emp_id;
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        $array1 = Array();
        $cns_cuentas = $this->pdf_epyg_model->lista_asientos_epyg($desde, $hasta,$rst_cja->emp_id);
        foreach ($cns_cuentas as $rst_cuentas) {
            if (!empty($rst_cuentas->con_concepto_debe)) {
                array_push($array1, $rst_cuentas->con_concepto_debe);
            }
        }

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
        $pdf->Cell(200, 5, "ESTADO DE RESULTADOS", 0, 0, 'C');
        $pdf->Ln();
        
        $pdf->SetFont('Calibri-light', '', 13);
        $pdf->Cell(200, 5, "Periodo " . $periodo, 0, 0, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(25, 5, "Codigo", 'B', 0, 'C');
        $pdf->Cell(90, 5, "Cuenta", 'B', 0, 'C');
        $pdf->Cell(25, 5, "Parcial", 'B', 0, 'C');
        $pdf->Cell(25, 5, "Total", 'B', 0, 'C');
        $pdf->Cell(25, 5, "% Rendimiento", 'B', 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 10);

        $n = 0;
        $j = 1;
        $g = 0;
        $g2 = 0;
        $g3 = 0;
        $g4 = 0;
        $det = 1;
        $ut = 1;
        $ua = 1;
        $uej = 1;
        $dv = 1;
        $d2 = 0;
        $d3 = 0;
        $d4 = 0;
        if(count($array1)>0){
            while ($n < count($array1)) {
                ///cambia forma de substrig por separador;
                $dt = explode('.', $array1[$n]);
                $d = $dt[0];
                $d1 = $dt[0].'.';
                if ($d > '4') {
                    /// txds= operador para la busqueda con like
                    $txs = $d . '%';
                    $sm = $this->pdf_epyg_model->lista_balance_general1($txs, $desde, $hasta,$emp_id);
                    $tnp = (round($sm->debe1,$dec) + round($sm->debe2,$dec) + round($sm->debe3,$dec)) - (round($sm->haber1,$dec) + round($sm->haber2,$dec) + round($sm->haber3,$dec));
                    $ing = $this->pdf_epyg_model->lista_balance_general1($txs, $desde, $hasta,$emp_id);
                    $tn1 = (round($ing->debe1,$dec) + round($ing->debe2,$dec) + round($ing->debe3,$dec)) - (round($ing->haber1,$dec) + round($ing->haber2,$dec) + round($ing->haber3,$dec));
                    $rd1 = (abs($tn1) * 100) / abs($tnp);
                } else {
                    $tvin = $this->ingresos($dec, $desde, $hasta,$emp_id);
                    $tn1 = ($tvin);
                    $rd1 = (abs($tn1) * 100) / abs($tn1);
                }



                if (($niv == 1 || $niv > 1)&& ($dt[0]!=null)) {
                    if ($g != $d1) {
                        if (($d1 == '5.' || $d1 == '6.' || $d1 == '7.') && $det == 1) {
                            $tvn = $this->ventas_netas($dec, $desde, $hasta,$emp_id);
                            $this->sms($pdf,$tvn, 'VENTAS NETAS', $tvin,$dec);
                            $det = 0;
                        }
                        if ($d1 == '6.' && $ut == 1) {
                            $tub = $this->utilidad_bruta($dec, $desde, $hasta, $tvn,$emp_id);
                            $this->sms($pdf,$tub, 'UTILIDAD BRUTA EN VENTAS', $tvin,$dec);
                            $ut = 0;
                        }
                        if ($d1 == '7.' && $ua == 1) {
                            $tui = $this->utilidad_antes($dec, $desde, $hasta,$emp_id);
                            $this->sms($pdf,$tui, 'UTILIDAD ANTES DE IMPUESTOS Y PARTICIPACIONES', $tvin,$dec);
                            $ua = 0;
                        }

                        
                        if(round($tn1,$dec)!=0){
                            $pdf->Ln();
                            $rst1 = $this->pdf_epyg_model->lista_una_cuenta($d, $d1);
                            $pdf->Cell(25, 5, $rst1->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(90, 5, substr(strtoupper($rst1->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(25, 5, '', 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(($tn1), $dec), 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(($rd1), $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }    
                    }
                }
                if (($niv == 2 || $niv > 2) && ($dt[1]!=null)) {
                    //cambia forma de substrig por separador;
                    $dt2 = explode('.', $array1[$n]);
                    $d2 = $dt2[0] . '.' . $dt2[1];
                    $ds2 = $dt2[0] . '.' . $dt2[1] . '.';
                    if ($g2 != $d2) {
                        $pdf->Ln();
                        $rst2 = $this->pdf_epyg_model->lista_una_cuenta($d2, $ds2);
                        $sm2 = $this->pdf_epyg_model->lista_balance_general1($d2 . '%', $desde, $hasta,$emp_id);
                        $tn2 = (round($sm2->debe1,$dec) + round($sm2->debe2,$dec) + round($sm2->debe3,$dec)) - (round($sm2->haber1,$dec) + round($sm2->haber2,$dec) + round($sm2->haber3,$dec));
                        if ($d > '4') {
                            $rd2 = (abs($tn2) * 100) / abs($tnp);
                        } else {
                            $rd2 = (abs($tn2) * 100) / abs($tn1);
                        }
                        if(round($tn2,$dec)!=0){
                            $pdf->Cell(25, 5, $rst2->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(90, 5, substr(strtoupper($rst2->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(25, 5, '', 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(abs($tn2), $dec), 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(abs($rd2), $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }    
                    }
                }
                if (($niv == 3 || $niv > 3 ) && ($dt[2]!=null)) {
                    //cambia forma de substrig por separador;
                    $dt3 = explode('.', $array1[$n]);
                    $d3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2];
                    $ds3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2] . '.';
                    if ($g3 != $d3) {
                         
                        $rst3 = $this->pdf_epyg_model->lista_una_cuenta($d3, $ds3);
                        $sm3 = $this->pdf_epyg_model->lista_balance_general1($d3 . '%', $desde, $hasta,$emp_id);
                        $tn3 = (round($sm3->debe1,$dec) + round($sm3->debe2,$dec) + round($sm3->debe3,$dec)) - (round($sm3->haber1,$dec) + round($sm3->haber2,$dec) + round($sm3->haber3,$dec));
                        if ($d > '4') {
                            $rd3 = (abs($tn3) * 100) / abs($tnp);
                        } else {
                            $rd3 = (abs($tn3) * 100) / $tn1;
                        }
                        if(round($tn3,$dec)!=0){
                            $pdf->Ln();
                            $pdf->Cell(25, 5, $rst3->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(90, 5, substr(strtoupper($rst3->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(25, 5, '', 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(abs($tn3), $dec), 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(abs($rd3), $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }    
                    }
                }
                if (($niv == 4 || $niv > 4) && ($dt[3]!=null)) {
                    $dt4 = explode('.', $array1[$n]);
                    $d4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3];
                    $ds4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3] . '.';
                    if ($g4 != $d4) {
                        
                        $rst4 = $this->pdf_epyg_model->lista_una_cuenta($d4, $ds4);
                        $sm4 = $this->pdf_epyg_model->lista_balance_general1($d4 . '%', $desde, $hasta,$emp_id);
                        $tn4 = (round($sm4->debe1, $dec) + round($sm4->debe2, $dec) + round($sm4->debe3, $dec)) - (round($sm4->haber1, $dec) + round($sm4->haber2, $dec) + round($sm4->haber3, $dec));
                        if ($d > '4') {
                            $rd4 = (abs($tn4) * 100) / abs($tnp);
                        } else {
                            $rd4 = (abs($tn4) * 100) / abs($tn1);
                        }
                        if(round($tn4,$dec)!=0){
                            $pdf->Ln();
                            $pdf->Cell(25, 5, $rst4->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(90, 5, substr(strtoupper($rst4->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(25, 5, '', 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(abs($tn4), $dec), 0, 0, 'R');
                            $pdf->Cell(25, 5, number_format(abs($rd4), $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }    
                    }
                }
                if (($niv == 5) && ($dt[4]!=null)) {
                    $d5 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2]. '.' . $dt3[3]. '.' . $dt3[4];
                    $ds5 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2] . '.' . $dt3[3]. '.' . $dt3[4]. '.';
                    $rst_cuentas1 = $this->pdf_epyg_model->lista_una_cuenta($array1[$n],$array1[$n]);
                    $rst_v = $this->pdf_epyg_model->suma_cuentas($array1[$n], $desde, $hasta,$emp_id);
                    $tot = round($rst_v->debe,$dec) - round($rst_v->haber,$dec);
                    if ($d > '4') {
                        $rd5 = (abs($tot) * 100) / abs($tnp);
                    } else {
                        $rd5 = (abs($tot) * 100) / abs($tn1);
                    }
                    if(round($tot,$dec)!=0){
                        $pdf->Cell(25, 5, $rst_cuentas1->pln_codigo, 0, 0, 'L');
                        $pdf->Cell(90, 5, substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 35), 0, 0, 'L');
                        $pdf->Cell(25, 5, number_format(abs($tot), $dec), 0, 0, 'R');
                        $pdf->Cell(25, 5, '', 0, 0, 'R');
                        $pdf->Cell(25, 5, number_format(abs($rd5), $dec), 0, 0, 'R');
                        $pdf->Ln();
                        $pdf->Ln();
                    }    
                }

                $n++;
                $g = $d1;
                $g2 = $d2;
                $g3 = $d3;
                $g4 = $d4;
            }
            if ($det == 1) {
                $tvn = $this->ventas_netas($dec, $desde, $hasta,$emp_id);
                $this->sms($pdf,abs($tvn), 'VENTAS NETAS', abs($tvin),$dec);
                $det = 0;
            }
            if ($ut == 1) {
                $tub = $this->utilidad_bruta($dec, $desde, $hasta,$emp_id);
                $this->sms($pdf,abs($tub), 'UTILIDAD BRUTA EN VENTAS', ($tvin),$dec);
                $ut = 0;
            }

            if ($ua == 1) {
                $tui = $this->utilidad_antes($dec, $desde, $hasta,$emp_id);
                $this->sms($pdf,($tui), 'UTILIDAD ANTES DE IMPUESTOS Y PARTICIPACIONES', ($tvin),$dec);
                $ua = 0;
            }
            if ($uej == 1) {
                $tuej = $this->utilidad_ejercicio($dec, $desde, $hasta,$emp_id);
                $this->sms($pdf,($tuej), 'UTILIDAD NETA DEL EJERCICIO', ($tvin),$dec);
                $ut = 0;
            }
        }
       
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'CONTADOR', 'T', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'REVISADO', 'T', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, '', 'T', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, '', '', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, '', '', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, '', '', 0, 'C');

       

        
        $pdf->Output('estado_perdidas_ganancias.pdf' , 'I' ); 
    }
        

    function sms($pdf,$val, $mensaje, $tnp,$dec) {
        $dc = 2;
        $rdp = ($val * 100) / $tnp;
        $pdf->Ln();
        $pdf->Cell(25, 5, '', 0, 0, 'L');
        $pdf->Cell(90, 5, $mensaje, 0, 0, 'L');
        $pdf->Cell(25, 5, '', 0, 0, 'R');
        $pdf->Cell(25, 5, number_format($val, $dec), 0, 0, 'R');
        $pdf->Cell(25, 5, number_format($rdp, $dec), 0, 0, 'R');
        $pdf->Ln();
    }

    function ingresos($dec, $desde, $hasta,$emp_id) {
        $n = 0;
        /// cambia forma de extraer informacion
        $cue = "4%";
        $vin = $this->pdf_epyg_model->lista_balance_general1($cue, $desde, $hasta,$emp_id);
        $tvi = (round($vin->debe1,$dec) + round($vin->debe2,$dec) + round($vin->debe3,$dec)) - (round($vin->haber1,$dec) + round($vin->haber2,$dec) + round($vin->haber3,$dec));
        return abs($tvi);
    }

    function ventas_netas($dec, $desde, $hasta,$emp_id) {
/// cambio las ventas netas = total ingreso
        $cue = "4%";
        $vin = $this->pdf_epyg_model->lista_balance_general1($cue, $desde, $hasta,$emp_id);
        $tvn = (round($vin->debe1,$dec) + round($vin->debe2,$dec) + round($vin->debe3,$dec)) - (round($vin->haber1,$dec) + round($vin->haber2,$dec) + round($vin->haber3,$dec));
        return abs($tvn);
    }

    function utilidad_bruta($dec, $desde, $hasta,$emp_id) {
        ///// cambio utilidad_bruta= ventas_netas - 5.;
        $tvn = $this->ventas_netas($dec, $desde, $hasta,$emp_id);
        $co = $this->pdf_epyg_model->lista_balance_general1('5%', $desde, $hasta,$emp_id);
        $tco = (round($co->debe1,$dec) + round($co->debe2,$dec) + round($co->debe3,$dec)) - (round($co->haber1,$dec) + round($co->haber2,$dec) + round($co->haber3,$dec));
        $tub = abs($tvn) - abs($tco);
        return ($tub);
    }

/// utilidad_neta_ventas no se utiliza
    function utilidad_neta_ventas($dec, $desde, $hasta,$emp_id) {
        $tub = $this->utilidad_bruta($dec, $desde, $hasta,$emp_id);
        $ga = $this->pdf_epyg_model->lista_balance_general1('6.01%', $desde, $hasta,$emp_id);
        $tga = round($ga->debe1,$dec) - round($ga->haber1,$dec);
        $tunv = ($tub) - ($tga);
        return ($tunv);
    }

    function utilidad_antes($dec, $desde, $hasta,$emp_id) {
        // cambio utilidad_antes_ejercicio= utilidad_bruta - 6.
        $tub = $this->utilidad_bruta($dec, $desde, $hasta,$emp_id);
        $uai = $this->pdf_epyg_model->lista_balance_general1('6%', $desde, $hasta,$emp_id);
        $tuai = (round($uai->debe1,$dec) + round($uai->debe2,$dec) + round($uai->debe3,$dec)) - (round($uai->haber1,$dec) + round($uai->haber2,$dec) + round($uai->haber3,$dec));
        $tui = ($tub) - ($tuai);
        return ($tui);
    }

    function utilidad_ejercicio($dec, $desde, $hasta,$emp_id) {
        ///cambio utilidad_ejercicio= utilidad_antes_ejercicio -7.
        $tui = $this->utilidad_antes($dec, $desde, $hasta,$emp_id);
        $ue = $this->pdf_epyg_model->lista_balance_general1('7%', $desde, $hasta,$emp_id);
        $tue = (round($ue->debe1,$dec) + round($ue->debe2,$dec) + round($ue->debe3,$dec)) - (round($ue->haber1,$dec) + round($ue->haber2,$dec) + round($ue->haber3,$dec));
        // $tuej = ($tui) - ($tue);
        $tuej = ($tui);
        return ($tuej);
    }

    function sms2($data,$val, $mensaje, $tnp,$dec) {
        $rdp = ($val * 100) / $tnp;
        $data.="<tr>
                <td></td>
                <td><strong>$mensaje</strong></td>
                <td></td>
                <td>".number_format($val, $dec)."</td>
                <td>".number_format($rdp, $dec)."</td>
                </tr>";
    }

    public function excel($desde,$hasta,$niv,$anio,$mes,$opc_id){

        $periodo="del $desde al $hasta";

        $rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
        $rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
        
        $dc=$this->configuracion_model->lista_una_configuracion('2');
        $dec=$dc->con_valor;
        $emp_id=$rst_cja->emp_id;
        $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        $array1 = Array();
        $cns_cuentas = $this->pdf_epyg_model->lista_asientos_epyg($desde, $hasta,$rst_cja->emp_id);
        foreach ($cns_cuentas as $rst_cuentas) {
            if (!empty($rst_cuentas->con_concepto_debe)) {
                array_push($array1, $rst_cuentas->con_concepto_debe);
            }
        }

        set_time_limit(0);
        $data="<table><tr><td>";
        $data.="</td></tr>";
        $data.="<tr><td colspan='5' align='center'><strong>ESTADO DE RESULTADOS</strong></td></tr>";
        $data.="<tr><td>";
        
        $data.="<tr><td colspan='5'>Periodo $periodo</td></tr>";
        $data.="<tr>
                <td><strong>Codigo</strong></td>
                <td><strong>Cuenta</strong></td>
                <td><strong>Parcial</strong></td>
                <td><strong>Total</strong></td>
                <td><strong>% Rendimiento</strong></td>";
        

        $n = 0;
        $j = 1;
        $g = 0;
        $g2 = 0;
        $g3 = 0;
        $g4 = 0;
        $det = 1;
        $ut = 1;
        $ua = 1;
        $uej = 1;
        $dv = 1;
        $d2 = 0;
        $d3 = 0;
        $d4 = 0;
        if(count($array1)>0){
            while ($n < count($array1)) {
                ///cambia forma de substrig por separador;
                $dt = explode('.', $array1[$n]);
                $d = $dt[0];
                $d1 = $dt[0].'.';
                if ($d > '4') {
                    /// txds= operador para la busqueda con like
                    $txs = $d . '%';
                    $sm = $this->pdf_epyg_model->lista_balance_general1($txs, $desde, $hasta,$emp_id);
                    $tnp = (round($sm->debe1,$dec) + round($sm->debe2,$dec) + round($sm->debe3,$dec)) - (round($sm->haber1,$dec) + round($sm->haber2,$dec) + round($sm->haber3,$dec));
                    $ing = $this->pdf_epyg_model->lista_balance_general1($txs, $desde, $hasta,$emp_id);
                    $tn1 = (round($ing->debe1,$dec) + round($ing->debe2,$dec) + round($ing->debe3,$dec)) - (round($ing->haber1,$dec) + round($ing->haber2,$dec) + round($ing->haber3,$dec));
                    $rd1 = (abs($tn1) * 100) / abs($tnp);
                } else {
                    $tvin = $this->ingresos($dec, $desde, $hasta,$emp_id);
                    $tn1 = ($tvin);
                    $rd1 = (abs($tn1) * 100) / abs($tn1);
                }



                if (($niv == 1 || $niv > 1)&& ($dt[0]!=null)) {
                    if ($g != $d1) {
                        if (($d1 == '5.' || $d1 == '6.' || $d1 == '7.') && $det == 1) {
                            $tvn = $this->ventas_netas($dec, $desde, $hasta,$emp_id);
                            // $this->sms2($data,$tvn, 'VENTAS NETAS', $tvin,$dec);
                            $rdp = ($tvn * 100) /  $tvin;
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>VENTAS NETAS</strong></td>
                                    <td></td>
                                    <td><strong>".number_format($tvn, $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                        
                            $det = 0;
                        }
                        if ($d1 == '6.' && $ut == 1) {
                            $tub = $this->utilidad_bruta($dec, $desde, $hasta, $tvn,$emp_id);
                            // $this->sms2($data,$tub, 'UTILIDAD BRUTA EN VENTAS', $tvin,$dec);
                            $rdp = ($tub * 100) /  $tvin;
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>UTILIDAD BRUTA EN VENTAS</strong></td>
                                    <td></td>
                                    <td><strong>".number_format($tub, $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                            $ut = 0;
                        }
                        if ($d1 == '7.' && $ua == 1) {
                            $tui = $this->utilidad_antes($dec, $desde, $hasta,$emp_id);
                            // $this->sms2($data,$tui, 'UTILIDAD ANTES DE IMPUESTOS Y PARTICIPACIONES', $tvin,$dec);
                            $rdp = ($tui * 100) /  $tvin;
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>UTILIDAD ANTES DE IMPUESTOS Y PARTICIPACIONES</strong></td>
                                    <td></td>
                                    <td><strong>".number_format($tui, $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                            $ua = 0;
                        }

                        $data.="<tr>
                                    <td></td>
                                </tr>";
                        if(round($tn1,$dec)!=0){
                            $rst1 = $this->pdf_epyg_model->lista_una_cuenta($d, $d1);
                            $data.="<tr>
                                    <td>$rst1->pln_codigo</td>
                                    <td>".substr(strtoupper($rst1->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format(($tn1), $dec)."</td>
                                    <td>".number_format(($rd1), $dec)."</td>
                                    <tr>";
                        }    
                    }
                }
                if (($niv == 2 || $niv > 2) && ($dt[1]!=null)) {
                    //cambia forma de substrig por separador;
                    $dt2 = explode('.', $array1[$n]);
                    $d2 = $dt2[0] . '.' . $dt2[1];
                    $ds2 = $dt2[0] . '.' . $dt2[1] . '.';
                    if ($g2 != $d2) {
                        $rst2 = $this->pdf_epyg_model->lista_una_cuenta($d2, $ds2);
                        $sm2 = $this->pdf_epyg_model->lista_balance_general1($d2 . '%', $desde, $hasta,$emp_id);
                        $tn2 = (round($sm2->debe1,$dec) + round($sm2->debe2,$dec) + round($sm2->debe3,$dec)) - (round($sm2->haber1,$dec) + round($sm2->haber2,$dec) + round($sm2->haber3,$dec));
                        if ($d > '4') {
                            $rd2 = (abs($tn2) * 100) / abs($tnp);
                        } else {
                            $rd2 = (abs($tn2) * 100) / abs($tn1);
                        }
                        $data.="<tr>
                                    <td></td>
                                </tr>";
                        if(round($tn2,$dec)!=0){
                            $data.="<tr>
                                    <td>$rst2->pln_codigo</td>
                                    <td>".substr(strtoupper($rst2->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format(abs($tn2), $dec)."</td>
                                    <td>".number_format(abs($rd2), $dec)."</td>
                            </tr>";
                        }    
                    }
                }
                if (($niv == 3 || $niv > 3 ) && ($dt[2]!=null)) {
                    //cambia forma de substrig por separador;
                    $dt3 = explode('.', $array1[$n]);
                    $d3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2];
                    $ds3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2] . '.';
                    if ($g3 != $d3) {
                         
                        $rst3 = $this->pdf_epyg_model->lista_una_cuenta($d3, $ds3);
                        $sm3 = $this->pdf_epyg_model->lista_balance_general1($d3 . '%', $desde, $hasta,$emp_id);
                        $tn3 = (round($sm3->debe1,$dec) + round($sm3->debe2,$dec) + round($sm3->debe3,$dec)) - (round($sm3->haber1,$dec) + round($sm3->haber2,$dec) + round($sm3->haber3,$dec));
                        if ($d > '4') {
                            $rd3 = (abs($tn3) * 100) / abs($tnp);
                        } else {
                            $rd3 = (abs($tn3) * 100) / $tn1;
                        }
                        $data.="<tr>
                                    <td></td>
                                </tr>";
                        if(round($tn3,$dec)!=0){
                            $data.="<tr>
                                    <td>$rst3->pln_codigo</td>
                                    <td>".substr(strtoupper($rst3->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format(abs($tn3), $dec)."</td>
                                    <td>".number_format(abs($rd3), $dec)."</td>
                                    </tr>";
                        }    
                    }
                }
                if (($niv == 4 || $niv > 4) && ($dt[3]!=null)) {
                    $dt4 = explode('.', $array1[$n]);
                    $d4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3];
                    $ds4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3] . '.';
                    if ($g4 != $d4) {
                        
                        $rst4 = $this->pdf_epyg_model->lista_una_cuenta($d4, $ds4);
                        $sm4 = $this->pdf_epyg_model->lista_balance_general1($d4 . '%', $desde, $hasta,$emp_id);
                        $tn4 = (round($sm4->debe1, $dec) + round($sm4->debe2, $dec) + round($sm4->debe3, $dec)) - (round($sm4->haber1, $dec) + round($sm4->haber2, $dec) + round($sm4->haber3, $dec));
                        if ($d > '4') {
                            $rd4 = (abs($tn4) * 100) / abs($tnp);
                        } else {
                            $rd4 = (abs($tn4) * 100) / abs($tn1);
                        }
                        $data.="<tr>
                                    <td></td>
                                </tr>";
                        if(round($tn4,$dec)!=0){
                            $data.="<tr>
                                    <td>$rst4->pln_codigo</td>
                                    <td>".substr(strtoupper($rst4->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format(abs($tn4), $dec)."</td>
                                    <td>".number_format(abs($rd4), $dec)."</td>
                                    </tr>";
                        }    
                    }
                }
                if (($niv == 5) && ($dt[4]!=null)) {
                    $d5 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2]. '.' . $dt3[3]. '.' . $dt3[4];
                    $ds5 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2] . '.' . $dt3[3]. '.' . $dt3[4]. '.';
                    $rst_cuentas1 = $this->pdf_epyg_model->lista_una_cuenta($array1[$n],$array1[$n]);
                    $rst_v = $this->pdf_epyg_model->suma_cuentas($array1[$n], $desde, $hasta,$emp_id);
                    $tot = round($rst_v->debe,$dec) - round($rst_v->haber,$dec);
                    if ($d > '4') {
                        $rd5 = (abs($tot) * 100) / abs($tnp);
                    } else {
                        $rd5 = (abs($tot) * 100) / abs($tn1);
                    }
                    
                    if(round($tot,$dec)!=0){
                        $data.="<tr>
                                <td>$rst_cuentas1->pln_codigo</td>
                                <td>".substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 35)."</td>
                                <td>".number_format(abs($tot), $dec)."</td>
                                <td></td>
                                <td>".number_format(abs($rd5), $dec)."</td>
                                </tr>";
                    }    
                }

                $n++;
                $g = $d1;
                $g2 = $d2;
                $g3 = $d3;
                $g4 = $d4;
            }
            if ($det == 1) {
                $tvn = $this->ventas_netas($dec, $desde, $hasta,$emp_id);
                // $this->sms2($data,abs($tvn), 'VENTAS NETAS', abs($tvin),$dec);
                $rdp = (abs($tvn) * 100) /  abs($tvin);
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>VENTAS NETAS</strong></td>
                                    <td></td>
                                    <td><strong>".number_format(abs($tvn), $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                $det = 0;
            }
            if ($ut == 1) {
                $tub = $this->utilidad_bruta($dec, $desde, $hasta,$emp_id);
                // $this->sms2($data,abs($tub), 'UTILIDAD BRUTA EN VENTAS', ($tvin),$dec);
                $rdp = (abs($tub) * 100) /  abs($tvin);
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>UTILIDAD BRUTA EN VENTAS</strong></td>
                                    <td></td>
                                    <td><strong>".number_format(abs($tub), $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                $ut = 0;
            }

            if ($ua == 1) {
                $tui = $this->utilidad_antes($dec, $desde, $hasta,$emp_id);
                // $this->sms2($data,($tui), 'UTILIDAD ANTES DE IMPUESTOS Y PARTICIPACIONES', ($tvin),$dec);
                $rdp = ($tui * 100) /  ($tvin);
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>UTILIDAD ANTES DE IMPUESTOS Y PARTICIPACIONES</strong></td>
                                    <td></td>
                                    <td><strong>".number_format($tui, $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                $ua = 0;
            }
            if ($uej == 1) {
                $tuej = $this->utilidad_ejercicio($dec, $desde, $hasta,$emp_id);
                // $this->sms2($data,($tuej), 'UTILIDAD NETA DEL EJERCICIO', ($tvin),$dec);
                $rdp = ($tuej * 100) /  ($tvin);
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td></td>
                                    <td><strong>UTILIDAD NETA DEL EJERCICIO</strong></td>
                                    <td></td>
                                    <td><strong>".number_format($tuej, $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                    </tr>";
                $ut = 0;
            }
        }
       
        $data.="<tr><td><br><br><br><br></td></tr>";
        $data.="<tr>
                    <td></td>
                    <td>CONTADOR</td>
                    <td></td>
                    <td>REVISADO</td>
                </tr>";
        
        $titulo='';
        $file="estado_perdidas_ganancias".date('Ymd');
        $this->export_excel->to_excel2($data,$file,$titulo);
    }

}
