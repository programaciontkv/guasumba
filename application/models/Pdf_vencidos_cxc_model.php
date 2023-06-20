<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf_vencidos_cxc_model extends CI_Model {

	public function buscar_documentos_vencidos($act, $fec1, $fec2, $txt,$emp){
		$query ="SELECT c.fac_id,c.fac_nombre,c.fac_identificacion,c.fac_fecha_emision, c.fac_total_valor,c.fac_numero,c.cli_id FROM  erp_factura c, erp_pagos_factura p where c.fac_id=p.com_id  and p.pag_fecha_v <= '$act' and (c.fac_estado=4 or c.fac_estado=6) and c.fac_fecha_emision between '$fec1' and '$fec2' $txt and c.emp_id=$emp and (c.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where c.fac_id=ct.com_id and cta_estado=1) or not exists(select * from erp_ctasxcobrar ct where c.fac_id=ct.com_id and cta_estado=1)) group by c.fac_id,c.fac_nombre,c.fac_identificacion,c.fac_fecha_emision, c.fac_total_valor,c.fac_numero,c.cli_id order by c.fac_nombre,c.fac_identificacion,c.fac_numero";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_ultimo_pago($id){
		$query ="SELECT * FROM erp_pagos_factura WHERE com_id='$id' order by pag_fecha_v desc limit 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function suma_pagos1($id) {
		$query ="select (select sum(cta_monto) from erp_ctasxcobrar where com_id=$id and cta_estado=1) as monto,
                        (select fac_total_valor from erp_factura where fac_id='$id') as pago";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}


	
}

?>