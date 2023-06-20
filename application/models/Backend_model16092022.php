<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_model extends CI_Model {


	public function get_id($link){
		
		$this->db->where("opc_direccion like '$link%'",null);
		$resultado=$this->db->get('erp_opciones');
		return $resultado->row();
		// $this->db->select('o.*,r.men_id'); 
		// $this->db->from('erp_opciones o'); 
		// $this->db->join('erp_roles_opcion r', 'r.opc_id=o.opc_id'); 
		// $this->db->where("opc_direccion like '$link%'",null);
		// $resultado=$this->db->get();
		// return $resultado->row();
	}

	public function get_permisos($opcion,$rol){
		
		// $this->db->where('opc_id',$opcion);
		// $this->db->where('rol_id',$rol);
		// $resultado=$this->db->get('erp_roles_opcion');
		//return $resultado->row();

		$query="select *from erp_roles_opcion where  opc_id='$opcion' and rol_id='$rol'";
		$resultado = $this->db->query($query);
      return $resultado->row();
		
	}
}
?>