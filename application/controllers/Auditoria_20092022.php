<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditoria extends CI_Controller {

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
		$this->load->model('auditoria_model');
		$this->load->model('opcion_model');
		$this->load->model('usuario_model');
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
	
	

	public function index($opc_id){

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$usuario= $this->input->post('usuario');
			$accion= $this->input->post('accion');	
			$cns_auditorias=$this->auditoria_model->lista_auditorias_buscador($text,$f1,$f2,$usuario,$accion);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$usuario= '';
			$accion= '';
			$cns_auditorias=$this->auditoria_model->lista_auditorias_buscador($text,$f1,$f2,$usuario,$accion);
		}
		$data=array(
					'usuarios'=>$this->usuario_model->lista_usuarios(),
					'acciones'=>$this->auditoria_model->lista_acciones(),
					'permisos'=>$this->permisos,
					'auditorias'=>$cns_auditorias,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'usuario'=>$usuario,
					'accion'=>$accion
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('auditoria/lista',$data);
		$modulo=array('modulo'=>'auditoria');
		$this->load->view('layout/footer',$modulo);
	}


	
	public function visualizar($id){
			$data=array(
						'auditoria'=>$this->auditoria_model->lista_una_auditoria($id)
						);
			$this->load->view('auditoria/visualizar',$data);
		
	}


	public function excel($opc_id){

    	$titulo='AUDITORIA';
    	$file="auditoria".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
