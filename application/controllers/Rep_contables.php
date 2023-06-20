<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_contables extends CI_Controller {

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
		$this->load->model('configuracion_model'); 
		$this->load->model('rep_contables_model');
		$this->load->model('asiento_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
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
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		///buscador 
		if($_POST){
			$reporte= $this->input->post('reporte');
			$nivel= $this->input->post('nivel');
			$anio= $this->input->post('anio');
			$mes= $this->input->post('mes');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$cuenta= $this->input->post('cuenta');	
		}else{
			$reporte= '';
			$nivel='1';
			$anio=date('Y');
			$mes=date('m');
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cuenta= '';
		}

		$data=array(
					'permisos'=>$this->permisos,
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>'buscar_reporte/'.$rst_opc->opc_id,
					'excel'=>'buscar_excel/'.$rst_opc->opc_id,
					'reporte'=>$reporte,
					'nivel'=>$nivel,
					'anio'=>$anio,
					'mes'=>$mes,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'cuenta'=>$cuenta,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'cuentas'=>$this->plan_cuentas_model->lista_plan_cuentas_estado('1'),
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('rep_contables/lista',$data);
		$modulo=array('modulo'=>'rep_contables');
		$this->load->view('layout/footer',$modulo);
	}


	public function buscar_reporte($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
            
            $reporte=$this->input->post('reporte');
            $desde=$this->input->post('fec1');
            $hasta=$this->input->post('fec2');
            $nivel=$this->input->post('nivel');
            $anio=$this->input->post('anio');
            $mes=$this->input->post('mes');
            $cta=$this->input->post('cuenta');

            if($reporte>=2){
                if ($mes < 10) {
                    $mes = '0' . $mes;
                } else {
                    $mes = $mes;
                }
                if ($mes == 13) {
                    $desde = trim($anio) . '-01' . '-01';
                    $hasta = trim($anio) . '-12' . '-31';
                } else {
                    $desde = trim($anio) . '-' . $mes . '-01';
                    $hasta=date("Y-m-t", strtotime($desde));
                }
            }
            
            switch ($reporte) {
                case '0':
                    $url="pdf_libro_diario/index/$desde/$hasta/$opc_id";
                    break;
                case '1':
                    $url="pdf_libro_mayor/index/$desde/$hasta/$opc_id/$cta";
                    break;
                case '2':
                    $url="pdf_balance_comprobacion/index/$desde/$hasta/$nivel/$opc_id";
                    break;   
                case '3':
                    $url="pdf_balance_general/index/$desde/$hasta/$nivel/$anio/$mes/$opc_id";
                    break;   
                case '4':
                    $url="pdf_epyg/index/$desde/$hasta/$nivel/$anio/$mes/$opc_id";
                    break;            
            }

          $data=array(
					'titulo'=>'Reportes Contables ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>$url,
					'fec1'=>$desde,
					'fec2'=>$hasta,
					'txt'=>'',
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
					'reporte'=>$reporte,
					'cuenta'=>$cta,
					'nivel'=>$nivel,
					'anio'=>$anio,
					'mes'=>intval($mes),
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'rep_contables');
			$this->load->view('layout/footer',$modulo);
                    

            
    }

    public function buscar_excel($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
            
            $reporte=$this->input->post('reporte');
            $desde=$this->input->post('fec1');
            $hasta=$this->input->post('fec2');
            $nivel=$this->input->post('nivel');
            $anio=$this->input->post('anio');
            $mes=$this->input->post('mes');
            $cta=$this->input->post('cuenta');

            if($reporte>=2){
                if ($mes < 10) {
                    $mes = '0' . $mes;
                } else {
                    $mes = $mes;
                }
                if ($mes == 13) {
                    $desde = trim($anio) . '-01' . '-01';
                    $hasta = trim($anio) . '-12' . '-31';
                } else {
                    $desde = trim($anio) . '-' . $mes . '-01';
                    $hasta=date("Y-m-t", strtotime($desde));
                }
            }
            
            switch ($reporte) {
                case '0':
                    $url="pdf_libro_diario/excel/$desde/$hasta/$opc_id";
                    break;
                case '1':
                    $url="pdf_libro_mayor/excel/$desde/$hasta/$opc_id/$cta";
                    break;
                case '2':
                    $url="pdf_balance_comprobacion/excel/$desde/$hasta/$nivel/$opc_id";
                    break;   
                case '3':
                    $url="pdf_balance_general/excel/$desde/$hasta/$nivel/$anio/$mes/$opc_id";
                    break;   
                case '4':
                    $url="pdf_epyg/excel/$desde/$hasta/$nivel/$anio/$mes/$opc_id";
                    break;            
            }
            // echo base_url().$url;
          redirect(base_url().$url);
                    
    }

}
