<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_mora_cxc_model extends CI_Model {

	public function lista_ultimo_vendedor($id, $emp){
		$query ="SELECT * FROM erp_factura f, erp_vendedor v WHERE f.vnd_id=v.vnd_id and cli_id='$id' and emp_id=$emp order by fac_numero desc limit 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function suma_pagos_vencidos($id, $fini, $ffin,$emp){
		$query ="select fac_id,fac_fecha_emision,fac_numero,fac_total_valor , pago as credito, 
                                   (select pag_fecha_v from pagos pg where pg.fac_id=p.fac_id order by pag_fecha_v desc limit 1 ) as pag_fecha_v from pagos p
                                    where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' and emp_id=$emp
                                    group by fac_id,fac_fecha_emision,fac_numero,fac_total_valor, pago, emp_id order by pag_fecha_v";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_pagos_vencidos($id, $fini, $ffin,$emp){
		$query ="select * from pagos where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' and emp_id=$emp order by fac_numero";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_pag_porvencer($id, $fec,$emp){
		$query ="select fac_id,fac_fecha_emision,fac_numero,fac_total_valor , pago as credito, (select pag_fecha_v from pagos pg where pg.fac_id=p.fac_id order by pag_fecha_v desc limit 1 ) as pag_fecha_v from pagos p where cli_id=$id and pag_fecha_v>'$fec' and emp_id=$emp
            group by fac_id,fac_fecha_emision,fac_numero,fac_total_valor, pago, emp_id ORDER BY pag_fecha_v ASC";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}


	
}

?>