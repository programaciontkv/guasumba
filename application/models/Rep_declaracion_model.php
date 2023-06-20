<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_declaracion_model extends CI_Model {


	public function lista_facturas_buscador($txt,$fec1,$fec2,$emp_id){
		$query ="select f.emp_id, f.fac_id, fac_fecha_emision, fac_numero, fac_nombre, fac_identificacion, fac_subtotal, fac_subtotal12, fac_subtotal0, fac_subtotal_ex_iva, fac_subtotal_no_iva,fac_total_descuento, fac_total_iva, fac_total_valor, '1990-01-01', '', '0', '0', '0', '0', '0', '0', '0', '0' from erp_factura f where (fac_estado=4 or fac_estado=6) and fac_fecha_emision between '$fec1' and '$fec2' and f.emp_id=$emp_id and (fac_numero like '%$txt%' or fac_nombre like '%$txt%' or fac_identificacion like '%$txt%')
			order by fac_fecha_emision, fac_numero";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_notas_factura($id, $fec1, $fec2){
		$this->db->from('erp_nota_credito n');
		$this->db->where("fac_id=$id and ncr_estado!=3 and ncr_fecha_emision between '$fec1' and '$fec2'",null);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_retenciones_factura($id, $fec1, $fec2){
		$this->db->from('erp_registro_retencion r');
		$this->db->where("fac_id=$id and rgr_estado!=3 and rgr_fecha_emision between '$fec1' and '$fec2'",null);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	

	public function lista_detalle_retenciones_factura($id,$fec1,$fec2){
		$this->db->from('erp_det_reg_retencion d');
		$this->db->join('erp_registro_retencion r','d.rgr_id=r.rgr_id');
		$this->db->join('porcentages_retencion p','d.por_id=p.por_id');
		$this->db->where("rgr_fecha_emision between '$fec1' and '$fec2' and rgr_estado!=3",null);
		$this->db->where('r.fac_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_notcre_periodo($txt,$f1,$f2,$emp_id){
		$query ="SELECT nc.*,fac_fecha_emision,fac_numero,fac_nombre,fac_identificacion,fac_subtotal12,fac_subtotal0,fac_subtotal_ex_iva,fac_subtotal_no_iva,fac_total_descuento,fac_total_iva,fac_total_propina,fac_total_valor,fac_subtotal FROM erp_nota_credito nc, erp_factura f where nc.fac_id=f.fac_id and ncr_denominacion_comprobante=1 and  not exists(select * from erp_factura f2 where fac_fecha_emision between '$f1' and '$f2' and  (fac_estado=6 or fac_estado=4) and nc.fac_id=f2.fac_id and f.emp_id=$emp_id) and (fac_numero like '%$txt%' or fac_nombre like '%$txt%' or fac_identificacion like '%$txt%') and f.emp_id=$emp_id and ncr_fecha_emision between '$f1' and '$f2' and (ncr_estado=6 or ncr_estado=4) 
                union 
                SELECT nc.*,ncr_fecha_emision,ncr_num_comp_modifica,ncr_nombre,ncr_identificacion,0,0,0,0,0,0,0,0,0 FROM erp_nota_credito nc where ncr_denominacion_comprobante=1 and fac_id=0 and (ncr_numero like '%$txt%' or ncr_nombre like '%$txt%' or ncr_identificacion like '%$txt%')and nc.emp_id=$emp_id and ncr_fecha_emision between '$f1' and '$f2' and (ncr_estado=6 or ncr_estado=4) 
             order by ncr_numero";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_retencion_periodo($txt,$f1,$f2,$emp) {
		$query ="SELECT r.*,fac_fecha_emision,fac_numero,fac_nombre,fac_identificacion,fac_subtotal12,fac_subtotal0,fac_subtotal_ex_iva,fac_subtotal_no_iva,fac_total_descuento,fac_total_iva,fac_total_propina,fac_total_valor,fac_subtotal FROM erp_registro_retencion r, erp_factura f where r.fac_id=f.fac_id  $txt and  not exists(select * from erp_factura f2 where fac_fecha_emision between '$f1' and '$f2' and  (fac_estado=4 or fac_estado=6) and r.fac_id=f2.fac_id and f2.emp_id=$emp) and r.emp_id=$emp and rgr_fecha_emision between '$f1' and '$f2' and rgr_estado!=3";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_retencion_factura($fac, $den,$f1,$f2) {
        $query ="SELECT * FROM erp_registro_retencion where rgr_num_comp_retiene='$fac' and rgr_denominacion_comp=$den and rgr_estado!=3 and rgr_fecha_emision between '$f1' and '$f2'";
        $resultado=$this->db->query($query);
		return $resultado->row();
      
    }

    public function lista_det_ret($ret,$f1,$f2) {
        $query ="select (select sum(drr_valor) from erp_det_reg_retencion d, erp_registro_retencion r where r.rgr_id =d.rgr_id and fac_id=$ret  and drr_tipo_impuesto='IV' and rgr_fecha_emision between '$f1' and '$f2' and rgr_estado!=3) as iva, 
                        (select sum(drr_valor) from erp_det_reg_retencion d, erp_registro_retencion r where r.rgr_id =d.rgr_id and fac_id=$ret  and drr_tipo_impuesto='IR' and rgr_fecha_emision between '$f1' and '$f2' and rgr_estado!=3) as renta";
        $resultado=$this->db->query($query);
		return $resultado->row();
        
    }

    public function lista_det_retenciones($ret,$f1,$f2) {
        $query ="select * from erp_det_reg_retencion d, erp_registro_retencion r where d.rgr_id=r.rgr_id and r.fac_id=$ret and rgr_estado!=3 and rgr_fecha_emision between '$f1' and '$f2' ";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_registros_factura($txt,$f1,$f2,$emp) {
        $query ="select * from  erp_reg_documentos f, erp_i_cliente c where c.cli_id=f.cli_id and (f.reg_num_registro like '%$txt%' or f.reg_num_documento like '%$txt%' or f.reg_ruc_cliente like '%$txt%') and reg_estado!=3 and reg_tipo_documento>'0' and reg_tipo_documento != '99' and f.reg_femision between '$f1' and '$f2' and emp_id=$emp order by f.reg_femision,f.reg_num_registro";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_notcre_factura2($id,$f1,$f2) {
        $query ="select * from erp_registro_nota_credito where reg_id='$id' and rnc_estado!=3 and rnc_fecha_emision between '$f1' and '$f2'";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_retencion_reg_factura($id,$f1,$f2) {
        $query ="select * from erp_retencion where reg_id=$id and (ret_estado='4' or ret_estado=6) and ret_fecha_emision between '$f1' and '$f2'";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_det_retencion($id, $f1, $f2) {
        $query ="select * from erp_det_retencion d, erp_retencion r where r.ret_id= d.ret_id and r.reg_id=$id and ret_fecha_emision between '$f1' and '$f2' and (ret_estado=4 or ret_estado=6)";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_notcre_periodo2($txt,$f1,$f2,$emp) {
        $query ="SELECT * FROM erp_registro_nota_credito nc, erp_reg_documentos f, erp_i_cliente c where nc.reg_id=f.reg_id and f.cli_id=c.cli_id and nc.emp_id=$emp and rnc_fecha_emision between '$f1' and '$f2' and rnc_estado!=3 and reg_tipo_documento != '99'  and reg_tipo_documento != '44' and (reg_num_documento like '%$txt%' or cli_raz_social like '%$txt%' or cli_ced_ruc like '%$txt%') and not exists(select * from erp_reg_documentos f2 where reg_femision between '$f1' and '$f2' and reg_estado!=3 and nc.reg_id=f2.reg_id)";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_retencion_factura2($id,$f1,$f2) {
        $query ="select * from erp_retencion where reg_id=$id and (ret_estado=4 or ret_estado=6) and ret_fecha_emision between '$f1' and '$f2'";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }


    public function lista_det_retenciones2($id,$f1,$f2) {
        $query ="select * from erp_det_retencion d, erp_retencion  r where d.ret_id=r.ret_id  and r.reg_id=$id and (ret_estado=6 or ret_estado=4) and ret_fecha_emision between '$f1' and '$f2'";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_retencion_periodo2($txt,$f1,$f2,$emp) {
        $query ="SELECT r.*,reg_femision,reg_num_documento,cli_raz_social,cli_ced_ruc,reg_sbt12,reg_sbt0,reg_sbt_excento,reg_sbt_noiva,reg_tdescuento,reg_iva12,reg_propina,reg_total,reg_sbt FROM erp_retencion r, erp_reg_documentos f, erp_i_cliente c where c.cli_id=r.cli_id and r.reg_id=f.reg_id  and (reg_num_documento like '%$txt%' or cli_raz_social like '%$txt%' or cli_ced_ruc like '%$txt%') and ret_fecha_emision between '$f1' and '$f2' and (ret_estado=4 OR  ret_estado=6) and reg_tipo_documento != '99'  and reg_tipo_documento != '44' and  not exists( select * from erp_reg_documentos f2 where reg_femision between '$f1' and '$f2' and  reg_estado<>3 and r.reg_id=f2.reg_id)";
        $resultado=$this->db->query($query);
		return $resultado->result();
        
    }

    public function lista_det_retenciones_agrup($txt,$f1,$f2,$emp) {
		$query ="SELECT drr_procentaje_retencion, drr_tipo_impuesto, sum(drr_valor) as drr_valor from erp_det_reg_retencion d, erp_registro_retencion  r, erp_factura f where  f.fac_id=r.fac_id and d.rgr_id=r.rgr_id  and rgr_estado!=3 and rgr_fecha_emision between '$f1' and '$f2' and (fac_estado=4 or  fac_estado=6)  and f.emp_id=$emp and (fac_numero like '%$txt%' or fac_nombre like '%$txt%' or fac_identificacion like '%$txt%') 
                group by drr_procentaje_retencion,drr_tipo_impuesto order by drr_tipo_impuesto,drr_procentaje_retencion";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_det_retenciones_agrup2($txt,$f1,$f2,$emp) {
		$query ="SELECT dtr_codigo_impuesto,dtr_procentaje_retencion, dtr_tipo_impuesto,sum(dtr_valor) as dtr_valor from erp_det_retencion d, erp_retencion  r, erp_reg_documentos f where f.reg_id=r.reg_id and d.ret_id=r.ret_id  and (ret_estado=4 or ret_estado=6) and r.emp_id=$emp
                and r.ret_fecha_emision between '$f1' and '$f2'
                 group by dtr_codigo_impuesto,dtr_procentaje_retencion,dtr_tipo_impuesto order by dtr_tipo_impuesto,dtr_codigo_impuesto,dtr_procentaje_retencion";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	
}

?>