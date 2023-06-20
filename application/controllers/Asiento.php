<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asiento extends CI_Controller {

	private $permisos;

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->library('form_validation');
		$this->load->model('configuracion_model'); 
		$this->load->model('asiento_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
	}

	public function _remap($method, $params = array()){
    
	    if(!method_exists($this, $method))
	      {
	       $this->index($method, $params);
	    }else{
	      return call_user_func_array(array($this, $method), $params);
	    }
  	}

	public function menus()
	{
		$menu=array(
					'menus' =>  $this->menu_model->lista_opciones_principal('1',$this->session->userdata('s_idusuario')),
					'sbmopciones' =>  $this->menu_model->lista_opciones_submenu('1',$this->session->userdata('s_idusuario'),$this->permisos->sbm_id),
					'actual'=>$this->permisos->men_id,
					'actual_sbm'=>$this->permisos->sbm_id,
					'actual_opc'=>$this->permisos->opc_id
				);
		return $menu;
	}
	
	

	public function index($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		///buscador 
		if($_POST){
			$text= trim($this->input->post('txt'));
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_asientos=$this->asiento_model->lista_asientos_buscador($rst_cja->emp_id,$f1,$f2,$text);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_asientos=$this->asiento_model->lista_asientos_buscador($rst_cja->emp_id,$f1,$f2,$text);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'asientos'=>$cns_asientos,
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('asiento/lista',$data);
		$modulo=array('modulo'=>'asiento');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'opc_id'=>$rst_opc->opc_id,
						'asiento'=> (object) array(
											'con_asiento'=>'',
											'con_concepto'=>'',
											'con_fecha_emision'=>date('Y-m-d'),
											'mod_id'=>'0',
											'doc_id'=>'0',
											'cli_id'=>'0',
											'emp_id'=>$rst_cja->emp_id,
										),
						'cns_det'=>(object) array(),
						'action'=>base_url().'asiento/guardar/'.$opc_id
						);
			$this->load->view('asiento/form',$data);
			$modulo=array('modulo'=>'asiento');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$con_fecha_emision = $this->input->post('con_fecha_emision');
		$con_concepto = $this->input->post('con_concepto');
		$emp_id = $this->input->post('emp_id');
		$doc_id = $this->input->post('doc_id');
		$mod_id = $this->input->post('mod_id');
		$cli_id = $this->input->post('cli_id');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('con_fecha_emision','Fecha Emision','required');
		$this->form_validation->set_rules('con_concepto','Concepto','required');

		if($this->form_validation->run()){
			$n=0;
			$v=0;
			$asiento =$this->asiento_model->siguiente_asiento_manual();
		    while($n<$count_det){
			    $n++;
			    if($this->input->post("con_documento$n")!=''){
			    	$con_documento = $this->input->post("con_documento$n");
			    	$con_concepto_debe = $this->input->post("con_concepto_debe$n");
			    	$con_concepto_haber = $this->input->post("con_concepto_haber$n");
					$con_valor_debe = $this->input->post("con_valor_debe$n");
			    	$con_valor_haber = $this->input->post("con_valor_haber$n");
					$data=array(
							 	'con_asiento'=>trim($asiento),
								'con_concepto'=>$con_concepto,
								'con_documento'=>$con_documento,
								'con_fecha_emision'=>$con_fecha_emision,
								'con_concepto_debe'=>$con_concepto_debe,
								'con_concepto_haber'=>$con_concepto_haber,
								'con_valor_debe'=>$con_valor_debe,
								'con_valor_haber'=>$con_valor_haber,
								'con_estado'=>'1',
								'doc_id'=>$doc_id,
								'cli_id'=>$cli_id,
								'mod_id'=>$mod_id,
								'emp_id'=>$emp_id,

					);
					if(!$this->asiento_model->insert($data)){
						$v+=1;
					}
				}
			}			

			if($v==0){	
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ASIENTOS CONTABLES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$asiento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'asiento/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'asiento/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_actualizar){
			$cns_det=array();
			$detalle=$this->asiento_model->lista_detalle_asiento($id);
			foreach ($detalle as $det) {
				$cta_db=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo($det->con_concepto_debe);
				
				if(!empty($cta_db->pln_descripcion)){
					$des_db=$cta_db->pln_descripcion;	
				}else{
					$des_db='';
				}
				$cta_hb=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo($det->con_concepto_haber);
				if(!empty($cta_hb->pln_descripcion)){
					$des_hb=$cta_hb->pln_descripcion;	
				}else{
					$des_hb='';
				}
				
				$dt= (object) array(
							'con_documento'=>$det->con_documento,
							'con_concepto_debe'=>$det->con_concepto_debe,
							'con_concepto_haber'=>$det->con_concepto_haber,
							'con_descripcion_debe'=>$des_db,
							'con_descripcion_haber'=>$des_hb,
							'con_valor_debe'=>$det->con_valor_debe,
							'con_valor_haber'=>$det->con_valor_haber,
						);
				array_push($cns_det, $dt);
			}
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'opc_id'=>$rst_opc->opc_id,
						'asiento'=> $this->asiento_model->lista_un_asiento($id),
						'cns_det'=>$cns_det,
						'action'=>base_url().'asiento/actualizar/'.$opc_id,
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('asiento/form',$data);
			$modulo=array('modulo'=>'asiento');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$asiento=$this->input->post('con_asiento');
		$con_fecha_emision = $this->input->post('con_fecha_emision');
		$con_concepto = $this->input->post('con_concepto');
		$emp_id = $this->input->post('emp_id');
		$doc_id = $this->input->post('doc_id');
		$mod_id = $this->input->post('mod_id');
		$cli_id = $this->input->post('cli_id');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('con_fecha_emision','Fecha Emision','required');
		$this->form_validation->set_rules('con_concepto','Concepto','required');

		if($this->form_validation->run()){
			//borra asiento
			$this->asiento_model->delete($asiento);

			$n=0;
			$v=0;
		    while($n<$count_det){
			    $n++;
			    if($this->input->post("con_documento$n")!=''){
			    	$con_documento = $this->input->post("con_documento$n");
			    	$con_concepto_debe = $this->input->post("con_concepto_debe$n");
			    	$con_concepto_haber = $this->input->post("con_concepto_haber$n");
					$con_valor_debe = $this->input->post("con_valor_debe$n");
			    	$con_valor_haber = $this->input->post("con_valor_haber$n");
					$data=array(
							 	'con_asiento'=>trim($asiento),
								'con_concepto'=>$con_concepto,
								'con_documento'=>$con_documento,
								'con_fecha_emision'=>$con_fecha_emision,
								'con_concepto_debe'=>$con_concepto_debe,
								'con_concepto_haber'=>$con_concepto_haber,
								'con_valor_debe'=>$con_valor_debe,
								'con_valor_haber'=>$con_valor_haber,
								'con_estado'=>'1',
								'doc_id'=>$doc_id,
								'cli_id'=>$cli_id,
								'mod_id'=>$mod_id,
								'emp_id'=>$emp_id,

					);
					if(!$this->asiento_model->insert($data)){
						$v+=1;
					}
				}
			}	

			if($v==0){	
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ASIENTOS CONTABLES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$asiento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'asiento/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'asiento/editar/'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	
	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->asiento_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PLAN DE CUENTAS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'asiento';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='PLAN DE CUENTAS';
    	$file="asientos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
    
    public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'pln_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'pln_id'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->asiento_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PLAN DE CUENTAS',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$id." ".$estado,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo "1";
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				echo "0";
			}
		
	}
    
	public function traer_cuenta($id){
		$rst=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo(trim($id));
		
		if(!empty($rst)){

			$data=array(
						'pln_id'=>$rst->pln_id,
						'pln_codigo'=>$rst->pln_codigo,
						'pln_descripcion'=>$rst->pln_descripcion,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}

	

	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Asientos Contables ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"asiento/reporte/$id",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'asiento');
			$this->load->view('layout/footer',$modulo);
		}
    }

	public function reporte($id){
		require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','A4',0);
	    $pdf->AddFont('Calibril','');//$pdf->SetFont('Calibri-Light', '', 9);
        $pdf->AddFont('Calibri-bold','');//$pdf->SetFont('Calibri-bold', '', 9);
	    $pdf->AliasNbPages();
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $rst1=$this->asiento_model->lista_un_asiento($id);
	    $emisor=$this->empresa_model->lista_una_empresa($rst1->emp_id);
	    $pdf->SetX(50);
        $pdf->Ln();

        $pdf->SetFont('Calibril', '', 9);
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


	    //$pdf->SetFont('helvetica', 'B', 12);

	    $pdf->SetFont('Calibri-bold', '', 14);
        $pdf->Cell(200, 15, "REPORTE LIBRO DIARIO", 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Calibri-bold', '', 13);
        $pdf->Cell(85, 5, "PERIODO: " . $rst1->con_fecha_emision, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(85, 5, "MONEDA: DOLAR", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(30, 5, "ASIENTO No.: ", 0, 0, 'L');
        $pdf->SetTextColor(255,0, 0);
        $pdf->Cell(85, 5,$rst1->con_asiento, 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetTextColor(0,0, 0);

        $pdf->SetFont('Calibri-bold', '', 11);
        $pdf->Cell(6, 5, "No", 'TB', 0, 'C');
        $pdf->Cell(18, 5, "F. EMISION", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "CODIGO", 'TB', 0, 'C');
        $pdf->Cell(45, 5, "CUENTA", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "DOCUMENTO", 'TB', 0, 'C');
        $pdf->Cell(40, 5, "CONCEPTO", 'TB', 0, 'C');
        $pdf->Cell(17, 5, "DEBE", 'TB', 0, 'C');
        $pdf->Cell(17, 5, "HABER", 'TB', 0, 'C');
        $pdf->Ln();
        
        $cns_cuentas = $this->asiento_model->lista_detalle_asiento($id);	
        $cuentas = Array();
        foreach($cns_cuentas as $rst_cuentas) {
            if (!empty($rst_cuentas->con_concepto_debe)) {
                array_push($cuentas, $rst_cuentas->con_concepto_debe . '&' . $rst_cuentas->con_id . '&0');
            }

            if (!empty($rst_cuentas->con_concepto_haber)) {
                array_push($cuentas, $rst_cuentas->con_concepto_haber . '&' . $rst_cuentas->con_id . '&1');
            }
        }

        //Eliminar Duplicados del Array
        $n = 0;
        $j = 1;
        $td = 0;
        $th = 0;
        
        $pdf->SetFont('Calibril', '', 10);

        while ($n < count($cuentas)) {
            $cta = explode('&', $cuentas[$n]);
            $rst_cuentas1 = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($cta[0]);
            $vdebe=0;
            $vhaber=0;
            if ($cta[2] == 0) {
                $rst_v = $this->asiento_model->listar_asientos_debe($rst1->con_asiento, $cta[0], $cta[1]);
	                $vdebe=$rst_v->con_valor_debe;
	                $vhaber = 0;
            } else {
                $rst_v = $this->asiento_model->listar_asientos_haber($rst1->con_asiento, $cta[0], $cta[1]);
	                $vdebe = 0;
	                $vhaber=$rst_v->con_valor_haber;
            }
            $pdf->Cell(5, 5, $j, 0, 0, 'L');
            $pdf->Cell(18, 5, $rst_v->con_fecha_emision, 0, 0, 'L');
            $pdf->Cell(25, 5, $rst_cuentas1->pln_codigo, 0, 0, 'L');
            $pdf->Cell(45, 5, substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 24), 0, 0, 'L');
            $pdf->Cell(30, 5, $rst_v->con_documento, 0, 0, 'L');
            $pdf->Cell(40, 5, substr($rst_v->con_concepto, 0, 30), 0, 0, 'L');
            $pdf->Cell(17, 5, number_format($vdebe, $dec), 0, 0, 'R');
            $pdf->Cell(17, 5, number_format($vhaber, $dec), 0, 0, 'R');
            $pdf->Ln();
            $n++;
            $j++;
            $td+= round($vdebe, $dec);
            $th+= round($vhaber, $dec);
        }
        $pdf->Cell(202, 7, '', 'T', 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Calibri-bold', '', 10);
        $pdf->Cell(160, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(20, 5, number_format($td, $dec), '', 0, 'R');
        $pdf->Cell(18, 5, number_format($th, $dec), '', 0, 'R');
        $pdf->Ln();
    	

	    $pdf->Output('asiento.pdf' , 'I' );
	}    

	public function reporte_cruce($id){
		require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','A4',0);
	    $pdf->AliasNbPages();
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $rst1=$this->asiento_model->lista_un_asiento_cruce($id);
	    $emisor=$this->empresa_model->lista_una_empresa($rst1->emp_id);
	    $pdf->Ln();
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 5, utf8_decode($emisor->emp_nombre), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(190, 5, $emisor->emp_identificacion, 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Ln();
        $pdf->Cell(190, 5, $emisor->emp_ciudad."-".$emisor->emp_pais, 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(190, 5, "TELEFONO: " . $emisor->emp_telefono, 0, 0, 'L');
        $pdf->SetX(0);
        $pdf->Cell(190, 5, $pdf->Image('./imagenes/'.$emisor->emp_logo, 175, 4, 25), 0, 0, 'R');
        $pdf->setY(30);
        
	    $pdf->SetFont('Arial','B',16);
	    $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(200, 15, "REPORTE LIBRO DIARIO", 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(85, 5, "PERIODO: " . $rst1->con_fecha_emision, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(85, 5, "RUC: " . $emisor->emp_identificacion, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(85, 5, "RAZON SOCIAL: $emisor->emp_nombre", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(85, 5, "MONEDA: DOLAR", 0, 0, 'L');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(85, 5, "ASIENTO No.: " . $rst1->con_asiento, 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(5, 5, "No", 'TB', 0, 'C');
        $pdf->Cell(18, 5, "F. EMISION", 'TB', 0, 'C');
        $pdf->Cell(25, 5, "CODIGO", 'TB', 0, 'C');
        $pdf->Cell(45, 5, "CUENTA", 'TB', 0, 'C');
        $pdf->Cell(30, 5, "DOCUMENTO", 'TB', 0, 'C');
        $pdf->Cell(40, 5, "CONCEPTO", 'TB', 0, 'C');
        $pdf->Cell(17, 5, "DEBE", 'TB', 0, 'C');
        $pdf->Cell(17, 5, "HABER", 'TB', 0, 'C');
        $pdf->Ln();
        
        $cns_cuentas = $this->asiento_model->lista_detalle_asiento_cruce($id);	
        $cuentas = Array();
        foreach($cns_cuentas as $rst_cuentas) {
        	//var_dump($rst_cuentas);
            if (!empty($rst_cuentas->con_concepto_debe)) {
                array_push($cuentas, $rst_cuentas->con_concepto_debe . '&' . $rst_cuentas->con_id . '&0');
            }else{
                array_push($cuentas, $rst_cuentas->con_concepto_debe . '&' . $rst_cuentas->con_id . '&0');
            }

            if (!empty($rst_cuentas->con_concepto_haber)) {
                array_push($cuentas, $rst_cuentas->con_concepto_haber . '&' . $rst_cuentas->con_id . '&1');
            }else{
                array_push($cuentas, $rst_cuentas->con_concepto_haber . '&' . $rst_cuentas->con_id . '&1');
            }
        }

        //Eliminar Duplicados del Array
        $n = 0;
        $j = 1;
        $td = 0;
        $th = 0;
        $pdf->SetFont('helvetica', '', 7);

        while ($n < count($cuentas)) {
            $cta = explode('&', $cuentas[$n]);
            $rst_cuentas1 = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($cta[0]);
            $vdebe=0;
            $vhaber=0;
            if ($cta[2] == 0) {
                $rst_v = $this->asiento_model->listar_asientos_debe($rst1->con_asiento, $cta[0], $cta[1]);
	                $vdebe=$rst_v->con_valor_debe;
	                $vhaber = 0;
            } else {
                $rst_v = $this->asiento_model->listar_asientos_haber($rst1->con_asiento, $cta[0], $cta[1]);
	                $vdebe = 0;
	                $vhaber=$rst_v->con_valor_haber;
            }
            $pdf->Cell(5, 5, $j, 0, 0, 'L');
            $pdf->Cell(18, 5, $rst_v->con_fecha_emision, 0, 0, 'L');
            $pdf->Cell(25, 5, $rst_cuentas1->pln_codigo, 0, 0, 'L');
            $pdf->Cell(45, 5, substr(strtoupper($rst_cuentas1->pln_descripcion), 0, 24), 0, 0, 'L');
            $pdf->Cell(30, 5, $rst_v->con_documento, 0, 0, 'L');
            $pdf->Cell(40, 5, substr($rst_v->con_concepto, 0, 30), 0, 0, 'L');
            $pdf->Cell(17, 5, number_format($vdebe, $dec), 0, 0, 'R');
            $pdf->Cell(17, 5, number_format($vhaber, $dec), 0, 0, 'R');
            $pdf->Ln();
            $n++;
            $j++;
            $td+= round($vdebe, $dec);
            $th+= round($vhaber, $dec);
        }
        $pdf->Cell(202, 7, '', 'T', 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(160, 5, 'TOTAL ', '', 0, 'R');
        $pdf->Cell(20, 5, number_format($td, $dec), '', 0, 'R');
        $pdf->Cell(18, 5, number_format($th, $dec), '', 0, 'R');
        $pdf->Ln();

    

	    $pdf->Output('asiento.pdf' , 'I' );
	}

}
