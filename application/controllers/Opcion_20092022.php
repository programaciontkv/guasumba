<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opcion extends CI_Controller {

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
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		
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

	public function index(){
		$data=array(
					'permisos'=>$this->permisos,
					'opciones'=>$this->opcion_model->lista_opciones()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('opcion/lista',$data);
		$modulo=array('modulo'=>'opcion');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cajas'=>$this->caja_model->lista_cajas_estado('1'),
						'opcion'=> (object) array(
											'opc_nombre'=>'',
											'opc_direccion'=>'',
											'opc_descripcion'=>'',
											'opc_estado'=>'1',
											'opc_id'=>'',
											'opc_orden'=>'0',
											'opc_caja'=>'1'
										),
						'action'=>base_url().'opcion/guardar'
						);
			$this->load->view('opcion/form',$data);
			$modulo=array('modulo'=>'opcion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$nombre = $this->input->post('opc_nombre');
		$direccion = $this->input->post('opc_direccion');
		$descripcion = $this->input->post('opc_descripcion');
		$estado = $this->input->post('opc_estado');
		$orden = $this->input->post('opc_orden');
		$caja = $this->input->post('opc_caja');

		$this->form_validation->set_rules('opc_nombre','Nombre','required');
		$this->form_validation->set_rules('opc_direccion','Direccion','required');
		$this->form_validation->set_rules('opc_caja','Emisor','required');
		$this->form_validation->set_rules('opc_orden','Orden','required');

		if($this->form_validation->run()){
			$data=array(
						 'opc_nombre'=>$nombre,
						 'opc_direccion'=>$direccion,
						 'opc_descripcion'=>$descripcion,
						 'opc_estado'=>$estado,
						 'opc_orden'=>$orden,
						 'opc_caja'=>$caja
			);	

			if($this->opcion_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'OPCIONES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'opcion');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'opcion/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cajas'=>$this->caja_model->lista_cajas_estado('1'),
						'opcion'=>$this->opcion_model->lista_una_opcion($id),
						'action'=>base_url().'opcion/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('opcion/form',$data);
			$modulo=array('modulo'=>'opcion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		$nombre = $this->input->post('opc_nombre');
		$direccion = $this->input->post('opc_direccion');
		$descripcion = $this->input->post('opc_descripcion');
		$estado = $this->input->post('opc_estado');
		$id = $this->input->post('opc_id');
		$orden = $this->input->post('opc_orden');
		$caja = $this->input->post('opc_caja');

		$this->form_validation->set_rules('opc_nombre','Nombre','required');
		$this->form_validation->set_rules('opc_direccion','Direccion','required');
		$this->form_validation->set_rules('opc_caja','Emisor','required');
		$this->form_validation->set_rules('opc_orden','Orden','required');


		if($this->form_validation->run()){
			$data=array(
						 'opc_nombre'=>$nombre,
						 'opc_direccion'=>$direccion,
						 'opc_descripcion'=>$descripcion,
						 'opc_estado'=>$estado,
						 'opc_orden'=>$orden,
						 'opc_caja'=>$caja
			);	

			if($this->opcion_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'OPCIONES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'opcion');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'opcion/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'opcion'=>$this->opcion_model->lista_una_opcion($id)
						);
			$this->load->view('opcion/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->opcion_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'OPCIONES',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'opcion';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='Opciones';
    	$file="opciones".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
