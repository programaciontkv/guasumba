<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingreso_model extends CI_Model {

	public function lista_ingresos(){
		$this->db->select("m.*,e.est_descripcion, c.cli_raz_social, t.trs_descripcion, p.id, p.mp_a,p.mp_b,p.mp_c,p.mp_d,p.mp_q" );
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_transacciones t','t.trs_id=m.trs_id');
		$this->db->join('erp_mp p','p.id=m.pro_id');
		$this->db->join('erp_estados e','e.est_id=m.mov_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=m.cli_id');
		$this->db->where('m.trs_id','28');
		$this->db->where('mp_i','1');
		$this->db->where('mov_estado','1');
		$this->db->order_by('m.mov_fecha_trans','desc');
		$this->db->order_by('m.mov_documento','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_ingresos_bodegas($txt,$bsq){
		$this->db->select("m.*,e.est_descripcion, c.cli_raz_social, t.trs_descripcion, p.id, p.mp_a,p.mp_b,p.mp_c,p.mp_d,p.mp_q" );
		$this->db->from('erp_i_mov_inv_pt m');
		$this->db->join('erp_transacciones t','t.trs_id=m.trs_id');
		$this->db->join('erp_mp p','p.id=m.pro_id');
		$this->db->join('erp_estados e','e.est_id=m.mov_estado');
		$this->db->join('erp_i_cliente c','c.cli_id=m.cli_id');
		$this->db->where($txt,$bsq);
		$this->db->where('m.trs_id','28');
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
		$this->db->where("(mov_documento like '%$text%' or cli_raz_social like '%$text%' or mp_c like '%$text%' or mp_d like '%$text%') and ids='$ids' and mov_fecha_trans between '$f1' and '$f2' and m.trs_id=28 $txt and mp_i='1' and mov_estado=1", null);
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

}

?>