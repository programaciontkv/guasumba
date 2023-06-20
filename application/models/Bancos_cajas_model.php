<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bancos_cajas_model extends CI_Model {

	public function lista_bancos_cajas(){
		$this->db->select("b.*,e.est_descripcion,pln.*");
		$this->db->from('erp_bancos_y_cajas b');
		$this->db->join('erp_estados e','e.est_id=b.byc_estado');
		$this->db->join('erp_plan_cuentas pln','pln.pln_id=b.byc_id_cuenta and pln.pln_codigo=b.byc_cuenta_contable');
		$this->db->order_by('byc_tipo');
		$this->db->order_by('byc_referencia');
		$resultado=$this->db->get();
		return $resultado->result();
		

	}

	public function insert($data){
		
		return $this->db->insert("erp_bancos_y_cajas",$data);
		
	}

	public function lista_un_banco_caja($id){
		$this->db->select("b.*,e.est_descripcion,pln.*");
		$this->db->from('erp_bancos_y_cajas b');
		$this->db->join('erp_estados e','e.est_id=b.byc_estado');
		$this->db->join('erp_plan_cuentas pln','pln.pln_id=b.byc_id_cuenta and pln.pln_codigo=b.byc_cuenta_contable');
		$this->db->where('b.byc_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	

	public function lista_bancos_cajas_estado($est){

		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_y_cajas b');
		$this->db->join('erp_estados e','e.est_id=b.byc_estado');
		$this->db->where('byc_estado',$est);
		$this->db->order_by('byc_referencia');
		$resultado=$this->db->get();
		return $resultado->result();		
	}

	public function lista_bancos_cajas_estado_2($est){

		$this->db->select("b.*,e.est_descripcion,pln.*");
		$this->db->from('erp_bancos_y_cajas b');
		$this->db->join('erp_estados e','e.est_id=b.byc_estado');
		$this->db->join('erp_plan_cuentas pln','pln.pln_id=b.byc_id_cuenta and pln.pln_codigo=b.byc_cuenta_contable');
		$this->db->where('byc_estado',$est);
		$this->db->order_by('byc_referencia');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
		
			
	}

	public function lista_bancos_cajas_tipo($tip,$est){

		$this->db->select("b.*,e.est_descripcion");
		$this->db->from('erp_bancos_y_cajas b');
		$this->db->join('erp_estados e','e.est_id=b.btr_estado');
		$this->db->where('btr_estado',$est);
		$this->db->where('btr_tipo',$tip);
		$this->db->order_by('btr_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	

	public function update($id,$data){
		$this->db->where('byc_id',$id);
		return $this->db->update("erp_bancos_y_cajas",$data);
			
	}

	public function delete($id){
		$this->db->where('byc_id',$id);
		return $this->db->delete("erp_bancos_y_cajas");
			
	}

}

?>