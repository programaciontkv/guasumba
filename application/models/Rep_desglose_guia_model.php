<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_desglose_guia_model extends CI_Model {


	public function lista_guias_buscador($f1,$f2,$emi,$txt){
		
		$query ="select g.gui_id, gui_fecha_emision, gui_numero, gui_nombre, gui_destino, t.tra_razon_social, tra_placa, f.fac_id, fac_fecha_emision, fac_numero, dtg_cantidad, mp_c, mp_d, mp_q  from erp_guia_remision g, erp_det_guia d, erp_mp mp, erp_factura f, erp_transportista t where g.fac_id=f.fac_id and g.gui_id =d.gui_id and mp.id=d.pro_id and g.tra_id=t.tra_id and gui_estado!=3 and fac_estado!=3 and (gui_numero like '%$txt%' or gui_nombre like '%$txt%' or fac_numero like '%$txt%' or dtg_codigo like '%$txt%' or dtg_codigo like '%$txt%'  ) and gui_fecha_emision between '$f1' and '$f2' and g.emi_id=$emi
			union 
			select g.gui_id, gui_fecha_emision, gui_numero, gui_nombre, gui_destino, t.tra_razon_social, tra_placa, '0', '1990-01-01', '', dtg_cantidad, mp_c, mp_d, mp_q  from erp_guia_remision g, erp_det_guia d, erp_mp mp, erp_transportista t where g.fac_id=0 and g.gui_id =d.gui_id and mp.id=d.pro_id and g.tra_id=t.tra_id and gui_estado!=3 and (gui_numero like '%$txt%' or gui_nombre like '%$txt%' or dtg_codigo like '%$txt%' or dtg_codigo like '%$txt%') and gui_fecha_emision between '$f1' and '$f2' and g.emi_id=$emi
			order by gui_fecha_emision, gui_numero
		";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

    
}

?>