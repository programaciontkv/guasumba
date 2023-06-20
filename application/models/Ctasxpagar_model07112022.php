<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctasxpagar_model extends CI_Model {


	public function lista_factura_buscador($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or c.cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id", null);
		$this->db->group_by('f.reg_id,f.reg_femision, , pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer_vencido($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id and (saldo is null or saldo!=0)", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer_pagado($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or c.cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id and (saldo is null or saldo=0) and pag_fecha_v >'$f2'", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencido_pagado($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or c.cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id and (saldo is null or saldo=0) and pag_fecha_v <='$f2'", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id and (saldo is null or saldo!=0) and pag_fecha_v > '$f2'", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencido($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_raz_social like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id and (saldo is null or saldo!=0) and pag_fecha_v <= '$f2'", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_pagado($text,$f1,$f2,$emp_id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where("(f.reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.reg_femision between '$f1' and '$f2' and reg_estado!=3 and emp_id=$emp_id and saldo=0", null);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->order_by('c.cli_raz_social','asc');
		$this->db->order_by('reg_num_documento','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	
	
	public function lista_pagos_factura($id){
		$this->db->from('erp_pagos_documentos p');
		$this->db->join('erp_reg_documentos c','p.reg_id=c.reg_id');
		$this->db->where('c.reg_id',$id);
		$this->db->where('pag_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_ctasxpagar($id){
		$this->db->from('erp_ctasxpagar c');
		$this->db->join('erp_reg_documentos f','c.reg_id=f.reg_id');
		$this->db->where("f.reg_id", $id);
		$this->db->where('ctp_estado','1');
		$this->db->order_by('ctp_fecha_pago','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	public function lista_saldo_factura($id){
		$this->db->select('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','c.cli_id=f.cli_id');
		$this->db->join('pagosxdocumento p','f.reg_id=p.reg_id');
		$this->db->where('f.reg_id',$id);
		$this->db->group_by('f.reg_id,f.reg_femision, pag_fecha_v, f.reg_num_documento, c.cli_raz_social, c.cli_ced_ruc,f.reg_total,pago,saldo,reg_estado');
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function insert($data){
		$this->db->insert("erp_ctasxpagar",$data);
		return $this->db->insert_id();
	}


    
	public function lista_nota_credito_factura($id){
		$this->db->from('reg_nota_credito');
		$this->db->where('reg_id',$id);
		$this->db->where('rnc_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	
	public function lista_retencion_factura($id){
		$this->db->from('erp_registro_retencion');
		$this->db->where('reg_id',$id);
		$this->db->where('rgr_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_una_factura_cliente($num,$id,$emp){
		$this->db->from('erp_factura');
		$this->db->where('fac_numero',$num);
		$this->db->where('cli_id',$id);
		$this->db->where('emp_id',$emp);
		$this->db->where('fac_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('com_id',$id);
		return $this->db->update("erp_ctasxpagar",$data);
			
	}

	

	public function delete_pagos_factura($id){
		$this->db->where('com_id',$id);
		return $this->db->delete("erp_ctasxpagar");
			
	}

    
}

?>