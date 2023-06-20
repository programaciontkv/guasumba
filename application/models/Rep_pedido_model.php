<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_pedido_model extends CI_Model {


	public function lista_pedido_buscador($f1,$f2,$emi,$txt){
		
		$query ="select p.ped_id, ped_femision, ped_num_registro, ped_nom_cliente, ped_sbt , ped_sbt12, ped_sbt0, ped_sbt_noiva, ped_sbt_excento, ped_tdescuento, ped_iva12, ped_total, f.fac_id, fac_fecha_emision, fac_numero, fac_subtotal, fac_subtotal12, fac_subtotal0, fac_subtotal_ex_iva, fac_subtotal_no_iva, fac_total_descuento, fac_total_iva, fac_total_valor from erp_reg_pedido_venta p, erp_factura f where p.ped_id=f.ped_id and ped_estado!=3 and fac_estado!=3 and (ped_num_registro like '%$txt%' or ped_nom_cliente like '%$txt%' or fac_numero like '%$txt%') and ped_femision between '$f1' and '$f2' and p.ped_local=$emi
			union 
			select p.ped_id, ped_femision, ped_num_registro, ped_nom_cliente, ped_sbt , ped_sbt12, ped_sbt0, ped_sbt_noiva, ped_sbt_excento, ped_tdescuento, ped_iva12, ped_total, '0', '1990-01-01', '', '0', '0', '0', '0', '0','0','0','0' from erp_reg_pedido_venta p where ped_estado!=3  and (ped_num_registro like '%$txt%' or ped_nom_cliente like '%$txt%') and ped_femision between '$f1' and '$f2' and p.ped_local=$emi and not exists(select * from erp_factura f where f.ped_id=p.ped_id)
			order by ped_femision,ped_num_registro
		";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

    
}

?>