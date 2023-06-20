<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pregunta_model extends CI_Model {

	public function lista_preguntas($txt){
		$this->db->from('erp_preguntas p');
		$this->db->join('erp_estados e','e.est_id=p.pre_estado');
		$this->db->where("pre_menu like '%$txt%' or pre_seccion like '%$txt%'");
		$this->db->order_by('pre_menu');
		$resultado=$this->db->get();
		return $resultado->result();
	} 

	public function lista_una_pregunta($id){
		$this->db->from('erp_preguntas p');
		$this->db->join('erp_estados e','e.est_id=p.pre_estado');
		$this->db->where('p.pre_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function insert($data){
		return $this->db->insert("erp_preguntas",$data);
	}
	
	public function update($id,$data){
		$this->db->where('pre_id',$id);
		return $this->db->update("erp_preguntas",$data);
			
	}

	public function delete($id){
		$this->db->where('pre_id',$id);
		return $this->db->delete("erp_preguntas");
			
	}

}

?>