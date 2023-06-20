<?php

class Backend_lib {
	
	private $CI;

	public function __construct(){
		$this->CI= & get_instance();
	}

	public function control($opc_id=''){
		if(!$this->CI->session->userdata('s_login')){

			redirect(base_url());
		}
		$url=$this->CI->uri->segment(1);
		$infomenu=$this->CI->backend_model->get_id(strtoupper($url));
		if($url=='factura' || $url=='nota_credito' || $url=='guia_remision' || $url=='ingreso' || $url=='egreso'){
			$accion=$this->CI->uri->segment(2);
			if($accion=='nuevo' || $accion=='guardar' || $accion=='show_frame'){
				$us=$this->CI->uri->total_segments();
				$url=$this->CI->uri->segment($us);
				$infomenu=$this->CI->backend_model->get_opc_id(strtoupper($url));
			}
		}
		// if($this->CI->uri->segment(3)){
		// 	$url=$this->CI->uri->segment(3);
		// }
		
		$permisos=$this->CI->backend_model->get_permisos($infomenu->opc_id,$this->CI->session->userdata('s_rol'));
		if(!$permisos->rop_visualizar){
			redirect(base_url().'inicio');
		}else{
			return $permisos;
		}
	}
}
?>