<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_desglose_nc_model extends CI_Model {


	public function lista_notas_buscador($f1,$f2,$emi,$txt){
		
		$query ="select ncr_id, ncr_fecha_emision, ncr_numero, ncr_nombre, nrc_total_valor, f.fac_id, fac_fecha_emision, fac_numero, fac_total_valor from erp_nota_credito n, erp_factura f where n.fac_id=f.fac_id and ncr_estado!=3 and fac_estado!=3 and (ncr_numero like '%$txt%' or ncr_nombre like '%$txt%' or fac_numero like '%$txt%'  ) and ncr_fecha_emision between '$f1' and '$f2' and n.emi_id=$emi
			union 
			select ncr_id, ncr_fecha_emision, ncr_numero, ncr_nombre, nrc_total_valor,0, '1990-01-01', '', '0'from erp_nota_credito n where fac_id=0 and ncr_estado!=3 and (ncr_numero like '%$txt%' or ncr_nombre like '%$txt%') and ncr_fecha_emision between '$f1' and '$f2' and n.emi_id=$emi
			order by ncr_fecha_emision, ncr_numero
		";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

    
}

?>