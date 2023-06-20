<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('login_model');
		$this->load->model('auditoria_model');
	}

	public function index(){
		if($this->session->userdata('s_login')){
			redirect(base_url().'inicio');
		}else{
			$this->load->view('login/index');
		}
	}

	public function ingresar(){
		$usuario = $this->input->post('usuario');
		$clave = $this->input->post('clave');

		$res = $this->login_model->ingresar($usuario, $clave);

		if(!$res){
			$this->session->set_flashdata('error','El usuario o contraseña son incorrectos');
			redirect(base_url());
		}else{
			$session_usuario=array(
									's_idusuario' =>$res->usu_id,
									's_usuario' =>$res->usu_login,
									's_rol' =>$res->rol_id,
									's_imagen' =>$res->usu_imagen,
									's_login'=>TRUE 
								);

			$this->session->set_userdata($session_usuario);
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'LOGIN',
								'adt_accion'=>'INGRESAR',
								'adt_campo'=>'',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			redirect(base_url().'inicio');
		}

	}

	public function salir(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

}
