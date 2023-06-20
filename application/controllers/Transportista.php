<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transportista extends CI_Controller {

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
		$this->load->model('transportista_model');
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
					'transportistas'=>$this->transportista_model->lista_transportistas(),
					'opc_id'=>$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('transportista/lista',$data);
		$modulo=array('modulo'=>'transportista');
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
						'transportista'=> (object) array(
											'tra_razon_social'=>'',
											'tra_email'=>'',
											'tra_placa'=>'',
											'tra_estado'=>'1',
											'tra_identificacion'=>'',
											'tra_direccion'=>'',
											'tra_telefono'=>'',
											'tra_id'=>''
										),
						'action'=>base_url().'transportista/guardar/'.$opc_id
						);
			$this->load->view('transportista/form',$data);
			$modulo=array('modulo'=>'transportista');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$tra_razon_social = $this->input->post('tra_razon_social');
		$tra_email = $this->input->post('tra_email');
		$tra_estado = $this->input->post('tra_estado');
		$tra_placa = $this->input->post('tra_placa');
		$tra_identificacion = $this->input->post('tra_identificacion');
		$tra_direccion = $this->input->post('tra_direccion');
		$tra_telefono = $this->input->post('tra_telefono');

		$this->form_validation->set_rules('tra_identificacion','Identificacion','required|is_unique[erp_transportista.tra_identificacion]');
		$this->form_validation->set_rules('tra_razon_social','Nombre','required');
		$this->form_validation->set_rules('tra_placa','Placa','required');
		$this->form_validation->set_rules('tra_direccion','Direccion','required');

		if($this->form_validation->run()){
			$data=array(
						 					'tra_razon_social'=>$tra_razon_social,
											'tra_email'=>$tra_email,
											'tra_placa'=>$tra_placa,
											'tra_estado'=>$tra_estado,
											'tra_identificacion'=>$tra_identificacion,
											'tra_direccion'=>$tra_direccion,
											'tra_telefono'=>$tra_telefono,
			);	

			if($this->transportista_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TRANSPORTISTAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'transportista/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'transportista/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'transportista'=>$this->transportista_model->lista_un_transportista($id),
						'action'=>base_url().'transportista/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('transportista/form',$data);
			$modulo=array('modulo'=>'transportista');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('tra_id');
		$tra_razon_social = $this->input->post('tra_razon_social');
		$tra_email = $this->input->post('tra_email');
		$tra_estado = $this->input->post('tra_estado');
		$tra_placa = $this->input->post('tra_placa');
		$tra_identificacion = $this->input->post('tra_identificacion');
		$tra_direccion = $this->input->post('tra_direccion');
		$tra_telefono = $this->input->post('tra_telefono');


		$transportista_act=$this->transportista_model->lista_un_transportista($id);

		if($tra_identificacion==$transportista_act->tra_identificacion){
			$unique='';
		}else{
			$unique='|is_unique[erp_transportista.tra_identificacion]';
		}

		$this->form_validation->set_rules('tra_identificacion','Identificacion','required'.$unique);
		$this->form_validation->set_rules('tra_razon_social','Nombre','required');
		$this->form_validation->set_rules('tra_placa','Placa','required');
		$this->form_validation->set_rules('tra_direccion','Direccion','required');

		if($this->form_validation->run()){
			$data=array(
						 'tra_razon_social'=>$tra_razon_social,
											'tra_email'=>$tra_email,
											'tra_placa'=>$tra_placa,
											'tra_estado'=>$tra_estado,
											'tra_identificacion'=>$tra_identificacion,
											'tra_direccion'=>$tra_direccion,
											'tra_telefono'=>$tra_telefono,
			);	

			if($this->transportista_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TRANSPORTISTAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'transportista/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'transportista/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'transportista'=>$this->transportista_model->lista_un_transportista($id)
						);
			$this->load->view('transportista/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->transportista_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TRANSPORTISTAS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'transportista';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='TRANSPORTISTAS';
    	$file="transportistas".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'tra_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'transportista'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->transportista_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TRANSPORTISTA',
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
