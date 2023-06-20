<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {

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
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
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

	public function _remap($method, $params = array()){
    
	    if(!method_exists($this, $method))
	      {
	       $this->index($method, $params);
	    }else{
	      return call_user_func_array(array($this, $method), $params);
	    }
  	}

	

	public function index(){
		$data=array(
					'permisos'=>$this->permisos,
					'conf1'=>$this->configuracion_model->lista_una_configuracion('1'),
					'conf2'=>$this->configuracion_model->lista_una_configuracion('2'),
					'conf3'=>$this->configuracion_model->lista_una_configuracion('3'),
					'conf4'=>$this->configuracion_model->lista_una_configuracion('4'),
					'conf5'=>$this->configuracion_model->lista_una_configuracion('5'),
					'conf6'=>$this->configuracion_model->lista_una_configuracion('6'),
					'conf8'=>$this->configuracion_model->lista_una_configuracion('8'),
					'conf15'=>$this->configuracion_model->lista_una_configuracion('15'),
					'conf20'=>$this->configuracion_model->lista_una_configuracion('20'),
					'conf21'=>$this->configuracion_model->lista_una_configuracion('21'),
					'conf22'=>$this->configuracion_model->lista_una_configuracion('22'),
					'conf23'=>$this->configuracion_model->lista_una_configuracion('24'),
					'conf25'=>$this->configuracion_model->lista_una_configuracion('25'),
					'conf27'=>$this->configuracion_model->lista_una_configuracion('27'),
					'conf28'=>$this->configuracion_model->lista_una_configuracion('28'),
					'action'=>base_url().'configuracion/actualizar',
				);

		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('configuracion/lista',$data);
		$modulo=array('modulo'=>'configuracion');
		$this->load->view('layout/footer',$modulo);
	}


	
	public function envio_mail(){
		if($this->permisos->rop_actualizar){
			$conf=$this->configuracion_model->lista_una_configuracion('8');
			if(!empty($conf)){
				$valor=$conf->con_valor2;
			}else{
				$valor="&&&&&&&&";
			}
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'configuracion'=>$valor,
						'con_id'=>$conf->con_id,
						'action'=>base_url().'configuracion/actualizar_mail',
						'usu_sesion'=>$this->session->userdata('s_idusuario'),

						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('configuracion/form_mail',$data);
			$modulo=array('modulo'=>'configuracion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$nom_sistema = $this->input->post('nom_sistema');
		$dec_cantidad= $this->input->post('dec_cantidad');
		$dec_moneda = $this->input->post('dec_moneda');
		$val_inventario = $this->input->post('val_inventario');
		$auto = $this->input->post('auto');
		$asiento = $this->input->post('asientos');
		$ambiente = $this->input->post('ambiente');
		$ctrl_inventario = $this->input->post('ctrl_inventario');
		$precio     = $this->input->post('precio');
		$descuento  = $this->input->post('descuento');
		$m_pagos    = $this->input->post('m_pagos');
		$precio_pro = $this->input->post('precio_pro');
		$cred_aut   = $this->input->post('cred_aut');
		$chk_upt   = $this->input->post('chk_upt');
		$chk_img = $this->input->post('chk_img');
		
		$this->form_validation->set_rules('nom_sistema','Nombre del Sistema','required');
		$this->form_validation->set_rules('dec_cantidad','Decimales Cantidad','required');
		$this->form_validation->set_rules('dec_moneda','Decimales Moneda','required');

		if($this->form_validation->run()){
			$this->configuracion_model->update('15',['con_valor2'=>strtolower($nom_sistema)]);
			$this->configuracion_model->update('1',['con_valor'=>$dec_cantidad]);
			$this->configuracion_model->update('2',['con_valor'=>$dec_moneda]);
			$this->configuracion_model->update('3',['con_valor'=>$val_inventario]);
			$this->configuracion_model->update('4',['con_valor'=>$asiento]);
			$this->configuracion_model->update('5',['con_valor'=>$ambiente]);
			$this->configuracion_model->update('6',['con_valor'=>$ctrl_inventario]);
			$this->configuracion_model->update('20',['con_valor'=>$precio]);
			$this->configuracion_model->update('21',['con_valor'=>$descuento]);
			$this->configuracion_model->update('22',['con_valor'=>$m_pagos]);
			$this->configuracion_model->update('24',['con_valor'=>$auto]);
			$this->configuracion_model->update('25',['con_valor'=>$precio_pro]);
			$this->configuracion_model->update('27',['con_valor'=>$chk_upt,'con_valor2'=>$cred_aut]);
			$this->configuracion_model->update('28',['con_valor'=>$chk_img]);
			
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACIONES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'configuracion');
			
		}else{
			$this->editar($id);
		}	
	}


	public function actualizar_mail(){
		
		$id = $this->input->post('con_id');
		$secure= $this->input->post('secure');
		$puerto= $this->input->post('puerto');
		$host = $this->input->post('host');
		$usuario = $this->input->post('usuario');
		$contrasena = $this->input->post('contrasena');
		$emisor = $this->input->post('emisor');
		$asunto = $this->input->post('asunto');
		$mensaje = $this->input->post('mensaje');

		$this->form_validation->set_rules('secure','STMPSecure','required');
		$this->form_validation->set_rules('puerto','Puerto','required');
		$this->form_validation->set_rules('host','Host','required');
		$this->form_validation->set_rules('usuario','Usuario','required');
		$this->form_validation->set_rules('contrasena','Contraseña','required');
		$this->form_validation->set_rules('emisor','Emisor','required');
		$this->form_validation->set_rules('asunto','Asunto','required');

		if($this->form_validation->run()){
			$valor2=$secure.'&'.$puerto.'&'.strtolower($host).'&'.strtolower($usuario).'&'.$contrasena.'&'.$emisor.'&'.$asunto.'&'.$mensaje;
			$data=array(
						'con_valor2'=>$valor2,
			);	


			if($this->configuracion_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACIONES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'configuracion');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'configuracion/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function firma(){
		if($this->permisos->rop_actualizar){
			$conf=$this->configuracion_model->lista_una_configuracion('13');
			if(!empty($conf->con_valor2)){
				$valor=$conf->con_valor2;
			}else{
				$valor="& & & & &";
			}
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'configuracion'=>$valor,
						'con_id'=>$conf->con_id,
						'action'=>base_url().'configuracion/actualizar_firma'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('configuracion/form_firma',$data);
			$modulo=array('modulo'=>'configuracion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'configuracion'=>$this->configuracion_model->lista_un_configuracion($id)
						);
			$this->load->view('configuracion/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->configuracion_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONFIGURACIONES',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'configuracion';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

}
