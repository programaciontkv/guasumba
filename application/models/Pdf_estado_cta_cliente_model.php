<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf_estado_cta_cliente_model extends CI_Model {

	public function lista_pagos_vencidos($id, $fini, $ffin,$emp){
		$query ="select * from pagos where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' and emp_id=$emp order by fac_numero";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_pag_porvencer($id, $fec,$emp){
		$query ="select * from pagos p where cli_id=$id and pag_fecha_v>'$fec' and emp_id=$emp
             ORDER BY fac_numero";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}


	public function suma_pagos1($id,$fec1,$fec2){
		$query ="select sum(cta_monto) as credito from erp_ctasxcobrar where com_id=$id and cta_estado=1 and cta_fecha_pago between '$fec1' and '$fec2'";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	
	public function lista_cheques_cliente($id, $emp){
		$query ="SELECT * FROM erp_cheques WHERE cli_id=$id and (chq_tipo_doc=3 or chq_tipo_doc=10) and (chq_estado_cheque!=3 or chq_estado_cheque!=12) and emp_id=$emp ORDER BY chq_fecha ASC";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	
}

?>