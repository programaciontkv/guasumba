<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_libro_mayor_model extends CI_Model {

	public function lista_cuentas_fecha($desde, $hasta,$emp, $txt1, $txt2){
		$query ="(SELECT trim(con_concepto_debe) as con_concepto_debe FROM erp_asientos_contables WHERE con_fecha_emision BETWEEN '$desde' AND '$hasta' and con_concepto_debe!='' and con_estado=1 and emp_id=$emp $txt1 group by con_concepto_debe order by con_concepto_debe)
				union 
				(SELECT trim(con_concepto_haber) as con_concepto_haber FROM erp_asientos_contables WHERE con_fecha_emision BETWEEN '$desde' AND '$hasta' and con_concepto_haber!='' and con_estado=1 and emp_id=$emp $txt2 group by con_concepto_haber  order by con_concepto_haber) order by con_concepto_debe";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_asientos_cuenta_fecha($cuenta, $desde, $hasta,$emp) {
		$query ="SELECT 0 as tipo, ac.* FROM erp_asientos_contables ac WHERE ac.con_fecha_emision BETWEEN '$desde' AND '$hasta' and ac.con_concepto_debe='$cuenta' and con_estado=1 and emp_id=$emp
                                 union
                             SELECT 1 as tipo, ac.* FROM erp_asientos_contables ac  WHERE ac.con_fecha_emision BETWEEN '$desde' AND '$hasta' and ac.con_concepto_haber='$cuenta' and con_estado=1 and emp_id=$emp
                             order by con_fecha_emision, con_asiento";
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

	function lista_suma_cuentas_ant($cuenta, $desde,$emp) {
		$query ="select (select  sum(ac.con_valor_debe) FROM erp_asientos_contables ac WHERE ac.con_fecha_emision <'$desde' and ac.con_concepto_debe='$cuenta' and con_estado=1 and emp_id=$emp) as debe, 
                (select  sum(ac.con_valor_haber) FROM erp_asientos_contables ac WHERE ac.con_fecha_emision < '$desde' and ac.con_concepto_haber='$cuenta' and con_estado=1 and emp_id=$emp) as haber";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	

	
}

?>