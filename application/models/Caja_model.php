<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja_model extends CI_Model {

	public function lista_cajas(){
		$this->db->select("cj.*,em.emi_nombre,em.emi_cod_punto_emision,e.est_descripcion,ep.emp_nombre,ep.emp_identificacion, ep.emp_direccion, c.cli_raz_social" );
		$this->db->from('erp_cajas cj');
		$this->db->join('erp_emisor em','em.emi_id=cj.emi_id');
		$this->db->join('erp_empresas ep','em.emp_id=ep.emp_id');
		$this->db->join('erp_estados e','e.est_id=em.emi_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=em.emi_cod_cli');
		$this->db->order_by('ep.emp_nombre');
		$this->db->order_by('em.emi_cod_punto_emision');
		$this->db->order_by('cj.cja_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_cajas",$data);
			
	}

	public function lista_una_caja($id){
		$this->db->select("cj.*,em.emi_id,em.emi_nombre,em.emi_cod_punto_emision,em.emi_cod_cli,e.est_descripcion,ep.emp_nombre,ep.emp_id,ep.emp_identificacion, ep.emp_direccion, c.cli_raz_social" );
		$this->db->from('erp_cajas cj');
		$this->db->join('erp_emisor em','em.emi_id=cj.emi_id');
		$this->db->join('erp_empresas ep','em.emp_id=ep.emp_id');
		$this->db->join('erp_estados e','e.est_id=em.emi_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=em.emi_cod_cli');
		$this->db->where('cj.cja_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_cajas_estado($est){

		$this->db->select("cj.*,em.emi_nombre,em.emi_cod_punto_emision,e.est_descripcion,ep.emp_nombre,ep.emp_identificacion, ep.emp_direccion, c.cli_raz_social" );
		$this->db->from('erp_cajas cj');
		$this->db->join('erp_emisor em','em.emi_id=cj.emi_id');
		$this->db->join('erp_empresas ep','em.emp_id=ep.emp_id');
		$this->db->join('erp_estados e','e.est_id=em.emi_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=em.emi_cod_cli');
		$this->db->where('cja_estado',$est);
		$this->db->order_by('cja_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('cja_id',$id);
		return $this->db->update("erp_cajas",$data);
			
	}

	public function delete($id){
		$this->db->where('cja_id',$id);
		return $this->db->delete("erp_cajas");
			
	}

}

?>