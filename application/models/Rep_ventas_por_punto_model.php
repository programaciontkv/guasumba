<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ventas_por_punto_model extends CI_Model {


	public function lista_factura_buscador($f1,$f2,$emp){
		$this->db->select('f.emp_id, f.emi_id, emi_nombre, f.vnd_id, vnd_nombre,count(*) as nf, sum(fac_subtotal12) as subtotal12, sum(fac_subtotal0+ fac_subtotal_ex_iva+fac_subtotal_no_iva) as subtotal_sin_iva,
			sum(fac_total_descuento) descuento,sum(fac_total_ice) as ice, sum(fac_total_iva) as iva, 
			sum(fac_subtotal) as subtotal, sum(fac_total_valor) as total ');
		$this->db->from('erp_factura f');
		$this->db->join('erp_vendedor v','f.vnd_id=v.vnd_id');
		$this->db->join('erp_emisor e','f.emi_id=e.emi_id');
		$this->db->where("fac_estado!=3 and fac_fecha_emision between '$f1' and '$f2'and f.emp_id=$emp", null);
		$this->db->group_by(array('f.emp_id', 'f.emi_id', 'emi_nombre', 'f.vnd_id', 'vnd_nombre'));
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_nota_credito($f1,$f2,$emp,$emi){
		$this->db->select('n.emp_id, n.emi_id, emi_nombre, n.vnd_id, vnd_nombre,count(*) as nc, sum(ncr_subtotal12) as subtotal12, sum(ncr_subtotal0+ ncr_subtotal_ex_iva+ncr_subtotal_no_iva) as subtotal_sin_iva,
			 sum(ncr_total_iva) as iva, 
			sum(ncr_subtotal) as subtotal, sum(nrc_total_valor) as total ');
		$this->db->from('erp_nota_credito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_emisor e','n.emi_id=e.emi_id');
		$this->db->where("ncr_estado!=3 and ncr_fecha_emision between '$f1' and '$f2' and n.emp_id=$emp and n.emi_id=$emi", null);
		$this->db->group_by(array('n.emp_id', 'n.emi_id', 'emi_nombre', 'n.vnd_id', 'vnd_nombre'));
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_cierre_caja($f1,$f2,$emp,$emi){
		$query="select (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='1' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as tc,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='2' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as td,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='3' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as cheque,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='4' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as efectivo,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='5' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as certificados,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='6' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as transferencia,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='7' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as retencion,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='8' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as nc,
       (select sum(pag_cant) from erp_pagos_factura p, erp_factura f, erp_formas_pago fp where f.fac_id=p.com_id and cast(pag_forma as integer)=fp.fpg_id and pag_estado!=3 and fpg_tipo='9' and fac_fecha_emision between '$f1' and '$f2' and f.emp_id=$emp and f.emi_id=$emi)as credito

";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}
	
    
}

?>