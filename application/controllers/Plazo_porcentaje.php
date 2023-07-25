<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class plazo_porcentaje extends CI_Controller {

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
		$this->load->model('plazo_porcentaje_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
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
					'plazo_porcentaje'=>$this->plazo_porcentaje_model->lista_plazo_porcentaje(),
					'opc_id'=>$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('plazo_porcentaje/lista',$data);
		$modulo=array('modulo'=>'bancos_tarjetas');
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
						'plazo_porcentaje'=> (object) array(
											'pcv_id'=>'',
					                        'pcv_desde'=>'0',
					                        'pcv_hasta'=>'0',
					                        'pcv_porcentaje'=>'1',
					                        'pcv_categoria'=>'',
					                        'pcv_tipo'=>'1',
                                            'pcv_estado'=>'1'
										),
						'action'=>base_url().'plazo_porcentaje/guardar/'.$opc_id
						);
			$this->load->view('plazo_porcentaje/form',$data);
			$modulo=array('modulo'=>'plazo_porcentaje');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		// $pcv_id        = $this->input->post('pcv_id');
		$pcv_desde     = $this->input->post('pcv_desde');
		$pcv_hasta     = $this->input->post('pcv_hasta');
		$pcv_porcentaje= $this->input->post('pcv_porcentaje');
		$pcv_categoria = $this->input->post('pcv_categoria');
		$pcv_tipo      = $this->input->post('pcv_tipo');
		$pcv_estado    = $this->input->post('pcv_estado');
		


		$this->form_validation->set_rules('pcv_tipo','Tipo','required');
		$this->form_validation->set_rules('pcv_desde','Desde','required');
		$this->form_validation->set_rules('pcv_hasta','Hasta','required');
		$this->form_validation->set_rules('pcv_porcentaje','Porcentaje','required');
		

		if($this->form_validation->run()){
			$data=array(
                        'pcv_desde'=>$pcv_desde,
                        'pcv_hasta'=>$pcv_hasta,
                        'pcv_porcentaje'=>$pcv_porcentaje,
                        'pcv_categoria'=>$pcv_categoria,
                        'pcv_tipo'=>$pcv_tipo,
                        'pcv_estado'=>$pcv_estado
			);	

			if($this->plazo_porcentaje_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PLAZOS Y PORCENTAJES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'plazo_porcentaje/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'plazo_porcentaje/nuevo/'.$opc_id);
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
						'plazo_porcentaje'=>$this->plazo_porcentaje_model->lista_un_plazo_porcentaje($id),
						'action'=>base_url().'plazo_porcentaje/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('plazo_porcentaje/form',$data);
			$modulo=array('modulo'=>'plazo_porcentaje');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$pcv_id        = $this->input->post('pcv_id');
		$pcv_desde     = $this->input->post('pcv_desde');
		$pcv_hasta     = $this->input->post('pcv_hasta');
		$pcv_porcentaje= $this->input->post('pcv_porcentaje');
		$pcv_categoria = $this->input->post('pcv_categoria');
		$pcv_tipo      = $this->input->post('pcv_tipo');
		$pcv_estado    = $this->input->post('pcv_estado');

		$this->form_validation->set_rules('pcv_tipo','Tipo','required');
		$this->form_validation->set_rules('pcv_desde','Desde','required');
		$this->form_validation->set_rules('pcv_hasta','Hasta','required');
		$this->form_validation->set_rules('pcv_porcentaje','Porcentaje','required');
		
		if($this->form_validation->run()){
			$data=array(
                            'pcv_desde'=>$pcv_desde,
                            'pcv_hasta'=>$pcv_hasta,
                            'pcv_porcentaje'=>$pcv_porcentaje,
                            'pcv_categoria'=>$pcv_categoria,
                            'pcv_tipo'=>$pcv_tipo,
                            'pcv_estado'=>$pcv_estado
			);
            //var_dump($data);
			if($this->plazo_porcentaje_model->update($pcv_id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PLAZO  Y PORCENTAJES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'plazo_porcentaje/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'plazo_porcentaje/editar'.$pcv_id.'/'.$opc_id);
			}
		}else{
			$this->editar($pcv_id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'bancos_tarjetas'=>$this->plazo_porcentaje_model->lista_un_banco_tarjeta($id)
						// 'bancos_tarjetas'=>$this->plazo_porcentaje_model->lista_una_bancos_tarjetas($id)
						);
			$this->load->view('plazo_porcentaje/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->plazo_porcentaje_model->delete($id)){
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
				echo 'bancos_tarjetas';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}
	
	public function excel($opc_id){

    	$titulo='Bancos, Tarjetas y Plazos ';
    	$file="bancos_tarjetas_plazos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'pcv_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Plazos_porcentaje'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->plazo_porcentaje_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Bancos_tarjetas',
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
