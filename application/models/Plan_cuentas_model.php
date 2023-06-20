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
	public function lista_plan_cuentas_buscador($text,$estado){
		$this->db->select("p.*,e.est_descripcion" );
		$this->db->where("(pln_descripcion like '%$text%' or pln_codigo like '%$text%' )",null);
		if ($estado!='') {
			$this->db->where("pln_estado",$estado);
		}
		
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->order_by('pln_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
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

	public function lista_plan_cuentas_estado_tipo_2($est,$tip){

		$this->db->select("p.*,e.est_descripcion" );
		$this->db->from('erp_plan_cuentas p');
		$this->db->join('erp_estados e','e.est_id=p.pln_estado');
		$this->db->where('pln_estado',$est);
		$this->db->where("(pln_codigo like '5.%'  or pln_codigo like '6.%' or pln_codigo like '1.%')",null);
		$this->db->where('pln_tipo',$tip);
		$this->db->order_by('trim(pln_codigo)');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
			
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
	public function consulta_cuenta($id){
		$this->db->select("pln.pln_codigo");
		$this->db->from("erp_asientos_contables as");
		$this->db->join('erp_plan_cuentas pln','pln.pln_codigo=as.con_concepto_haber');
		$this->db->where("con_documento",$id);
		$resultado=$this->db->get();
		return $resultado->row();
		//echo $this->db->last_query();
	}
	public function lista_plan(){
		$this->db->from("erp_plan_cuentas");
		$this->db->where("pln_estado",1);
		$this->db->order_by("pln_id",'asc');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
	}
	public function lista_plan_cuentas_id($id){
		$this->db->from("erp_plan_cuentas");
		$this->db->where("pln_estado",1);
		$this->db->where("pln_id",$id);
		$this->db->order_by("pln_id",'asc');
		$resultado=$this->db->get();
		return $resultado->row();
		
	}
	public function lista_plan_mov(){
		$this->db->from("erp_plan_cuentas");
		$this->db->where("pln_estado",1);
		$this->db->where("pln_tipo",1);
		$this->db->order_by("pln_id",'asc');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query();
	}
	

	
}

?>