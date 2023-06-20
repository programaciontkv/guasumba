<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_guia_model extends CI_Model {

	public function lista_buscador_guias($text,$f1,$f2,$emp_id){
		$this->db->from('erp_reg_guias g');
		$this->db->join('erp_i_cliente c','g.cli_id=c.cli_id');
		$this->db->join('erp_estados e','g.rgu_estado=e.est_id');
		$this->db->where("(cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%' or rgu_num_documento like '%$text%' or rgu_num_ingreso like '%$text%') and emp_id=$emp_id and rgu_fregistro between '$f1' and '$f2'");
		$this->db->order_by('rgu_estado asc, rgu_fregistro asc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_ultimo_sec_reg_guia() {
		$this->db->where("rgu_secuencia_unif !=''",null);
		$this->db->order_by('rgu_secuencia_unif','desc');
		$resultado=$this->db->get("erp_reg_guias");
		return $resultado->row();
    }

    public function lista_una_guia($id) {
		$this->db->where('rgu_id',$id);
		$resultado=$this->db->get("erp_reg_guias");
		return $resultado->row();
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

    public function lista_guias_secuencial($id) {

		$this->db->from('erp_reg_guias g');
		$this->db->join('erp_i_cliente c', 'g.cli_id=c.cli_id');
		$this->db->where('rgu_secuencia_unif',$id);
		$resultado=$this->db->get();
		return $resultado->result();
    }

    public function lista_una_guia_secuencial($id) {

		$this->db->from('erp_reg_guias g');
		$this->db->join('erp_i_cliente c', 'g.cli_id=c.cli_id');
		$this->db->where('rgu_secuencia_unif',$id);
		$resultado=$this->db->get();
		return $resultado->row();
    }


    public function lista_detalle_secuencial($id) {
    	$this->db->select('d.pro_id, mp_c, mp_d, mp_q, sum(drg_cantidad) as drg_cantidad');
		$this->db->from('erp_reg_det_guias d');
		$this->db->join('erp_reg_guias g', 'g.rgu_id=d.rgu_id');
		$this->db->join('erp_i_cliente c', 'g.cli_id=c.cli_id');
		$this->db->join('erp_mp p', 'p.id=d.pro_id');
		$this->db->where('rgu_secuencia_unif',$id);
		$this->db->group_by('d.pro_id, mp_c, mp_d,mp_q');
		$resultado=$this->db->get();
		return $resultado->result();
    }

    public function lista_guias_facturas_secuencial($id) {
    	$this->db->select('gf.reg_id');
		$this->db->from('erp_reg_guia_factura gf');
		$this->db->join('erp_reg_guias g', 'gf.rgu_id=g.rgu_id');
		$this->db->join('erp_reg_documentos f', 'gf.reg_id=f.reg_id');
		$this->db->where('rgu_secuencia_unif',$id);
		$this->db->where('f.reg_estado!=3',null);
		$this->db->group_by('gf.reg_id');
		$resultado=$this->db->get();
		return $resultado->result();
    }

    public function lista_facturado_secuencial($id,$txt) {
    	$this->db->select('sum(det_cantidad) as  facturado');
		$this->db->from('erp_reg_det_documentos');
		$this->db->where("$txt pro_id=$id");
		$resultado=$this->db->get();
		return $resultado->row();
    }


	
	public function insert($data){
		
		$this->db->insert("erp_reg_guias",$data);
		return $this->db->insert_id();
			
	}

	public function insert_detalle($data){
		
		return $this->db->insert("erp_reg_det_guias",$data);
			
	}

	public function insert_guias($data){
		
		$this->db->insert("erp_reg_guia_factura",$data);
		return $this->db->insert_id();
			
	}
	
	public function update($id,$data){
		$this->db->where('rgu_id',$id);
		return $this->db->update("erp_reg_guias",$data);
			
	}

	public function delete($id){
		$this->db->where('rgu_id',$id);
		return $this->db->delete("erp_reg_guias");
			
	}

	
}

?>