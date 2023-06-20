<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

	public function lista_usuarios(){
		$this->db->select('u.*,r.rol_nombre, e.est_descripcion');
		$this->db->from('erp_users u');
		$this->db->join('erp_roles r','r.rol_id=u.rol_id');
		$this->db->join('erp_estados e','e.est_id=u.usu_estado');
		$this->db->order_by('usu_person', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}
	public function lista_usuarios_2(){
		$this->db->select('u.*,r.rol_nombre, e.est_descripcion');
		$this->db->from('erp_users u');
		$this->db->where('usu_id!=1',null);
		$this->db->join('erp_roles r','r.rol_id=u.rol_id');
		$this->db->join('erp_estados e','e.est_id=u.usu_estado');
		$this->db->order_by('usu_person', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_usuarios_estado($estado){
		$this->db->where('usu_estado',$estado);
		$resultado=$this->db->get('erp_users');
		return $resultado->result();
			
	}
	public function lista_usuarios_estado_2($estado){
		$this->db->where('usu_estado',$estado);
		$this->db->where('usu_id != 1',null);
		$resultado=$this->db->get('erp_users');
		return $resultado->result();
			
	}

	public function insert($data){
		
		return $this->db->insert("erp_users",$data);
			
	}

	public function lista_un_usuario($id){
		$this->db->select('u.*,r.rol_nombre');
		$this->db->from('erp_users u');
		$this->db->join('erp_roles r','r.rol_id=u.rol_id');
		$this->db->where('usu_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('usu_id',$id);
		return $this->db->update("erp_users",$data);
			
	}

	public function delete($id){
		$this->db->where('usu_id',$id);
		return $this->db->delete("erp_users");
			
	}
}

?>