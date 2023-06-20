<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipo_model extends CI_Model {

	
	public function lista_tipos(){
		$this->db->select('t.*, c.cat_descripcion, (select t2.tps_nombre from erp_tipos t2 where t2.tps_id=t.tps_familia) as familia, e.est_descripcion'); 
		$this->db->from('erp_tipos t'); 
		$this->db->join('erp_categorias c','c.cat_id=cast(t.tps_tipo as integer)'); 
		$this->db->join('erp_estados e','e.est_id=t.tps_estado'); 
		$this->db->order_by('c.cat_descripcion', 'asc'); 
		$this->db->order_by('t.tps_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_categorias(){
		$resultado=$this->db->get('erp_categorias');
		return $resultado->result();
			
	}

	public function lista_familias($ct){
		$resultado=$this->db->where('tps_tipo',$ct);
		$resultado=$this->db->where('tps_relacion','1');
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}

	public function lista_tipos_todos(){
		$resultado=$this->db->where('tps_relacion','2');
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}

	public function lista_familias_todos(){
		$resultado=$this->db->where('tps_relacion','1');
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}
	public function lista_familias_mp(){
		$resultado=$this->db->where('tps_tipo !=','2');
		$resultado=$this->db->where('tps_tipo !=','7');
		$resultado=$this->db->where('tps_tipo !=','8');
		$resultado=$this->db->where('tps_relacion','1');
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}


	public function lista_tipos_familia($f){
		$resultado=$this->db->where('tps_familia',$f);
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}
	public function lista_tipos_familia_2(){
		//$resultado=$this->db->where('tps_familia',$f);
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}


	
	public function insert($data){
		
		return $this->db->insert("erp_tipos",$data);
			
	}

	public function lista_un_tipo($id){
		$this->db->select('t.*, c.cat_descripcion,(select tps_nombre from erp_tipos t2 where t.tps_familia=t2.tps_id) as familia, e.est_descripcion'); 
		$this->db->from('erp_tipos t'); 
		$this->db->join('erp_categorias c','c.cat_id=cast(t.tps_tipo as integer)'); 
		$this->db->join('erp_estados e','e.est_id=t.tps_estado'); 
		$this->db->where('tps_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('tps_id',$id);
		return $this->db->update("erp_tipos",$data);
			
	}

	public function delete($id){
		$this->db->where('tps_id',$id);
		return $this->db->delete("erp_tipos");
			
	}

	public function lista_familia_categoria($ct){
		$resultado=$this->db->where('tps_tipo',$ct);
		$resultado=$this->db->where('tps_relacion','1');
		$resultado=$this->db->where('tps_estado','1');
		$resultado=$this->db->order_by('tps_nombre');
		$resultado=$this->db->get('erp_tipos');
		return $resultado->result();
			
	}


}

?>