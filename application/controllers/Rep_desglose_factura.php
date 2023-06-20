<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_desglose_factura extends CI_Controller {

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
		$this->load->model('empresa_model');
		$this->load->model('emisor_model');
		$this->load->model('rep_desglose_factura_model');
		$this->load->model('vendedor_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('opcion_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
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
		$we =  intval($this->session->userdata('s_we'));
		if($we>=760){
		$mensaje='';
		}else{
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		}

		///buscador 
		if($_POST){
			$mensaje='';
			$txt=strtoupper($this->input->post('txt'));
			$emisor= $this->input->post('emisor');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_facturas=$this->rep_desglose_factura_model->lista_factura_buscador($f1,$f2,$emisor,$txt);
		}else{
			$txt='';
			$emisor='6';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_facturas=$this->rep_desglose_factura_model->lista_factura_buscador($f1,$f2,$emisor,$txt);
		}
		
					
															
			$cns_emisores=$this->emisor_model->lista_emisores_estado('1');		
			$data=array(
						'permisos'=>$this->permisos,
						'emisores'=>$cns_emisores,
						'opc_id'=>$rst_opc->opc_id,
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'emisor'=>$emisor,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'txt'=>$txt,
						'facturas'=>$cns_facturas,
						'mensaje'=> $mensaje
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reportes/rep_desglose_factura',$data);
			$modulo=array('modulo'=>'reportes');
			$this->load->view('layout/footer_bodega',$modulo);
	}


	
	
    
	
    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

    	$titulo='Reporte Desglose Facturas ';
    	$file="rep_desglose_factura".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

     

}
