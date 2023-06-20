<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor extends CI_Controller {

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
		$this->load->model('usuario_model');
		$this->load->model('vendedor_model');
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
					'vendedores'=>$this->vendedor_model->lista_vendedores(),
					'usu_sesion'=>$this->session->userdata('s_idusuario'),
					'opc_id'=>$rst_opc->opc_id,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('vendedor/lista',$data);
		$modulo=array('modulo'=>'vendedor');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$usu=$this->session->userdata('s_idusuario');
		if($usu==1){
			$lista=$this->usuario_model->lista_usuarios_estado('1');
		}else{
			$lista=$this->usuario_model->lista_usuarios_estado_2('1');
		}

		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'usuarios'=>$lista,
						'opc_id'=>$rst_opc->opc_id,
						'vendedor'=> (object) array(
											'vnd_nombre'=>'',
											'vnd_local'=>'',
											'vnd_estado'=>'1',
											'vnd_id'=>''
										),
						'action'=>base_url().'vendedor/guardar/'.$opc_id
						);
			$this->load->view('vendedor/form',$data);
			$modulo=array('modulo'=>'vendedor');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$nombre = $this->input->post('vnd_nombre');
		$usuario = $this->input->post('vnd_local');
		$estado = $this->input->post('vnd_estado');

		$this->form_validation->set_rules('vnd_nombre','Nombre','required');
		$this->form_validation->set_rules('vnd_local','Usuario','required');

		if($this->form_validation->run()){
			$data=array(
						 'vnd_nombre'=>$nombre,
						 'vnd_local'=>$usuario,
						 'vnd_estado'=>$estado,
			);	

			if($this->vendedor_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'VENDEDORES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'vendedor/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'vendedor/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$usu=$this->session->userdata('s_idusuario');
		if($usu==1){
			$lista=$this->usuario_model->lista_usuarios_estado('1');
		}else{
			$lista=$this->usuario_model->lista_usuarios_estado_2('1');
		}
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'usuarios'=>$lista,
						'vendedor'=>$this->vendedor_model->lista_un_vendedor($id),
						'action'=>base_url().'vendedor/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('vendedor/form',$data);
			$modulo=array('modulo'=>'vendedor');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('vnd_id');
		$nombre = $this->input->post('vnd_nombre');
		$usuario = $this->input->post('vnd_local');
		$estado = $this->input->post('vnd_estado');

		$vendedor_act=$this->vendedor_model->lista_un_vendedor($id);

		if($usuario==$vendedor_act->vnd_codigo){
			$unique='';
		}else{
			$unique='|is_unique[erp_vendedor.vnd_codigo]';
		}

		$this->form_validation->set_rules('vnd_nombre','Nombre','required');
		$this->form_validation->set_rules('vnd_local','Usuario','required');

		if($this->form_validation->run()){
			$data=array(
						 'vnd_nombre'=>$nombre,
						 'vnd_local'=>$usuario,
						 'vnd_estado'=>$estado,
			);	

			if($this->vendedor_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'VENDEDORES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'vendedor/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'vendedor/editar'.$id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'vendedor'=>$this->vendedor_model->lista_un_vendedor($id)
						);
			$this->load->view('vendedor/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->vendedor_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'VENDEDORES',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'vendedor';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function excel($opc_id){

    	$titulo='Vendedores';
    	$file="vendedores".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'vnd_estado'=>$estado, 
		    );

			$data_audito=array(
		    			'Vendedor'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->vendedor_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'Vendedor',
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
