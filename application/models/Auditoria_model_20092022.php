<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditoria_model extends CI_Model {

	public function lista_auditorias(){
		$this->db->order_by('adt_date', 'desc'); 
		$this->db->order_by('adt_hour', 'desc'); 
		$resultado=$this->db->get('erp_auditoria');
		return $resultado->result();
			
	}

	public function lista_auditorias_buscador($text,$f1,$f2,$usu,$accion){
		$this->db->from('erp_auditoria a');
		$this->db->order_by('adt_date', 'desc'); 
		$this->db->order_by('adt_hour', 'desc'); 
		$this->db->where("adt_documento like '%$text%' and usu_login like '%$usu%' and adt_accion like '%$accion%' and adt_date between '$f1' and '$f2'", null);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		
		return $this->db->insert("erp_auditoria",$data);
			
	}

	public function lista_una_auditoria($id){
		$this->db->where('adt_id', $id); 
		$resultado=$this->db->get('erp_auditoria');
		return $resultado->result();
			
	}

	public function lista_acciones(){
		$query ="select adt_accion from erp_auditoria group by adt_accion order by adt_accion";
        $resultado=$this->db->query($query);
		return $resultado->result();
	}


	
}

?>