<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_libro_diario extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('configuracion_model'); 
		$this->load->model('rep_contables_model');
		$this->load->model('pdf_libro_diario_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}

	

	 public function index($desde,$hasta,$opc_id){
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

        
        //$pdf->SetFont('helvetica','B',10);
        $pdf->SetFont('Calibri-bold', '', 14);
        $pdf->Cell(200, 5, "REPORTE LIBRO DIARIO", 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Calibri-light', '', 13);
        $pdf->Cell(200, 5, "DE: " . $desde . '  AL ' . $hasta, 0, 0, 'C');
        $pdf->Ln();
        

        $txt=" where con_fecha_emision between '$desde' and '$hasta' and emp_id=$rst_cja->emp_id";
        $cns=$this->pdf_libro_diario_model->lista_total_asientos_fecha($txt);
        $cuentas = Array();
        foreach ($cns as $rst) {
        	$cns_cuentas = $this->pdf_libro_diario_model->lista_cuentas_asientos($rst->con_asiento);
	        
	        foreach ($cns_cuentas as $rst_cuentas) {
	            if (!empty($rst_cuentas->con_concepto_debe)) {
	            	$dt_debe=(object) array(
	            					'id'=>$rst_cuentas->con_id,
	            					'asiento'=>$rst->con_asiento,
	            					'cuenta'=>$rst_cuentas->con_concepto_debe,
	            					'tipo'=>0

	            	);
	                array_push($cuentas, $dt_debe);
	            }

	            if (!empty($rst_cuentas->con_concepto_haber)) {
	            	$dt_haber=(object) array(
	            					'id'=>$rst_cuentas->con_id,
	            					'asiento'=>$rst->con_asiento,
	            					'cuenta'=>$rst_cuentas->con_concepto_haber,
	            					'tipo'=>1

	            	);
	                array_push($cuentas, $dt_haber);
	            }
	        }

        }
	
			
        $n=0;
        $debe = 0;
        $haber = 0;
        $td = 0;
        $th = 0;
        $asiento='';

	    foreach ($cuentas as $rst_cta) {
	    	$n++;
            $rst_cuentas1 = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($rst_cta->cuenta);

            if ($rst_cta->tipo == 0) {
                $rst_v = $this->pdf_libro_diario_model->listar_asientos_debe($rst_cta->asiento, trim($rst_cta->cuenta), $rst_cta->id);
                $debe=$rst_v->con_valor_debe;
                $haber = 0;
            } else {
                $rst_v = $this->pdf_libro_diario_model->listar_asientos_haber($rst_cta->asiento, trim($rst_cta->cuenta), $rst_cta->id);
                $debe = 0;
                $haber = $rst_v->con_valor_haber;
                 
            }

            if($rst_cta->asiento!=$asiento && $n!=1){
	            $pdf->Ln();
	            $pdf->Cell(202, 2, '', 'T', 0, 'L');
		        $pdf->Ln();
		        $pdf->SetFont('Calibri-bold', '', 10);
		        $pdf->Cell(163, 5, 'TOTAL ', '', 0, 'R');
		        $pdf->Cell(18, 5, number_format($td, $dec), '', 0, 'R');
		        $pdf->Cell(18, 5, number_format($th, $dec), '', 0, 'R');
		        $pdf->Ln();
		        $pdf->Ln();
		        $n=1;
		        $td = 0;
        		$th = 0;
	    	}
	    	$datos = $pdf->getY();
    if($datos<= 250)
    {
            if($rst_cta->asiento!=$asiento){
            	//$pdf->SetFont('Arial', 'B', 10);
            	$pdf->SetFont('Calibri-light', '', 13);
		        $pdf->Cell(30, 5, "ASIENTO No.: ", 0, 0, 'L');
		        $pdf->SetTextColor(255,0, 0);
		        $pdf->Cell(85, 5, $rst_cta->asiento, 0, 0, 'L');
		        $pdf->SetTextColor(0,0, 0);
		        $pdf->Ln();
            	$pdf->SetFont('Calibri-light', '', 12);
		        $pdf->Cell(10, 5, "No", 'TB', 0, 'C');
		        $pdf->Cell(18, 5, "F. EMISION", 'TB', 0, 'C');
		        $pdf->Cell(25, 5, "CODIGO", 'TB', 0, 'C');
		        $pdf->Cell(45, 5, "CUENTA", 'TB', 0, 'C');
		        $pdf->Cell(30, 5, "DOCUMENTO", 'TB', 0, 'C');
		        $pdf->Cell(35, 5, "CONCEPTO", 'TB', 0, 'C');
		        $pdf->Cell(18, 5, "DEBE", 'TB', 0, 'C');
		        $pdf->Cell(18, 5, "HABER", 'TB', 0, 'C');
		        $pdf->Ln();
            }
            $pdf->SetFont('Calibri-light', '', 10);
            $pdf->Cell(10, 5, $n, 0, 0, 'L');
            $pdf->Cell(18, 5, $rst_v->con_fecha_emision, 0, 0, 'L');
            $pdf->Cell(25, 5, $rst_cuentas1->pln_codigo, 0, 0, 'L');
            $pdf->Cell(45, 5, substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 24), 0, 0, 'L');
            $pdf->Cell(30, 5, $rst_v->con_documento, 0, 0, 'L');
            $pdf->Cell(35, 5, substr($rst_v->con_concepto, 0, 20), 0, 0, 'L');
            $pdf->Cell(18, 5, number_format($debe, $dec), 0, 0, 'R');
            $pdf->Cell(18, 5, number_format($haber, $dec), 0, 0, 'R');
            $pdf->Ln();
            
            $td+= round($debe, $dec);
            $th+= round($haber, $dec);

	    	$asiento=$rst_cta->asiento;
        
    }else{
    	$pdf->AddPage();
    }
}
  		$pdf->Ln();
	    $pdf->Cell(202, 2, '', 'T', 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('Calibri-bold', '', 10);
		$pdf->Cell(163, 5, 'TOTAL ', '', 0, 'R');
		$pdf->Cell(18, 5, number_format($td, $dec), '', 0, 'R');
		$pdf->Cell(18, 5, number_format($th, $dec), '', 0, 'R');
		$pdf->Ln();
		$pdf->Ln();
        

        $pdf->Ln();
        $pdf->Ln();
        //$pdf->Ln();

        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'PREPARADO', 'T', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'REVISADO', 'T', 0, 'C');
        $pdf->Cell(20, 5, '', '');
        $pdf->Cell(40, 5, 'AUTORIZADO', 'T', 0, 'C');


        $pdf->Output('libro_diario.pdf' , 'I' ); 

    } 

    public function excel($desde,$hasta,$opc_id){
	 	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 		    
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        set_time_limit(0);
        
        $data="<table><tr><td>";
        $data.="</td></tr>";
        $data.="<tr><td colspan='8' align='center'><strong>REPORTE LIBRO DIARIO</strong></td></tr>";
        $data.="<tr><td>";
        
        $data.="<tr><td colspan='8'>PERIODO  DESDE:  $desde AL $hasta</td></tr>";
        
        $txt=" where con_fecha_emision between '$desde' and '$hasta' and emp_id=$rst_cja->emp_id";
        $cns=$this->pdf_libro_diario_model->lista_total_asientos_fecha($txt);
        $cuentas = Array();
        foreach ($cns as $rst) {
        	$cns_cuentas = $this->pdf_libro_diario_model->lista_cuentas_asientos($rst->con_asiento);
	        
	        foreach ($cns_cuentas as $rst_cuentas) {
	            if (!empty($rst_cuentas->con_concepto_debe)) {
	            	$dt_debe=(object) array(
	            					'id'=>$rst_cuentas->con_id,
	            					'asiento'=>$rst->con_asiento,
	            					'cuenta'=>$rst_cuentas->con_concepto_debe,
	            					'tipo'=>0

	            	);
	                array_push($cuentas, $dt_debe);
	            }

	            if (!empty($rst_cuentas->con_concepto_haber)) {
	            	$dt_haber=(object) array(
	            					'id'=>$rst_cuentas->con_id,
	            					'asiento'=>$rst->con_asiento,
	            					'cuenta'=>$rst_cuentas->con_concepto_haber,
	            					'tipo'=>1

	            	);
	                array_push($cuentas, $dt_haber);
	            }
	        }

        }
	
			
        $n=0;
        $debe = 0;
        $haber = 0;
        $td = 0;
        $th = 0;
        $asiento='';

	    foreach ($cuentas as $rst_cta) {
	    	$n++;
            $rst_cuentas1 = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($rst_cta->cuenta);

            if ($rst_cta->tipo == 0) {
                $rst_v = $this->pdf_libro_diario_model->listar_asientos_debe($rst_cta->asiento, trim($rst_cta->cuenta), $rst_cta->id);
                $debe=$rst_v->con_valor_debe;
                $haber = 0;
            } else {
                $rst_v = $this->pdf_libro_diario_model->listar_asientos_haber($rst_cta->asiento, trim($rst_cta->cuenta), $rst_cta->id);
                $debe = 0;
                $haber = $rst_v->con_valor_haber;
                 
            }

            if($rst_cta->asiento!=$asiento && $n!=1){
	            $data.="<tr>
	            			<td colspan='5'></td>
	            			<td><strong>TOTAL</strong></td>
	            			<td><strong>".number_format($td, $dec)."</strong></td>
	            			<td><strong>".number_format($th, $dec)."</strong></td>
	            		</tr>";
		        $data.="<tr><td><br><br></td></tr>";
		        $n=1;
		        $td = 0;
        		$th = 0;
	    	}
            if($rst_cta->asiento!=$asiento){
            	$data.="<tr>
	            			<td colspan='5'><strong>ASIENTO No.: " . $rst_cta->asiento."</strong></td>
	            		</tr>	
		        		<tr>
	            			<td><strong>No</strong></td>
		        			<td><strong>F. EMISION</strong></td>
		        			<td><strong>CODIGO</strong></td>
		       				<td><strong>CUENTA</strong></td>
		        			<td><strong>DOCUMENTO</strong></td>
		        			<td><strong>CONCEPTO</strong></td>
		        			<td><strong>DEBE</strong></td>
		        			<td><strong>HABER</strong></td>
		        		</tr>";
            }
            $data.="<tr>
            			<td>$n</td>
            			<td>$rst_v->con_fecha_emision</td>
            			<td>$rst_cuentas1->pln_codigo</td>
            			<td>".substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 24)."</td>
            			<td>$rst_v->con_documento</td>
            			<td>".substr($rst_v->con_concepto, 0, 25)."</td>
            			<td>".number_format($debe, $dec)."</td>
            			<td>".number_format($haber, $dec)."</td>
            		</tr>";	
            
            $td+= round($debe, $dec);
            $th+= round($haber, $dec);

	    	$asiento=$rst_cta->asiento;
        }

  		$data.="<tr>
  					<td colspan='5'></td>
		            <td><strong>TOTAL</strong></td>
		            <td><strong>".number_format($td, $dec)."</strong></td>
		            <td><strong>".number_format($th, $dec)."</strong></td>
		        </tr>";    
		$data.="<tr><td><br><br><br></td></tr>";
        
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
        $file="libro_diario".date('Ymd');
        $this->export_excel->to_excel2($data,$file,$titulo);        
        
    } 

}
