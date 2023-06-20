<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Impuesto extends CI_Controller {

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
		$this->load->model('impuesto_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
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
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$data=array(
					'permisos'=>$this->permisos,
					'impuestos'=>$this->impuesto_model->lista_impuestos(),
					'opc_id'=>$rst_opc->opc_id,
					
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('impuesto/lista',$data);
		$modulo=array('modulo'=>'impuesto');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->impuesto_model->lista_plan_cuentas_estado('1'),
						'opc_id'=>$rst_opc->opc_id,
						'impuesto'=> (object) array(
											'por_id'=>'',
					                        'por_descripcion'=>'',
					                        'por_codigo'=>'',
					                        'por_cod_ats'=>'',
					                        'cta_id'=>'',
					                        'por_porcentage'=>'0',
					                        'por_estado'=>'1',
					                        'pln_codigo'=>'',
					                        'pln_descripcion'=>'',
					                        'por_siglas'=>'',
										),
						'action'=>base_url().'impuesto/guardar/'.$opc_id,
						);
			$this->load->view('impuesto/form',$data);
			$modulo=array('modulo'=>'impuesto');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$por_descripcion= $this->input->post('por_descripcion');
		$por_codigo = $this->input->post('por_codigo');
		$por_cod_ats = $this->input->post('por_cod_ats');
		$por_porcentage = $this->input->post('por_porcentage');
		$cta_id = $this->input->post('cta_id');
		$por_siglas = $this->input->post('por_siglas');
		$por_estado = $this->input->post('por_estado');
		
		$this->form_validation->set_rules('por_siglas','Tipo','required');
		$this->form_validation->set_rules('por_codigo','Codigo','required');
		// $this->form_validation->set_rules('por_codigo','Codigo','required|is_unique[porcentages_retencion.por_codigo]');
		// $this->form_validation->set_rules('por_cod_ats','Codigo ATS','required');
		$this->form_validation->set_rules('por_descripcion','Descripcion','required');
		$this->form_validation->set_rules('cta_id','Cuenta contable','required');
		

		if($this->form_validation->run()){
			$data=array(
					    'por_descripcion'=>$por_descripcion,
					    'por_codigo'=>$por_codigo,
					    'por_cod_ats'=>$por_cod_ats,
					    'por_porcentage'=>$por_porcentage,
					    'cta_id'=>$cta_id,
					    'por_estado'=>$por_estado,
					    'por_siglas'=>$por_siglas,
			);	

			if($this->impuesto_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'IMPUESTOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'impuesto/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'impuesto/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id,$opc_id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cuentas'=>$this->impuesto_model->lista_plan_cuentas_estado('1'),
						'impuesto'=>$this->impuesto_model->lista_un_impuesto($id),
						'action'=>base_url().'impuesto/actualizar/'.$opc_id,
						'opc_id'=>$opc_id,
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('impuesto/form',$data);
			$modulo=array('modulo'=>'impuesto');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('por_id');
		$por_descripcion= $this->input->post('por_descripcion');
		$por_codigo = $this->input->post('por_codigo');
		$por_cod_ats = $this->input->post('por_cod_ats');
		$por_porcentage = $this->input->post('por_porcentage');
		$cta_id = $this->input->post('cta_id');
		$por_siglas = $this->input->post('por_siglas');
		$por_estado = $this->input->post('por_estado');

		$impuesto_act=$this->impuesto_model->lista_un_impuesto($id);

		if($por_codigo==$impuesto_act->fpg_descripcion){
			$unique='';
		}else{
			$unique='|is_unique[porcentages_retencion.por_codigo]';
		}
		
		$this->form_validation->set_rules('por_siglas','Tipo','required');
		$this->form_validation->set_rules('por_codigo','Codigo','required');
		// $this->form_validation->set_rules('por_codigo','Codigo','required|is_unique[porcentages_retencion.por_codigo]');
		// $this->form_validation->set_rules('por_cod_ats','Codigo ATS','required');
		$this->form_validation->set_rules('por_descripcion','Descripcion','required');
		$this->form_validation->set_rules('cta_id','Cuenta contable','required');

		if($this->form_validation->run()){
			$data=array(
					    'por_descripcion'=>$por_descripcion,
					    'por_codigo'=>$por_codigo,
					    'por_cod_ats'=>$por_cod_ats,
					    'por_porcentage'=>$por_porcentage,
					    'cta_id'=>$cta_id,
					    'por_estado'=>$por_estado,
					    'por_siglas'=>$por_siglas,
			);	

			if($this->impuesto_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'IMPUESTOS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'impuesto/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'impuesto/editar/'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'impuesto'=>$this->impuesto_model->lista_un_impuesto($id)
						);
			$this->load->view('impuesto/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->impuesto_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'IMPUESTOS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'impuesto';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function traer_cuenta($id){
		$rst=$this->impuesto_model->lista_un_plan_cuentas($id);
		echo $rst->pln_id.'&&'.$rst->pln_codigo.'&&'.$rst->pln_descripcion;
	}

	public function excel($opc_id){

    	$titulo='Impuestos';
    	$file="impuestos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'por_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Impuesto'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->impuesto_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'IMPUESTO',
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
