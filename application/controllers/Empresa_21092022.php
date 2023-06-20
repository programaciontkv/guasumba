<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends CI_Controller {

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
		$this->load->model('empresa_model');
		$this->load->model('configuracion_model');
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
					'empresas'=>$this->empresa_model->lista_empresas(),
					
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('empresa/lista',$data);
		$modulo=array('modulo'=>'empresa');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'empresa'=> (object) array(
											'emp_id'=>'',
					                        'emp_identificacion'=>'',
					                        'emp_nombre'=>'',
					                        'emp_direccion'=>'',
					                        'emp_obligado_llevar_contabilidad'=>'NO',
					                        'emp_orden'=>'0',
					                        'emp_contribuyente_especial'=>'0',
					                        'emp_telefono'=>'',
					                        'emp_ciudad'=>'',
					                        'emp_pais'=>'',
					                        'emp_email'=>'',
					                        'emp_logo'=>'',
					                        'emp_estado'=>'1',
					                        'emp_leyenda'=>'0',
					                        'emp_leyenda_sri'=>'',
										),
						'action'=>base_url().'empresa/guardar'
						);
			$this->load->view('empresa/form',$data);
			$modulo=array('modulo'=>'empresa');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$emp_identificacion= $this->input->post('emp_identificacion');
		$emp_nombre = $this->input->post('emp_nombre');
		$emp_direccion = $this->input->post('emp_direccion');
		$emp_obligado_llevar_contabilidad = $this->input->post('emp_obligado_llevar_contabilidad');
		$emp_cod_orden = $this->input->post('emp_cod_orden');
		$emp_contribuyente_especial = $this->input->post('emp_contribuyente_especial');
		$emp_telefono = $this->input->post('emp_telefono');
		$emp_ciudad = $this->input->post('emp_ciudad');
		$emp_pais = $this->input->post('emp_pais');
		$emp_email = $this->input->post('emp_email');
		$emp_logo = $this->input->post('emp_logo');
		$emp_estado = $this->input->post('emp_estado');
		$emp_leyenda = $this->input->post('emp_leyenda');
		$emp_leyenda_sri = $this->input->post('emp_leyenda_sri');
		
		$this->form_validation->set_rules('emp_identificacion','RUC','required|is_unique[erp_empresas.emp_identificacion]');
		$this->form_validation->set_rules('emp_nombre','Nombre','required');
		$this->form_validation->set_rules('emp_pais','Pais','required');
		$this->form_validation->set_rules('emp_ciudad','Ciudad','required');
		$this->form_validation->set_rules('emp_direccion','Direccion Matriz','required');
		$this->form_validation->set_rules('emp_contribuyente_especial','Codigo Tributario','required');
		if($emp_leyenda==1){
			$this->form_validation->set_rules('emp_leyenda_sri','Leyenda SRI','required');
		}
		if($this->form_validation->run()){
			$data=array(
					    'emp_identificacion'=>$emp_identificacion,
					    'emp_nombre'=>$emp_nombre,
					    'emp_direccion'=>$emp_direccion,
					    'emp_obligado_llevar_contabilidad'=>$emp_obligado_llevar_contabilidad,
					    'emp_contribuyente_especial'=>$emp_contribuyente_especial,
					    'emp_telefono'=>$emp_telefono,
					    'emp_ciudad'=>$emp_ciudad,
					    'emp_pais'=>$emp_pais,
					    'emp_estado'=>$emp_estado,
					    'emp_email'=>$emp_email,
					    'emp_logo'=>$emp_logo,
					    'emp_leyenda'=>$emp_leyenda,
					    'emp_leyenda_sri'=>$emp_leyenda_sri,
					   
			);	

			if($this->empresa_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'EMPRESAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'empresa');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'empresa/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'empresa'=>$this->empresa_model->lista_una_empresa($id),
						'action'=>base_url().'empresa/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('empresa/form',$data);
			$modulo=array('modulo'=>'empresa');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('emp_id');
		$emp_identificacion= $this->input->post('emp_identificacion');
		$emp_nombre = $this->input->post('emp_nombre');
		$emp_direccion = $this->input->post('emp_direccion');
		$emp_obligado_llevar_contabilidad = $this->input->post('emp_obligado_llevar_contabilidad');
		$emp_cod_orden = $this->input->post('emp_cod_orden');
		$emp_contribuyente_especial = $this->input->post('emp_contribuyente_especial');
		$emp_telefono = $this->input->post('emp_telefono');
		$emp_ciudad = $this->input->post('emp_ciudad');
		$emp_pais = $this->input->post('emp_pais');
		$emp_email = $this->input->post('emp_email');
		$emp_logo = $this->input->post('emp_logo');
		$emp_estado = $this->input->post('emp_estado');
		$emp_leyenda = $this->input->post('emp_leyenda');
		$emp_leyenda_sri = $this->input->post('emp_leyenda_sri');


		$empresa_act=$this->empresa_model->lista_una_empresa($id);

		if($emp_identificacion==$empresa_act->emp_identificacion){
			$unique='';
		}else{
			$unique='|is_unique[erp_empresas.emp_identificacion]';
		}
		
		$this->form_validation->set_rules('emp_identificacion','RUC','required'.$unique);
		$this->form_validation->set_rules('emp_nombre','Nombre','required');
		$this->form_validation->set_rules('emp_pais','Pais','required');
		$this->form_validation->set_rules('emp_ciudad','Ciudad','required');
		$this->form_validation->set_rules('emp_direccion','Direccion Matriz','required');
		$this->form_validation->set_rules('emp_contribuyente_especial','Codigo Tributario','required');
		if($emp_leyenda==1){
			$this->form_validation->set_rules('emp_leyenda_sri','Leyenda SRI','required');
		}
		if($this->form_validation->run()){
			$data=array(
					    'emp_identificacion'=>$emp_identificacion,
					    'emp_nombre'=>$emp_nombre,
					    'emp_direccion'=>$emp_direccion,
					    'emp_obligado_llevar_contabilidad'=>$emp_obligado_llevar_contabilidad,
					    'emp_contribuyente_especial'=>$emp_contribuyente_especial,
					    'emp_telefono'=>$emp_telefono,
					    'emp_ciudad'=>$emp_ciudad,
					    'emp_pais'=>$emp_pais,
					    'emp_estado'=>$emp_estado,
					    'emp_email'=>$emp_email,
					    'emp_logo'=>$emp_logo,
					    'emp_leyenda'=>$emp_leyenda,
					    'emp_leyenda_sri'=>$emp_leyenda_sri,
			);	
		

			if($this->empresa_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'EMPRESAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'empresa');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'empresa/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'empresa'=>$this->empresa_model->lista_una_empresa($id)
						);
			$this->load->view('empresa/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->empresa_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'EMPRESAS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'empresa';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='Empresas';
    	$file="empresas".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
