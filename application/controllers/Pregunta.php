<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pregunta extends CI_Controller {

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
		$this->load->model('pregunta_model');
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
			$preguntas=$this->pregunta_model->lista_preguntas($text);
		}else{
			$text= '';
			$preguntas=$this->pregunta_model->lista_preguntas($text);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'preguntas'=>$preguntas,
					'buscar'=>base_url().'pregunta/',
					'txt'=>$text,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('pregunta/lista',$data);
		$modulo=array('modulo'=>'pregunta');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'pregunta'=> (object) array(
											'pre_id'=>'',
											'pre_menu'=>'',
					                        'pre_seccion'=>'',
					                        'pre_archivo'=>'',
					                        'pre_video'=>'',
					                        'pre_estado'=>'1'
										),
						'action'=>base_url().'pregunta/guardar'
						);
			$this->load->view('pregunta/form',$data);
			$modulo=array('modulo'=>'pregunta');
			$this->load->view('layout/footer_ayuda',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$pre_menu= $this->input->post('pre_menu');
		$pre_seccion = $this->input->post('pre_seccion');
		$pre_archivo= $this->input->post('pre_archivo');
		$pre_video = $this->input->post('pre_video');
		$pre_estado = $this->input->post('pre_estado');
		
		$this->form_validation->set_rules('pre_menu','Menu','required');
		$this->form_validation->set_rules('pre_seccion','Seccion','required');
		$this->form_validation->set_rules('pre_archivo','Archivo','required');
		
		if($this->form_validation->run()){
			$data=array(
					    'pre_menu'=>$pre_menu,
					    'pre_seccion'=>$pre_seccion,
					    'pre_archivo'=>$pre_archivo,
					    'pre_video'=>$pre_video,
					    'pre_estado'=>$pre_estado,
					   
			);	

			if($this->pregunta_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'pregunta',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pre_menu,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'pregunta');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'pregunta/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$pregunta=$this->pregunta_model->lista_una_pregunta($id);
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'pregunta'=>$pregunta,
						'action'=>base_url().'pregunta/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pregunta/form',$data);
			$modulo=array('modulo'=>'pregunta');
			$this->load->view('layout/footer_ayuda',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('pre_id');
		$pre_menu= $this->input->post('pre_menu');
		$pre_seccion = $this->input->post('pre_seccion');
		$pre_archivo= $this->input->post('pre_archivo');
		$pre_video = $this->input->post('pre_video');
		$pre_estado = $this->input->post('pre_estado');
		
		$this->form_validation->set_rules('pre_menu','Menu','required');
		$this->form_validation->set_rules('pre_seccion','Seccion','required');
		$this->form_validation->set_rules('pre_archivo','Archivo','required');
		
		if($this->form_validation->run()){
			$data=array(
					    'pre_menu'=>$pre_menu,
					    'pre_seccion'=>$pre_seccion,
					    'pre_archivo'=>$pre_archivo,
					    'pre_video'=>$pre_video,
					    'pre_estado'=>$pre_estado,
					   
			);	
		

			if($this->pregunta_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'pregunta',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pre_menu,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'pregunta');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'pregunta/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'pregunta'=>$this->pregunta_model->lista_una_pregunta($id)
						);
			$this->load->view('pregunta/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->pregunta_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'pregunta',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'pregunta';
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
					'titulo'=>'Preguntas Frecuentes',
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"multimedia/$id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'pregunta');
			$this->load->view('layout/footer_bodega',$modulo);
		}
    	


    }


	public function excel($opc_id){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Preguntas Frecuentes';
    	$file="pregunta".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

    


}
