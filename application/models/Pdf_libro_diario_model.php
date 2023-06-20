<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_libro_diario_model extends CI_Model {

	public function lista_total_asientos_fecha($txt){
		$query ="SELECT con_asiento FROM  erp_asientos_contables $txt group BY con_asiento order by con_asiento";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_cuentas_asientos($asiento){
		$this->db->where('con_asiento',$asiento);
		$this->db->where('con_estado','1');
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}

	public function listar_asientos_debe($as, $cuenta, $id){
		$this->db->where('con_asiento',$as);
		$this->db->where('trim(con_concepto_debe)',$cuenta);
		$this->db->where('con_id',$id);
		$this->db->where('con_estado','1');
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->row();
			
	}

	public function listar_asientos_haber($as, $cuenta, $id){
		$this->db->where('con_asiento',$as);
		$this->db->where('con_concepto_haber',$cuenta);
		$this->db->where('con_id',$id);
		$this->db->where('con_estado','1');
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->row();
			
	}

	
}

?>