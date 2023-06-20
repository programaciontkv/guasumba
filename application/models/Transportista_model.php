<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transportista_model extends CI_Model {

	public function lista_transportistas(){
		$this->db->select('t.*,e.est_descripcion'); 
		$this->db->from('erp_transportista t'); 
		$this->db->join('erp_estados e','e.est_id=t.tra_estado'); 
		$this->db->order_by('t.tra_razon_social', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	
	public function insert($data){
		
		return $this->db->insert("erp_transportista",$data);
			
	}

	public function lista_un_transportista($id){
		$this->db->select('t.*,e.est_descripcion'); 
		$this->db->from('erp_transportista t'); 
		$this->db->join('erp_estados e','e.est_id=t.tra_estado'); 
		$this->db->where('tra_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_un_transportista_identificacion($id){
		$this->db->select('t.*,e.est_descripcion'); 
		$this->db->from('erp_transportista t'); 
		$this->db->join('erp_estados e','e.est_id=t.tra_estado'); 
		$this->db->where('tra_identificacion',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	
	public function update($id,$data){
		$this->db->where('tra_id',$id);
		return $this->db->update("erp_transportista",$data);
			
	}

	public function delete($id){
		$this->db->where('tra_id',$id);
		return $this->db->delete("erp_transportista");
			
	}

	public function lista_transportistas_estado($est){
		$this->db->select('t.*,e.est_descripcion'); 
		$this->db->from('erp_transportista t'); 
		$this->db->join('erp_estados e','e.est_id=t.tra_estado'); 
		$this->db->where('t.tra_estado', $est); 
		$this->db->order_by('t.tra_razon_social', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}
}

?>