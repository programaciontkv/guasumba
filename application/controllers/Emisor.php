<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class emisor extends CI_Controller {

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
		$this->load->model('emisor_model');
		$this->load->model('cliente_model');
		$this->load->model('configuracion_model');
		$this->load->model('empresa_model');
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
					'emisores'=>$this->emisor_model->lista_emisores(),
					
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('emisor/lista',$data);
		$modulo=array('modulo'=>'emisor');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'empresas'=>$this->empresa_model->lista_empresas_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'credenciales'=>$this->configuracion_model->lista_credenciales(),
						'emisor'=> (object) array(
											'emi_id'=>'',
					                        'emi_nombre'=>'',
					                        'emi_dir_establecimiento_emisor'=>'',
					                        'emi_cod_punto_emision'=>'1',
					                        'emi_cod_orden'=>'0',
					                        'emi_cod_cli'=>'',
					                        'emi_credencial'=>'',
					                        'emi_telefono'=>'',
					                        'emi_ciudad'=>'',
					                        'emi_pais'=>'',
					                        'emi_email'=>'',
					                        'emi_estado'=>'1',
					                        'emp_id'=>'',
					                        'cli_raz_social'=>'',
										),
						'action'=>base_url().'emisor/guardar'
						);
			$this->load->view('emisor/form',$data);
			$modulo=array('modulo'=>'emisor');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$emp_id= $this->input->post('emp_id');
		$emi_identificacion= $this->input->post('emi_identificacion');
		$emi_nombre = $this->input->post('emi_nombre');
		$emi_dir_establecimiento_emisor = $this->input->post('emi_dir_establecimiento_emisor');
		$emi_cod_punto_emision = $this->input->post('emi_cod_punto_emision');
		$emi_credencial = $this->input->post('emi_credencial');
		$emi_cod_cli = $this->input->post('emi_cod_cli');
		$emi_cod_orden = $this->input->post('emi_cod_orden');
		$emi_email = $this->input->post('emi_email');
		$emi_telefono = $this->input->post('emi_telefono');
		$emi_ciudad = $this->input->post('emi_ciudad');
		$emi_pais = $this->input->post('emi_pais');
		$emi_estado = $this->input->post('emi_estado');

		$this->form_validation->set_rules('emp_id','Empresa','required');
		$this->form_validation->set_rules('emi_nombre','Nombre','required');
		$this->form_validation->set_rules('emi_pais','Pais','required');
		$this->form_validation->set_rules('emi_ciudad','Ciudad','required');
		$this->form_validation->set_rules('emi_dir_establecimiento_emisor','Direccion','required');
		$this->form_validation->set_rules('emi_cod_cli','Cliente','required');
		$this->form_validation->set_rules('emi_cod_orden','Orden','required');
		$this->form_validation->set_rules('emi_cod_punto_emision','Codigo Punto Emision','required');
		if($this->form_validation->run()){
			$data=array(
					    'emp_id'=>$emp_id,
					    'emi_nombre'=>$emi_nombre,
					    'emi_dir_establecimiento_emisor'=>$emi_dir_establecimiento_emisor,
					    'emi_cod_punto_emision'=>$emi_cod_punto_emision,
					    'emi_cod_orden'=>$emi_cod_orden,
					    'emi_cod_cli'=>$emi_cod_cli,
					    'emi_credencial'=>$emi_credencial,
					    'emi_telefono'=>$emi_telefono,
					    'emi_ciudad'=>$emi_ciudad,
					    'emi_pais'=>$emi_pais,
					    'emi_email'=>$emi_email,
					    'emi_estado'=>$emi_estado,
			);	
			$emi_id=$this->emisor_model->insert($data);
			if($emi_id!=''){
				$this->emisor_model->insert_ctas_asientos($emi_id);
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PUNTOS DE EMISION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'emisor');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'emisor/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'credenciales'=>$this->configuracion_model->lista_credenciales(),
						'empresas'=>$this->empresa_model->lista_empresas_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'emisor'=>$this->emisor_model->lista_un_emisor($id),
						'action'=>base_url().'emisor/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('emisor/form',$data);
			$modulo=array('modulo'=>'emisor');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('emi_id');
	$emp_id= $this->input->post('emp_id');
		$emi_identificacion= $this->input->post('emi_identificacion');
		$emi_nombre = $this->input->post('emi_nombre');
		$emi_dir_establecimiento_emisor = $this->input->post('emi_dir_establecimiento_emisor');
		$emi_cod_punto_emision = $this->input->post('emi_cod_punto_emision');
		$emi_credencial = $this->input->post('emi_credencial');
		$emi_cod_cli = $this->input->post('emi_cod_cli');
		$emi_cod_orden = $this->input->post('emi_cod_orden');
		$emi_email = $this->input->post('emi_email');
		$emi_telefono = $this->input->post('emi_telefono');
		$emi_ciudad = $this->input->post('emi_ciudad');
		$emi_pais = $this->input->post('emi_pais');
		$emi_estado = $this->input->post('emi_estado');

		$this->form_validation->set_rules('emp_id','Empresa','required');
		$this->form_validation->set_rules('emi_nombre','Nombre','required');
		$this->form_validation->set_rules('emi_pais','Pais','required');
		$this->form_validation->set_rules('emi_ciudad','Ciudad','required');
		$this->form_validation->set_rules('emi_dir_establecimiento_emisor','Direccion','required');
		$this->form_validation->set_rules('emi_cod_cli','Cliente','required');
		$this->form_validation->set_rules('emi_cod_orden','Orden','required');
		$this->form_validation->set_rules('emi_cod_punto_emision','Codigo Punto Emision','required');
		if($this->form_validation->run()){
			$data=array(
					    'emp_id'=>$emp_id,
					    'emi_nombre'=>$emi_nombre,
					    'emi_dir_establecimiento_emisor'=>$emi_dir_establecimiento_emisor,
					    'emi_cod_punto_emision'=>$emi_cod_punto_emision,
					    'emi_cod_orden'=>$emi_cod_orden,
					    'emi_cod_cli'=>$emi_cod_cli,
					    'emi_credencial'=>$emi_credencial,
					    'emi_telefono'=>$emi_telefono,
					    'emi_ciudad'=>$emi_ciudad,
					    'emi_pais'=>$emi_pais,
					    'emi_email'=>$emi_email,
					    'emi_estado'=>$emi_estado,
			);	
			
			if($this->emisor_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PUNTOS DE EMISION',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'emisor');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'emisor/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'emisor'=>$this->emisor_model->lista_un_emisor($id)
						);
			$this->load->view('emisor/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->emisor_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PUNTOS DE EMISION',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'emisor';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente($id);
		if(!empty($rst)){
			$data=array(
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function excel($opc_id){

    	$titulo='Puntos de Emision';
    	$file="puntos_emision".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
