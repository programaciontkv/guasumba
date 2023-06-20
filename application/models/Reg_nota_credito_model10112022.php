<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_nota_credito_model extends CI_Model {


	public function lista_notas_credito(){
		$this->db->from('erp_registro_nota_credito n');
		$this->db->join('erp_estados e','n.rnc_estado=e.est_id');
		$this->db->order_by('ncr_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_nota_buscador($text,$f1,$f2,$emp_id){
		$this->db->from('erp_registro_nota_credito n');
		$this->db->join('erp_reg_documentos f','n.reg_id=f.reg_id');
		$this->db->join('erp_estados e','n.rnc_estado=e.est_id');
		$this->db->join('erp_empresas m','n.emp_id=m.emp_id');
		$this->db->where('n.emp_id',$emp_id);
		$this->db->where("(rnc_numero like '%$text%' or rnc_nombre like '%$text%' or rnc_identificacion like '%$text%') and rnc_fecha_emision between '$f1' and '$f2'", null);
		$this->db->order_by('rnc_fecha_emision','desc');
		$this->db->order_by('rnc_numero','desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_notas_empresa($emp_id){
		$this->db->from('erp_registro_nota_credito n');
		$this->db->join('erp_reg_documentos f','n.reg_id=f.reg_id');
		$this->db->join('erp_estados e','n.rnc_estado=e.est_id');
		$this->db->join('erp_empresas m','n.emp_id=m.emp_id');
		$this->db->where('n.emp_id',$emp_id);
		$this->db->order_by('rnc_fecha_emision','desc');
		$this->db->order_by('rnc_numero','desc');

		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_secuencial_documento($emi,$cja){
		$this->db->select('ncr_numero');
		$this->db->from('erp_registro_nota_credito');
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
		$this->db->from('erp_registro_nota_credito n');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_reg_documentos f','n.reg_id=f.reg_id');
		$this->db->join('erp_estados e','n.rnc_estado=e.est_id');
		$this->db->where('rnc_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_detalle_nota($id){
		$this->db->from('erp_reg_det_nota_credito d');
		$this->db->join('erp_mp p','d.pro_id=p.id');
		$this->db->where('rnc_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_un_detalle_factura($id,$pro){
		$this->db->from('erp_reg_det_documentos d');
		$this->db->join('erp_mp p','d.pro_id=p.id');
		$this->db->where('reg_id',$id);
		$this->db->where('d.pro_id',$pro);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_suma_detalle($id,$pro){
		$query="SELECT sum(drc_cantidad) as drc_cantidad from erp_reg_det_nota_credito d, erp_registro_nota_credito n where d.rnc_id=n.rnc_id and rnc_estado!=3  and reg_id=$id and pro_id=$pro";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_suma_detalle_edit($id,$pro,$ncr){
		$query="SELECT sum(drc_cantidad) as drc_cantidad from erp_reg_det_nota_credito d, erp_registro_nota_credito n where d.rnc_id=n.rnc_id and rnc_estado!=3 and reg_id=$id and pro_id=$pro and n.rnc_id!=$ncr ";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function insert($data){
		$this->db->insert("erp_registro_nota_credito",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_reg_det_nota_credito",$data);
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
		$this->db->where('rnc_id',$id);
		return $this->db->update("erp_registro_nota_credito",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_registro_nota_credito");
			
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
		$this->db->where('rnc_id',$id);
		return $this->db->delete("erp_reg_det_nota_credito");
			
	}

	
	public function insert_movimientos($data){
		
		return $this->db->insert("erp_i_mov_inv_pt",$data);
			
	}

	public function update_movimientos($num,$id,$data){
		$this->db->where('mov_documento',$num);
		$this->db->where('bod_id',$id);
		$this->db->where('trs_id=6 or trs_id=7');
		return $this->db->update("erp_i_mov_inv_pt",$data);
			
	}

	public function lista_doc_duplicado($id,$num){
		$this->db->from('erp_registro_nota_credito');
		$this->db->where('cli_id',$id);
		$this->db->where('rnc_numero',$num);
		$this->db->where('rnc_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}
    
}

?>