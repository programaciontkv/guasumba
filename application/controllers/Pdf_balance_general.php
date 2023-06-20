<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_balance_general extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('rep_contables_model');
		$this->load->model('pdf_balance_general_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}

	

	public function index($desde,$hasta,$niv,$anio,$mes,$opc_id){
        if ($mes == 13) {
            $periodo = "Anual del " . trim($anio);
        } else {
            $periodo = " AL $hasta";
        }
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
        $pdf->Cell(200, 5, "BALANCE GENERAL", 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 13);
        $pdf->Cell(200, 5, $periodo, 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(85, 5, "MONEDA: DOLAR", 0, 0, 'L');
        $pdf->Ln();
        

        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(30, 5, "COD. CUENTA", 'TB', 0, 'L');
        $pdf->Cell(65, 5, "NOMBRE CUENTA", 'TB', 0, 'L');
        $pdf->Cell(30, 5, "PARCIAL", 'TB', 0, 'R');
        $pdf->Cell(30, 5, "TOTAL", 'TB', 0, 'R');
        $pdf->Cell(20, 5, "% REND", 'TB', 0, 'R');
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 10);

        $i = 0;
        while ($i < 3) {
            $i++;
            $d = $i . '.';
            $cns_cuentas = $this->pdf_balance_general_model->listar_asiento_agrupado($d, $desde, $hasta,$rst_cja->emp_id);
            $cuentas = Array();
            foreach ($cns_cuentas as $rst_cuentas) {
                if (!empty($rst_cuentas->con_concepto_debe)) {
                    array_push($cuentas, $rst_cuentas->con_concepto_debe);
                }
            }
            $n = 0;
            $j = 1;
            $g = 0;
            $g2 = 0;
            $g3 = 0;
            $g4 = 0;
            $d2 = 0;
            $d3 = 0;
            $d4 = 0;
            $det = 0;
            $tpp = 0;
            $activo = 0;
            if(count($cuentas)>0){
                while ($n < count($cuentas)) {
                    $dt = explode('.', $cuentas[$n]);
                    $d1 = $dt[0];
                    $d = $dt[0] . '.';


                    if ($d == '2.' && $det == 0) {

                        $pp = $this->pdf_balance_general_model->suma_pasivo_patrimonio($desde, $hasta,$rst_cja->emp_id);
                        $tpp = round($pp->debe,$dec) - round($pp->haber,$dec);
                        if($tpp==0){
                            $rdp=0;
                        }else{
                            $rdp = ($tpp * 100) / $tpp;
                        }
                        $pdf->Ln();
                        $pdf->Cell(30, 5, '', 0, 0, 'L');
                        $pdf->Cell(65, 5, 'PASIVO Y PATRIMONIO', 0, 0, 'L');
                        $pdf->Cell(30, 5, '', 0, 0, 'R');
                        $pdf->Cell(30, 5, number_format($tpp, $dec), 0, 0, 'R');
                        $pdf->Cell(20, 5, number_format($rdp, $dec), 0, 0, 'R');
                        $pdf->Ln();
                        $det = 1;
                    }
                  

                    if (($niv == 1 || $niv > 1) && !empty($dt[0])) {
                        if ($g != $d1) {
                            $pdf->Ln();
                            $rst1 = $this->pdf_balance_general_model->lista_una_cuenta($d1, $d);
                            $sm = $this->pdf_balance_general_model->lista_balance_general1($d1 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn1 = (round($sm->debe1,$dec) + round($sm->debe2,$dec) - round($sm->debe3,$dec)) - (round($sm->haber1,$dec) + round($sm->haber2,$dec) - round($sm->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd1=0;
                                }else{
                                    $rd1 = ($tn1 * 100) / $tpp;
                                }
                                
                            } else {
                                if($tn1==0){
                                    $rd1=0;
                                }else{
                                    $rd1 = ($tn1 * 100) / $tn1;
                                }
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }

                            $pdf->Cell(30, 5, $rst1->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(65, 5, substr(strtoupper($rst1->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(30, 5, '', 0, 0, 'R');
                            $pdf->Cell(30, 5, number_format($tn1, $dec), 0, 0, 'R');
                            $pdf->Cell(20, 5, number_format($rd1, $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }
                    }
                    if (($niv == 2 || $niv > 2) && !empty($dt[1])) {
                        $dt2 = explode('.', $cuentas[$n]);
                        $d2 = $dt2[0] . '.' . $dt2[1];
                        $ds2 = $dt2[0] . '.' . $dt2[1] . '.';
                        if ($g2 != $d2) {
                            $pdf->Ln();
                            $rst2 = $this->pdf_balance_general_model->lista_una_cuenta($d2, $ds2);
                            $sm2 = $this->pdf_balance_general_model->lista_balance_general1($d2 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn2 = (round($sm2->debe1,$dec) + round($sm2->debe2,$dec) - round($sm2->debe3,$dec)) - (round($sm2->haber1,$dec) + round($sm2->haber2,$dec) - round($sm2->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd2=0;
                                }else{
                                    $rd2 = ($tn2 * 100) / $tpp;
                                }
                                
                            } else {
                                if($tn1==0){
                                    $rd2=0;
                                }else{
                                    $rd2 = ($tn2 * 100) / $tn1;
                                }
                               
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }
                            $pdf->Cell(30, 5, $rst2->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(65, 5, substr(strtoupper($rst2->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(30, 5, '', 0, 0, 'R');
                            $pdf->Cell(30, 5, number_format($tn2, $dec), 0, 0, 'R');
                            $pdf->Cell(20, 5, number_format($rd2, $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }
                    }
                    if (($niv == 3 || $niv > 3) && !empty($dt[2])) {
                        $dt3 = explode('.', $cuentas[$n]);
                        $d3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2];
                        $ds3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2] . '.';
                        if ($g3 != $d3) {
                            $pdf->Ln();
                            $rst3 = $this->pdf_balance_general_model->lista_una_cuenta($d3, $ds3);
                            $sm3 = $this->pdf_balance_general_model->lista_balance_general1($d3 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn3 = (round($sm3->debe1,$dec) + round($sm3->debe2,$dec) - round($sm3->debe3,$dec)) - (round($sm3->haber1,$dec) + round($sm3->haber2,$dec) - round($sm3->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd3=0;
                                }else{
                                    $rd3 = ($tn3 * 100) / $tpp;
                                }
                                
                            } else {
                                if($tn1==0){
                                    $rd3=0;
                                }else{
                                    $rd3 = ($tn3 * 100) / $tn1;
                                }
                                
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }
                            $pdf->Cell(30, 5, $rst3->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(65, 5, substr(strtoupper($rst3->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(30, 5, '', 0, 0, 'R');
                            $pdf->Cell(30, 5, number_format($tn3, $dec), 0, 0, 'R');
                            $pdf->Cell(20, 5, number_format($rd3, $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }
                    }
                    if (($niv == 4 || $niv > 4) && !empty($dt[3])) {
                        $dt4 = explode('.', $cuentas[$n]);
                        $d4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3];
                        $ds4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3] . '.';
                        if ($g4 != $d4) {
                            $pdf->Ln();
                            $rst4 = $this->pdf_balance_general_model->lista_una_cuenta($d4, $ds4);
                            $sm4 = $this->pdf_balance_general_model->lista_balance_general1($d4 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn4 = (round($sm4->debe1,$dec) + round($sm4->debe2,$dec) - round($sm4->debe3,$dec)) - (round($sm4->haber1,$dec) + round($sm4->haber2,$dec) - round($sm4->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd4=0;
                                }else{
                                    $rd4 = ($tn4 * 100) / $tpp;
                                }

                            } else {
                                if($tn1==0){
                                    $rd4=0;
                                }else{
                                    $rd4 = ($tn4 * 100) / $tn1;
                                }
                                
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }
                            $pdf->Cell(30, 5, $rst4->pln_codigo, 0, 0, 'L');
                            $pdf->Cell(65, 5, substr(strtoupper($rst4->pln_descripcion), 0, 35), 0, 0, 'L');
                            $pdf->Cell(30, 5, '', 0, 0, 'R');
                            $pdf->Cell(30, 5, number_format($tn4, $dec), 0, 0, 'R');
                            $pdf->Cell(20, 5, number_format($rd4, $dec), 0, 0, 'R');
                            $pdf->Ln();
                        }
                    }
                    if (($niv == 5) && !empty($dt[4])) {
                        $rst_cuentas1 = $this->pdf_balance_general_model->lista_una_cuenta($cuentas[$n],$cuentas[$n]);
                        $rst_v = $this->pdf_balance_general_model->suma_cuentas($cuentas[$n], $desde, $hasta,$rst_cja->emp_id);
                        $tot = round($rst_v->debe,$dec) - round($rst_v->haber,$dec);
                        if ($d1 == '2.' || $d1 == '3.') {
                            if($tpp==0){
                                $rd5=0;
                            }else{
                                $rd5 = ($tot * 100) / $tpp;
                            }
                            
                        } else {
                            if($tn1==0){
                                $rd5=0;
                            }else{
                                $rd5 = ($tot * 100) / $tn1;
                            }
                            
                        }
                        if ($d == '1.') {
                            $activo = round($tn1,$dec);
                        }
                        $pdf->Cell(30, 5, $rst_cuentas1->pln_codigo, 0, 0, 'L');
                        $pdf->Cell(65, 5, substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 35), 0, 0, 'L');
                        $pdf->Cell(30, 5, '', 0, 0, 'R');
                        $pdf->Cell(30, 5, number_format($tot, $dec), 0, 0, 'R');
                        $pdf->Cell(20, 5, number_format($rd5, $dec), 0, 0, 'R');
                        $pdf->Ln();
                    }
                    $n++;
                    $g = $d1;
                    $g2 = $d2;
                    $g3 = $d3;
                    $g4 = $d4;
                }
            }
            
        }   
        $pdf->Ln();
        $pdf->Cell(30, 5, '', 0, 0, 'L');
        $pdf->Cell(65, 5, 'RESULTADO DEL PERIODO', 0, 0, 'L');
        $pdf->Cell(30, 5, '', 0, 0, 'R');
        $pdf->Cell(30, 5, number_format($activo + $tpp, $dec), 0, 0, 'R');
        $pdf->Cell(20, 5, number_format(0, $dec), 0, 0, 'R'); 

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(40, 5, '', 0, 0, 'C');
        $pdf->Cell(40, 5, '_____________________', 0, 0, 'C');
        $pdf->Cell(40, 5, '_____________________', 0, 0, 'C');
        $pdf->Cell(40, 5, '_____________________', 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(40, 5, '', 0, 0, 'C');
        $pdf->Cell(40, 5, 'APROBADO', 0, 0, 'C');
        $pdf->Cell(40, 5, 'REVISADO', 0, 0, 'C');
        $pdf->Cell(40, 5, 'ELABORADO', 0, 0, 'C');
        
        $pdf->Output('balance_general.pdf' , 'I' ); 

    }

    public function excel($desde,$hasta,$niv,$anio,$mes,$opc_id){
        if ($mes == 13) {
            $periodo = "Anual del " . trim($anio);
        } else {
            $periodo = " AL $hasta";
        }
        $rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
        $rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
                
        $dc=$this->configuracion_model->lista_una_configuracion('2');
        $dec=$dc->con_valor;
        $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        

        set_time_limit(0);
        
        $data="<table><tr><td>";
        $data.="</td></tr>";
        $data.="<tr><td colspan='8' align='center'><strong>BALANCE GENERAL</strong></td></tr>";
        $data.="<tr><td>";
        
        $data.="<tr><td colspan='8'>Periodo  $periodo</td></tr>";
        $data.="<tr><td colspan='8'>MONEDA: DOLAR</td></tr>";
        
        

        $data.="<tr>
                    <td><strong>COD. CUENTA</td>
                    <td><strong>NOMBRE CUENTA</td>
                    <td><strong>PARCIAL</td>
                    <td><strong>TOTAL</td>
                    <td><strong>% REND</td>
                </tr>";    

        $i = 0;
        while ($i < 3) {
            $i++;
            $d = $i . '.';
            $cns_cuentas = $this->pdf_balance_general_model->listar_asiento_agrupado($d, $desde, $hasta,$rst_cja->emp_id);
            $cuentas = Array();
            foreach ($cns_cuentas as $rst_cuentas) {
                if (!empty($rst_cuentas->con_concepto_debe)) {
                    array_push($cuentas, $rst_cuentas->con_concepto_debe);
                }
            }
            $n = 0;
            $j = 1;
            $g = 0;
            $g2 = 0;
            $g3 = 0;
            $g4 = 0;
            $d2 = 0;
            $d3 = 0;
            $d4 = 0;
            $det = 0;
            $tpp = 0;
            $activo = 0;
            if(count($cuentas)>0){
                while ($n < count($cuentas)) {
                    $dt = explode('.', $cuentas[$n]);
                    $d1 = $dt[0];
                    $d = $dt[0] . '.';


                    if ($d == '2.' && $det == 0) {

                        $pp = $this->pdf_balance_general_model->suma_pasivo_patrimonio($desde, $hasta,$rst_cja->emp_id);
                        $tpp = round($pp->debe,$dec) - round($pp->haber,$dec);
                        $rdp = ($tpp * 100) / $tpp;
                        $data.="<tr><td><br></td></tr>
                                <tr>
                                    <td></td>
                                    <td><strong>PASIVO Y PATRIMONIO</strong></td>
                                    <td></td>
                                    <td><strong>".number_format($tpp, $dec)."</strong></td>
                                    <td><strong>".number_format($rdp, $dec)."</strong></td>
                                </tr>";    
                        $det = 1;
                    }
                  

                    if (($niv == 1 || $niv > 1) && ($dt[0] != null)) {
                        if ($g != $d1) {
                            $rst1 = $this->pdf_balance_general_model->lista_una_cuenta($d1, $d);
                            $sm = $this->pdf_balance_general_model->lista_balance_general1($d1 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn1 = (round($sm->debe1,$dec) + round($sm->debe2,$dec) - round($sm->debe3,$dec)) - (round($sm->haber1,$dec) + round($sm->haber2,$dec) - round($sm->haber3,$dec));
                            
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd1=0;
                                }else{
                                    $rd1 = ($tn1 * 100) / $tpp;
                                }
                                
                            } else {
                                if($tn1==0){
                                    $rd1=0;
                                }else{
                                    $rd1 = ($tn1 * 100) / $tn1;
                                }
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }

                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td>$rst1->pln_codigo</td>
                                    <td>".substr(strtoupper($rst1->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format($tn1, $dec)."</td>
                                    <td>".number_format($rd1, $dec)."</td>
                                    </tr>";
                        }
                    }
                    if (($niv == 2 || $niv > 2) && ($dt[1] != null)) {
                        $dt2 = explode('.', $cuentas[$n]);
                        $d2 = $dt2[0] . '.' . $dt2[1];
                        $ds2 = $dt2[0] . '.' . $dt2[1] . '.';
                        if ($g2 != $d2) {
                            $rst2 = $this->pdf_balance_general_model->lista_una_cuenta($d2, $ds2);
                            $sm2 = $this->pdf_balance_general_model->lista_balance_general1($d2 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn2 = (round($sm2->debe1,$dec) + round($sm2->debe2,$dec) - round($sm2->debe3,$dec)) - (round($sm2->haber1,$dec) + round($sm2->haber2,$dec) - round($sm2->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd2=0;
                                }else{
                                    $rd2 = ($tn2 * 100) / $tpp;
                                }
                                
                            } else {
                                if($tn1==0){
                                    $rd2=0;
                                }else{
                                    $rd2 = ($tn2 * 100) / $tn1;
                                }
                               
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td>$rst2->pln_codigo</td>
                                    <td>".substr(strtoupper($rst2->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format($tn2, $dec)."</td>
                                    <td>".number_format($rd2, $dec)."</td>
                                    </tr>";
                        }
                    }
                    if (($niv == 3 || $niv > 3) && ($dt[2] != null)) {
                        $dt3 = explode('.', $cuentas[$n]);
                        $d3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2];
                        $ds3 = $dt3[0] . '.' . $dt3[1] . '.' . $dt3[2] . '.';
                        if ($g3 != $d3) {
                            $rst3 = $this->pdf_balance_general_model->lista_una_cuenta($d3, $ds3);
                            $sm3 = $this->pdf_balance_general_model->lista_balance_general1($d3 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn3 = (round($sm3->debe1,$dec) + round($sm3->debe2,$dec) - round($sm3->debe3,$dec)) - (round($sm3->haber1,$dec) + round($sm3->haber2,$dec) - round($sm3->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd3=0;
                                }else{
                                    $rd3 = ($tn3 * 100) / $tpp;
                                }
                                
                            } else {
                                if($tn1==0){
                                    $rd3=0;
                                }else{
                                    $rd3 = ($tn3 * 100) / $tn1;
                                }
                                
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td>$rst3->pln_codigo</td>
                                    <td>".substr(strtoupper($rst3->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format($tn3, $dec)."</td>
                                    <td>".number_format($rd3, $dec)."</td>
                                    </tr>";
                        }
                    }
                    if (($niv == 4 || $niv > 4) && ($dt[3] != null)) {
                        $dt4 = explode('.', $cuentas[$n]);
                        $d4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3];
                        $ds4 = $dt4[0] . '.' . $dt4[1] . '.' . $dt4[2] . '.' . $dt4[3] . '.';
                        if ($g4 != $d4) {
                            $rst4 = $this->pdf_balance_general_model->lista_una_cuenta($d4, $ds4);
                            $sm4 = $this->pdf_balance_general_model->lista_balance_general1($d4 . '%', $desde, $hasta,$rst_cja->emp_id);
                            $tn4 = (round($sm4->debe1,$dec) + round($sm4->debe2,$dec) - round($sm4->debe3,$dec)) - (round($sm4->haber1,$dec) + round($sm4->haber2,$dec) - round($sm4->haber3,$dec));
                            if ($d1 == '2.' || $d1 == '3.') {
                                if($tpp==0){
                                    $rd4=0;
                                }else{
                                    $rd4 = ($tn4 * 100) / $tpp;
                                }

                            } else {
                                if($tn1==0){
                                    $rd4=0;
                                }else{
                                    $rd4 = ($tn4 * 100) / $tn1;
                                }
                                
                            }
                            if ($d == '1.') {
                                $activo = $tn1;
                            }
                            $data.="<tr><td><br></td></tr>
                                    <tr>
                                    <td>$rst4->pln_codigo</td>
                                    <td>".substr(strtoupper($rst4->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format($tn4, $dec)."</td>
                                    <td>".number_format($rd4, $dec)."</td>
                                    </tr>";
                        }
                    }
                    if (($niv == 5) && ($dt[4] != null)) {
                        $rst_cuentas1 = $this->pdf_balance_general_model->lista_una_cuenta($cuentas[$n],$cuentas[$n]);
                        $rst_v = $this->pdf_balance_general_model->suma_cuentas($cuentas[$n], $desde, $hasta,$rst_cja->emp_id);
                        $tot = round($rst_v->debe,$dec) - round($rst_v->haber,$dec);
                        if ($d1 == '2.' || $d1 == '3.') {
                            if($tpp==0){
                                $rd5=0;
                            }else{
                                $rd5 = ($tot * 100) / $tpp;
                            }
                            
                        } else {
                            if($tn1==0){
                                $rd5=0;
                            }else{
                                $rd5 = ($tot * 100) / $tn1;
                            }
                            
                        }
                        if ($d == '1.') {
                            $activo = round($tn1,$dec);
                        }
                        $data.="<tr>
                                    <td>$rst_cuentas1->pln_codigo</td>
                                    <td>".substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 35)."</td>
                                    <td></td>
                                    <td>".number_format($tot, $dec)."</td>
                                    <td>".number_format($rd5, $dec)."</td>
                                </tr>";
                    }
                    $n++;
                    $g = $d1;
                    $g2 = $d2;
                    $g3 = $d3;
                    $g4 = $d4;
                }
            }
            
        }   
        $data.="<tr>
                    <td></td>
                    <td><strong>RESULTADO DEL PERIODO</strong></td>
                    <td></td>
                    <td><strong>".number_format($activo + $tpp, $dec)."</strong></td>
                    <td><strong>".number_format(0, $dec)."</strong></td>
                </tr>";

        $data.="<tr><td><br><br><br></td></tr>";
        $data.="<tr>
                    <td>APROBADO</td>
                    <td></td>
                    <td>REVISADO</td>
                    <td></td>
                    <td>ELABORADO</td>
                </tr>";
        $titulo='';
        $file="balance_general".date('Ymd');
        $this->export_excel->to_excel2($data,$file,$titulo);

    } 

}
