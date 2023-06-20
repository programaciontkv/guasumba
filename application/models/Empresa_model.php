<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa_model extends CI_Model {

	public function lista_empresas(){
		$this->db->select("em.*,e.est_descripcion" );
		$this->db->from('erp_empresas em');
		$this->db->join('erp_estados e','e.est_id=em.emp_estado');
		$this->db->order_by('emp_id');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_empresas",$data);
			
	}

	public function lista_una_empresa($id){
		$this->db->select("em.*,e.est_descripcion" );
		$this->db->from('erp_empresas em');
		$this->db->join('erp_estados e','e.est_id=em.emp_estado');
		$this->db->where('em.emp_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_empresas_estado($est){

		$this->db->select("em.*,e.est_descripcion" );
		$this->db->from('erp_empresas em');
		$this->db->join('erp_estados e','e.est_id=em.emp_estado');
		$this->db->where('emp_estado',$est);
		$this->db->order_by('emp_id');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('emp_id',$id);
		return $this->db->update("erp_empresas",$data);
			
	}

	public function delete($id){
		$this->db->where('emp_id',$id);
		return $this->db->delete("erp_empresas");
			
	}

}

?>