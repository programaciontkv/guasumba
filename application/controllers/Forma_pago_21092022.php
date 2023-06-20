<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forma_pago extends CI_Controller {

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
		$this->load->model('forma_pago_model');
		$this->load->model('bancos_tarjetas_model');
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
					'formas_pago'=>$this->forma_pago_model->lista_formas_pago(),
					
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('forma_pago/lista',$data);
		$modulo=array('modulo'=>'forma_pago');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'bancos'=>$this->bancos_tarjetas_model->lista_bancos_tarjetas_tipo('0','1'),
						'tarjetas'=>$this->bancos_tarjetas_model->lista_bancos_tarjetas_tipo('1','1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->forma_pago_model->lista_plan_cuentas_estado('1'),
						'forma_pago'=> (object) array(
											'fpg_id'=>'',
					                        'fpg_descripcion'=>'',
					                        'fpg_codigo'=>'',
					                        'fpg_banco'=>'0',
					                        'fpg_tarjeta'=>'0',
					                        'pln_id'=>'',
					                        'fpg_precio'=>'1',
					                        'fpg_estado'=>'1',
					                        'pln_codigo'=>'',
					                        'pln_descripcion'=>'',
					                        'fpg_tipo'=>'',
					                        'fpg_descripcion_sri'=>'',
										),
						'action'=>base_url().'forma_pago/guardar'
						);
			$this->load->view('forma_pago/form',$data);
			$modulo=array('modulo'=>'forma_pago');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$fpg_descripcion= $this->input->post('fpg_descripcion');
		$fpg_codigo = $this->input->post('fpg_codigo');
		$fpg_banco = $this->input->post('fpg_banco');
		$fpg_tarjeta = $this->input->post('fpg_tarjeta');
		$pln_id = $this->input->post('pln_id');
		$fpg_estado = $this->input->post('fpg_estado');
		$fpg_tipo = $this->input->post('fpg_tipo');
		$fpg_descripcion_sri= $this->input->post('fpg_descripcion_sri');
		
		$this->form_validation->set_rules('fpg_descripcion','Descripcion','required|is_unique[erp_formas_pago.fpg_descripcion]');
		$this->form_validation->set_rules('fpg_tipo','Tipo','required');

		if($this->form_validation->run()){
			$data=array(
					    'fpg_descripcion'=>$fpg_descripcion,
					    'fpg_codigo'=>$fpg_codigo,
					    'fpg_banco'=>$fpg_banco,
					    'fpg_tarjeta'=>$fpg_tarjeta,
					    'fpg_precio'=>1,
					    'fpg_estado'=>$fpg_estado,
					    'fpg_tipo'=>$fpg_tipo,
					    'fpg_descripcion_sri'=>$fpg_descripcion_sri,
			);	

			if($this->forma_pago_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'FORMAS DE PAGO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'forma_pago');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'forma_pago/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'bancos'=>$this->bancos_tarjetas_model->lista_bancos_tarjetas_tipo('0','1'),
						'tarjetas'=>$this->bancos_tarjetas_model->lista_bancos_tarjetas_tipo('1','1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'forma_pago'=>$this->forma_pago_model->lista_una_forma_pago_id($id),
						'action'=>base_url().'forma_pago/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('forma_pago/form',$data);
			$modulo=array('modulo'=>'forma_pago');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('fpg_id');
		$fpg_descripcion= $this->input->post('fpg_descripcion');
		$fpg_codigo = $this->input->post('fpg_codigo');
		$fpg_banco = $this->input->post('fpg_banco');
		$fpg_tarjeta = $this->input->post('fpg_tarjeta');
		$fpg_estado = $this->input->post('fpg_estado');
		$fpg_tipo = $this->input->post('fpg_tipo');
		$fpg_descripcion_sri= $this->input->post('fpg_descripcion_sri');

		$forma_pago_act=$this->forma_pago_model->lista_una_forma_pago_id($id);

		if($fpg_descripcion==$forma_pago_act->fpg_descripcion){
			$unique='';
		}else{
			$unique='|is_unique[erp_formas_pago.fpg_descripcion]';
		}

		
		$this->form_validation->set_rules('fpg_descripcion','Descripcion','required'.$unique);
		$this->form_validation->set_rules('fpg_tipo','Tipo','required');

		if($this->form_validation->run()){
			$data=array(
					    'fpg_descripcion'=>$fpg_descripcion,
					    'fpg_codigo'=>$fpg_codigo,
					    'fpg_banco'=>$fpg_banco,
					    'fpg_tarjeta'=>$fpg_tarjeta,
					    'fpg_precio'=>1,
					    'fpg_estado'=>$fpg_estado,
					    'fpg_tipo'=>$fpg_tipo,
					    'fpg_descripcion_sri'=>$fpg_descripcion_sri,
			);	

			if($this->forma_pago_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'FORMAS DE PAGO',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'forma_pago');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'forma_pago/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'forma_pago'=>$this->forma_pago_model->lista_una_forma_pago_id($id)
						);
			$this->load->view('forma_pago/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->forma_pago_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'FORMAS DE PAGO',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'forma_pago';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function traer_cuenta($id){
		$rst=$this->forma_pago_model->lista_un_plan_cuentas($id);
		echo $rst->pln_id.'&&'.$rst->pln_codigo.'&&'.$rst->pln_descripcion;
	}

	public function excel($opc_id){

    	$titulo='Formas de Pago';
    	$file="formas_pago".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'fpg_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Forma_pago'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->forma_pago_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Forma de pago',
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
