<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_cuentas_model extends CI_Model {

	public function lista_plan_cuentas(){
		$this->db->select("p.*,e.est_descripcion" );
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->order_by('pln_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	public function insert($data){
		
		return $this->db->insert("erp_plan_cuentas",$data);
			
	}

	public function lista_un_plan_cuentas($id){
		$this->db->select("p.*,e.est_descripcion" );
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->where('pln_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_un_plan_cuentas_codigo($id){
		$this->db->where('trim(pln_codigo)',$id);
		$resultado=$this->db->get('erp_plan_cuentas');
		return $resultado->row();
			
	}

	public function lista_plan_cuentas_estado($est){

		$this->db->select("p.*,e.est_descripcion" );
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->where('pln_estado',$est);
		$this->db->order_by('pln_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_plan_cuentas_estado_tipo($est,$tip){

		$this->db->select("p.*,e.est_descripcion" );
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->where('pln_estado',$est);
		$this->db->where('pln_tipo',$tip);
		$this->db->order_by('trim(pln_codigo)');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_plan_cuentas_mov_estado($est){

		$this->db->select("p.*,e.est_descripcion" );
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->where('pln_estado',$est);
		$this->db->order_by('pln_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('pln_id',$id);
		return $this->db->update("erp_plan_cuentas",$data);
			
	}

	public function delete($id){
		$this->db->where('pln_id',$id);
		return $this->db->delete("erp_plan_cuentas");
			
	}

	
}

?>