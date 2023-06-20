<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submenu extends CI_Controller {

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
		$this->load->model('submenu_model');
		$this->load->model('menu_model');
		$this->load->model('opcion_model');
		$this->load->model('auditoria_model');
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
					'submenus'=>$this->submenu_model->lista_submenus()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('submenu/lista',$data);
		$modulo=array('modulo'=>'submenu');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());

			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'submenu'=> (object) array(
											'sbm_nombre'=>'',
											'sbm_estado'=>'1',
											'sbm_id'=>'',
											'sbm_orden'=>'0',
										),
						'action'=>base_url().'submenu/guardar',

						);
			$this->load->view('submenu/form',$data);
			$modulo=array('modulo'=>'submenu');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function guardar(){
		$men_id = $this->input->post('men_id');
		$nombre = $this->input->post('sbm_nombre');
		$estado = $this->input->post('sbm_estado');
		$orden = $this->input->post('sbm_orden');

		$this->form_validation->set_rules('sbm_nombre','Nombre','required|is_unique[erp_submenus.sbm_nombre]');
		
		if($this->form_validation->run()){
			$data=array(
						 'sbm_nombre'=>$nombre,
						 'sbm_estado'=>$estado,
						 'sbm_orden'=>$orden,
			);	

			if($this->submenu_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'SUBMENUS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'submenu');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'submenu/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'submenu'=>$this->submenu_model->lista_un_submenu($id),
						'action'=>base_url().'submenu/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('submenu/form',$data);
			$modulo=array('modulo'=>'submenu');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		$nombre = $this->input->post('sbm_nombre');
		$estado = $this->input->post('sbm_estado');
		$orden = $this->input->post('sbm_orden');
		$id=$this->input->post('sbm_id');

		$submenu_act=$this->submenu_model->lista_un_submenu($id);

		if($nombre==$submenu_act->sbm_nombre){
			$unique='';
		}else{
			$unique='|is_unique[erp_submenus.sbm_nombre]';
		}
		
		$this->form_validation->set_rules('sbm_nombre','Nombre','required'.$unique);
		
		if($this->form_validation->run()){
			$data=array(
						 'sbm_nombre'=>$nombre,
						 'sbm_estado'=>$estado,
						 'sbm_orden'=>$orden,
			);	

			if($this->submenu_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'SUBMENUS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'submenu');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'submenu/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		$data=array(
					'submenu'=>$this->submenu_model->lista_un_menu($id)
					);
		$this->load->view('submenu/visualizar',$data);
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->submenu_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'MENUS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'submenu';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='Subenus';
    	$file="submenus".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

    
	

}
