<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estado extends CI_Controller {

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
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
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
					'estados'=>$this->estado_model->lista_estados()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('estado/lista',$data);
		$modulo=array('modulo'=>'estado');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estado'=> (object) array(
					                        'est_descripcion'=>'',
					                        'est_orden'=>'',
					                        'est_color'=>'',
					                        'est_id'=>''
										),
						'action'=>base_url().'estado/guardar'
						);
			$this->load->view('estado/form',$data);
			$modulo=array('modulo'=>'estado');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$est_descripcion = $this->input->post('est_descripcion');
		$est_orden = $this->input->post('est_orden');
		$est_color = $this->input->post('est_color');

		$this->form_validation->set_rules('est_descripcion','Descripcion','required|is_unique[erp_estados.est_descripcion]');
		$this->form_validation->set_rules('est_orden','Orden','required');

		if($this->form_validation->run()){
			$data=array(
						'est_descripcion'=>$est_descripcion,
					    'est_orden'=>$est_orden,
					    'est_color'=>$est_color,
			);	

			if($this->estado_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ESTADOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'estado');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'estado/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estado'=>$this->estado_model->lista_un_estado($id),
						'action'=>base_url().'estado/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('estado/form',$data);
			$modulo=array('modulo'=>'estado');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('est_id');
		$est_descripcion = $this->input->post('est_descripcion');
		$est_orden = $this->input->post('est_orden');
		$est_color = $this->input->post('est_color');
		
		$estado_act=$this->estado_model->lista_un_estado($id);

		if($est_descripcion==$estado_act->est_descripcion){
			$unique='';
		}else{
			$unique='|is_unique[erp_estados.est_descripcion]';
		}

		$this->form_validation->set_rules('est_descripcion','Descripcion','required'.$unique);
		$this->form_validation->set_rules('est_orden','Orden','required');

		if($this->form_validation->run()){
			$data=array(
						'est_descripcion'=>$est_descripcion,
					    'est_orden'=>$est_orden,
					    'est_color'=>$est_color,
			);	

			if($this->estado_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ESTADOS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'estado');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'estado/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'estado'=>$this->estado_model->lista_un_estado($id)
						);
			$this->load->view('estado/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->estado_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ESTADOS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'estado';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function asignar($id){
		$data=array(
					'estado'=>$this->estado_model->lista_un_estado($id),
					'opciones'=>$this->opcion_model->lista_opciones_estados($id),
					'listas'=>$this->estado_model->lista_estados_opcion($id),
					'action'=>base_url().'estado/guardar_opcion'
					);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('estado/form_asignar',$data);
		$modulo=array('modulo'=>'estado');
		$this->load->view('layout/footer',$modulo);
	}

	public function guardar_opcion(){
		$est_id = $this->input->post('est_id');
		$opc_id = $this->input->post('opc_id');

			$data=array(
						 'est_id'=>$est_id,
						 'opc_id'=>$opc_id,
			);	

			if($this->estado_model->insert_opcion($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ESTADOS',
								'adt_accion'=>'ASIGNAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'estado/asignar/'.$est_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'estado/asignar/'.$est_id);
			}
			
	}

	public function eliminar_opcion($id,$estado,$nombre){
		if($this->estado_model->delete_opcion($id)){
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ESTADOS',
								'adt_accion'=>'ELMINAR',
								'adt_campo'=>'',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'$nombre',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			echo 'estado/asignar/'.$estado;
		}
	}

	public function excel($opc_id){

    	$titulo='Estados';
    	$file="estados".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
	
}
