<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ayuda_model extends CI_Model {

	public function lista_ayudas($txt){
		$this->db->select("a.*,s.sbm_nombre, o.opc_nombre, e.est_descripcion" );
		$this->db->from('erp_ayuda a');
		$this->db->join('erp_submenus s','s.sbm_id=cast(a.ayu_codigo as integer)');
		$this->db->join('erp_opciones o','o.opc_id=cast(a.ayu_descripcion as integer)');
		$this->db->join('erp_estados e','e.est_id=a.ayu_estado');
		$this->db->where("sbm_nombre like '%$txt%' or opc_nombre like '%$txt%'");
		$this->db->order_by('sbm_nombre');
		$this->db->order_by('cast(a.ayu_codigo as integer)');
		$this->db->order_by('opc_nombre');
		$resultado=$this->db->get();
		return $resultado->result();
	} 

	public function lista_una_ayuda($id){
		$this->db->from('erp_ayuda a');
		$this->db->join('erp_submenus s','s.sbm_id=cast(a.ayu_codigo as integer)');
		$this->db->join('erp_opciones o','o.opc_id=cast(a.ayu_descripcion as integer)');
		$this->db->join('erp_estados e','e.est_id=a.ayu_estado');
		$this->db->where('a.ayu_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function insert($data){
		return $this->db->insert("erp_ayuda",$data);
	}
	
	public function update($id,$data){
		$this->db->where('ayu_id',$id);
		return $this->db->update("erp_ayuda",$data);
			
	}

	public function delete($id){
		$this->db->where('ayu_id',$id);
		return $this->db->delete("erp_ayuda");
			
	}

	public function lista_opciones_submenu($id){
		$this->db->select("ro.*,opc_nombre" );
		$this->db->from('erp_roles_opcion ro');
		$this->db->join('erp_opciones o','o.opc_id=ro.opc_id');
		$this->db->where('opc_estado','1');
		$this->db->where('sbm_id',$id);
		$this->db->order_by('opc_nombre',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

}

?>