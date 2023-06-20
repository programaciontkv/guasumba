<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_model extends CI_Model {

	public function lista_productos(){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion, e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where('ids','79');
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}
	public function lista_productos_buscador($text,$estado){

		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion, e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->where("(p.mp_c like '%$text%' or p.mp_d like '%$text%')",null);
		$this->db->where("p.mp_i ",$estado);
		$this->db->where('ids','79');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
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

	public function update($id,$data){
		$this->db->where('id',$id);
		return $this->db->update("erp_mp",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_mp");
			
	}
	public function lista_by_tipo($id1,$id2){
		$this->db->from('erp_mp');
		$this->db->where("mp_a='$id1' and mp_b='$id2' order by split_part(mp_c,'.',3) desc limit 1 ",null);
		//$this->db->order_by("order by split_part(mp_c,'.',3) desc limit 1");
		$resultado=$this->db->get();
		//return $resultado->result();
		return $resultado->row();
	}
	public function lista_by_tipo_count($id1,$id2){
		$this->db->select("count (id) as n ");
		$this->db->from('erp_mp');
		$this->db->where("mp_a='$id1' and mp_b='$id2'",null);
		$resultado=$this->db->get();
		//return $resultado->result();
		return $resultado->row();
	}

	
}

?>