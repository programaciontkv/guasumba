<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_factura_model extends CI_Model {


	public function lista_facturas_empresa($emp_id){
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('erp_estados e','f.reg_estado=e.est_id');
		$this->db->join('erp_tip_documentos t','t.tdc_id=cast(f.reg_tipo_documento as integer)');
		$this->db->where('emp_id',$emp_id);
		$this->db->order_by('reg_femision','desc');
		$this->db->order_by('reg_num_documento','desc');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_factura_buscador($text,$f1,$f2,$emp_id){
		$query ="select f.*,c.*,e.*,t.*, 0 as retencion from erp_reg_documentos f, erp_i_cliente c, erp_estados e, erp_tip_documentos t where f.cli_id=c.cli_id and f.reg_estado=e.est_id and t.tdc_id=cast(f.reg_tipo_documento as integer) and emp_id=$emp_id and (reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and reg_estado=7 
			union
				select f.*,c.*,e.*,t.*, (select reg_id from erp_retencion r where r.reg_id=f.reg_id and (ret_estado=4 or ret_estado=6) limit 1) as retencion from erp_reg_documentos f, erp_i_cliente c, erp_estados e, erp_tip_documentos t where f.cli_id=c.cli_id and f.reg_estado=e.est_id and t.tdc_id=cast(f.reg_tipo_documento as integer) and emp_id=$emp_id and (reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and reg_femision is null and reg_clave_acceso is null and reg_estado!=7
				union 
				select f.*,c.*,e.*,t.*, (select reg_id from erp_retencion r where r.reg_id=f.reg_id and (ret_estado=4 or ret_estado=6) limit 1) as retencion from erp_reg_documentos f, erp_i_cliente c, erp_estados e, erp_tip_documentos t where f.cli_id=c.cli_id and f.reg_estado=e.est_id and t.tdc_id=cast(f.reg_tipo_documento as integer) and emp_id=$emp_id and (reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and reg_femision between '$f1' and '$f2' and reg_clave_acceso is null  and reg_estado!=7 
				order by reg_estado desc, reg_femision desc, reg_num_documento";
        $resultado=$this->db->query($query);
		return $resultado->result();
		// $this->db->from('erp_reg_documentos f');
		// $this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		// $this->db->join('erp_estados e','f.reg_estado=e.est_id');
		// $this->db->join('erp_tip_documentos t','t.tdc_id=cast(f.reg_tipo_documento as integer)');
		// $this->db->where('emp_id',$emp_id);
		// $this->db->where("(reg_num_documento like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and reg_femision between '$f1' and '$f2' and reg_clave_acceso is null", null);
		// $this->db->order_by('reg_estado','desc');
		// $this->db->order_by('reg_femision','desc');
		// $this->db->order_by('reg_num_documento','desc');
		// $resultado=$this->db->get();
		// return $resultado->result();
			
	}

	public function lista_sustento_documentos($est){
		$this->db->from('erp_doc_sustento s');
		$this->db->join('erp_estados e','s.sus_estado=e.est_id');
		$this->db->where('sus_estado',$est);
		$this->db->order_by('sus_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_tipo_documentos($est){
		$this->db->from('erp_tip_documentos t');
		$this->db->join('erp_estados e','t.tdc_estado=e.est_id');
		$this->db->where('tdc_estado',$est);
		$this->db->order_by('tdc_codigo');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_paises($est){
		$this->db->from('erp_paises p');
		$this->db->join('erp_estados e','p.pai_estado=e.est_id');
		$this->db->where('pai_estado',$est);
		$this->db->order_by('pai_descripcion');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_doc_duplicado($id,$num,$tip){
		$this->db->from('erp_reg_documentos');
		$this->db->where('cli_id',$id);
		$this->db->where('reg_num_documento',$num);
		$this->db->where('reg_tipo_documento',$tip);
		$this->db->where('reg_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_productos(){
		$this->db->from('erp_mp p');
		$this->db->where('p.mp_i','1');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_una_factura($id){
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('erp_estados e','f.reg_estado=e.est_id');
		$this->db->join('erp_tip_documentos t','t.tdc_id=cast(f.reg_tipo_documento as integer)');
		$this->db->where('reg_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
		//echo $this->db->last_query();
			
	}

	public function lista_una_factura_emp($id){
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_empresas m','f.emp_id=m.emp_id');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('erp_estados e','f.reg_estado=e.est_id');
		$this->db->join('erp_tip_documentos t','t.tdc_id=cast(f.reg_tipo_documento as integer)');
		$this->db->where('reg_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
		//echo $this->db->last_query();
	}

	public function lista_factura_numero($id,$emp){
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('erp_estados e','f.reg_estado=e.est_id');
		$this->db->join('erp_tip_documentos t','t.tdc_id=cast(f.reg_tipo_documento as integer)');
		$this->db->where('f.emp_id',$emp);
		$this->db->where('f.reg_num_documento',$id);
		$this->db->where('f.reg_estado != 3',null);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_factura_numero_registradas($id,$emp){
		$this->db->from('erp_reg_documentos f');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->join('erp_estados e','f.reg_estado=e.est_id');
		$this->db->join('erp_tip_documentos t','t.tdc_id=cast(f.reg_tipo_documento as integer)');
		$this->db->where('f.emp_id',$emp);
		$this->db->where('f.reg_num_documento',$id);
		$this->db->where('f.reg_estado =4',null);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}



	public function lista_detalle_factura($id){
		$this->db->from('erp_reg_det_documentos d');
		$this->db->join('erp_mp p','d.pro_id=p.id');
		$this->db->where('reg_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_pagos_factura($id){
		$this->db->from('erp_pagos_documentos p');
		$this->db->where('reg_id',$id);
		$this->db->where('pag_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		$this->db->insert("erp_reg_documentos",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_reg_det_documentos",$data);
	}

	public function insert_pagos($data){
		return $this->db->insert("erp_pagos_documentos",$data);
	}

	public function lista_un_producto($id){
		$this->db->select("p.*,tmp.*,(select tps_nombre from erp_tipos where tps_id=cast(mp_b as integer)) as tip_nombre, c.cat_descripcion,e.est_descripcion");
		$this->db->from('erp_mp p');
		$this->db->join('erp_tipos tmp','tmp.tps_id=cast(p.mp_a as integer)');
		$this->db->join('erp_categorias c','c.cat_id=cast(tmp.tps_tipo as integer)');
		$this->db->join('erp_estados e','e.est_id=cast(p.mp_i as integer)');
		$this->db->where('id',$id);
		$this->db->order_by('c.cat_descripcion');
		$this->db->order_by('p.mp_c');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('reg_id',$id);
		return $this->db->update("erp_reg_documentos",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_reg_documentos");
			
	}

	public function delete_detalle($id){
		$this->db->where('reg_id',$id);
		return $this->db->delete("erp_reg_det_documentos");
			
	}

	public function update_pagos($id,$data){
		$this->db->where('reg_id',$id);
		return $this->db->update("erp_pagos_documentos",$data);
			
	}

	public function lista_nota_credito_factura($id){
		$this->db->from('erp_registro_nota_credito');
		$this->db->where('reg_id',$id);
		$this->db->where('rnc_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_nota_debito_factura($id){
		$this->db->from('erp_registro_nota_debito');
		$this->db->where('reg_id',$id);
		$this->db->where('rnd_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_categorias(){
		$this->db->from('erp_categorias');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_una_categoria($id){
		$this->db->from('erp_categorias');
		$this->db->where('cat_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_sum_cuentas($id){
		$query ="select pln_id,reg_codigo_cta, sum(det_total) as dtot, sum(det_descuento_moneda) as ddesc  from erp_reg_det_documentos where reg_id=$id group by pln_id,reg_codigo_cta";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_retencion_factura($id){
		$this->db->from('erp_retencion');
		$this->db->where('reg_id',$id);
		$this->db->where('ret_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_cuentas_factura($id){
		$query ="select * from erp_reg_det_documentos where reg_id=$id and pln_id=0";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function delete_pagos($id){
		$this->db->where('reg_id',$id);
		return $this->db->delete("erp_pagos_documentos");
			
	}

	public function lista_factura_numero_cedula($num,$id,$emp){
		$this->db->from('erp_reg_documentos f');
		$this->db->where('f.emp_id',$emp);
		$this->db->where('f.reg_num_documento',$num);
		$this->db->where('f.reg_ruc_cliente',$id);
		$this->db->where('f.reg_estado != 3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function insert_cliente($data){
		
		$this->db->insert("erp_i_cliente",$data);
		return $this->db->insert_id();
			
	}

	
	public function lista_ultima_cta_prod($id){
		$query ="select * from erp_reg_det_documentos where pro_id=$id and pln_id!=0 order by det_id desc limit 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_una_orden_compra($id){
		$this->db->select("oc.*,e.est_descripcion, c.cli_raz_social, c.cli_ced_ruc, c.cli_telefono, emp_logo, emp_nombre" );
		$this->db->from('erp_i_enc_orden_compra_mp oc');
		$this->db->join('erp_i_cliente c','c.cli_id=oc.cli_id');
		$this->db->join('erp_estados e','e.est_id=oc.orc_estado');
		$this->db->join('erp_empresas em','em.emp_id=oc.emp_id');
		$this->db->where("orc_codigo", $id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function suma_ingreso($id){
		$this->db->select("sum(mov_val_tot) as valor" );
		$this->db->from('erp_i_mov_inv_pt');
		$this->db->where("mov_documento", $id);
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function suma_guia($id){
		$this->db->select("sum(drg_cantidad) as valor" );
		$this->db->from('erp_reg_det_guias d');
		$this->db->join('erp_reg_guias g','g.rgu_id=d.rgu_id');
		$this->db->where("rgu_secuencia_unif", $id);
		$resultado=$this->db->get();
		return $resultado->row();
	}
}

?>