<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class caja extends CI_Controller {

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
		$this->load->model('caja_model');
		$this->load->model('emisor_model');
		$this->load->model('auditoria_model');
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
	

	public function index(){
		$data=array(
					'permisos'=>$this->permisos,
					'cajas'=>$this->caja_model->lista_cajas(),
					
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('caja/lista',$data);
		$modulo=array('modulo'=>'caja');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->permisos->opc_id;
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'emisores'=>$this->emisor_model->lista_emisores_estado('1'),
						'caja'=> (object) array(
											'cja_id'=>'',
					                        'emi_id'=>'',
					                        'cja_nombre'=>'',
					                        'cja_codigo'=>'1',
					                        'cja_sec_factura'=>'1',
					                        'cja_sec_nota_credito'=>'1',
					                        'cja_sec_nota_debito'=>'1',
					                        'cja_sec_guia'=>'1',
					                        'cja_sec_retencion'=>'1',
					                        'cja_estado'=>'1',
										),
						'action'=>base_url().'caja/guardar'
						);
			$this->load->view('caja/form',$data);
			$modulo=array('modulo'=>'caja');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$emi_id= $this->input->post('emi_id');
		$cja_nombre= $this->input->post('cja_nombre');
		$cja_codigo = $this->input->post('cja_codigo');
		$cja_sec_factura = $this->input->post('cja_sec_factura');
		$cja_sec_nota_credito = $this->input->post('cja_sec_nota_credito');
		$cja_sec_nota_debito = $this->input->post('cja_sec_nota_debito');
		$cja_sec_guia = $this->input->post('cja_sec_guia');
		$cja_sec_retencion = $this->input->post('cja_sec_retencion');
		$cja_estado = $this->input->post('cja_estado');

		$this->form_validation->set_rules('emi_id','Punto Emision','required');
		$this->form_validation->set_rules('cja_codigo','Codigo','required');
		$this->form_validation->set_rules('cja_nombre','Nombre','required');
		$this->form_validation->set_rules('cja_sec_factura','Secuencial Inicia Factura','required');
		$this->form_validation->set_rules('cja_sec_nota_credito','Secuencial Inicia Nota Credito','required');
		$this->form_validation->set_rules('cja_sec_nota_debito','Secuencial Inicia Nota Debito','required');
		$this->form_validation->set_rules('cja_sec_guia','Secuencial Inicia Guia Remision','required');
		$this->form_validation->set_rules('cja_sec_retencion','Secuencial Inicia Retencion','required');

		if($this->form_validation->run()){
			$data=array(
					    'emi_id'=>$emi_id,
					    'cja_nombre'=>$cja_nombre,
					    'cja_codigo'=>$cja_codigo,
					    'cja_sec_factura'=>$cja_sec_factura,
					    'cja_sec_nota_credito'=>$cja_sec_nota_credito,
					    'cja_sec_nota_debito'=>$cja_sec_nota_debito,
					    'cja_sec_guia'=>$cja_sec_guia,
					    'cja_sec_retencion'=>$cja_sec_retencion,
					    'cja_estado'=>$cja_estado,
			);	

			if($this->caja_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CAJAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'caja');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'caja/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'emisores'=>$this->emisor_model->lista_emisores_estado('1'),
						'caja'=>$this->caja_model->lista_una_caja($id),
						'action'=>base_url().'caja/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('caja/form',$data);
			$modulo=array('modulo'=>'caja');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('cja_id');
		$emi_id= $this->input->post('emi_id');
		$cja_nombre= $this->input->post('cja_nombre');
		$cja_codigo = $this->input->post('cja_codigo');
		$cja_sec_factura = $this->input->post('cja_sec_factura');
		$cja_sec_nota_credito = $this->input->post('cja_sec_nota_credito');
		$cja_sec_nota_debito = $this->input->post('cja_sec_nota_debito');
		$cja_sec_guia = $this->input->post('cja_sec_guia');
		$cja_sec_retencion = $this->input->post('cja_sec_retencion');
		$cja_estado = $this->input->post('cja_estado');

		$this->form_validation->set_rules('emi_id','Punto Emision','required');
		$this->form_validation->set_rules('cja_codigo','Codigo','required');
		$this->form_validation->set_rules('cja_nombre','Nombre','required');
		$this->form_validation->set_rules('cja_sec_factura','Secuencial Inicia Factura','required');
		$this->form_validation->set_rules('cja_sec_nota_credito','Secuencial Inicia Nota Credito','required');
		$this->form_validation->set_rules('cja_sec_nota_debito','Secuencial Inicia Nota Debito','required');
		$this->form_validation->set_rules('cja_sec_guia','Secuencial Inicia Guia Remision','required');
		$this->form_validation->set_rules('cja_sec_retencion','Secuencial Inicia Retencion','required');

		if($this->form_validation->run()){
			$data=array(
					    'emi_id'=>$emi_id,
					    'cja_nombre'=>$cja_nombre,
					    'cja_codigo'=>$cja_codigo,
					    'cja_sec_factura'=>$cja_sec_factura,
					    'cja_sec_nota_credito'=>$cja_sec_nota_credito,
					    'cja_sec_nota_debito'=>$cja_sec_nota_debito,
					    'cja_sec_guia'=>$cja_sec_guia,
					    'cja_sec_retencion'=>$cja_sec_retencion,
					    'cja_estado'=>$cja_estado,
			);	
			
			if($this->caja_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CAJAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'caja');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'caja/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'caja'=>$this->caja_model->lista_una_caja($id)
						);
			$this->load->view('caja/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->caja_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CAJAS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'caja';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente($id);
		if(!empty($rst)){
			$data=array(
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function excel($opc_id){

    	$titulo='Cajas';
    	$file="cajas".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
