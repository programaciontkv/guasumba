<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_desglose_factura_model extends CI_Model {


	public function lista_factura_buscador($f1,$f2,$emi,$txt){
		
		$this->db->from('erp_factura f');
		$this->db->join('erp_det_factura d','f.fac_id=d.fac_id');
		$this->db->join('erp_mp mp','mp.id=d.pro_id');
		$this->db->where("fac_estado!=3 and fac_fecha_emision between '$f1' and '$f2'and f.emi_id=$emi and (fac_numero like '%$txt%' or fac_nombre like '%$txt%' or mp_c like '%$txt%' or mp_d like '%$txt%')", null);
		$this->db->order_by('fac_fecha_emision','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

    
}

?>