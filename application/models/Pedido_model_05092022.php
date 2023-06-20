<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_model extends CI_Model {

	public function lista_ultimo_registro_sec(){
		$this->db->select("ped_num_registro");
		$this->db->from('erp_reg_pedido_venta p');
		$this->db->order_by('ped_num_registro','desc');
		$resultado=$this->db->get();
		return $resultado->row();
	}



	public function lista_pedidos(){
		$this->db->select("p.*, e.est_descripcion");
		$this->db->from('erp_reg_pedido_venta p');
		$this->db->join('erp_estados e','e.est_id=p.ped_estado');
		$this->db->order_by('p.ped_femision','desc');
		$this->db->order_by('p.ped_num_registro', 'desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_pedidos_buscador($text,$f1,$f2,$emp_id,$est){
		$this->db->select("p.*, emi.*, v.*, e.est_descripcion");
		$this->db->from('erp_reg_pedido_venta p');
		$this->db->join('erp_vendedor v','v.vnd_id=p.ped_vendedor');
		$this->db->join('erp_emisor emi','emi.emi_id=p.ped_local');
		$this->db->join('erp_estados e','e.est_id=p.ped_estado');
		$this->db->where('p.emp_id',$emp_id);
		$this->db->where("(ped_num_registro like '%$text%' or ped_ruc_cc_cliente like '%$text%' or ped_nom_cliente like '%$text%') and ped_femision between '$f1' and '$f2'  $est", null);
		$this->db->order_by('p.ped_femision','desc');
		$this->db->order_by('p.ped_num_registro', 'desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	
	
	public function insert($data){
		
		$this->db->insert("erp_reg_pedido_venta",$data);
		return $this->db->insert_id();
			
	}

	public function last_id(){
		
		return $this->db->insert_id();
			
	}

	public function insert_detalle($data){
		
		return $this->db->insert("erp_det_ped_venta",$data);
			
	}

	public function insert_pagos($data){
		
		return $this->db->insert("erp_pagos_pedventa",$data);
			
	}

	public function delete_pago($id){
		$this->db->where('ped_id',$id);
		return $this->db->delete("erp_abonos_pedcliente");
	}

	public function lista_un_pedido($id){
		$this->db->select("p.*,em.*, emi.*, v.*, e.est_descripcion");
		$this->db->from('erp_reg_pedido_venta p');
		$this->db->join('erp_vendedor v','v.vnd_id=p.ped_vendedor');
		$this->db->join('erp_emisor emi','emi.emi_id=p.ped_local');
		$this->db->join('erp_empresas em','em.emp_id=emi.emp_id');
		$this->db->join('erp_estados e','e.est_id=p.ped_estado');
		$this->db->where('ped_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_un_pedido_detalle($id){
		$this->db->select("d.*, mp.*, e.est_descripcion");
		$this->db->from('erp_det_ped_venta d');
		$this->db->join('erp_mp mp','mp.id=d.pro_id');
		$this->db->join('erp_estados e','e.est_id=d.det_estado');
		$this->db->where('d.ped_id',$id);
		$this->db->where('d.det_estado!=3',null);
		$resultado=$this->db->get();
		// print_r($resultado->row());
		return $resultado->result();
			
	}

	public function lista_pedidos_detalles($id){
		$this->db->select("d.*, mp.*, p.*, e.est_descripcion");
		$this->db->from('erp_det_ped_venta d');
		$this->db->join('erp_reg_pedido_venta p','p.ped_id=d.ped_id');
		$this->db->join('erp_mp mp','mp.id=d.pro_id');
		$this->db->join('erp_estados e','e.est_id=d.det_estado');
		$this->db->where('d.ped_id',$id);
		$this->db->where('d.det_estado!=3',null);
		$resultado=$this->db->get();
		// print_r($resultado->row());
		return $resultado->result();
			
	}

	public function lista_un_pedido_pagos($id){
		$this->db->select("p.*, e.est_descripcion");
		$this->db->from('erp_pagos_pedventa p');
		$this->db->join('erp_estados e','e.est_id=p.pag_estado');
		$this->db->where('ped_id',$id);
		$this->db->where('p.pag_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function update($id,$data){
		$this->db->where('ped_id',$id);
		return $this->db->update("erp_reg_pedido_venta",$data);
			
	}

	public function delete_detalle($id){
		$this->db->where('ped_id',$id);
		return $this->db->delete("erp_det_ped_venta");
			
	}

	public function delete_pagos($id){
		$this->db->where('ped_id',$id);
		return $this->db->delete("erp_pagos_pedventa");
			
	}

	public function insert_cliente($data){
		
		$this->db->insert("erp_i_cliente",$data);
		return $this->db->insert_id();
			
	}	

	public function lista_facturado($id){
		
		$query = "select sum(dfc_cantidad) as facturado from erp_det_factura d, erp_factura f where d.fac_id=f.fac_id and fac_estado!=3 and f.ped_id=$id";
        $resultado = $this->db->query($query);
        return $resultado->row();
			
	}

	public function lista_facturado_producto($id,$pro){
		
		$query = "select sum(dfc_cantidad) as facturado from erp_det_factura d, erp_factura f where d.fac_id=f.fac_id and fac_estado!=3 and f.ped_id=$id and d.pro_id=$pro";
        $resultado = $this->db->query($query);
        return $resultado->row();
			
	}

	public function lista_solicitado($id){
		
		$query = "select sum(det_cantidad) as solicitado from erp_det_ped_venta where det_estado!=3 and ped_id=$id";
        $resultado = $this->db->query($query);
        return $resultado->row();
			
	}

	public function lista_entregado_producto($id,$pro){
		
		$query = "select sum(mov_cantidad) as entregado from erp_i_mov_inv_pt  where mov_num_fac_entrega='$id' and pro_id=$pro and trs_id=20";
        $resultado = $this->db->query($query);
        return $resultado->row();
			
	}

	public function lista_entregado_bodega($id){
		
		$query = "select sum(mov_cantidad) as entregado from erp_i_mov_inv_pt  where mov_num_fac_entrega='$id' and trs_id=20";
        $resultado = $this->db->query($query);
        return $resultado->row();
			
	}

	

}

?>