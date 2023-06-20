<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producto_model extends CI_Model {

	public function lista_productos(){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=emp_id) as tip_nombre, c.cat_descripcion, e.est_descripcion,cl.cli_raz_social");
		$this->db->from('erp_i_productos p');
		$this->db->join('erp_i_cliente cl','cl.cli_id=p.cli_id');
		$this->db->join('erp_tipos tmp','tmp.tps_id=p.pro_familia');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=p.pro_estado');
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.pro_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	


	
	public function insert($data){
		
		if($this->db->insert("erp_i_productos",$data)){
			return $this->db->insert_id();	
		}else{
			return '';
		}
		
			
	}

	public function last_id(){
		
		
			
	}

	public function lista_un_producto($id){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=emp_id) as tip_nombre, c.cat_descripcion,e.est_descripcion,cl.cli_raz_social");
		$this->db->from('erp_i_productos p');
		$this->db->join('erp_i_cliente cl','cl.cli_id=p.cli_id');
		$this->db->join('erp_tipos tmp','tmp.tps_id=p.pro_familia');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=p.pro_estado');
		$this->db->where('pro_id',$id);
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.pro_codigo');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}



	public function update($id,$data){
		$this->db->where('pro_id',$id);
		return $this->db->update("erp_i_productos",$data);
			
	}

	public function delete($id){
		$this->db->where('pro_id',$id);
		return $this->db->delete("erp_i_productos");
			
	}

	public function lista_productos_cliente($id){
		$this->db->where('cli_id',$id);
		$this->db->where('pro_estado','1');
		$this->db->order_by('pro_codigo');
		$resultado=$this->db->get('erp_i_productos');
		return $resultado->result();
			
	}
}

?>