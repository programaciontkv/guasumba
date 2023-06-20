<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

	private $permisos;

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		
		$this->load->library('form_validation');
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->load->model('usuario_model');
		$this->load->model('rol_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->permisos=$this->backend_lib->control();
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
					'usuarios'=>$this->usuario_model->lista_usuarios(),
					'usu_sesion'=>$this->session->userdata('s_idusuario'),
					'opc_id'=>$rst_opc->opc_id,

				);
		
		$this->load->view('layout/header', $this->menus());
		$this->load->view('layout/menu', $this->menus());
		$this->load->view('usuario/lista',$data);
		$modulo=array('modulo'=>'usuario');
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
						'usuario'=> (object) array(
											'usu_person'=>'',
											'usu_login'=>'',
											'usu_pass'=>'',
											'usu_estado'=>'1',
											'usu_id'=>'',
											'rol_id'=>'1',
											'usu_imagen'=>'',
										),
						
						'roles'=>$this->rol_model->lista_roles_estado('1'),
						'action'=>base_url().'usuario/guardar/'.$opc_id,

						);
			$this->load->view('usuario/form',$data);
			$modulo=array('modulo'=>'usuario');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function guardar($opc_id){
		$nombre = $this->input->post('usu_person');
		$login = $this->input->post('usu_login');
		$clave = $this->input->post('usu_pass');
		$rol = $this->input->post('rol_id');
		$imagen = $this->input->post('usu_imagen');
		$estado = $this->input->post('usu_estado');

		$this->form_validation->set_rules('usu_person','Nombre','required|is_unique[erp_users.usu_person]');
		$this->form_validation->set_rules('usu_login','Usuario','required|is_unique[erp_users.usu_login]');
		$this->form_validation->set_rules('usu_imagen','Imagen','required');
		$this->form_validation->set_rules('usu_pass','Contraseña','required');
		$this->form_validation->set_rules('usu_pass2','Confirmar Contraseña','required|matches[usu_pass]');

		if($this->form_validation->run()){
			$data=array(
						 'usu_person'=>$nombre,
						 'usu_login'=>$login,
						 'usu_pass'=>md5($clave),
						 'usu_estado'=>$estado,
						 'usu_imagen'=>$imagen,
						 'rol_id'=>$rol
			);	

			if($this->usuario_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'USUARIOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'usuario/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'usuario/nuevo/'.$opc_id);
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
						'roles'=>$this->rol_model->lista_roles_estado('1'),
						'usuario'=>$this->usuario_model->lista_un_usuario($id),
						'action'=>base_url().'usuario/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('usuario/form',$data);
			$modulo=array('modulo'=>'usuario');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$nombre = $this->input->post('usu_person');
		$login = $this->input->post('usu_login');
		$clave = $this->input->post('usu_pass');
		$id = $this->input->post('usu_id');
		$rol = $this->input->post('rol_id');
		$estado = $this->input->post('usu_estado');
		$imagen = $this->input->post('usu_imagen');
		
		$usuario_act=$this->usuario_model->lista_un_usuario($id);

		if($nombre==$usuario_act->usu_person){
			$unique_usu='';
		}else{
			$unique_usu='|is_unique[erp_users.usu_person]';
		}
		if($login==$usuario_act->usu_login){
			$unique_log='';
		}else{
			$unique_log='|is_unique[erp_users.usu_login]';
		}
		$this->form_validation->set_rules('usu_person','Nombre','required'.$unique_usu);
		$this->form_validation->set_rules('usu_login','Usuario','required'.$unique_log);
		$this->form_validation->set_rules('usu_imagen','Imagen','required');
		$this->form_validation->set_rules('usu_pass','Contraseña','required');
		$this->form_validation->set_rules('usu_pass2','Confirmar Contraseña','required|matches[usu_pass]');

		if($this->form_validation->run()){
			$data=array(
						 'usu_person'=>$nombre,
						 'usu_login'=>$login,
						 'usu_pass'=>md5($clave),
						 'usu_estado'=>$estado,
						 'usu_imagen'=>$imagen,
						 'rol_id'=>$rol,
			);	

			if($this->usuario_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'USUARIOS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'usuario/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'usuario/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'usuario'=>$this->usuario_model->lista_un_usuario($id)
						);
			$this->load->view('usuario/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre,$opc_id){
		if($this->permisos->rop_eliminar){
			if($this->usuario_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'USUARIOS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'usuario/'.$opc_id;
			}
		}else{
			redirect(base_url().'inicio');
			
		}	
	}

	public function perfil($id){
			$data=array(
						'roles'=>$this->rol_model->lista_roles_estado('1'),
						'usuario'=>$this->usuario_model->lista_un_usuario($id),
						'action'=>base_url().'usuario/actualizar_perfil'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('usuario/form_perfil',$data);
			$modulo=array('modulo'=>'usuario');
			$this->load->view('layout/footer',$modulo);
			
	}


	public function actualizar_perfil(){
		$id = $this->input->post('usu_id');
		$clave = $this->input->post('usu_pass');
		$imagen = $this->input->post('usu_imagen');
		
		
		$this->form_validation->set_rules('usu_imagen','Imagen','required');
		$this->form_validation->set_rules('usu_pass','Contraseña','required');
		$this->form_validation->set_rules('usu_pass2','Confirmar Contraseña','required|matches[usu_pass]');

		if($this->form_validation->run()){
			$data=array(
						 'usu_pass'=>md5($clave),
						 'usu_imagen'=>$imagen,
			);	

			if($this->usuario_model->update($id,$data)){
				$session_usuario=array(
									's_idusuario' =>$id,
									's_usuario' =>$this->session->userdata('s_usuario'),
									's_rol' =>$this->session->userdata('s_rol'),
									's_imagen' =>$imagen,
									's_login'=>TRUE 
								);

				$this->session->set_userdata($session_usuario);
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'USUARIOS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'inicio');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'usuario/perfil'.$id);
			}
		}else{
			$this->perfil($id);
		}	
	}

	public function excel($opc_id){

    	$titulo='Usuarios';
    	$file="usuarios".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
    public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'usu_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Usuario'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->usuario_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Usuario',
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
