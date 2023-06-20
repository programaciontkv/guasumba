<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cierre_caja_model extends CI_Model {


	public function lista_cierre_buscador($text,$f1,$f2,$emp_id,$emi_id){
		$this->db->from('erp_cierres c');
		$this->db->join('erp_vendedor v','cast(c.cie_usuario as integer)=cast(v.vnd_local as integer) ');
		$this->db->join('erp_estados e','c.cie_estado=e.est_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('cie_punto_emision',$emi_id);
		$this->db->where("(cie_secuencial like '%$text%') and cie_fecha between '$f1' and '$f2'", null);
		$this->db->order_by('cie_fecha','desc');
		$this->db->order_by('cie_secuencial','desc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_num_facturas($fec,$emp_id,$emi_id,$vnd_id){
		$query ="select count(*) as n_factura from erp_factura where emp_id=$emp_id and emi_id=$emi_id and vnd_id=$vnd_id and fac_estado!='3' and fac_fecha_emision='$fec'";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}

	public function lista_ultimo_secuencial($emi_id){
		$this->db->from('erp_cierres');
		$this->db->where('cie_punto_emision',$emi_id);
		$this->db->order_by('cie_secuencial','desc');
        $resultado=$this->db->get();
		return $resultado->row();
	}

	public function delete_cierre($fec,$emi_id,$vnd_id){
		$this->db->where('cie_fecha',$fec);
        $this->db->where('cie_usuario',$vnd_id);
        $this->db->where('cie_punto_emision',$emi_id);
		return $this->db->delete("erp_cierres");
	}

	public function lista_cantidad_productos($fec,$emi_id,$vnd_id){
		$query ="select sum(dfc_cantidad) as suma_cantidad, sum(dfc_precio_total) as suma_valor from erp_factura c,erp_det_factura dc 
                                    where dc.fac_id= c.fac_id
                                    and c.fac_estado!='3'
                                    and fac_fecha_emision='$fec' and emi_id= '$emi_id' and vnd_id='$vnd_id'";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}

	public function lista_total_subtotal($fec,$emi_id,$vnd_id){
		$query ="SELECT sum(fac_subtotal) as suma_subtotal, sum(fac_total_descuento) as suma_descuento, sum(fac_total_iva) as suma_iva, sum(fac_total_valor) as suma_total_valor 
                                        from erp_factura
                                        where fac_fecha_emision = '$fec' 
                                        and fac_estado<>'3' 
                                        and emi_id= '$emi_id'
                                        and vnd_id='$vnd_id'";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}

	public function lista_total_notacredito($fec,$emi_id,$vnd_id){
		$query ="select sum(nc.nrc_total_valor) as suma_total_valor_nc from erp_nota_credito nc
                                        where nc.ncr_fecha_emision='$fec'
                                        and nc.ncr_estado_aut!='3'
                                        and nc.emi_id=$emi_id
                                        and exists (select * from erp_factura f where nc.fac_id=f.fac_id and f.vnd_id='$vnd_id' and fac_estado<>'3')";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}

	public function lista_formas_pago($fec,$emi_id,$vnd_id){
		$query ="select(select sum(pg.pag_cant) as tarjeta_credito from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='1' and pg.pag_estado=1 and c.fac_estado !='3' and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  tarjeta_debito from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='2' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  cheque from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp
where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='3' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  efectivo from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='4' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  certificados from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='5' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  transferencia from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='6' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  retencion from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='7' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'), 
(select sum(pg.pag_cant) as  nota_credito from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='8' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id'),
(select sum(pg.pag_cant) as  credito from erp_factura c, erp_pagos_factura pg, erp_formas_pago fp where pg.com_id=c.fac_id and cast(pg.pag_forma as integer)=fp.fpg_id and  fp.fpg_tipo='9' and pg.pag_estado=1 and c.fac_estado !='3'
and c.fac_fecha_emision='$fec' and emi_id = '$emi_id' and vnd_id='$vnd_id')";
        $resultado=$this->db->query($query);
		return $resultado->row();
	}


	public function insert($data){
		
		return $this->db->insert("erp_cierres",$data);
			
	}


	public function lista_un_cierre_secuencial($secuencial){
		$this->db->from('erp_cierres c');
		$this->db->join('erp_users u','cast(c.cie_usuario as integer)=u.usu_id');
		$this->db->join('erp_emisor em','c.cie_punto_emision=em.emi_id');
		$this->db->join('erp_empresas emp','c.emp_id=emp.emp_id');
		$this->db->join('erp_estados e','c.cie_estado=e.est_id');
		$this->db->where('cie_secuencial',$secuencial);
		$resultado=$this->db->get();
		return $resultado->row();
	}


	public function update($id,$data){
		$this->db->where('cie_id',$id);
		return $this->db->update("erp_cierres",$data);
			
	}
}

?>