<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ats_model extends CI_Model {

	public function lista_num_emisor($d,$h,$emp){
		$this->db->select('f.emi_id');
		$this->db->from('erp_factura f');
		$this->db->group_by('f.emi_id'); 
		$this->db->where("fac_fecha_emision between '$d' and '$h' and (fac_estado=6 or fac_estado=4) and f.emp_id=$emp", null);
		$resultado=$this->db->count_all_results();
		return $resultado;
			
	}

	public function lista_ventas0($d, $h, $emp){
		$query ="SELECT (select sum(fac_subtotal) as total_ventas0 FROM  erp_factura where  fac_fecha_emision between '$d' and '$h' and (fac_estado=6 or fac_estado=4) and emp_id =$emp) as venta,
                (select sum(ncr_subtotal) as total_devolucion0 FROM  erp_nota_credito where  ncr_fecha_emision between '$d' and '$h' and (ncr_estado=4 or ncr_estado=6) and emp_id=$emp) as devolucion";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_compras($d, $h,$emp) {
		$query ="SELECT reg_sustento,reg_ruc_cliente, reg_num_documento, reg_id,reg_femision,tdc_codigo,reg_num_autorizacion,
reg_sbt_noiva,reg_sbt,reg_sbt0,reg_sbt12,reg_sbt_excento,reg_ice,reg_iva12,reg_tipo_pago
 FROM erp_reg_documentos r, erp_tip_documentos td where cast(r.reg_tipo_documento as integer)=td.tdc_id and reg_tipo_documento != '44' and reg_tipo_documento != '99' and reg_tipo_documento != '15' and reg_femision between '$d' and '$h' and (reg_estado=4 or reg_estado=6) and emp_id=$emp
union 
SELECT '1',rnc_identificacion,rnc_numero,rnc_id,rnc_fecha_emision,'4',rnc_autorizacion,
rnc_subtotal_no_iva,rnc_subtotal,rnc_subtotal0,rnc_subtotal12,rnc_subtotal_ex_iva,rnc_total_ice,rnc_total_iva,'0' FROM erp_registro_nota_credito r where rnc_fecha_emision between '$d' and '$h' and rnc_estado!='3' and emp_id=$emp
order by tdc_codigo";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_retencion_iva($id){
		$query ="select (select sum(dtr_valor) from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IV' and dtr_procentaje_retencion=10 and (ret_estado=4 or ret_estado=6)) as iva10, 
                             (select sum(dtr_valor) from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IV' and dtr_procentaje_retencion=20 and (ret_estado=4 or ret_estado=6)) as iva20,
                             (select sum(dtr_valor) from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IV' and dtr_procentaje_retencion=30 and (ret_estado=4 or ret_estado=6)) as iva30,
                             (select sum(dtr_valor) from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IV' and dtr_procentaje_retencion=50 and (ret_estado=4 or ret_estado=6)) as iva50, 
                             (select sum(dtr_valor) from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IV' and dtr_procentaje_retencion=70 and (ret_estado=4 or ret_estado=6)) as iva70,
                             (select sum(dtr_valor) from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IV' and dtr_procentaje_retencion=100 and (ret_estado=4 or ret_estado=6)) as iva100";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_retencion_renta($id){
		$query ="select dtr_codigo_impuesto,sum(dtr_base_imponible) as dtr_base_imponible, dtr_procentaje_retencion, sum(dtr_valor) as dtr_valor from erp_retencion r, erp_det_retencion d where r.ret_id=d.ret_id and reg_id=$id and dtr_tipo_impuesto='IR' and (ret_estado=4 or  ret_estado=6) group by dtr_codigo_impuesto, dtr_procentaje_retencion";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_retencion($id){
		$query ="select * from erp_retencion r where reg_id=$id and char_length(ret_numero)=17 and (ret_estado=4 or ret_estado=6)";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_reg_nota_credito($id){
		$query ="select * from erp_registro_nota_credito n, erp_reg_documentos f where f.reg_id=n.reg_id and rnc_id=$id and rnc_estado!=3";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_ventas_clientes($d, $h, $emp){
		$query ="SELECT c.cli_ced_ruc,count(*) as facturas, sum(fac_subtotal12) as sub12, sum(fac_subtotal0) as sub0, sum(fac_subtotal_ex_iva) as subex, sum(fac_subtotal_no_iva) as subno, sum(fac_subtotal) as subtotal, sum(fac_total_iva) as iva, sum(fac_total_ice) as ice,  
                (select sum(drr_valor) from erp_registro_retencion r, erp_det_reg_retencion d, erp_i_cliente clr where r.rgr_id =d.rgr_id and r.cli_id=clr.cli_id and drr_tipo_impuesto='IV' and clr.cli_ced_ruc=c.cli_ced_ruc and rgr_estado=4 and rgr_fecha_emision between '$d' and '$h' and emp_id=$emp) as ret_iva,
                (select sum(drr_valor) from erp_registro_retencion r, erp_det_reg_retencion d, erp_i_cliente clr where r.rgr_id =d.rgr_id and r.cli_id=clr.cli_id and drr_tipo_impuesto='IR' and clr.cli_ced_ruc=c.cli_ced_ruc and rgr_estado=4 and rgr_fecha_emision between '$d' and '$h' and emp_id=$emp) as ret_renta
                FROM  erp_factura f , erp_i_cliente c where f.cli_id=c.cli_id and fac_fecha_emision between '$d' and '$h' and emp_id=$emp and (fac_estado=4 or fac_estado=6) 
                group by c.cli_ced_ruc";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}


	public function lista_cliente($id){
		$query ="SELECT * FROM  erp_i_cliente where cli_ced_ruc='$id'";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_pagos_cliente($id, $d, $h,$emp) {
		$query ="select * from erp_pagos_factura p, erp_factura f, erp_i_cliente c, erp_formas_pago fp where f.fac_id=p.com_id and f.cli_id=c.cli_id and fp.fpg_id=cast(p.pag_forma as integer) and cli_ced_ruc='$id' and fac_fecha_emision between '$d' and '$h' and f.emp_id=$emp limit 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_notas_creditos_venta($d, $h, $emp) {
		$query ="SELECT c.cli_ced_ruc,count(*) as notas, sum(ncr_subtotal12) as sub12, sum(ncr_subtotal0) as sub0, sum(ncr_subtotal_ex_iva) as subex, sum(ncr_subtotal_no_iva) as subno, sum(ncr_subtotal) as subtotal, sum(ncr_total_iva) as iva, sum(ncr_total_ice) as ice,  
                ('0') as ret_iva,
                ('0') as ret_renta
                                FROM  erp_nota_credito f , erp_i_cliente c where f.cli_id=c.cli_id and ncr_fecha_emision between '$d' and '$h'  and (ncr_estado=4 or ncr_estado=6) and emp_id=$emp group by c.cli_ced_ruc";
        $resultado=$this->db->query($query);
		return $resultado->result(); 
	}

	public function lista_ventas_emisor($d, $h, $emp) {
		$query ="select split_part(fac_numero,'-',1) as emi_id,sum(fac_subtotal) from erp_factura where fac_fecha_emision between '$d' and '$h' and (fac_estado=4 or fac_estado=6) and emp_id=$emp group by split_part(fac_numero,'-',1)";
        $resultado=$this->db->query($query);
		return $resultado->result(); 
	}

	public function lista_devoluciones_emisor($d, $h, $emi) {
		$query ="select sum(ncr_subtotal) from erp_nota_credito where ncr_fecha_emision between '$d' and '$h' and  split_part(ncr_numero,'-',1)='$emi' and (ncr_estado=4 or ncr_estado=6)";
		$resultado=$this->db->query($query);
		return $resultado->row(); 
	}


	public function lista_anulados($d, $h, $emp) {
		$query ="select '01' as tipo, fac_numero, fac_autorizacion  from erp_factura where  fac_estado=3 and fac_fecha_emision between '$d' and '$h' and emp_id=$emp and fac_autorizacion!=''
                            UNION 
                            select '04' as tipo, ncr_numero, ncr_autorizacion from erp_nota_credito where ncr_estado=3 and ncr_fecha_emision between '$d' and '$h' and emp_id=$emp and ncr_autorizacion!=''
                            UNION 
                            select '07' as tipo, ret_numero, ret_autorizacion from erp_retencion where ret_estado=3 and ret_numero<>'' and ret_fecha_emision between '$d' and '$h' and emp_id=$emp and ret_autorizacion!=''";
		$resultado=$this->db->query($query);
		return $resultado->result(); 
	}
	
}

?>