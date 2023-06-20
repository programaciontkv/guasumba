<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rol_model extends CI_Model {

	public function lista_roles(){
		$this->db->select('r.*,e.est_descripcion');
		$this->db->from('erp_roles r');
		$this->db->join('erp_estados e','e.est_id=r.rol_estado');
		$this->db->order_by('rol_nombre');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_roles_estado($estado){
		$resultado=$this->db->where('rol_estado',$estado);
		$this->db->order_by('rol_nombre', 'asc'); 
		$this->db->where('rol_id!=1',null);
		$resultado=$this->db->get('erp_roles');
		return $resultado->result();
			
	}

	public function insert($data){
		
		return $this->db->insert("erp_roles",$data);
			
	}

	public function lista_un_rol($id){
		$this->db->where('rol_id',$id);
		$resultado=$this->db->get('erp_roles');
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('rol_id',$id);
		return $this->db->update("erp_roles",$data);
			
	}

	public function delete($id){
		$this->db->where('rol_id',$id);
		return $this->db->delete("erp_roles");
			
	}

	public function insert_opcion($data){
		return $this->db->insert("erp_roles_opcion",$data);
	}

	public function lista_roles_opcion($id){
		$this->db->select('ro.*, r.rol_nombre, o.opc_nombre, m.men_nombre, s.sbm_nombre');
		$this->db->from('erp_roles_opcion ro');
		$this->db->join('erp_roles r','ro.rol_id=r.rol_id');
		$this->db->join('erp_opciones o','ro.opc_id=o.opc_id');
		$this->db->join('erp_menus m','ro.men_id=m.men_id');
		$this->db->join('erp_submenus s','ro.sbm_id=s.sbm_id');
		$this->db->where('ro.rol_id',$id);
		$this->db->order_by('m.men_nombre', 'asc'); 
		$this->db->order_by('s.sbm_nombre', 'asc'); 
		$this->db->order_by('o.opc_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function delete_opcion($id){
		$this->db->where('rop_id',$id);
		return $this->db->delete("erp_roles_opcion");
			
	}
	public function traer_cod($id){
	
		$this->db->select('men_id,sbm_id');
		$this->db->from('erp_roles_opcion');
		$this->db->where('rol_id=1',null);
		$this->db->where('opc_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

}

?>