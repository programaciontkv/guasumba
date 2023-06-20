<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bancos_cajas extends CI_Controller {

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
		$this->load->model('bancos_cajas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('plan_cuentas_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
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
					'bancos_cajas'=>$this->bancos_cajas_model->lista_bancos_cajas(),
					'opc_id'=>$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('bancos_cajas/lista',$data);
		$modulo=array('modulo'=>'bancos_cajas');
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
						'cuentas'=>$this->plan_cuentas_model->lista_plan(),
						'banco_caja'=> (object) array(
											'byc_id'=>'',
											'byc_tipo'=>'',
					                        'byc_referencia'=>'',
					                        'byc_num_cuenta'=>'0',
					                        'byc_tipo_cuenta'=>'',
					                        'byc_documento'=>'1',
					                        'byc_saldo'=>'0',
					                        'byc_cuenta_contable'=>'',
					                        'byc_id_cuenta'=>'',
					                        'byc_estado'=>'0',
					                        'pln_descripcion'=>'',
					                        'pln_id'=>'0'
										),
						'action'=>base_url().'bancos_cajas/guardar/'.$opc_id
						);
			$this->load->view('bancos_cajas/form',$data);
			$modulo=array('modulo'=>'bancos_cajas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$byc_referencia      = $this->input->post('byc_referencia');
		$byc_tipo            = $this->input->post('byc_tipo');
		$byc_num_cuenta      = $this->input->post('byc_num_cuenta');
		$byc_tipo_cuenta     = $this->input->post('byc_tipo_cuenta');
		$byc_documento       = $this->input->post('byc_documento');
		$byc_saldo           = $this->input->post('byc_saldo');
		$byc_cuenta_contable = $this->input->post('byc_cuenta_contable');
		$byc_id_cuenta       = $this->input->post('byc_id_cuenta');
		$byc_estado          = $this->input->post('byc_estado');
		
		
		$this->form_validation->set_rules('byc_referencia','Referencia','required');
		$this->form_validation->set_rules('byc_num_cuenta','Número de cuenta','required');
		$this->form_validation->set_rules('byc_tipo_cuenta','Tipo de cuenta','required');
		$this->form_validation->set_rules('byc_cuenta_contable','Cuenta contable','required');
		

		if($this->form_validation->run()){
			$data=array(
						'byc_tipo'           =>$byc_tipo,
						'byc_referencia'     =>$byc_referencia,
						'byc_num_cuenta'     =>$byc_num_cuenta,
						'byc_tipo_cuenta'    =>$byc_tipo_cuenta,
						'byc_documento'      =>$byc_documento,
						'byc_saldo'          =>$byc_saldo,
						'byc_cuenta_contable'=>$byc_cuenta_contable,
						'byc_id_cuenta'      =>$byc_id_cuenta ,
						'byc_estado'         =>$byc_estado,
			);	

			if($this->bancos_cajas_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'BANCOS Y CAJAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'bancos_cajas/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'bancos_cajas/nuevo/'.$opc_id);
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
						'banco_caja'=>$this->bancos_cajas_model->lista_un_banco_caja($id),
						'action'=>base_url().'bancos_cajas/actualizar/'.$opc_id,
						'cuentas'=>$this->plan_cuentas_model->lista_plan(),
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('bancos_cajas/form',$data);
			$modulo=array('modulo'=>'bancos_cajas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id                  = $this->input->post('byc_id');
		$byc_referencia      = $this->input->post('byc_referencia');
		$byc_tipo            = $this->input->post('byc_tipo');
		$byc_num_cuenta      = $this->input->post('byc_num_cuenta');
		$byc_tipo_cuenta     = $this->input->post('byc_tipo_cuenta');
		$byc_documento       = $this->input->post('byc_documento');
		$byc_saldo           = $this->input->post('byc_saldo');
		$byc_cuenta_contable = $this->input->post('byc_cuenta_contable');
		$byc_id_cuenta       = $this->input->post('byc_id_cuenta');
		$byc_estado          = $this->input->post('byc_estado');

		$this->form_validation->set_rules('byc_referencia','Referencia','required');
		$this->form_validation->set_rules('byc_num_cuenta','Número de cuenta','required');
		$this->form_validation->set_rules('byc_tipo_cuenta','Tipo de cuenta','required');
		$this->form_validation->set_rules('byc_cuenta_contable','Cuenta contable','required');
		
		if($this->form_validation->run()){
			$data=array(
						'byc_tipo'           =>$byc_tipo,
						'byc_referencia'     =>$byc_referencia,
						'byc_num_cuenta'     =>$byc_num_cuenta,
						'byc_tipo_cuenta'    =>$byc_tipo_cuenta,
						'byc_documento'      =>$byc_documento,
						'byc_saldo'          =>$byc_saldo,
						'byc_cuenta_contable'=>$byc_cuenta_contable,
						'byc_id_cuenta'      =>$byc_id_cuenta ,
						'byc_estado'         =>$byc_estado,
			);	
			if($this->bancos_cajas_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'BANCOS Y CAJAS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'bancos_cajas/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'bancos_cajas/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'banco_caja'=>$this->bancos_cajas_model->lista_un_banco_caja($id)
						// 'bancos_tarjetas'=>$this->bancos_tarjetas_model->lista_una_bancos_tarjetas($id)
						);
			$this->load->view('bancos_cajas/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->bancos_cajas_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'BANCOS Y TARJETAS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'bancos_cajas';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}
	
	public function excel($opc_id){

    	$titulo='Bancos y Cajas ';
    	$file="bancos_cajas".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'byc_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Bancos_Cajas'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->bancos_cajas_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Bancos_Cajas',
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

	public function load_codigo($id){

		$cta = $this->plan_cuentas_model->lista_plan_cuentas_id($id);
        echo $cta->pln_id . '&' . $cta->pln_codigo . '&' . $cta->pln_descripcion;
        
	}
    
}
