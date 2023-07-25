<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_comisiones_model extends CI_Model {


	public function lista_documentos_buscador($txt){
		

// SELECT f.fac_id,fac_numero,fac_nombre,fac_fecha_emision,fac_total_iva, f.vnd_id,vnd_nombre
// FROM erp_factura f, erp_vendedor v,erp_ctasxcobrar c ,erp_cheques ch  
// where c.chq_id=ch.chq_id and f.vnd_id=v.vnd_id and c.com_id=f.fac_id and cta_estado=0 
// and (char_length(trim(f.fac_estado_aut))=0 or f.fac_estado_aut is null or f.fac_estado_aut ='RECIBIDA AUTORIZADO') and cta_forma_pago<>'RETENCION' and cta_forma_pago<>'NOTA DE CREDITO' $txt
// group by f.fac_id,fac_numero,fac_nombre,fac_fecha_emision,fac_total_iva, f.vnd_id,vnd_nombre
// order by vnd_nombre,fac_numero

        $this->db->select('f.fac_id,fac_numero,fac_nombre,fac_fecha_emision,fac_total_iva, f.vnd_id,vnd_nombre');
        $this->db->from('erp_factura f, erp_vendedor v,erp_ctasxcobrar c ,erp_cheques ch');
        $this->db->where("c.chq_id=ch.chq_id and f.vnd_id=v.vnd_id and c.com_id=f.fac_id  and cta_estado=1 
        and (char_length(trim(f.fac_estado_aut))=0 or f.fac_estado_aut is null or f.fac_estado ='6') and  cta_forma_pago <> '7' and cta_forma_pago <> '8' $txt");
        $this->db->group_by(array('f.fac_id' , 'f.fac_numero' , 'f.fac_nombre' , 'f.fac_fecha_emision' , 'f.fac_total_iva' , 'f.vnd_id' ,'vnd_nombre'));
        $this->db->order_by(' vnd_nombre,fac_numero');
        $resultado=$this->db->get();
        return $resultado->result();
        //echo $this->db->last_query();
	}

	public function lista_periodos($tipo)
    {
        $this->db->from('erp_periodoscv');
        $this->db->where('pcv_estado', 1);
        $this->db->where('pcv_tipo',"$tipo");
        $this->db->order_by('pcv_desde','asc');
        $resultado=$this->db->get();
        return $resultado->result();
    }

    
    public function suma_ctasxcobrar_fechas($txt,$id)
    {
        $this->db->select('sum(cta_monto) as cta_monto ');
        $this->db->from('erp_ctasxcobrar');
        $this->db->where("com_id='$id'  and cta_estado=1 and  cta_forma_pago <> '7' and cta_forma_pago <> '8' $txt", null);
        $resultado=$this->db->get();
		return $resultado->row();
        //echo $this->db->last_query();

    }

    


	
	
    
}

?>