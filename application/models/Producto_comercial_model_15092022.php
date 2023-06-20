<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producto_comercial_model extends CI_Model {

	public function lista_productos(){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion, e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where('ids','26');
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_mp",$data);
		
			
	}

	

	public function lista_un_producto($id){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion,e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where('id',$id);
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_un_producto_cod($text){

		$text= str_replace("%20"," ", $text);
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion,e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where("p.mp_d = '$text' or p.mp_c like '$text' or p.mp_n ='$text'", null);

		$resultado=$this->db->get();
		
		
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('id',$id);
		return $this->db->update("erp_mp",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_mp");
			
	}

	public function lista_productos_estado($est){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion, e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where('mp_i',$est);
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_productos_bodega_estado($est){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion, e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where('mp_i',$est);
		$this->db->where('ids=26 or ids=69',null);
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}


	
	
}

?>