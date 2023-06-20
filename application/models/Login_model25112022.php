<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {


	public function ingresar($usuario,$clave){
		
		$this->db->where('usu_login',$usuario);
		$this->db->where('usu_pass',md5($clave));
		$this->db->where('usu_estado','1'); ///estado activo
		$resultado=$this->db->get('erp_users');

		if($resultado->num_rows()>0){
			return $resultado->row();
		}else{
			return false;
		}
	}
}
?>