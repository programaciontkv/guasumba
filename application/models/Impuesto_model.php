<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Impuesto_model extends CI_Model {

	public function lista_impuestos(){
		// $this->db->select("i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion");
		// $this->db->from('porcentages_retencion i');
		// $this->db->join('erp_estados e','e.est_id=i.por_estado');
		// $this->db->join('erp_plan_cuentas p','p.pln_id=i.cta_id');
		// $this->db->order_by('por_siglas');
		// $resultado=$this->db->get();
		$query ="select i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion from porcentages_retencion i, erp_estados e, erp_plan_cuentas p where e.est_id=i.por_estado and p.pln_id=i.cta_id 
			union 
			select i.*,'','',e.est_descripcion from porcentages_retencion i, erp_estados e where e.est_id=i.por_estado and i.cta_id=0
		order by por_siglas, por_codigo";
        $resultado=$this->db->query($query);
		return $resultado->result();
	}


	
	public function insert($data){
		
		return $this->db->insert("porcentages_retencion",$data);
			
	}

	public function lista_un_impuesto($id){
		// $this->db->select("i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion");
		// $this->db->from('porcentages_retencion i');
		// $this->db->join('erp_estados e','e.est_id=i.por_estado');
		// $this->db->join('erp_plan_cuentas p','p.pln_id=i.cta_id');
		// $this->db->where('i.por_id',$id);
		// $resultado=$this->db->get();
		$query ="select i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion from porcentages_retencion i, erp_estados e, erp_plan_cuentas p where e.est_id=i.por_estado and p.pln_id=i.cta_id and i.por_id='$id'
			union 
			select i.*,'','',e.est_descripcion from porcentages_retencion i, erp_estados e where e.est_id=i.por_estado and i.cta_id=0 and i.por_id='$id'";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_un_impuesto_cod($id){
		// $this->db->select("i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion");
		// $this->db->from('porcentages_retencion i');
		// $this->db->join('erp_estados e','e.est_id=i.por_estado');
		// $this->db->join('erp_plan_cuentas p','p.pln_id=i.cta_id');
		// $this->db->where('i.por_codigo',$id);
		// $resultado=$this->db->get();
		$query ="select i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion from porcentages_retencion i, erp_estados e, erp_plan_cuentas p where e.est_id=i.por_estado and p.pln_id=i.cta_id and i.por_codigo='$id'
			union 
			select i.*,'','',e.est_descripcion from porcentages_retencion i, erp_estados e where e.est_id=i.por_estado and i.cta_id=0 and i.por_codigo='$id'";
        $resultado=$this->db->query($query);

		return $resultado->row();
			
	}

	public function lista_impuestos_estado($est){

		// $this->db->select("i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion");
		// $this->db->from('porcentages_retencion i');
		// $this->db->join('erp_estados e','e.est_id=i.por_estado');
		// $this->db->join('erp_plan_cuentas p','p.pln_id=i.cta_id');
		// $this->db->where('por_estado',$est);
		// $this->db->order_by('por_siglas');
		// $resultado=$this->db->get();
		$query ="select i.*,p.pln_codigo, p.pln_descripcion,e.est_descripcion from porcentages_retencion i, erp_estados e, erp_plan_cuentas p where e.est_id=i.por_estado and p.pln_id=i.cta_id and i.por_estado='$est'
			union 
			select i.*,'','',e.est_descripcion from porcentages_retencion i, erp_estados e where e.est_id=i.por_estado and i.cta_id=0 and i.por_estado='$est' order by por_siglas, por_codigo";
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('por_id',$id);
		return $this->db->update("porcentages_retencion",$data);
			
	}

	public function delete($id){
		$this->db->where('por_id',$id);
		return $this->db->delete("porcentages_retencion");
			
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

	public function lista_impuestos_retencion(){
		$this->db->from('porcentages_retencion');
		$this->db->where('por_codigo!=','0');
		$this->db->where('por_estado',1);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_impuestos_reg_retencion(){
		$this->db->from('porcentages_retencion');
		$this->db->where('por_codigo','0');
		$this->db->where('por_estado',1);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

}

?>