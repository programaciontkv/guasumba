<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf_saldo_clientes_model extends CI_Model {

	public function lista_documentos_ctas($nm,$emp){
		$query ="select f.fac_identificacion, f.fac_nombre, c.pln_id from erp_factura f, erp_ctasxcobrar c where c.com_id=f.fac_id  $nm and f.emp_id=$emp group by f.fac_nombre,f.fac_identificacion,c.pln_id
                     union
                     select f.fac_identificacion, f.fac_nombre,'0' as pln_id from erp_factura f where not exists(select * from erp_ctasxcobrar c where c.com_id=f.fac_id) $nm and f.emp_id=$emp group by f.fac_nombre,f.fac_identificacion order by fac_nombre,pln_id desc";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function suma_documentos_cliente($id,$fec,$emp){
		$query ="select (select sum(fac_total_valor) from erp_factura f where fac_identificacion ='$id' and (fac_estado=6 or fac_estado=4) and emp_id=$emp and fac_fecha_emision<='$fec') as fac_total_valor,
						(select sum(cta_monto) from erp_factura f , erp_ctasxcobrar c where fac_identificacion ='$id' and f.fac_id=c.com_id and (fac_estado=6 or fac_estado=4) and cta_estado=1 and f.emp_id=$emp and fac_fecha_emision<='$fec' and cta_fecha_pago<='$fec') as credito
		";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}


	
}

?>