<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_model extends CI_Model {

	public function lista_configuraciones(){
		$this->db->from('erp_configuraciones c');
		$this->db->order_by('con_id');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	public function lista_una_configuracion($id){
		$this->db->from('erp_configuraciones');
		$this->db->where('con_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('con_id',$id);
		return $this->db->update("erp_configuraciones",$data);
			
	}

	public function lista_credenciales(){
		$this->db->from('erp_configuraciones c');
		$this->db->where('con_id',13);
		$this->db->where('con_id',14);
		$this->db->order_by('con_id');
		$resultado=$this->db->get();
		return $resultado->result();
	}

}

?>