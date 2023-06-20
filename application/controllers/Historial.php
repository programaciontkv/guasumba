<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial  extends CI_Controller {

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
		$this->load->model('historial_model');
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

		///buscador 
		if($_POST){

			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$pes= $this->input->post('his_lugar');	
			if($pes!=""){
				$txt_pes="and his_lugar=$pes";
			}else{
				$txt_pes="";
			}
			$cns_historial=$this->historial_model->lista_historial_buscador($f1,$f2,$txt_pes);
		}else{
			$fecha_actual = date("Y-m-d");
   			$f1=date("Y-m-d",strtotime($fecha_actual."- 2 month")); 
			$f2= date('Y-m-d');
			$pes="";
			$cns_historial=$this->historial_model->lista_historial_buscador($f1,$f2,$pes);
		}
		

		$data=array(
					'permisos'  =>$this->permisos,	
					'historial' =>$cns_historial,
					'pestanias' =>$this->opcion_model->lista_opciones(),
					'buscar'    =>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'fec1'      =>$f1,
					'fec2'      =>$f2,
					'usu_sesion'=>$this->session->userdata('s_idusuario'),
					'opc_id'    =>$rst_opc->opc_id,

				);
		
		$this->load->view('layout/header', $this->menus());
		$this->load->view('layout/menu', $this->menus());
		$this->load->view('historial/lista',$data);
		$modulo=array('modulo'=>'historial');
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
						'pestanias' =>$this->opcion_model->lista_opciones(),
						'historial'=> (object) array(
											'his_fregistro'=>date('Y-m-d'),
											'his_fcambio'=>date('Y-m-d'),
											'his_usuario_soli'=>'',
											'his_per_modifica'=>'',
											'his_solicitud'=>'',
											'his_lugar'=>'',
											'his_archivos'=>'',
											'his_obser'=>'',
											'his_usu'=>''
											
										),
						'action'=>base_url().'historial/guardar/'.$opc_id,

						);
			$this->load->view('historial/form',$data);
			$modulo=array('modulo'=>'usuario');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function guardar($opc_id){
		$his_fregistro    = $this->input->post('his_fregistro');
		$his_fcambio      = $this->input->post('his_fcambio');
		$his_usuario_soli = $this->input->post('his_usuario_soli');
		$his_per_modifica = $this->input->post('his_per_modifica');
		$his_solicitud    = $this->input->post('his_solicitud');
		$his_lugar        = $this->input->post('his_lugar');
		$his_archivos     = $this->input->post('his_archivos');
		$his_obser        = $this->input->post('his_obser');
		$his_usu          = $this->session->userdata('s_usuario');

		$this->form_validation->set_rules('his_fregistro','Fecha registro','required');
		$this->form_validation->set_rules('his_fcambio','Fecha cambio','required');
		$this->form_validation->set_rules('his_usuario_soli','Usuario solicita','required');
		$this->form_validation->set_rules('his_per_modifica','Personal modifica','required');
		$this->form_validation->set_rules('his_solicitud','Solicitud','required');
		$this->form_validation->set_rules('his_lugar','Pestaña','required');
		$this->form_validation->set_rules('his_archivos','Archivos','required');
		//$this->form_validation->set_rules('his_obser','Observaciones','required');

		if($this->form_validation->run()){
			$data=array(
	                    'his_fregistro'=>$his_fregistro,
						'his_fcambio'=>$his_fcambio,
						'his_usuario_soli'=>$his_usuario_soli,
						'his_per_modifica'=>$his_per_modifica ,
						'his_solicitud'=>$his_solicitud,
						'his_lugar'=>$his_lugar,
						'his_archivos'=>preg_replace("/[\r\n|\n|\r]+/", " ", $his_archivos),
						'his_obser'=>preg_replace("/[\r\n|\n|\r]+/", " ", $his_obser) ,
						'his_usu'=>$his_usu 
			);	

			if($this->historial_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Historial',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'historial/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'historial/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

		if($this->permisos->rop_actualizar){
			$data=array(
						'pestanias' =>$this->opcion_model->lista_opciones(),
						'historial'=> $this->historial_model->lista_un_historial($id),
						'action'=>base_url().'historial/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('historial/form',$data);
			$modulo=array('modulo'=>'usuario');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$his_fregistro    = $this->input->post('his_fregistro');
		$his_fcambio      = $this->input->post('his_fcambio');
		$his_usuario_soli = $this->input->post('his_usuario_soli');
		$his_per_modifica = $this->input->post('his_per_modifica');
		$his_solicitud    = $this->input->post('his_solicitud');
		$his_lugar        = $this->input->post('his_lugar');
		$his_archivos     = $this->input->post('his_archivos');
		$his_obser        = $this->input->post('his_obser');
		$his_usu          = $this->session->userdata('s_usuario');

		$this->form_validation->set_rules('his_fregistro','Fecha registro','required');
		$this->form_validation->set_rules('his_fcambio','Fecha cambio','required');
		$this->form_validation->set_rules('his_usuario_soli','Usuario solicita','required');
		$this->form_validation->set_rules('his_per_modifica','Personal modifica','required');
		$this->form_validation->set_rules('his_solicitud','Solicitud','required');
		$this->form_validation->set_rules('his_lugar','Pestaña','required');
		$this->form_validation->set_rules('his_archivos','Archivos','required');

		if($this->form_validation->run()){
			$data=array(
	                    'his_fregistro'   =>$his_fregistro,
						'his_fcambio'     =>$his_fcambio,
						'his_usuario_soli'=>$his_usuario_soli,
						'his_per_modifica'=>$his_per_modifica ,
						'his_solicitud'   =>$his_solicitud,
						'his_lugar'       =>$his_lugar,
						'his_archivos'    =>preg_replace("/[\r\n|\n|\r]+/", " ", $his_archivos),
						'his_obser'       =>preg_replace("/[\r\n|\n|\r]+/", " ", $his_obser) ,
						'his_usu'         =>$his_usu 
			);	

			if($this->historial_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Historial',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'historial/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'historial/editar'.$id.'/'.$opc_id);
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
