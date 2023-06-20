<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submenu_model extends CI_Model {

	public function lista_submenus(){
		$this->db->select('s.*,e.est_descripcion'); 
		$this->db->from('erp_submenus s'); 
		$this->db->join('erp_estados e', 'e.est_id=s.sbm_estado'); 
		$this->db->order_by('s.sbm_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		
		return $this->db->insert("erp_submenus",$data);
			
	}

	public function lista_un_submenu($id){
		$this->db->where('sbm_id',$id);
		$this->db->order_by('sbm_nombre', 'asc'); 
		$resultado=$this->db->get('erp_submenus');
		return $resultado->row();
			
	}

	public function lista_submenus_estado($estado){
		$this->db->where('sbm_estado',$estado);
		$this->db->order_by('sbm_nombre', 'asc'); 
		$resultado=$this->db->get('erp_submenus');
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('sbm_id',$id);
		return $this->db->update("erp_submenus",$data);
			
	}

	public function delete($id){
		$this->db->where('sbm_id',$id);
		return $this->db->delete("erp_submenus");
			
	}


}

?>