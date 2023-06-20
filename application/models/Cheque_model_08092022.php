<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cheque_model extends CI_Model {


	public function lista_cheque_buscador($text,$f1,$f2,$emp_id){
		$this->db->select('ch.*, c.*,e.*,(select est_descripcion from erp_estados es where es.est_id=chq_estado_cheque) as est_cheque');
		$this->db->from('erp_cheques ch');
		$this->db->join('erp_i_cliente c','c.cli_id=ch.cli_id');
		$this->db->join('erp_estados e','e.est_id=ch.chq_estado');
		$this->db->where("(ch.chq_numero like '%$text%' or ch.chq_banco like '%$text%' or cli_ced_ruc like '%$text%'or cli_raz_social like '%$text%') and ch.chq_fecha between '$f1' and '$f2' and emp_id=$emp_id", null);
		$this->db->order_by('chq_fecha','desc');
		$this->db->order_by('chq_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_un_cheque($id){
		$this->db->select('ch.*, c.*,e.*,(select est_descripcion from erp_estados es where es.est_id=chq_estado_cheque) as est_cheque');
		$this->db->from('erp_cheques ch');
		$this->db->join('erp_i_cliente c','c.cli_id=ch.cli_id');
		$this->db->join('erp_estados e','e.est_id=ch.chq_estado');
		$this->db->where('chq_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function lista_cheque_nota($id){
		$this->db->from('erp_cheques ch');
		$this->db->where('doc_id',$id);
		$this->db->where('chq_tipo_doc','8');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_cheque_retencion($id){
		$this->db->from('erp_cheques ch');
		$this->db->where('doc_id',$id);
		$this->db->where('chq_tipo_doc','7');
		$this->db->where('chq_estado_cheque','11');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function insert($data){
		$this->db->insert("erp_cheques",$data);
		return $this->db->insert_id();
	}

	public function update($id,$data){
		$this->db->where('chq_id',$id);
		return $this->db->update("erp_cheques",$data);
			
	}

	public function update_chq_nota($id,$data){
		$this->db->where('doc_id',$id);
		$this->db->where('chq_tipo_doc','8');
		return $this->db->update("erp_cheques",$data);
			
	}

	public function update_chq_retencion($id,$data){
		$this->db->where('doc_id',$id);
		$this->db->where('chq_tipo_doc','7');
		return $this->db->update("erp_cheques",$data);
			
	}

	public function update_ctaxcobrar($id,$data){
		$this->db->where('chq_id',$id);
		return $this->db->update("erp_ctasxcobrar",$data);
			
	}

	public function lista_facturas_cliente($id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->where('f.cli_id',$id);
		$this->db->where('f.fac_estado !=3',null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, f.fac_nombre, f.fac_identificacion,f.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('f.fac_fecha_emision');
		$this->db->order_by('f.fac_numero');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_secuencial(){
		$this->db->from('erp_cheques ch');
		$this->db->where('chq_tipo_doc!=5 and char_length(chq_secuencial)>0',null);
		$this->db->order_by('chq_secuencial','desc');
		$resultado=$this->db->get();
		return $resultado->row();
	}


	public function lista_cheques_tip_cliente($tip,$id,$emp){
		$this->db->select('ch.*, c.*,e.*,(select est_descripcion from erp_estados es where es.est_id=chq_estado_cheque) as est_cheque');
		$this->db->from('erp_cheques ch');
		$this->db->join('erp_i_cliente c','c.cli_id=ch.cli_id');
		$this->db->join('erp_estados e','e.est_id=ch.chq_estado');
		$this->db->where('ch.cli_id',$id);
		$this->db->where('chq_tipo_doc',$tip);
		$this->db->where('ch.emp_id',$emp);
		$this->db->where('chq_estado_cheque','11');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_credito_cliente($id,$emp){
		$this->db->select('sum(chq_monto) as monto, sum(chq_cobro) as cobro');
		$this->db->from('erp_cheques ch');
		$this->db->where('cli_id',$id);
		$this->db->where('emp_id',$emp);
		$this->db->where('chq_estado!=3');
		$this->db->where('chq_estado!=12');
		$this->db->where('chq_estado_cheque!=3');
		$this->db->where('chq_estado_cheque!=12');
		
		$resultado=$this->db->get();
		return $resultado->row();
	}
}

?>