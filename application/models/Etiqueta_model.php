<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etiqueta_model extends CI_Model {

	
	public function lista_etiquetas(){
		$this->db->select('i.*,e.est_descripcion');
		$this->db->from('erp_etiquetas i');
		$this->db->join('erp_estados e','e.est_id=i.eti_estado');
		$this->db->order_by('eti_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		
		return $this->db->insert("erp_etiquetas",$data);
			
	}

	public function lista_una_etiqueta($id){
		$this->db->select('i.*,e.est_descripcion');
		$this->db->from('erp_etiquetas i');
		$this->db->join('erp_estados e','e.est_id=i.eti_estado');
		$this->db->where('eti_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('eti_id',$id);
		return $this->db->update("erp_etiquetas",$data);
			
	}

	public function delete($id){
		$this->db->where('eti_id',$id);
		return $this->db->delete("erp_etiquetas");
			
	}

}

?>