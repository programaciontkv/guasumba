<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retencion_model extends CI_Model {


	public function lista_retenciones(){
		$this->db->from('erp_retencion r');
		$this->db->join('erp_vendedor v','r.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->order_by('ret_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_retencion_buscador($text,$f1,$f2,$emp_id,$emi_id){
		$this->db->from('erp_retencion r');
		$this->db->join('erp_vendedor v','r.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('emi_id',$emi_id);
		$this->db->where("(ret_numero like '%$text%' or ret_nombre like '%$text%' or ret_identificacion like '%$text%') and ret_fecha_emision between '$f1' and '$f2'", null);
		$this->db->order_by('ret_numero', 'desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_retenciones_empresa_emisor($emp_id,$emi_id){
		$this->db->from('erp_retencion r');
		$this->db->join('erp_vendedor v','r.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('emi_id',$emi_id);
		$this->db->order_by('ret_numero','desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_secuencial_documento($emi,$cja){
		$this->db->select('ret_numero');
		$this->db->from('erp_retencion');
		$this->db->where('emi_id',$emi);
		$this->db->where('cja_id',$cja);
		$this->db->order_by('ret_numero','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_una_retencion($id){
		$this->db->from('erp_retencion r');
		$this->db->join('erp_vendedor v','r.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=r.cli_id');
		$this->db->join('erp_reg_documentos f','r.reg_id=f.reg_id');
		$this->db->join('erp_emisor m','m.emi_id=r.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=r.emp_id');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->where('ret_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_detalle_retencion($id){
		$this->db->from('erp_det_retencion d');
		$this->db->join('porcentages_retencion p','d.por_id=p.por_id');
		$this->db->where('ret_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		$this->db->insert("erp_retencion",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_det_retencion",$data);
	}

	
	public function update($id,$data){
		$this->db->where('ret_id',$id);
		return $this->db->update("erp_retencion",$data);
			
	}

	public function delete($id){
		$this->db->where('ret_id',$id);
		return $this->db->delete("erp_retencion");
			
	}

	public function delete_detalle($id){
		$this->db->where('ret_id',$id);
		return $this->db->delete("erp_det_retencion");
			
	}

	public function lista_retencion_sin_autorizar(){
		$this->db->from('erp_retencion r');
		$this->db->join('erp_vendedor v','r.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=r.cli_id');
		$this->db->join('erp_reg_documentos f','r.reg_id=f.reg_id');
		$this->db->join('erp_emisor m','m.emi_id=r.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=r.emp_id');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->where('ret_estado', '4');
		$this->db->order_by('ret_id','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_una_retencion_factura($id){
		$this->db->from('erp_retencion r');
		$this->db->join('erp_vendedor v','r.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=r.cli_id');
		$this->db->join('erp_reg_documentos f','r.reg_id=f.reg_id');
		$this->db->join('erp_emisor m','m.emi_id=r.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=r.emp_id');
		$this->db->join('erp_estados e','r.ret_estado=e.est_id');
		$this->db->where('ret_estado!=','3');
		$this->db->where('r.reg_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
    
}

?>