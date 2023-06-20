<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ctas_cobrar_model extends CI_Model {

	

	
	public function lista_documentos_buscador($txt) {
       
        //$query ="SELECT * FROM erp_factura c $txt  where c.cli_id='35494'   order by c.fac_nombre,c.fac_identificacion,c.fac_numero limit 7 ";
      $query ="SELECT * FROM erp_factura c $txt   order by c.fac_nombre,c.fac_identificacion,c.fac_numero  ";
        $resultado=$this->db->query($query);
		return $resultado->result();
		//echo $this->db->last_query();
    }
    public function lista_pagos_vencidos($id, $fini, $ffin,$fac_id) {
    	//$query = "select * from pagos where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' and fac_id='$fac_id' order by fac_numero";
    	$query = "select * from pagosxfactura where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' and fac_id='$fac_id' order by fac_numero";
    	$resultado=$this->db->query($query);
		return $resultado->row();
		//echo $this->db->last_query();
    }
    public function lista_pagos_vencer($id, $fec1, $fec2,$fac_id) {
    	//$query = "select * from pagos where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' and fac_id='$fac_id' order by fac_numero";
    	$query = "select * from pagosxfactura where cli_id=$id and pag_fecha_v >'$fec1' and pag_fecha_v <= '$fec2' and fac_id='$fac_id' order by fac_numero";
    	
    	$resultado=$this->db->query($query);
		return $resultado->row();
		//echo $this->db->last_query().'<br>';
    }
     public function lista_pagos_porvencer($id, $fini, $ffin) {
    	$query = "select * from pagos where cli_id=$id and pag_fecha_v between '$fini' and '$ffin' order by fac_numero";
    	$resultado=$this->db->query($query);
		return $resultado->row();
		//echo $this->db->last_query();
    }
    	public function lista_pagos_vencidos_mdias($id, $fec,$fac_id){

        $query ="select * from pagosxfactura where cli_id=$id and pag_fecha_v<='$fec' and fac_id='$fac_id' order by pag_fecha_v,fac_numero ";
        $resultado=$this->db->query($query);
		return $resultado->row();
		//echo $this->db->last_query().'<br>';

		
    }



     public function lista_pag_porvencer($id, $fec,$fac_id){
        
        $query ="select * from pagosxfactura where cli_id=$id and pag_fecha_v>'$fec' and fac_id='$fac_id' ORDER BY pag_fecha_v ASC";
        $resultado=$this->db->query($query);
		return $resultado->row();


    }



    

}

?>