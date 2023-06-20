<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_credito_model extends CI_Model {


	public function lista_notas_credito(){
		$this->db->from('erp_nota_credito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','f.ncr_estado=e.est_id');
		$this->db->order_by('ncr_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_notas_empresa_emisor($emp_id,$emi_id){
		$query="SELECT ncr_id, ncr_fecha_emision,ncr_numero,v.vnd_nombre as usuario,ncr_identificacion,ncr_nombre,nrc_total_valor,est_descripcion, v2.vnd_nombre as vendedor, f.fac_total_valor, ncr_num_comp_modifica, ncr_estado, ncr_clave_acceso
			FROM erp_nota_credito n, erp_vendedor v, erp_estados e, erp_factura f,  erp_vendedor v2  
			WHERE n.vnd_id=v.vnd_id AND n.ncr_estado=e.est_id AND n.fac_id=f.fac_id AND f.vnd_id=v2.vnd_id  AND n.emp_id= $emp_id AND n.emi_id=$emi_id 
			UNION
			SELECT ncr_id, ncr_fecha_emision,ncr_numero,v.vnd_nombre as usuario,ncr_identificacion,ncr_nombre,nrc_total_valor,est_descripcion, '', '0',ncr_num_comp_modifica,ncr_estado, ncr_clave_acceso
			FROM erp_nota_credito n, erp_vendedor v, erp_estados e
			WHERE n.vnd_id=v.vnd_id AND n.ncr_estado=e.est_id  AND n.emp_id= $emp_id AND n.emi_id=$emi_id and fac_id=0 
			ORDER BY ncr_numero";
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_nota_buscador($text,$f1,$f2,$emp_id,$emi_id){
		$query="SELECT ncr_id, ncr_fecha_emision,ncr_numero,v.vnd_nombre as usuario,ncr_identificacion,ncr_nombre,nrc_total_valor,est_descripcion, v2.vnd_nombre as vendedor, f.fac_total_valor, ncr_num_comp_modifica, ncr_estado, ncr_clave_acceso
			FROM erp_nota_credito n, erp_vendedor v, erp_estados e, erp_factura f,  erp_vendedor v2  
			WHERE n.vnd_id=v.vnd_id AND n.ncr_estado=e.est_id AND n.fac_id=f.fac_id AND f.vnd_id=v2.vnd_id  AND n.emp_id= $emp_id AND n.emi_id=$emi_id and (ncr_numero like '%$text%' or ncr_nombre like '%$text%' or ncr_identificacion like '%$text%') and ncr_fecha_emision between '$f1' and '$f2'
			UNION
			SELECT ncr_id, ncr_fecha_emision,ncr_numero,v.vnd_nombre as usuario,ncr_identificacion,ncr_nombre,nrc_total_valor,est_descripcion, '', '0',ncr_num_comp_modifica,ncr_estado, ncr_clave_acceso
			FROM erp_nota_credito n, erp_vendedor v, erp_estados e
			WHERE n.vnd_id=v.vnd_id AND n.ncr_estado=e.est_id  AND n.emp_id= $emp_id AND n.emi_id=$emi_id and fac_id=0 and (ncr_numero like '%$text%' or ncr_nombre like '%$text%' or ncr_identificacion like '%$text%') and ncr_fecha_emision between '$f1' and '$f2' ORDER BY ncr_numero desc";
		$resultado=$this->db->query($query);
		return $resultado->result();
	}

	public function lista_secuencial_documento($emi,$cja){
		$this->db->select('ncr_numero');
		$this->db->from('erp_nota_credito');
		$this->db->where('emi_id',$emi);
		$this->db->where('cja_id',$cja);
		$this->db->order_by('ncr_numero','desc');
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

	public function lista_una_nota($id){
		$this->db->from('erp_nota_credito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_emisor m','m.emi_id=n.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_estados e','n.ncr_estado=e.est_id');
		$this->db->where('ncr_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_detalle_nota($id){
		// $this->db->from('erp_det_nota_credito d');
		// $this->db->join('erp_mp p','d.pro_id=p.id');
		// $this->db->where('ncr_id',$id);
		$query="SELECT pro_id,ids,dnc_codigo,dnc_descripcion,dnc_precio_unit,dnc_iva,dnc_porcentaje_descuento,dnc_val_descuento,mp_q,dnc_cantidad,dnc_ice,dnc_p_ice,dnc_cod_ice,dnc_precio_total from erp_det_nota_credito d, erp_mp p where d.pro_id=p.id and d.ncr_id=$id and pro_id!=0
		union
		SELECT pro_id,0,dnc_codigo,dnc_descripcion,dnc_precio_unit,dnc_iva,dnc_porcentaje_descuento,dnc_val_descuento,'',dnc_cantidad,dnc_ice,dnc_p_ice,dnc_cod_ice,dnc_precio_total from erp_det_nota_credito  where ncr_id=$id and pro_id=0
		";
		$resultado=$this->db->query($query);
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
		$query="SELECT sum(dnc_cantidad) as dnc_cantidad from erp_det_nota_credito d, erp_nota_credito n where d.ncr_id=n.ncr_id and (ncr_estado=4 or ncr_estado=6) and fac_id=$id and pro_id=$pro";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_suma_detalle_edit($id,$pro,$ncr){
		$query="SELECT sum(dnc_cantidad) as dnc_cantidad from erp_det_nota_credito d, erp_nota_credito n where d.ncr_id=n.ncr_id and (ncr_estado=4 or ncr_estado=6) and fac_id=$id and pro_id=$pro and n.ncr_id!=$ncr ";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function insert($data){
		$this->db->insert("erp_nota_credito",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_det_nota_credito",$data);
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
		$this->db->where('ncr_id',$id);
		return $this->db->update("erp_nota_credito",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_nota_credito");
			
	}


	public function total_ingreso_egreso_fact($id, $txt) {
       
        $query ="select(SELECT SUM(m.mov_cantidad)as suma FROM erp_i_mov_inv_pt m, erp_transacciones t WHERE m.trs_id=t.trs_id and m.pro_id=$id and t.trs_operacion= 0 $txt and mov_estado=1) as ingreso,
                                   (SELECT SUM(m.mov_cantidad)as suma FROM erp_i_mov_inv_pt m, erp_transacciones t WHERE m.trs_id=t.trs_id and m.pro_id=$id  and t.trs_operacion= 1 $txt and mov_estado=1) as egreso";
        $resultado=$this->db->query($query);
		return $resultado->row();
    }
    
	

	public function lista_costos_mov($id, $txt) {
        $query ="select (select sum(m.mov_val_tot)  from erp_i_mov_inv_pt m, erp_transacciones t where m.trs_id=t.trs_id and m.pro_id=$id and t.trs_operacion='0' $txt and mov_estado=1) as ingreso,
                                    (select sum(m.mov_val_tot)  from erp_i_mov_inv_pt m, erp_transacciones t where m.trs_id=t.trs_id and m.pro_id=$id and t.trs_operacion='1' $txt and mov_estado=1) as egreso,
                                    (select sum(m.mov_cantidad)  from erp_i_mov_inv_pt m, erp_transacciones t where m.trs_id=t.trs_id and m.pro_id=$id and t.trs_operacion='0' $txt and mov_estado=1) as icnt,
                                    (select sum(m.mov_cantidad)  from erp_i_mov_inv_pt m, erp_transacciones t where m.trs_id=t.trs_id and m.pro_id=$id and t.trs_operacion='1' $txt and mov_estado=1) as ecnt";
        $resultado=$this->db->query($query);
		return $resultado->row();
    }

   
	public function delete_detalle($id){
		$this->db->where('ncr_id',$id);
		return $this->db->delete("erp_det_nota_credito");
			
	}

	
	public function insert_movimientos($data){
		
		return $this->db->insert("erp_i_mov_inv_pt",$data);
			
	}

	public function update_movimientos($num,$id,$data){
		$this->db->where('mov_documento',$num);
		$this->db->where('bod_id',$id);
		$this->db->where('trs_id=12 or trs_id=13');
		return $this->db->update("erp_i_mov_inv_pt",$data);
			
	}

	public function lista_nota_sin_autorizar(){
		$this->db->from('erp_nota_credito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_emisor m','m.emi_id=n.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_estados e','n.ncr_estado=e.est_id');
		$this->db->where('ncr_estado', '4');
		$this->db->order_by('ncr_id','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_suma_notas_factura($id){
		$query="SELECT sum(nrc_total_valor) as nrc_total_valor from erp_nota_credito where (ncr_estado=4 or ncr_estado=6) and fac_id=$id";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}
    
}

?>