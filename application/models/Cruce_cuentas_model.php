<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cruce_cuentas_model extends CI_Model {

	public function lista_facturas_pendientes($text,$f1,$f2,$estado,$emp_id){
		if($estado!=''){
			$es="and (reg_estado =$estado ) ";
		}else{
			$es=' and ((reg_estado != 3 )  or (saldo is null or saldo !=0 and reg_estado = 22))';
		}

		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado,e.est_descripcion');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('erp_estados e','e.est_id=f.reg_estado');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') 
		 and f.reg_femision between '$f1' and '$f2' $es
		 and emp_id=$emp_id ", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado,e.est_descripcion');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
		//echo $this->db->last_query().'<br>';
	}


	public function lista_facturas_cliente($cli_id,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("fac_estado!=3 and f.cli_id=$cli_id and f.emp_id=$emp_id and (saldo is null or saldo!=0)", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}
	public function lista_facturas_cliente_contador($cli_id,$emp_id){
		$this->db->select(' count(f.fac_id) as n');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("fac_estado!=3 and f.cli_id=$cli_id and f.emp_id=$emp_id and (saldo is null or saldo!=0)", null);
		$resultado=$this->db->get();
		return $resultado->row();
	}



	public function lista_pago_reg_factura($id){
		$query ="SELECT sum(ctp_monto) as saldo FROM erp_ctasxpagar where reg_id=$id and ctp_estado='1'";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}

	
}

?>