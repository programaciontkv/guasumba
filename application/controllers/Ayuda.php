<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ayuda extends CI_Controller {

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
		$this->load->model('ayuda_model');
		$this->load->model('configuracion_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('submenu_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->library('html2pdf');
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
		
		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$ayudas=$this->ayuda_model->lista_ayudas($text);
		}else{
			$text= '';
			$ayudas=$this->ayuda_model->lista_ayudas($text);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'ayudas'=>$ayudas,
					'buscar'=>base_url().'ayuda/',
					'txt'=>$text,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('ayuda/lista',$data);
		$modulo=array('modulo'=>'ayuda');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'opciones'=>'',
						'submenus'=>$this->submenu_model->lista_submenus_estado('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'ayuda'=> (object) array(
											'ayu_id'=>'',
											'ayu_codigo'=>'0',
					                        'ayu_descripcion'=>'0',
					                        'ayu_archivo'=>'',
					                        'ayu_video'=>'',
					                        'ayu_estado'=>'1'
										),
						'action'=>base_url().'ayuda/guardar'
						);
			$this->load->view('ayuda/form',$data);
			$modulo=array('modulo'=>'ayuda');
			$this->load->view('layout/footer_ayuda',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$ayu_codigo= $this->input->post('ayu_codigo');
		$ayu_descripcion = $this->input->post('ayu_descripcion');
		$ayu_archivo= $this->input->post('ayu_archivo');
		$ayu_video = $this->input->post('ayu_video');
		$ayu_estado = $this->input->post('ayu_estado');
		
		$this->form_validation->set_rules('ayu_codigo','Codigo','required');
		$this->form_validation->set_rules('ayu_descripcion','Descripcion','required');
		$this->form_validation->set_rules('ayu_archivo','Archivo','required');
		
		if($this->form_validation->run()){
			$data=array(
					    'ayu_codigo'=>$ayu_codigo,
					    'ayu_descripcion'=>$ayu_descripcion,
					    'ayu_archivo'=>$ayu_archivo,
					    'ayu_video'=>$ayu_video,
					    'ayu_estado'=>$ayu_estado,
					   
			);	

			if($this->ayuda_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'AYUDA',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$ayu_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'ayuda');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'ayuda/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$ayuda=$this->ayuda_model->lista_una_ayuda($id);
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'submenus'=>$this->submenu_model->lista_submenus_estado('1'),
						'opciones'=>$this->ayuda_model->lista_opciones_submenu($ayuda->ayu_codigo),
						'ayuda'=>$ayuda,
						'action'=>base_url().'ayuda/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('ayuda/form',$data);
			$modulo=array('modulo'=>'ayuda');
			$this->load->view('layout/footer_ayuda',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('ayu_id');
		$ayu_codigo= $this->input->post('ayu_codigo');
		$ayu_descripcion = $this->input->post('ayu_descripcion');
		$ayu_archivo= $this->input->post('ayu_archivo');
		$ayu_video = $this->input->post('ayu_video');
		$ayu_estado = $this->input->post('ayu_estado');
		
		
		$this->form_validation->set_rules('ayu_codigo','Codigo','required');
		$this->form_validation->set_rules('ayu_descripcion','Descripcion','required');
		$this->form_validation->set_rules('ayu_archivo','Archivo','required');

		
		if($this->form_validation->run()){
			$data=array(
					    'ayu_codigo'=>$ayu_codigo,
					    'ayu_descripcion'=>$ayu_descripcion,
					    'ayu_archivo'=>$ayu_archivo,
					    'ayu_video'=>$ayu_video,
					    'ayu_estado'=>$ayu_estado,
					   
			);
		

			if($this->ayuda_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'AYUDA',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$ayu_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'ayuda');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'ayuda/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'ayuda'=>$this->ayuda_model->lista_una_ayuda($id)
						);
			$this->load->view('ayuda/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->ayuda_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'AYUDA',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'ayuda';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function show_frame($id,$opc_id){
    	$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Manual de Ayuda e Instrucciones',
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"multimedia/$id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer_ayuda',$modulo);
		}
    	


    }


	public function excel($opc_id){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Ayuda';
    	$file="ayuda".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

    public function traer_opciones($id){
    	
    	$opciones=$this->ayuda_model->lista_opciones_submenu($id);
    	$lista="<option value='0'>SELECCIONE</option>";
    	foreach ($opciones as $opcion) {
    		$lista.="<option value='$opcion->opc_id'>$opcion->opc_nombre</option>";
    	}
    	
					
		echo $lista;

    	
    }


}
