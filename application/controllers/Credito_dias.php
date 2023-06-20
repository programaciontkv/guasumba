<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class credito_dias extends CI_Controller {

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
		$this->load->model('credito_dias_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
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
		$data=array(
					'permisos'=>$this->permisos,
					'credito_dias'=>$this->credito_dias_model->lista_credito_dias(),
					'opc_id'=>$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('credito_dias/lista',$data);
		$modulo=array('modulo'=>'credito_dias');
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
						'credito_dias'=> (object) array(
											'cre_id'=>'',
					                        'cre_dias'=>'0',
					                        'cre_descripcion'=>'',
					                        'cre_estado'=>'1'
										),
						'action'=>base_url().'credito_dias/guardar/'.$opc_id
						);
			$this->load->view('credito_dias/form',$data);
			$modulo=array('modulo'=>'credito_dias');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$cre_dias= $this->input->post('cre_dias');
		$cre_descripcion = $this->input->post('cre_descripcion');
		$cre_estado = $this->input->post('cre_estado');
		
		$this->form_validation->set_rules('cre_descripcion','Descripcion','required');
		$this->form_validation->set_rules('cre_dias','Dias de Credito','required');
		

		if($this->form_validation->run()){
			$data=array(
					    'cre_dias'=>$cre_dias,
					    'cre_descripcion'=>$cre_descripcion,
					    'cre_estado'=>$cre_estado
			);	

			if($this->credito_dias_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CREDITO A DIAS ',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'credito_dias/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'credito_dias/nuevo/'.$opc_id);
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
						'credito_dias'=>$this->credito_dias_model->lista_un_credito_dias($id),
						'action'=>base_url().'credito_dias/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('credito_dias/form',$data);
			$modulo=array('modulo'=>'bancos_tarjetas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id               = $this->input->post('cre_id');
		$cre_dias         = $this->input->post('cre_dias');
		$cre_descripcion = $this->input->post('cre_descripcion');
		$cre_estado = $this->input->post('cre_estado');

		$this->form_validation->set_rules('cre_descripcion','Descripcion','required');
		$this->form_validation->set_rules('cre_dias','Dias de Credito','required');
		
		if($this->form_validation->run()){
			$data=array(
					    'cre_dias'=>$cre_dias,
					    'cre_descripcion'=>$cre_descripcion,
					    'cre_estado'=>$cre_estado
			);	
			if($this->credito_dias_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CREDITO DIAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'credito_dias/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'credito_dias/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	
	
	public function excel($opc_id){

    	$titulo='Credito dias ';
    	$file="Credito_dias".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'cre_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'credito_dias'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->credito_dias_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'credito_dias',
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
    
}
