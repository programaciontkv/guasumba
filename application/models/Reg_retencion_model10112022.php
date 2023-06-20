<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_retencion_model extends CI_Model {


	public function lista_retenciones(){
		$this->db->from('erp_registro_retencion r');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->order_by('rgr_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_retenciones_empresa($emp_id){
		$this->db->from('erp_registro_retencion r');
		$this->db->join('erp_estados e','r.rgr_estado=e.est_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->order_by('rgr_fecha_emision','desc');
		$this->db->order_by('rgr_numero','desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_retencion_buscador($text,$f1,$f2,$emp_id){
		$this->db->from('erp_registro_retencion r');
		$this->db->join('erp_estados e','r.rgr_estado=e.est_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->where("(rgr_numero like '%$text%' or rgr_nombre like '%$text%' or rgr_identificacion like '%$text%') and rgr_fecha_emision between '$f1' and '$f2'", null);
		$this->db->order_by('rgr_fecha_emision','desc');
		$this->db->order_by('rgr_numero','desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_una_retencion($id){
		$this->db->from('erp_registro_retencion r');
		$this->db->join('erp_i_cliente c','c.cli_id=r.cli_id');
		$this->db->join('erp_factura f','r.fac_id=f.fac_id');
		$this->db->join('erp_empresas em','em.emp_id=r.emp_id');
		$this->db->join('erp_estados e','r.rgr_estado=e.est_id');
		$this->db->where('rgr_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_detalle_retencion($id){
		$this->db->from('erp_det_reg_retencion d');
		$this->db->join('porcentages_retencion p','d.por_id=p.por_id');
		$this->db->where('rgr_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		$this->db->insert("erp_registro_retencion",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_det_reg_retencion",$data);
	}

	
	public function update($id,$data){
		$this->db->where('rgr_id',$id);
		return $this->db->update("erp_registro_retencion",$data);
			
	}

	public function delete($id){
		$this->db->where('rgr_id',$id);
		return $this->db->delete("erp_registro_retencion");
			
	}

	public function delete_detalle($id){
		$this->db->where('rgr_id',$id);
		return $this->db->delete("erp_det_reg_retencion");
			
	}

	public function lista_doc_duplicado($id,$num){
		$this->db->from('erp_registro_retencion');
		$this->db->where('cli_id',$id);
		$this->db->where('rgr_numero',$num);
		$this->db->where('rgr_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
    
}

?>