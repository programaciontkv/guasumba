<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opcion_model extends CI_Model {

	public function lista_opciones(){
		$this->db->select('o.*,em,emi_nombre,ep.emp_nombre,cj.cja_nombre,e.est_descripcion'); 
		$this->db->from('erp_opciones o'); 
		$this->db->join('erp_cajas cj','cj.cja_id=o.opc_caja');
		$this->db->join('erp_emisor em','em.emi_id=cj.emi_id');
		$this->db->join('erp_empresas ep','em.emp_id=ep.emp_id');
		$this->db->join('erp_estados e', 'e.est_id=o.opc_estado'); 
		$this->db->order_by('o.opc_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_opciones_estado($estado){
		$this->db->where('opc_estado',$estado);
		$this->db->order_by('opc_nombre', 'asc'); 
		$resultado=$this->db->get('erp_opciones');
		return $resultado->result();
			
	}

	public function lista_opciones_rol($id){
		
		$query = "select * from erp_opciones o where NOT EXISTS (select * from erp_roles_opcion r where o.opc_id = r.opc_id and r.rol_id=$id) and opc_estado='1' order by opc_nombre";
        $resultado = $this->db->query($query);
        return $resultado->result();
			
	}

	
	public function insert($data){
		
		return $this->db->insert("erp_opciones",$data);
			
	}

	public function lista_una_opcion($id){
		$this->db->where('opc_id',$id);
		$resultado=$this->db->get('erp_opciones');
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('opc_id',$id);
		return $this->db->update("erp_opciones",$data);
			
	}

	public function delete($id){
		$this->db->where('opc_id',$id);
		return $this->db->delete("erp_opciones");
			
	}

	public function lista_opciones_estados($id){
		$query = "select * from erp_opciones o where NOT EXISTS (select * from erp_estados_opcion e where o.opc_id = e.opc_id and e.est_id=$id) order by opc_nombre";
        $resultado = $this->db->query($query);
        return $resultado->result();
			
	}
}

?>