<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf_estado_cliente_model extends CI_Model {

	public function lista_estado_cuenta_cliente($txt,$f1,$f2, $emp){
		$query ="select '0' as cta_id, c.fac_id,c.fac_fecha_emision, c.fac_numero,('FACTURACION VENTA') as concepto,('') as forma,c.fac_total_valor as total_valor,('0') as haber from erp_factura c where c.fac_identificacion='$txt' and (fac_estado=4 or fac_estado=6) and exists(select * from erp_pagos_factura p where p.com_id=c.fac_id and pag_estado=1) and c.fac_fecha_emision between '$f1' and '$f2' and c.emp_id=$emp 
                            union 
                            select cta.cta_id,c.fac_id,cta.cta_fecha_pago, c.fac_numero,cta.cta_concepto,fpg_descripcion as cta_forma_pago,('0') as total_valor ,cta.cta_monto from erp_ctasxcobrar cta,erp_factura c, erp_formas_pago fp where c.fac_id=cta.com_id and fp.fpg_id=cast(cta.cta_forma_pago as integer) and c.fac_identificacion='$txt' and cta.cta_fecha_pago between '$f1' and '$f2' and cta_estado=1 and (fac_estado=4 or fac_estado=6) and c.emp_id=$emp order by fac_numero, fac_fecha_emision,cta_id";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_codigo_cuenta($id){
		$query ="select * from erp_factura f, erp_ctasxcobrar c where c.com_id=f.fac_id  and fac_identificacion='$id'";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function suma_pagos1($id){
		$query ="select (select sum(cta_monto) from erp_ctasxcobrar where com_id=$id and cta_estado=1) as monto,
                        (select fac_total_valor from erp_factura where fac_id='$id') as pago";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_ultimo_pago($id){
		$query ="SELECT * FROM erp_pagos_factura WHERE com_id='$id' order by pag_fecha_v desc limit 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}


	
}

?>