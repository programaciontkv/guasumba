<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bancos_tarjetas extends CI_Controller {

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
		$this->load->model('bancos_tarjetas_model');
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
					'bancos_tarjetas'=>$this->bancos_tarjetas_model->lista_bancos_tajetas()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('bancos_tarjetas/lista',$data);
		$modulo=array('modulo'=>'bancos_tarjetas');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'banco_tarjeta'=> (object) array(
											'btr_id'=>'',
					                        'btr_tipo'=>'',
					                        'btr_forma'=>'0',
					                        'btr_descripcion'=>'',
					                        'btr_estado'=>'1',
					                        'btr_dias'=>'0',
										),
						'action'=>base_url().'bancos_tarjetas/guardar'
						);
			$this->load->view('bancos_tarjetas/form',$data);
			$modulo=array('modulo'=>'bancos_tarjetas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$btr_tipo= $this->input->post('btr_tipo');
		$btr_forma = $this->input->post('btr_forma');
		$btr_descripcion = $this->input->post('btr_descripcion');
		$btr_estado = $this->input->post('btr_estado');
		$btr_dias = $this->input->post('btr_dias');
		
		$this->form_validation->set_rules('btr_descripcion','Descripcion','required');
		

		if($this->form_validation->run()){
			$data=array(
					    'btr_tipo'=>$btr_tipo,
					    'btr_forma'=>$btr_forma,
					    'btr_descripcion'=>$btr_descripcion,
					    'btr_estado'=>$btr_estado,
					    'btr_dias'=>$btr_dias,
			);	

			if($this->bancos_tarjetas_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'BANCOS Y TARJETAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'bancos_tarjetas');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'bancos_tarjetas/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'banco_tarjeta'=>$this->bancos_tarjetas_model->lista_un_banco_tarjeta($id),
						'action'=>base_url().'bancos_tarjetas/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('bancos_tarjetas/form',$data);
			$modulo=array('modulo'=>'bancos_tarjetas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('btr_id');
		$btr_tipo= $this->input->post('btr_tipo');
		$btr_forma = $this->input->post('btr_forma');
		$btr_descripcion = $this->input->post('btr_descripcion');
		$btr_estado = $this->input->post('btr_estado');
		$btr_dias = $this->input->post('btr_dias');

		$this->form_validation->set_rules('btr_descripcion','Descripcion','required');
		
		if($this->form_validation->run()){
			$data=array(
					    'btr_tipo'=>$btr_tipo,
					    'btr_forma'=>$btr_forma,
					    'btr_descripcion'=>$btr_descripcion,
					    'btr_estado'=>$btr_estado,
					    'btr_dias'=>$btr_dias,
			);	
			if($this->bancos_tarjetas_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'BANCOS Y TARJETAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'bancos_tarjetas');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'bancos_tarjetas/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'bancos_tarjetas'=>$this->bancos_tarjetas_model->lista_un_banco_tarjeta($id)
						// 'bancos_tarjetas'=>$this->bancos_tarjetas_model->lista_una_bancos_tarjetas($id)
						);
			$this->load->view('bancos_tarjetas/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->bancos_tarjetas_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'BANCOS Y TARJETAS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'bancos_tarjetas';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}
	
	public function excel($opc_id){

    	$titulo='Bancos, Tarjetas y Plazos ';
    	$file="bancos_tarjetas_plazos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
}
