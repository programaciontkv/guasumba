<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_cuentas extends CI_Controller {

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
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->library('html4pdf');
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

		if($_POST){
			$text= trim($this->input->post('txt'));
			$est= $this->input->post('estado');	
			if($est!=""){
				$txt_est="$est";
			}else{
				$txt_est="";
			}
			$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_buscador($text,$txt_est);
		}else{
			$text= '';
			$txt_est="1";
			$est="1";
			$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_buscador($text,$txt_est);
		}


		$data=array(
					'permisos'=>$this->permisos,
					'cuentas'=>$cuentas,
					'opc_id'=>$rst_opc->opc_id,
					'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
					'txt'=>$text,
					'estado'=>$est,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('plan_cuentas/lista',$data);
		$modulo=array('modulo'=>'plan_cuentas');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'opc_id'=>$rst_opc->opc_id,
						'cuenta'=> (object) array(
											'pln_codigo'=>'',
											'pln_descripcion'=>'',
											'pln_tipo'=>'1',
											'pln_estado'=>'1',
											'pln_obs'=>'',
											'pln_operacion'=>'0',
											'pln_id'=>''
										),
						'action'=>base_url().'plan_cuentas/guardar/'.$opc_id
						);
			$this->load->view('plan_cuentas/form',$data);
			$modulo=array('modulo'=>'plan_cuentas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$pln_codigo = $this->input->post('pln_codigo');
		$pln_descripcion = $this->input->post('pln_descripcion');
		$pln_tipo = $this->input->post('pln_tipo');
		$pln_estado = $this->input->post('pln_estado');
		$pln_obs = $this->input->post('pln_obs');
		$pln_operacion = $this->input->post('pln_operacion');
		
		$this->form_validation->set_rules('pln_codigo','Codigo','required|is_unique[erp_plan_cuentas.pln_codigo]');
		$this->form_validation->set_rules('pln_descripcion','Descripcion','required');
		$this->form_validation->set_rules('pln_tipo','Tipo','required');

		if($this->form_validation->run()){
			$data=array(
						 					'pln_codigo'=>trim($pln_codigo),
											'pln_descripcion'=>$pln_descripcion,
											'pln_tipo'=>$pln_tipo,
											'pln_estado'=>$pln_estado,
											'pln_obs'=>$pln_obs,
											'pln_operacion'=>$pln_operacion,
			);	

			if($this->plan_cuentas_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PLAN DE CUENTAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pln_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'plan_cuentas/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'plan_cuentas/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuenta'=>$this->plan_cuentas_model->lista_un_plan_cuentas($id),
						'action'=>base_url().'plan_cuentas/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('plan_cuentas/form',$data);
			$modulo=array('modulo'=>'plan_cuentas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('pln_id');
		$pln_codigo = $this->input->post('pln_codigo');
		$pln_descripcion = $this->input->post('pln_descripcion');
		$pln_tipo = $this->input->post('pln_tipo');
		$pln_estado = $this->input->post('pln_estado');
		$pln_obs = $this->input->post('pln_obs');
		$pln_operacion = $this->input->post('pln_operacion');
		
		$plan_cuentas_act=$this->plan_cuentas_model->lista_un_plan_cuentas($id);

		if($pln_codigo==$plan_cuentas_act->pln_codigo){
			$unique='';
		}else{
			$unique='|is_unique[erp_plan_cuentas.pln_codigo]';
		}

		$this->form_validation->set_rules('pln_codigo','Codigo','required'.$unique);
		$this->form_validation->set_rules('pln_descripcion','Descripcion','required');
		$this->form_validation->set_rules('pln_tipo','Tipo','required');

		if($this->form_validation->run()){
			$data=array(
						 					'pln_codigo'=>trim($pln_codigo),
											'pln_descripcion'=>$pln_descripcion,
											'pln_tipo'=>$pln_tipo,
											'pln_estado'=>$pln_estado,
											'pln_obs'=>$pln_obs,
											'pln_operacion'=>$pln_operacion,
			);	

			if($this->plan_cuentas_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PLAN DE CUENTAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'plan_cuentas/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'plan_cuentas/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'cuenta'=>$this->plan_cuentas_model->lista_un_plan_cuentas($id)
						);
			$this->load->view('plan_cuentas/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->plan_cuentas_model->delete($id)){
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
				echo 'plan_cuentas';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='PLAN DE CUENTAS';
    	$file="plan_cuentass".date('Ymd');
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

		    if($this->plan_cuentas_model->update($id,$data)){
		    	
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
    
	function traer_cuenta($id){
		if(strlen($id)==14){
			$id=$id.'.';
		}
		$vl=explode('.', $id);
		$n=count($vl)-2;
		if($n>0){
			$m=0;
			$cod="";
			while($m<$n){
				$cod.=$vl[$m].".";
				$m++;
			}
			$rst=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo($cod);
			if(!empty($rst)){
				$data=array(
						'cuenta'=>'',
						'resp'=>0,
				);
				echo json_encode($data);
			}else{
				$data=array(
						'cuenta'=>$cod,
						'resp'=>0,
				);
				echo json_encode($data);	
			}	
		}else{
			$data=array(
				'resp'=>1,
			);
			echo json_encode($data);
		}
	}

	public  function show_rpt_pdf($opc_id){

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		if($_POST){
			$text= trim($this->input->post('txt'));
			$est= $this->input->post('estado');	
			if($est!=""){
				$txt_est="$est";
			}else{
				$txt_est="";
			}
			if($text!=""){
				$text="$text";
			}else{
				$text="";
			}
			$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_buscador($text,$txt_est);
		}else{
			$text= '';
			$txt_est="1";
			$est="1";
			$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_buscador($text,$txt_est);
		}

		if($permisos->rop_reporte){
		$data=array(
					'permisos'=>$this->permisos,
					'cuentas'=>$cuentas,
					'opc_id'=>$rst_opc->opc_id,
					'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
					'txt'=>$text,
					'estado'=>$est,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"plan_cuentas/show_pdf_2/$txt_est/$opc_id/$text",
					'titulo'=>'Plan de cuentas - '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
				);

			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'ctasxcobrar');
			$this->load->view('layout/footer',$modulo);

	}
}

	public function show_pdf_2($txt_est='',$opc_id,$text=''){


		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_buscador($text,$txt_est);

		$data=array(
						'permisos'=>$this->permisos,
						'cuentas'=>$cuentas,
						'titulo'=>'Plan de cuentas - '.ucfirst(strtolower($rst_cja->emp_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'emp' => $this->empresa_model->lista_una_empresa(1),
						
						);
	 //$this->load->view('pdf/pdf_plan',$data);

		$this->html4pdf->filename('plan_cuentas.pdf');
		$this->html4pdf->paper('a4', 'landscape');
		$this->html4pdf->html(utf8_decode($this->load->view('pdf/pdf_plan', $data, true)));
		$this->html4pdf->output(array("Attachment" => 0));
	}


}
