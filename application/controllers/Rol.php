<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rol extends CI_Controller {

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->library('form_validation');
		$this->load->model('rol_model');
		$this->load->model('menu_model');
		$this->load->model('submenu_model');
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

	public function index($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$data=array(
					'permisos'=>$this->permisos,
					'roles'=>$this->rol_model->lista_roles(),
					'usu_sesion'=>$this->session->userdata('s_idusuario'),
					'opc_id'=>$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('rol/lista',$data);
		$modulo=array('modulo'=>'rol');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		if($this->permisos->rop_insertar){
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'opc_id'=>$rst_opc->opc_id,
						'rol'=> (object) array(
											'rol_nombre'=>'',
											'rol_descripcion'=>'',
											'rol_estado'=>'1',
											'rol_id'=>'',
										),
						'action'=>base_url().'rol/guardar/'.$opc_id
						);
			$this->load->view('rol/form',$data);
			$modulo=array('modulo'=>'rol');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function guardar($opc_id){
		$nombre = $this->input->post('rol_nombre');
		$descripcion = $this->input->post('rol_descripcion');
		$estado = $this->input->post('rol_estado');

		$this->form_validation->set_rules('rol_nombre','Nombre','required|is_unique[erp_roles.rol_nombre]');
		$this->form_validation->set_rules('rol_descripcion','Descrpcion','required');

		if($this->form_validation->run()){
			$data=array(
						 'rol_nombre'=>$nombre,
						 'rol_descripcion'=>$descripcion,
						 'rol_estado'=>$estado
			);	

			if($this->rol_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ROLES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'rol/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'rol/nuevo/'.$opc_id);
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
						'rol'=>$this->rol_model->lista_un_rol($id),
						'action'=>base_url().'rol/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('rol/form',$data);
			$modulo=array('modulo'=>'rol');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){

		$nombre = $this->input->post('rol_nombre');
		$descripcion = $this->input->post('rol_descripcion');
		$estado = $this->input->post('rol_estado');
		$id = $this->input->post('rol_id');
		
		$rol_act=$this->rol_model->lista_un_rol($id);

		if($nombre==$rol_act->rol_nombre){
			$unique='';
		}else{
			$unique='|is_unique[erp_roles.rol_nombre]';
		}

		$this->form_validation->set_rules('rol_nombre','Nombre','required'.$unique);
		$this->form_validation->set_rules('rol_descripcion','Descrpcion','required');

		if($this->form_validation->run()){
			$data=array(
						 'rol_nombre'=>$nombre,
						 'rol_descripcion'=>$descripcion,
						 'rol_estado'=>$estado
			);	
			if($this->rol_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ROLES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'rol/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'rol/editar/'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		$data=array(
					'rol'=>$this->rol_model->lista_un_rol($id)
					);
		$this->load->view('rol/visualizar',$data);
	}


	public function eliminar($id,$nombre,$opc_id){
		if($this->permisos->rop_eliminar){
			if($this->rol_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ROLES',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'rol/'.$opc_id;
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function asignar($id){
		$data=array(
					'rol'=>$this->rol_model->lista_un_rol($id),
					'menus'=>$this->menu_model->lista_menus_estado('1'),
					'submenus'=>$this->submenu_model->lista_submenus_estado('1'),
					'opciones'=>$this->opcion_model->lista_opciones_rol($id),
					'listas'=>$this->rol_model->lista_roles_opcion($id),
					'action'=>base_url().'rol/guardar_opcion',
					'usu_sesion'=>$this->session->userdata('s_idusuario')

					);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('rol/form_asignar',$data);
		$modulo=array('modulo'=>'rol');
		$this->load->view('layout/footer',$modulo);
	}

	public function guardar_opcion(){
		$rol_id = $this->input->post('rol_id');
		$id=$this->session->userdata('s_idusuario');
		if($id==1){
			$men_id = $this->input->post('men_id');
			$sbm_id = $this->input->post('sbm_id');
		}else{
			$men_id = $this->input->post('men_aux');
			$sbm_id = $this->input->post('sb_aux');
		}
		
		$opc_id = $this->input->post('opc_id');
		$todos = $this->input->post('rop_todos');
		$insertar = $this->input->post('rop_insertar');
		$actualizar = $this->input->post('rop_actualizar');
		$eliminar = $this->input->post('rop_eliminar');
		$visualizar = $this->input->post('rop_visualizar');
		$reporte = $this->input->post('rop_reporte');

		$this->form_validation->set_rules('men_id','Menu','required');


			$data=array(
						 'rol_id'=>$rol_id,
						 'men_id'=>$men_id,
						 'sbm_id'=>$sbm_id,
						 'opc_id'=>$opc_id,
						 'rop_todos'=>$todos,
						 'rop_insertar'=>$insertar,
						 'rop_actualizar'=>$actualizar,
						 'rop_eliminar'=>$eliminar,
						 'rop_visualizar'=>$visualizar,
						 'rop_reporte'=>$reporte,
			);	
		


			if($this->rol_model->insert_opcion($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ROLES',
								'adt_accion'=>'ASIGNAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'rol/asignar/'.$rol_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'rol/asignar/'.$rol_id);
			}
			
	}

	public function eliminar_opcion($id,$rol,$nombre){
		if($this->rol_model->delete_opcion($id)){
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ROLES',
								'adt_accion'=>'ELMINAR',
								'adt_campo'=>'',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'$nombre',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			echo 'rol/asignar/'.$rol;
		}
	}

	public function excel($opc_id){

    	$titulo='Roles';
    	$file="roles".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'rol_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Rol'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->rol_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Rol',
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

	public function traer_cods($id){
		$id = $this->rol_model->traer_cod($id);
		if(!empty($id)){
			echo $id->men_id."&&".$id->sbm_id;
		}else{
			echo "0";
		}

	}
}
