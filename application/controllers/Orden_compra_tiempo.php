<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_tiempo extends CI_Controller {

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
		$this->load->model('orden_compra_tiempo_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
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
	

	public function index($opc_id){
		$data=array(
					'permisos'=>$this->permisos,
					'tiempos'=>$this->orden_compra_tiempo_model->lista_tiempos(),
					'opc_id'=>$opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('orden_compra_tiempo/lista',$data);
		$modulo=array('modulo'=>'orden_compra_tiempo');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->permisos->opc_id;
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'tiempo'=> (object) array(
											'tie_id'=>'0',
					                        'tie_tipo'=>'',
					                        'tie_cantidad'=>'',
					                        'tie_estado'=>'1',
										),
						'action'=>base_url().'orden_compra_tiempo/guardar/'.$opc_id,
						'opc_id'=>$opc_id,
						);
			$this->load->view('orden_compra_tiempo/form',$data);
			$modulo=array('modulo'=>'orden_compra_tiempo');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$tie_tipo= $this->input->post('tie_tipo');
		$tie_cantidad = $this->input->post('tie_cantidad');
		$tie_estado = $this->input->post('tie_estado');

		$this->form_validation->set_rules('tie_tipo','Tiempo','required');
		$this->form_validation->set_rules('tie_cantidad','Cantidad','required');

		if($this->form_validation->run()){
			$data=array(
					    'tie_tipo'=>$tie_tipo,
					    'tie_cantidad'=>$tie_cantidad,
					    'tie_estado'=>$tie_estado,
			);	

			if($this->orden_compra_tiempo_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ORDEN COMPRA TIEMPO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode( $this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$tie_tipo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'orden_compra_tiempo/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				$this->nuevo($opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'tiempo'=>$this->orden_compra_tiempo_model->lista_un_tiempo($id),
						'action'=>base_url().'orden_compra_tiempo/actualizar/'.$opc_id,
						'opc_id'=>$opc_id,
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('orden_compra_tiempo/form',$data);
			$modulo=array('modulo'=>'orden_compra_tiempo');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('tie_id');
		$tie_tipo= $this->input->post('tie_tipo');
		$tie_cantidad = $this->input->post('tie_cantidad');
		$tie_estado = $this->input->post('tie_estado');

		$this->form_validation->set_rules('tie_tipo','Tiempo','required');
		$this->form_validation->set_rules('tie_cantidad','Cantidad','required');


		if($this->form_validation->run()){
			$data=array(
					    'tie_tipo'=>$tie_tipo,
					    'tie_cantidad'=>$tie_cantidad,
					    'tie_estado'=>$tie_estado,
			);	
			
			if($this->orden_compra_tiempo_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ORDEN COMPRA TIEMPO',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'orden_compra_tiempo/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'orden_compra_tiempo/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'tiempo'=>$this->orden_compra_tiempo_model->lista_un_tiempo($id)
						);
			$this->load->view('orden_compra_tiempo/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre,$opc_id){
		if($this->permisos->rop_eliminar){
			if($this->orden_compra_tiempo_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ORDEN COMPRA TIEMPO',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo "orden_compra_tiempo/$opc_id";
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	

	public function excel($opc_id){

    	$titulo='Configuracion de Tiempo de Caducidad de Orden Compra';
    	$file="orden_compra_tiempos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
