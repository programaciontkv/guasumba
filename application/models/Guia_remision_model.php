<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guia_remision_model extends CI_Model {


	public function lista_guias_remision(){
		$this->db->from('erp_guia_remision g');
		$this->db->join('erp_vendedor v','g.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','g.gui_estado=e.est_id');
		$this->db->order_by('gui_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_guia_buscador($text,$f1,$f2,$emp_id,$emi_id){
		$query="SELECT gui_id, gui_fecha_emision,gui_numero,v.vnd_nombre,gui_identificacion,gui_nombre,est_descripcion, gui_num_comprobante, gui_estado, gui_clave_acceso, gui_denominacion_comp, tra_razon_social
			FROM erp_guia_remision g, erp_vendedor v, erp_estados e, erp_factura f,erp_transportista t
			WHERE g.vnd_id=v.vnd_id AND t.tra_id=g.tra_id AND g.gui_estado=e.est_id AND g.fac_id=f.fac_id AND g.emp_id= $emp_id AND g.emi_id=$emi_id and (gui_numero like '%$text%' or gui_nombre like '%$text%' or gui_identificacion like '%$text%') and gui_fecha_emision between '$f1' and '$f2'
			UNION
			SELECT gui_id, gui_fecha_emision,gui_numero,v.vnd_nombre, gui_identificacion, gui_nombre, est_descripcion, gui_num_comprobante,gui_estado, gui_clave_acceso, gui_denominacion_comp, tra_razon_social
			FROM erp_guia_remision g, erp_vendedor v, erp_estados e, erp_transportista T
			WHERE g.vnd_id=v.vnd_id AND g.gui_estado=e.est_id AND t.tra_id=g.tra_id  AND g.emp_id= $emp_id AND g.emi_id=$emi_id and fac_id=0  and (gui_numero like '%$text%' or gui_nombre like '%$text%' or gui_identificacion like '%$text%') and gui_fecha_emision between '$f1' and '$f2'
			ORDER BY gui_numero desc";
		$resultado=$this->db->query($query);
		return $resultado->result();

	}

	public function lista_guias_empresa_emisor($emp_id,$emi_id){
		$query="SELECT gui_id, gui_fecha_emision,gui_numero,v.vnd_nombre,gui_identificacion,gui_nombre,est_descripcion, gui_num_comprobante, gui_estado, gui_clave_acceso
			FROM erp_guia_remision g, erp_vendedor v, erp_estados e, erp_factura f
			WHERE g.vnd_id=v.vnd_id AND g.gui_estado=e.est_id AND g.fac_id=f.fac_id AND g.emp_id= $emp_id AND g.emi_id=$emi_id 
			UNION
			SELECT gui_id, gui_fecha_emision,gui_numero,v.vnd_nombre, gui_identificacion, gui_nombre, est_descripcion, gui_num_comprobante,gui_estado, gui_clave_acceso
			FROM erp_guia_remision g, erp_vendedor v, erp_estados e
			WHERE g.vnd_id=v.vnd_id AND g.gui_estado=e.est_id  AND g.emp_id= $emp_id AND g.emi_id=$emi_id and fac_id=0 
			ORDER BY gui_numero";
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_secuencial_documento($emi,$cja){
		$this->db->select('gui_numero');
		$this->db->from('erp_guia_remision');
		$this->db->where('emi_id',$emi);
		$this->db->where('cja_id',$cja);
		$this->db->order_by('gui_numero','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_productos(){
		$this->db->from('erp_mp p');
		$this->db->where('p.mp_i','1');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_una_guia($id){
		$this->db->from('erp_guia_remision g');
		$this->db->join('erp_vendedor v','g.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=g.cli_id');
		$this->db->join('erp_transportista t','t.tra_id=g.tra_id');
		$this->db->join('erp_emisor m','m.emi_id=g.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=g.emp_id');
		$this->db->join('erp_estados e','g.gui_estado=e.est_id');
		$this->db->where('gui_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_detalle_guia($id){
		$this->db->from('erp_det_guia d');
		$this->db->join('erp_mp p','d.pro_id=p.id');
		$this->db->where('gui_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_un_detalle_factura($id,$pro){
		$this->db->from('erp_det_factura d');
		$this->db->join('erp_mp p','d.pro_id=p.id');
		$this->db->where('fac_id',$id);
		$this->db->where('d.pro_id',$pro);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_suma_detalle($id,$pro){
		$query="SELECT sum(dtg_cantidad) as dtg_cantidad from erp_det_guia d, erp_guia_remision g where d.gui_id=g.gui_id and (gui_estado=4 or gui_estado=6) and fac_id=$id and pro_id=$pro";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_suma_detalle_edit($id,$pro,$gui){
		$query="SELECT sum(dtg_cantidad) as dtg_cantidad from erp_det_guia d, erp_guia_remision g where d.gui_id=g.gui_id and (gui_estado=4 or gui_estado=6) and fac_id=$id and pro_id=$pro and g.gui_id!=$gui ";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function insert($data){
		$this->db->insert("erp_guia_remision",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_det_guia",$data);
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
		$this->db->where('gui_id',$id);
		return $this->db->update("erp_guia_remision",$data);
			
	}

	public function delete($id){
		$this->db->where('gui_id',$id);
		return $this->db->delete("erp_guia_remision");
			
	}


	public function delete_detalle($id){
		$this->db->where('gui_id',$id);
		return $this->db->delete("erp_det_guia");
			
	}

	public function lista_guia_sin_autorizar(){
		$this->db->from('erp_guia_remision g');
		$this->db->join('erp_vendedor v','g.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=g.cli_id');
		$this->db->join('erp_transportista t','t.tra_id=g.tra_id');
		$this->db->join('erp_emisor m','m.emi_id=g.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=g.emp_id');
		$this->db->join('erp_estados e','g.gui_estado=e.est_id');
		$this->db->where('gui_estado', '4');
		$this->db->order_by('gui_id','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
    
}

?>