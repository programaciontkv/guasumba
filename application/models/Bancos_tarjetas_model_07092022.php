<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bancos_tarjetas_model extends CI_Model {

	public function lista_bancos_tajetas(){
		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_tarjetas b');
		$this->db->join('erp_estados e','e.est_id=b.btr_estado');
		$this->db->order_by('btr_tipo');
		$this->db->order_by('btr_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function insert($data){
		
		return $this->db->insert("erp_bancos_tarjetas",$data);
			
	}

	public function lista_un_banco_tarjeta($id){
		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_tarjetas b');
		$this->db->join('erp_estados e','e.est_id=b.btr_estado');
		$this->db->where('b.btr_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	

	public function lista_bancos_tarjetas_estado($est){

		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_tarjetas b');
		$this->db->join('erp_estados e','e.est_id=b.btr_estado');
		$this->db->where('btr_estado',$est);
		$this->db->order_by('btr_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_bancos_tarjetas_tipo($tip,$est){

		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_tarjetas b');
		$this->db->join('erp_estados e','e.est_id=b.btr_estado');
		$this->db->where('btr_estado',$est);
		$this->db->where('btr_tipo',$tip);
		$this->db->order_by('btr_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_bancos_tarjetas_plazo($tip,$forma,$est){

		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_tarjetas b');
		$this->db->join('erp_estados e','e.est_id=b.btr_estado');
		$this->db->where('btr_estado',$est);
		$this->db->where('btr_tipo',$tip);
		$this->db->where('btr_forma',$forma);
		$this->db->order_by('btr_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('btr_id',$id);
		return $this->db->update("erp_bancos_tarjetas",$data);
			
	}

	public function delete($id){
		$this->db->where('btr_id',$id);
		return $this->db->delete("erp_bancos_tarjetas");
			
	}

}

?>