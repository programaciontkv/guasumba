<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_model extends CI_Model {

	
	public function lista_historial_buscador($f1,$f2,$est){
		$this->db->select("to_char(h.his_fcambio ,'TMMon-yyyy') AS mes,h.*,r.* ");
		$this->db->from('erp_historial h');
		$this->db->join('erp_opciones r','r.opc_id=h.his_lugar');
		$this->db->where("his_fcambio between '$f1' and '$f2'  $est ",null);
		$this->db->order_by('his_fcambio', 'desc'); 
		$resultado=$this->db->get();
		 return $resultado->result();
			
	}

	
	public function insert($data){
		
		return $this->db->insert("erp_historial",$data);
			
	}

	public function lista_un_historial($id){
		$this->db->from('erp_historial h');
		$this->db->join('erp_opciones r','r.opc_id=h.his_lugar');
		$this->db->where("his_id =$id ",null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('his_id',$id);
		return $this->db->update("erp_historial",$data);
			
	}

	public function delete($id){
		$this->db->where('his_id',$id);
		return $this->db->delete("erp_historial");
			
	}
}

?>