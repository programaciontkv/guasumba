<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_Cuentas extends CI_Controller {

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
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('opcion_model');
		$this->load->model('usuario_model');
		$this->load->model('emisor_model');
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
			$emisor= $this->input->post('emisor');
			$cns_configuraciones=$this->configuracion_cuentas_model->lista_configuracion_cuentas('6');
		}else{
			$emisor= '6';
			$cns_configuraciones=$this->configuracion_cuentas_model->lista_configuracion_cuentas($emisor);
		}
		$data=array(
					'emisor'=>$emisor,
					'emisores'=>$this->emisor_model->lista_emisores_estado('1'),
					'cuentas'=>$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1'),
					'permisos'=>$this->permisos,
					'configuraciones'=>$cns_configuraciones,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'action'=>base_url()."configuracion_cuentas/actualizar/$rst_opc->opc_id",
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('configuracion_cuentas/lista',$data);
		$modulo=array('modulo'=>'configuracion_cuentas');
		$this->load->view('layout/footer',$modulo);
	}


	public function actualizar($opc_id){
		echo $detalle = $this->input->post('detalle');
		$n=0;
		while ($n < $detalle) {
			$n++;
			$id=$this->input->post("cas_id$n");
			$pln_id=$this->input->post("pln_id$n");
			$data=array('pln_id' =>  $pln_id);
			$data2=array('pln_id' =>  $pln_id,
						'cas_id' =>  $id);
			$this->configuracion_cuentas_model->update($id,$data);
		}

		$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACION CUENTAS',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'',
								'usu_login'=>$this->session->userdata('s_usuario'),
						);
		$this->auditoria_model->insert($data_aud);
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);

	}

	public function excel($opc_id){

    	$titulo='Configuracion de Cuentas';
    	$file="auditoria".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }


    public function traer_cuenta($id){
		$rst=$this->plan_cuentas_model->lista_un_plan_cuentas($id);
		
		if(!empty($rst)){

			$data=array(
						'pln_id'=>$rst->pln_id,
						'pln_codigo'=>$rst->pln_codigo,
						'pln_descripcion'=>$rst->pln_descripcion,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}

}
