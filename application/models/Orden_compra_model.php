<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_model extends CI_Model {

	

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


	public function lista_secuencial(){
		$this->db->from('erp_i_enc_orden_compra_mp');
		$this->db->order_by("orc_codigo desc",null);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function insert($data){
		
		$this->db->insert("erp_i_enc_orden_compra_mp",$data);
		return $this->db->insert_id();
			
	}

	public function insert_det($data){
		
		return $this->db->insert("erp_i_det_orden_compra_mp",$data);
			
	}


	public function lista_una_orden($id){
		$this->db->select("oc.*,e.est_descripcion, c.cli_raz_social, c.cli_ced_ruc, c.cli_telefono, emp_logo, emp_nombre" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->join('erp_empresas em','em.emp_id=oc.emp_id');
		$this->db->where("orc_id", $id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function lista_detalle($id){
		$this->db->select("d.*,p.*" );
		$this->db->from('erp_i_det_orden_compra_mp d');
		$this->db->join('erp_mp p','d.mp_id=p.id');
		$this->db->where("d.orc_id", $id);
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function update($id,$data){
		$this->db->where('orc_id',$id);
		return $this->db->update("erp_i_enc_orden_compra_mp",$data);
			
	}

	public function delete_det($id){
		$this->db->where('orc_id',$id);
		return $this->db->delete("erp_i_det_orden_compra_mp");
			
	}

	public function lista_ordenes_aprobar_buscador($text,$f1,$f2,$txt){
		$this->db->select("oc.*,e.est_descripcion, c.cli_raz_social" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->where("(orc_codigo like '%$text%' or cli_raz_social like '%$text%' )and orc_fecha between '$f1' and '$f2' $txt and orc_estado=7", null);
		$this->db->order_by('oc.orc_codigo','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	

	public function lista_ultima_compra_aprob($cli,$id){
		$this->db->from('erp_i_det_orden_compra_mp d');
		$this->db->join('erp_i_enc_orden_compra_mp o','o.orc_id=d.orc_id');
		$this->db->where("o.cli_id=$cli and d.mp_id=$id and o.orc_estado=13", null );
		$this->db->order_by("orc_codigo desc");
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function lista_detalle_aprob($id){
		$this->db->select("d.*,p.*, (select orc_det_vu from erp_i_det_orden_compra_mp d2 , erp_i_enc_orden_compra_mp o2 where o2.orc_id=d2.orc_id and o2.cli_id=o.cli_id and d2.mp_id=d.mp_id and o2.orc_estado=13 order by o2.orc_codigo desc limit 1 ) as val_aprob" );
		$this->db->from('erp_i_det_orden_compra_mp d');
		$this->db->join('erp_i_enc_orden_compra_mp o','o.orc_id=d.orc_id');
		$this->db->join('erp_mp p','d.mp_id=p.id');
		$this->db->where("d.orc_id", $id);
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_ordenes_estado($est){
		$this->db->select("oc.*,e.est_descripcion, c.cli_raz_social" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->where("orc_estado", $est);
		$resultado=$this->db->get();
		return $resultado->result();
	}
}

?>