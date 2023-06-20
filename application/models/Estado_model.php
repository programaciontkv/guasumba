<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estado_model extends CI_Model {

	public function lista_estados(){
		
		$this->db->order_by('est_descripcion');
		$resultado=$this->db->get('erp_estados');
		return $resultado->result();
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_estados",$data);
	}

	public function lista_un_estado($id){
		
		$this->db->where('est_id',$id);
		$resultado=$this->db->get('erp_estados');
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('est_id',$id);
		return $this->db->update("erp_estados",$data);
			
	}

	public function delete($id){
		$this->db->where('est_id',$id);
		return $this->db->delete("erp_estados");
			
	}


	public function lista_estados_opcion($id){
		$this->db->select('eo.*, o.opc_nombre');
		$this->db->from('erp_estados_opcion eo');
		$this->db->join('erp_opciones o', 'o.opc_id=eo.opc_id');
		$this->db->where('eo.est_id',$id);
		$this->db->order_by('o.opc_nombre');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function insert_opcion($data){
		return $this->db->insert("erp_estados_opcion",$data);
	}


	public function delete_opcion($id){
		$this->db->where('eop_id',$id);
		return $this->db->delete("erp_estados_opcion");
			
	}

	public function lista_estados_modulo($id){
		$this->db->select('eo.*, o.opc_nombre,e.est_descripcion');
		$this->db->from('erp_estados_opcion eo');
		$this->db->join('erp_opciones o', 'o.opc_id=eo.opc_id');
		$this->db->join('erp_estados e', 'e.est_id=eo.est_id');
		$this->db->where('eo.opc_id',$id);
		
		$resultado=$this->db->get();
		return $resultado->result();
	}
	
	
}

?>