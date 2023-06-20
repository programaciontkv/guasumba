<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctasxcobrar_model extends CI_Model {


	public function lista_factura_buscador($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer_vencido($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (saldo is null or saldo!=0)", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer_pagado($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (saldo is null or saldo=0) and pag_fecha_v >'$f2'", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencido_pagado($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (saldo is null or saldo=0) and pag_fecha_v <='$f2'", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (saldo is null or saldo!=0) and pag_fecha_v > '$f2'", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencido($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (saldo is null or saldo!=0) and pag_fecha_v <= '$f2'", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_pagado($text,$f1,$f2,$emp_id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where("(f.fac_numero like '%$text%' or fac_nombre like '%$text%' or fac_identificacion like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and saldo=0", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_nombre','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	
	
	public function lista_pagos_factura($id){
		$this->db->from('erp_pagos_factura p');
		$this->db->join('erp_factura c','cast(p.com_id as integer)=c.fac_id');
		$this->db->where('com_id',$id);
		$this->db->where('pag_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_ctasxcobrar($id){
		$this->db->from('erp_ctasxcobrar c');
		$this->db->join('erp_factura f','c.com_id=f.fac_id');
		$this->db->order_by('cta_fecha_pago','asc');
		$this->db->where("fac_id", $id);
		$this->db->where('cta_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	public function lista_saldo_factura($id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where('f.fac_id',$id);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function insert($data){
		$this->db->insert("erp_ctasxcobrar",$data);
		return $this->db->insert_id();
	}


    public  function lista_notcre_cliente($id) {
		$this->db->from('erp_cheques');
		$this->db->where('cli_id',$id);
		$this->db->where('chq_tipo_doc','8');
		$this->db->where('chq_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->result();
    }



	public function lista_nota_credito_factura($id){
		$this->db->from('erp_nota_credito');
		$this->db->where('fac_id',$id);
		$this->db->where('ncr_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_guia_factura($id){
		$this->db->from('erp_guia_remision');
		$this->db->where('fac_id',$id);
		$this->db->where('gui_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_retencion_factura($id){
		$this->db->from('erp_registro_retencion');
		$this->db->where('fac_id',$id);
		$this->db->where('rgr_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_una_factura_cliente($num,$id,$emp){
		$this->db->from('erp_reg_documentos');
		$this->db->where('cli_id',$id);
		$this->db->where('emp_id',$emp);
		$this->db->where('reg_num_documento',$num);
		$this->db->where('reg_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('com_id',$id);
		return $this->db->update("erp_ctasxcobrar",$data);
			
	}

	public function update_ctascob_cheque($id,$data){
		$this->db->where('chq_id',$id);
		return $this->db->update("erp_ctasxcobrar",$data);
			
	}

	public function delete_pagos_factura($id){
		$this->db->where('com_id',$id);
		return $this->db->delete("erp_ctasxcobrar");
			
	}

	public  function lista_ctasxcobrar_notcre($id) {
		$this->db->select("sum(cta_monto)");
		$this->db->from('erp_ctasxcobrar');
		$this->db->where('chq_id',$id);
		$this->db->where('cta_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
    }

    
}

?>