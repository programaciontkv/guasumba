<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credito_dias_model extends CI_Model {

	public function lista_credito_dias(){
		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_credito_dias b');
		$this->db->join('erp_estados e','e.est_id=b.cre_estado');
		$this->db->order_by('cre_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
	}
	public function lista_credito_dias_estado($est){
		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_credito_dias b');
		$this->db->join('erp_estados e','e.est_id=b.cre_estado');
		$this->db->where('b.cre_estado',$est);
		$this->db->order_by('cre_dias','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function insert($data){
		
		return $this->db->insert("erp_credito_dias",$data);
			
	}

	public function lista_un_credito_dias($id){
		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_credito_dias b');
		$this->db->join('erp_estados e','e.est_id=b.cre_estado');
		$this->db->where('b.cre_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	
	public function update($id,$data){
		$this->db->where('cre_id',$id);
		return $this->db->update("erp_credito_dias",$data);
			
	}


}

?>