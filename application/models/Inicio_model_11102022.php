<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {

	public function count($table){
		$this->db->select('count(*) as contador');
		$resultado=$this->db->get($table);
		return $resultado->row();
			
	}

	public function datos_pro($fecha){

		// select count(pro_id) as n ,p.* from erp_det_factura f, erp_mp p,erp_factura ef where f.pro_id =p.id and ef.fac_fecha_emision >= '2022-09-20'     group by p.mp_d,p.id order by n desc 

		$this->db->select("count(pro_id) as nu ,p.* from erp_det_factura f, erp_mp p,erp_factura ef ");
		$this->db->where("f.pro_id = p.id and f.fac_id=ef.fac_id and ef.fac_fecha_emision >= '$fecha' and p.ids='26' group by p.mp_d,p.id order by nu desc limit 5 ",null);
		$resultado=$this->db->get();
		return $resultado->result();


	}

	   

    public function datos_ventas($f1,$f2){

    // 	 SELECT to_char(ef.fac_fecha_emision ,'mm') AS fech, SUM(ef.fac_total_valor) as total
    // FROM erp_factura ef  
    // WHERE ef.fac_fecha_emision  BETWEEN '2022-01-01' AND '2022-12-31' 
    // GROUP BY fech
		$this->db->select("to_char(ef.fac_fecha_emision ,'TMMonth') AS fech, SUM(ef.fac_total_valor) as total");
		$this->db->from("erp_factura ef ");
		$this->db->where("ef.fac_fecha_emision  BETWEEN '$f1' AND '$f2' GROUP BY fech order by fech asc",null);
		$resultado=$this->db->get();
		return $resultado->result();


    
    }
	
}

?>