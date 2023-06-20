<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario_model extends CI_Model {

	public function lista_inventarios_buscador($text,$ids,$f2,$txt ) {
       
        $query ="select pro_id,mp_a,mp_b,tps_nombre,mp_c,mp_d,mp_q,
        (select tps_nombre from erp_tipos t2 where cast(p.mp_b as integer)=t2.tps_id),
		(select sum(mov_cantidad) from erp_i_mov_inv_pt m, erp_transacciones t
		where m.trs_id=t.trs_id and m.mov_fecha_trans <='$f2' and m.pro_id=i.pro_id and t.trs_operacion=0 $txt) as ingresos,
		(select sum(mov_cantidad) from erp_i_mov_inv_pt m, erp_transacciones t
		where m.trs_id=t.trs_id and m.mov_fecha_trans <='$f2' and m.pro_id=i.pro_id and t.trs_operacion=1 $txt) as egresos  
		from erp_i_mov_inv_pt i, erp_mp p, erp_tipos tp
		where i.pro_id=p.id and cast(p.mp_a as integer)=tp.tps_id and mov_fecha_trans <='$f2' and p.ids=$ids and (mp_c like '%$text%' or mp_d like '%$text%') $txt and mp_i='1' and mov_estado='1'
		group by pro_id,mp_a,mp_b,tp.tps_nombre,mp_c,mp_d,mp_q
		order by tp.tps_nombre,mp_b,mp_c,mp_d";
        $resultado=$this->db->query($query);
		return $resultado->result();
    }

	
	public function total_ingreso_egreso_fact($id, $txt) {
       
        $query ="select(SELECT SUM(m.mov_cantidad)as suma FROM erp_i_mov_inv_pt m, erp_transacciones t WHERE m.trs_id=t.trs_id and m.pro_id=$id and t.trs_operacion= 0 $txt) as ingreso,
                                   (SELECT SUM(m.mov_cantidad)as suma FROM erp_i_mov_inv_pt m, erp_transacciones t WHERE m.trs_id=t.trs_id and m.pro_id=$id  and t.trs_operacion= 1 $txt) as egreso";
        $resultado=$this->db->query($query);
		return $resultado->row();
    }
	

    

}

?>