<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf_estado_cta_cobros_model extends CI_Model {

	public function lista_facturas_cliente($id, $emp){
		$query ="select emp_id,fac_id,cli_id, fac_fecha_emision,fac_numero,fac_total_valor,fac_fecha_emision, pago as credito,(select pag_fecha_v from pagos where fac_id=$id order by pag_fecha_v desc limit 1) as pag_fecha_v from pagos where cli_id=$id and emp_id=$emp group by emp_id,fac_id,cli_id, fac_fecha_emision,fac_numero,fac_total_valor, fac_fecha_emision, pago ORDER BY fac_numero ASC ";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_pag_factura_completo($id){
		$query ="select sum(pago) as credito from pagos where fac_id=$id and (credito=(pag_cant) or round(cast(credito as numeric),2)=round(cast(pag_cant as numeric),2))";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	
	
}

?>