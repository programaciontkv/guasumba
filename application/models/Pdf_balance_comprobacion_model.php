<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_balance_comprobacion_model extends CI_Model {

	function lista_cuentas_fecha($desde, $hasta, $emp) {
		$query ="(SELECT trim(con_concepto_debe) as con_concepto_debe FROM erp_asientos_contables WHERE con_fecha_emision BETWEEN '$desde' AND '$hasta' and con_concepto_debe!='' and emp_id=$emp and con_estado=1 group by con_concepto_debe order by con_concepto_debe)
			union 
			(SELECT trim(con_concepto_haber) as con_concepto_haber FROM erp_asientos_contables WHERE con_fecha_emision BETWEEN '$desde' AND '$hasta' and con_concepto_haber!='' and emp_id=$emp and con_estado=1 group by con_concepto_haber  order by con_concepto_haber) order by con_concepto_debe";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_una_cuenta($id,$id1){
		$query ="SELECT * FROM  erp_plan_cuentas where trim(pln_codigo)=trim('$id') or trim(pln_codigo)=trim('$id1')";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}


	public function lista_balance_general1($cuenta, $desde, $hasta, $emp) {
		$query ="SELECT (SELECT sum(con_valor_debe) FROM erp_asientos_contables where trim(con_concepto_debe) like trim('$cuenta%') and con_fecha_emision between '$desde' and '$hasta' and emp_id=$emp and con_estado=1)as debe,
                        (SELECT sum(con_valor_haber) FROM erp_asientos_contables where trim(con_concepto_haber) like trim('$cuenta%') and con_fecha_emision between '$desde' and '$hasta' and emp_id=$emp and con_estado=1)as haber";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_suma_cuentas($cuenta, $desde, $hasta, $emp) {
		$query ="select (select  sum(ac.con_valor_debe) FROM erp_asientos_contables ac WHERE ac.con_fecha_emision BETWEEN '$desde' AND '$hasta' and trim(ac.con_concepto_debe)=trim('$cuenta') and emp_id=$emp and con_estado=1) as debe, 
                                    (select  sum(ac.con_valor_haber) FROM erp_asientos_contables ac WHERE ac.con_fecha_emision BETWEEN '$desde' AND '$hasta' and trim(ac.con_concepto_haber)=trim('$cuenta') and emp_id=$emp and con_estado=1) as haber";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	

	
}

?>