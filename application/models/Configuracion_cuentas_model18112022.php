<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_cuentas_model extends CI_Model {

	public function lista_configuracion_cuentas($id){
		$query ="select cas_id,cas_tipo_doc, cas_descripcion,cas_orden_emi,emi_id, c.pln_id, pln_codigo, pln_descripcion from erp_ctas_asientos c, erp_plan_cuentas p where c.pln_id=p.pln_id and emi_id=$id
		union 
		select cas_id,cas_tipo_doc, cas_descripcion, cas_orden_emi,emi_id, c.pln_id, '', '' from erp_ctas_asientos c where pln_id=0 and emi_id=$id
		order by  cas_tipo_doc, cas_orden_emi
		";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('cas_id',$id);
		return $this->db->update("erp_ctas_asientos",$data);
			
	}
	
	public function lista_una_configuracion_cuenta($ord,$id){
		$query ="SELECT c.pln_id,pln_codigo FROM erp_ctas_asientos c, erp_plan_cuentas p where c.pln_id=p.pln_id and cas_orden_emi=$ord and emi_id=$id";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}	


	public function lista_configuracion_cuenta_completa($id){
		$query ="SELECT * FROM erp_ctas_asientos c where not exists(select * from erp_plan_cuentas p where c.pln_id=p.pln_id) and emi_id=$id";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}	

	public function lista_cta_config($id){
		$query = "SELECT pln_codigo as cod FROM erp_ctas_asientos cta, erp_plan_cuentas pln where cta.cas_id=$id and pln.pln_id=cta.pln_id";
		$resultado=$this->db->query($query);
		return $resultado->row();
		//echo $this->db->last_query();
	}


	
}

?>