<?php

class Backend_lib {
	
	private $CI;

	public function __construct(){
		$this->CI= & get_instance();
	}

	public function control(){
		if(!$this->CI->session->userdata('s_login')){
			redirect(base_url());
		}
		$url=$this->CI->uri->segment(1);
		// if($this->CI->uri->segment(3)){
		// 	$url=$this->CI->uri->segment(3);
		// }

		$infomenu=$this->CI->backend_model->get_id(strtoupper($url));
		$permisos=$this->CI->backend_model->get_permisos($infomenu->opc_id,$this->CI->session->userdata('s_rol'));
		
		if(!$permisos->rop_visualizar){
			redirect(base_url().'inicio');
		}else{
			return $permisos;
		}
	}
}
?>