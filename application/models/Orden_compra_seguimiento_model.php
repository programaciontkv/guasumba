<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_seguimiento_model extends CI_Model {

	

	public function lista_ordenes_buscador($text,$f1,$f2,$txt){
		$this->db->select("oc.*,e.est_descripcion, c.cli_raz_social" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->where("(orc_codigo like '%$text%' or cli_raz_social like '%$text%' )and orc_fecha between '$f1' and '$f2' $txt", null);
		$this->db->order_by('oc.orc_codigo','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_una_orden($id){
		$this->db->select("oc.*,e.est_descripcion, c.cli_raz_social, c.cli_ced_ruc, c.cli_telefono, c.cli_tipo_cliente, emp_logo, emp_nombre" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->join('erp_empresas em','em.emp_id=oc.emp_id');
		$this->db->where("orc_id", $id);
		$resultado=$this->db->get();
		return $resultado->row();
	}


	public function lista_tipo_documentos($est){
		$this->db->from('erp_tip_documentos t');
		$this->db->join('erp_estados e','t.tdc_estado=e.est_id');
		$this->db->where('tdc_estado',$est);
		$this->db->order_by('tdc_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_doc_duplicado($id,$num,$tip){
		$this->db->from('erp_reg_documentos');
		$this->db->where('cli_id',$id);
		$this->db->where('reg_num_documento',$num);
		$this->db->where('reg_tipo_documento',$tip);
		$this->db->where('reg_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
	
	public function update($id,$data){
		$this->db->where('orc_id',$id);
		return $this->db->update("erp_i_enc_orden_compra_mp",$data);
			
	}

		
	public function lista_detalle($id){
		$this->db->select("d.*,p.*, (select sum(mov_cantidad) as unidad from erp_i_mov_inv_pt mi, erp_transacciones trs where mi.trs_id=trs.trs_id and mi.pro_id=d.mp_id and mi.mov_guia_transporte=o.orc_codigo and trs.trs_operacion=0)" );
		$this->db->from('erp_i_det_orden_compra_mp d');
		$this->db->join('erp_i_enc_orden_compra_mp o','o.orc_id=d.orc_id');
		$this->db->join('erp_mp p','d.mp_id=p.id');
		$this->db->where("d.orc_id", $id);
		$resultado=$this->db->get();
		return $resultado->result();
	}


	public function lista_suma_detalle($id){
		$query ="select sum(orc_det_cant) as cantidad, (select sum(mov_cantidad) as unidad from erp_i_mov_inv_pt mi, erp_transacciones trs where mi.trs_id=trs.trs_id and mi.mov_guia_transporte=o.orc_codigo and trs.trs_operacion=0) from erp_i_det_orden_compra_mp d, erp_i_enc_orden_compra_mp o where o.orc_id=d.orc_id and d.orc_id=$id group by o.orc_id, orc_codigo";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}

		
	

	public function lista_una_detalle_orden($id){
		$this->db->select("oc.*,e.est_descripcion, d.*,p.*" );
		$this->db->from('erp_i_det_orden_compra_mp d');
		$this->db->join('erp_i_enc_orden_compra_mp oc','oc.orc_id=d.orc_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->join('erp_mp p','d.mp_id=p.id');
		$this->db->where("orc_det_id", $id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function insert_etiqueta($data){
		
		return $this->db->insert("erp_i_etq_orden",$data);
			
	}

	public function update_etiqueta($id,$data){
		$this->db->where('orc_det_id',$id);
		return $this->db->update("erp_i_etq_orden",$data);
			
	}

	public function lista_etiquetas_det($id){
		$this->db->select("et.*, d.*,p.*" );
		$this->db->from('erp_i_etq_orden et');
		$this->db->join('erp_i_det_orden_compra_mp d','et.orc_det_id=d.orc_det_id');
		$this->db->join('erp_mp p','d.mp_id=p.id');
		$this->db->where("et.orc_det_id", $id);
		$this->db->order_by("et.etq_id", null);
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_etq_orden($id){
		$this->db->from('erp_i_etq_orden et');
		$this->db->join('erp_i_det_orden_compra_mp d','et.orc_det_id=d.orc_det_id');
		$this->db->join('erp_mp p','d.mp_id=p.id');
		$this->db->where("et.orc_det_id", $id);
		$this->db->where("et.etq_estado_imp", 0);
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_etq_orden_total($id){	
		$query ="SELECT sum(etq_peso) AS peso,mp.mp_c,mp.mp_d,etq.etq_fecha,substring(etq_bar_code,-3,char_length(etq_bar_code)) as etq_bar_code,mp.mp_q
			FROM erp_i_etq_orden etq, erp_i_det_orden_compra_mp ord, erp_mp mp
			WHERE ord.orc_det_id=etq.orc_det_id AND ord.mp_id=mp.id AND etq.orc_det_id=$id and etq_estado_imp=0 
			GROUP BY mp.mp_c,mp.mp_d,etq.etq_fecha,substring(etq_bar_code,-3,char_length(etq_bar_code)),mp.mp_q";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}	

	public function lista_etq_orden_mov($id){
		$this->db->from('erp_i_etq_orden et');
		$this->db->join('erp_i_mov_inv_pt m','m.mov_pago=et.etq_bar_code');
		$this->db->join('erp_mp p','m.pro_id=p.id');
		$this->db->where("et.orc_det_id", $id);
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_etq_orden_total_mov($id){	
		$query ="SELECT sum(etq_peso) AS peso,mp.mp_d,mp.mp_q,etq.etq_fecha,substring(etq.etq_bar_code,-3,char_length(etq_bar_code)) as etq_bar_code
				FROM erp_i_etq_orden etq, erp_i_mov_inv_pt mov, erp_mp mp
				WHERE mov.mov_pago=etq.etq_bar_code and mov.pro_id=mp.id and etq.orc_det_id=$id 
				GROUP BY mp.mp_d,mp.mp_q,etq.etq_fecha,substring(etq.etq_bar_code,-3,char_length(etq_bar_code))";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}	

	public function lista_ordenes_reimpresion_buscador($text,$f1,$f2,$txt){
		$this->db->select("oc.*,d.*,p.*,e.est_descripcion, c.cli_raz_social" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_det_orden_compra_mp d', 'd.orc_id=oc.orc_id');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->join('erp_mp p','p.id=d.mp_id');
		$this->db->where("(orc_codigo like '%$text%' or cli_raz_social like '%$text%' or mp_c like '%$text%' or mp_d like '%$text%') and orc_fecha between '$f1' and '$f2' $txt and exists(select * from erp_i_etq_orden et where et.orc_det_id=d.orc_det_id) ", null);
		$this->db->order_by('oc.orc_codigo','desc');
		$this->db->order_by('oc.orc_codigo','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

}

?>