<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor_model extends CI_Model {

	public function lista_vendedores(){
		$this->db->select('v.*, u.usu_login,e.est_descripcion'); 
		$this->db->from('erp_vendedor v'); 
		$this->db->join('erp_users u','u.usu_id=cast(v.vnd_local as integer)'); 
		$this->db->join('erp_estados e','e.est_id=v.vnd_estado'); 
		$this->db->order_by('v.vnd_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	
	public function insert($data){
		
		return $this->db->insert("erp_vendedor",$data);
			
	}

	public function lista_un_vendedor($id){
		$this->db->select('v.*, u.usu_login,e.est_descripcion'); 
		$this->db->from('erp_vendedor v'); 
		$this->db->join('erp_users u','u.usu_id=cast(v.vnd_local as integer)'); 
		$this->db->join('erp_estados e','e.est_id=v.vnd_estado'); 
		$this->db->where('vnd_id',$id);
		$resultado=$this->db->get();
		
		return $resultado->row();
			
	}

	public function lista_un_vendedor_usuario($id){
		$this->db->select('v.*, u.usu_login,e.est_descripcion'); 
		$this->db->from('erp_vendedor v'); 
		$this->db->join('erp_users u','u.usu_id=cast(v.vnd_local as integer)'); 
		$this->db->join('erp_estados e','e.est_id=v.vnd_estado'); 
		$this->db->where('vnd_local',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('vnd_id',$id);
		return $this->db->update("erp_vendedor",$data);
			
	}

	public function delete($id){
		$this->db->where('vnd_id',$id);
		return $this->db->delete("erp_vendedor");
			
	}

	public function lista_vendedores_estado($est){
		$this->db->select('v.*, u.usu_login,e.est_descripcion'); 
		$this->db->from('erp_vendedor v'); 
		$this->db->join('erp_users u','u.usu_id=cast(v.vnd_local as integer)'); 
		$this->db->join('erp_estados e','e.est_id=v.vnd_estado'); 
		$this->db->where('v.vnd_estado', $est); 
		$this->db->where('v.vnd_id != 1'); 
		$this->db->order_by('v.vnd_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}
}

?>