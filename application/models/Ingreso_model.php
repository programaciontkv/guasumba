<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingreso_model extends CI_Model {

	public function lista_movimientos(){
		$this->db->select("m.*,e.est_descripcion, c.cli_raz_social, t.trs_descripcion, p.id, p.mp_a,p.mp_b,p.mp_c,p.mp_d,p.mp_q" );
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_transacciones t','t.trs_id=m.trs_id');
		$this->db->join('erp_mp p','p.id=m.pro_id');
		$this->db->join('erp_estados e','e.est_id=m.mov_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=m.cli_id');
		$this->db->where('mp_i','1');
		$this->db->where('mov_estado','1');
		$this->db->order_by('m.mov_fecha_trans','desc');
		$this->db->order_by('m.mov_documento','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_movimientos_bodegas($txt,$bsq){
		$this->db->select("m.*,e.est_descripcion, c.cli_raz_social, t.trs_descripcion, p.id, p.mp_a,p.mp_b,p.mp_c,p.mp_d,p.mp_q" );
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_transacciones t','t.trs_id=m.trs_id');
		$this->db->join('erp_mp p','p.id=m.pro_id');
		$this->db->join('erp_estados e','e.est_id=m.mov_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=m.cli_id');
		$this->db->where($txt,$bsq);
		$this->db->where('mp_i','1');
		$this->db->where('mov_estado','1');
		$this->db->order_by('m.mov_fecha_trans','desc');
		$this->db->order_by('m.mov_documento','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_ingresos_buscador($text,$ids,$f1,$f2,$txt){
		$this->db->select("m.*,e.est_descripcion, c.cli_raz_social, t.trs_descripcion, p.id, p.mp_a,p.mp_b,p.mp_c,p.mp_d,p.mp_q" );
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_transacciones t','t.trs_id=m.trs_id');
		$this->db->join('erp_mp p','p.id=m.pro_id');
		$this->db->join('erp_estados e','e.est_id=m.mov_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=m.cli_id');
		$this->db->where("(mov_documento like '%$text%' or cli_raz_social like '%$text%' or mp_c like '%$text%' or mp_d like '%$text%') and ids='$ids' and mov_fecha_trans between '$f1' and '$f2' $txt and mp_i='1' and mov_estado='1' and trs_operacion=0", null);
		$this->db->order_by('m.mov_fecha_trans','desc');
		$this->db->order_by('m.mov_documento','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_transacciones(){
		$this->db->from('erp_transacciones');
		$this->db->where('trs_estado','1');
		$this->db->order_by('trs_descripcion','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function insert($data){
		
		return $this->db->insert("erp_i_mov_inv_pt",$data);
			
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

    public function lista_secuencial(){
		$this->db->from('erp_i_mov_inv_pt');
		$this->db->where('char_length(mov_documento)','14');
		$this->db->order_by("split_part(mov_documento,'-',2) desc",null);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function lista_una_transaccion($id){
		$this->db->from('erp_transacciones');
		$this->db->where('trs_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function lista_un_ingreso_secuencial($id){
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_mp p','m.pro_id=p.id');
		$this->db->join('erp_i_cliente c','m.cli_id=c.cli_id');
		$this->db->join('erp_emisor e','m.bod_id=e.emi_id');
		$this->db->join('erp_empresas em','e.emp_id=em.emp_id');
		$this->db->join('erp_transacciones t','m.trs_id=t.trs_id');
		$this->db->where('trs_operacion','0');
		$this->db->where('mov_documento',$id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function lista_detalle_ingreso_secuencial($id){
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_mp p','m.pro_id=p.id');
		$this->db->join('erp_i_cliente c','m.cli_id=c.cli_id');
		$this->db->join('erp_emisor e','m.bod_id=e.emi_id');
		$this->db->join('erp_empresas em','e.emp_id=em.emp_id');
		$this->db->join('erp_transacciones t','m.trs_id=t.trs_id');
		$this->db->where('trs_operacion','0');
		$this->db->where('mov_documento',$id);
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_tipo_documentos($est){
		$this->db->from('erp_tip_documentos t');
		$this->db->join('erp_estados e','t.tdc_estado=e.est_id');
		$this->db->where('tdc_estado',$est);
		$this->db->order_by('tdc_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_doc_duplicado($id,$num,$tip){
		$this->db->from('erp_reg_documentos');
		$this->db->where('cli_id',$id);
		$this->db->where('reg_num_documento',$num);
		$this->db->where('reg_tipo_documento',$tip);
		$this->db->where('reg_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_guia_duplicado($id,$num){
		$this->db->from('erp_reg_guias');
		$this->db->where('cli_id',$id);
		$this->db->where('rgu_num_documento',$num);
		$this->db->where('rgu_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

}

?>