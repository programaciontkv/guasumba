<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_tiempo_model extends CI_Model {

	public function lista_tiempos(){
		$this->db->select("t.*,e.est_descripcion" );
		$this->db->from('erp_i_tiempo_orden_compra t');
		$this->db->join('erp_estados e','e.est_id=t.tie_estado');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_i_tiempo_orden_compra",$data);
			
	}

	public function lista_un_tiempo($id){
		$this->db->select("t.*,e.est_descripcion" );
		$this->db->from('erp_i_tiempo_orden_compra t');
		$this->db->join('erp_estados e','e.est_id=t.tie_estado');
		$this->db->where('t.tie_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_tiempos_estado($est){

		$this->db->select("t.*,e.est_descripcion" );
		$this->db->from('erp_i_tiempo_orden_compra t');
		$this->db->join('erp_estados e','e.est_id=t.tie_estado');
		$this->db->where('tie_estado',$est);
		$this->db->order_by('tie_id desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('tie_id',$id);
		return $this->db->update("erp_i_tiempo_orden_compra",$data);
			
	}

	public function delete($id){
		$this->db->where('tie_id',$id);
		return $this->db->delete("erp_i_tiempo_orden_compra");
			
	}

	public function lista_tiempo_vigente(){

		$this->db->select("t.*,e.est_descripcion" );
		$this->db->from('erp_i_tiempo_orden_compra t');
		$this->db->join('erp_estados e','e.est_id=t.tie_estado');
		$this->db->where('tie_estado',1);
		$this->db->order_by('tie_id desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

}

?>