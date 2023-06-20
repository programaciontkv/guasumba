<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forma_pago_model extends CI_Model {

	public function lista_formas_pago(){
		$this->db->select("f.*,e.est_descripcion,CASE WHEN f.fpg_banco>0 THEN (select btr_descripcion from erp_bancos_tarjetas b where f.fpg_banco=b.btr_id) ELSE '' END as banco,CASE WHEN f.fpg_tarjeta>0 THEN (select btr_descripcion from erp_bancos_tarjetas b where f.fpg_tarjeta=b.btr_id) ELSE '' END as tarjeta" );
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		$this->db->order_by('fpg_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_formas_pago",$data);
			
	}

	public function lista_una_forma_pago($id){
		$this->db->select("f.*,e.est_descripcion");
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		$this->db->where('f.fpg_descripcion',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	public function lista_una_forma_pago_des($id){
		$id= str_replace("%20"," ", $id);
		$this->db->select("f.*,e.est_descripcion");
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		$this->db->where('f.fpg_descripcion',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	public function lista_una_forma_pago_id($id){
		$this->db->select("f.*,e.est_descripcion");
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		$this->db->where('f.fpg_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_formas_pago_estado($est){

		$this->db->select("f.*,e.est_descripcion");
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		$this->db->where('fpg_estado',$est);
		$this->db->order_by('fpg_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
			
	}
	public function lista_formas_pago_masivo($est){

		$this->db->select("f.*,e.est_descripcion");
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		// $this->db->where('fpg_estado',$est);
		$this->db->where("(f.fpg_descripcion ='EFECTIVO' OR f.fpg_descripcion ='TRANSFERENCIA') and fpg_estado='$est'   ",null);
		$this->db->order_by('fpg_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
			
	}

	public function update($id,$data){
		$this->db->where('fpg_id',$id);
		return $this->db->update("erp_formas_pago",$data);
			
	}

	public function delete($id){
		$this->db->where('fpg_id',$id);
		return $this->db->delete("erp_formas_pago");
			
	}

	public function lista_plan_cuentas_estado($est){
		$this->db->from('erp_plan_cuentas');
		$this->db->where('pln_estado',$est);
		$this->db->order_by('pln_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_un_plan_cuentas($id){
		$this->db->from('erp_plan_cuentas');
		$this->db->where('pln_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	public function lista_una_forma_pago_tipo($id){
		$this->db->select("f.*,e.est_descripcion");
		$this->db->from('erp_formas_pago f');
		$this->db->join('erp_estados e','e.est_id=f.fpg_estado');
		$this->db->where('f.fpg_tipo',$id);
		$resultado=$this->db->get();
		return $resultado->row();
				
	}

}

?>