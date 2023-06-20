<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipo extends CI_Controller {

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
		$this->load->model('tipo_model');
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
					'tipos'=>$this->tipo_model->lista_tipos()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('tipo/lista',$data);
		$modulo=array('modulo'=>'tipo');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'categorias'=>$this->tipo_model->lista_categorias(),
						'familias'=>$this->tipo_model->lista_familias('0'),
						'tipo'=> (object) array(
											'tps_tipo'=>'',
											'tps_relacion'=>'1',
											'tps_familia'=>'0',
											'tps_siglas'=>'',
											'tps_nombre'=>'',
											// 'tps_densidad'=>'0',
											'tps_estado'=>'1',
											'tps_id'=>''
										),
						'action'=>base_url().'tipo/guardar'
						);
			$this->load->view('tipo/form',$data);
			$modulo=array('modulo'=>'tipo');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function traer_familias($ct){
		$familias=$this->tipo_model->lista_familias($ct);
		$lista="<option value='0'>SELECCIONE</option>";
		foreach ($familias as $rst) {
			$lista.="<option value='$rst->tps_id'>$rst->tps_nombre</option>";
		}
		 echo $lista;
	}	

	public function guardar(){
		$categoria = $this->input->post('tps_tipo');
		$relacion = $this->input->post('tps_relacion');
		$familia = $this->input->post('tps_familia');
		$siglas = $this->input->post('tps_siglas');
		$nombre = $this->input->post('tps_nombre');
		//$densidad = $this->input->post('tps_densidad');
		$estado = $this->input->post('tps_estado');

		$this->form_validation->set_rules('tps_tipo','Categoria','required');
		if($relacion==2){
			$this->form_validation->set_rules('tps_familia','Familia','required');
		}
		$this->form_validation->set_rules('tps_siglas','Siglas','required');
		$this->form_validation->set_rules('tps_nombre','Nombre','required');
		//$this->form_validation->set_rules('tps_densidad','Densidad','required');

		if($this->form_validation->run()){
			$data=array(
						 'tps_tipo'=>$categoria,
						 'tps_relacion'=>$relacion,
						 'tps_familia'=>$familia,
						 'tps_siglas'=>$siglas,
						 'tps_nombre'=>$nombre,
						 //'tps_densidad'=>$densidad,
						 'tps_estado'=>$estado,
			);	
			if($this->tipo_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TIPOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'tipo');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'tipo/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$rst=$this->tipo_model->lista_un_tipo($id);
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'categorias'=>$this->tipo_model->lista_categorias(),
						'familias'=>$this->tipo_model->lista_familias($rst->tps_tipo),
						'tipo'=>$this->tipo_model->lista_un_tipo($id),
						'action'=>base_url().'tipo/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('tipo/form',$data);
			$modulo=array('modulo'=>'tipo');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('tps_id');
		$categoria = $this->input->post('tps_tipo');
		$relacion = $this->input->post('tps_relacion');
		$familia = $this->input->post('tps_familia');
		$siglas = $this->input->post('tps_siglas');
		$nombre = $this->input->post('tps_nombre');
		//$densidad = $this->input->post('tps_densidad');
		$estado = $this->input->post('tps_estado');

		$this->form_validation->set_rules('tps_tipo','Categoria','required');
		if($relacion==2){
			$this->form_validation->set_rules('tps_familia','Familia','required');
		}
		$this->form_validation->set_rules('tps_siglas','Siglas','required');
		$this->form_validation->set_rules('tps_nombre','Nombre','required');
		//$this->form_validation->set_rules('tps_densidad','Densidad','required');

		if($this->form_validation->run()){
			$data=array(
						 'tps_tipo'=>$categoria,
						 'tps_relacion'=>$relacion,
						 'tps_familia'=>$familia,
						 'tps_siglas'=>$siglas,
						 'tps_nombre'=>$nombre,
						 //'tps_densidad'=>$densidad,
						 'tps_estado'=>$estado,
			);	

			if($this->tipo_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TIPOS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'tipo');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'tipo/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'tipo'=>$this->tipo_model->lista_un_tipo($id)
						);
			$this->load->view('tipo/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->tipo_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TIPOS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'tipo';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='Tipos';
    	$file="tipos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
