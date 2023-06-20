<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ventas_por_producto_model extends CI_Model {


	public function lista_productos_buscador($f1,$f2,$emp,$ids,$txt=""){
		$this->db->select('d.pro_id, p.mp_a,p.mp_b,p.mp_c,p.mp_d,p.mp_q');
		$this->db->from('erp_factura f');
		$this->db->join('erp_det_factura d','d.fac_id=f.fac_id');
		$this->db->join('erp_mp p','p.id=d.pro_id');
		$this->db->where("fac_estado!=3 and fac_fecha_emision between '$f1' and '$f2'and f.emp_id=$emp and p.ids='$ids' and (p.mp_c like '%$txt' or p.mp_d like '%$txt')", null);
		$this->db->group_by(array('d.pro_id', 'p.mp_a','p.mp_b','p.mp_c','p.mp_d','p.mp_q'));
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_productos_local($emi,$pro,$f1,$f2){
		$this->db->select('sum(dfc_cantidad) as cantidad, sum(dfc_precio_total) as valor');
		$this->db->from('erp_factura f');
		$this->db->join('erp_det_factura d','d.fac_id=f.fac_id');
		$this->db->where("fac_estado!=3 and fac_fecha_emision between '$f1' and '$f2' and f.emi_id=$emi and d.pro_id=$pro", null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}	

	
	
    
}

?>