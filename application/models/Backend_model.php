<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_model extends CI_Model {


	public function get_id($link){
		
		$this->db->where("opc_direccion like '$link%'",null);
		$resultado=$this->db->get('erp_opciones');
		return $resultado->row();
	}

	public function get_opc_id($link){
		
		$this->db->where("opc_id",$link);
		$resultado=$this->db->get('erp_opciones');
		return $resultado->row();
	}

	public function get_permisos($opcion, $rol){
		
		$this->db->where('opc_id',$opcion);
		$this->db->where('rol_id',$rol);
		$resultado=$this->db->get('erp_roles_opcion');
		return $resultado->row();
	}
}
?>