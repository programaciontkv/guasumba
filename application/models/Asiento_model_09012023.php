<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asiento_model extends CI_Model {

	public function lista_asientos(){
		$this->db->order_by('con_asiento');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}

	public function lista_asientos_buscador($emp,$f1,$f2,$txt){
		$query ="select con_asiento, con_concepto, con_fecha_emision, sum(con_valor_debe) as con_valor_debe, sum(con_valor_haber) as con_valor_haber, con_estado, est_descripcion from erp_asientos_contables a, erp_estados e where a.con_estado=e.est_id and (con_asiento like '%$txt%' or con_concepto like '%$txt%') and con_fecha_emision between '$f1' and '$f2' and emp_id=$emp group by con_asiento, con_concepto, con_fecha_emision, con_estado, est_descripcion order by con_fecha_emision, con_asiento";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function ultimo_asiento(){
		$query ="SELECT * FROM erp_asientos_contables where con_asiento like 'AS%' ORDER BY con_asiento DESC LIMIT 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function siguiente_asiento() {
        
            $rst = $this->ultimo_asiento();
            if (!empty($rst)) {
                $sec = (substr($rst->con_asiento, -10) + 1);
                $n_sec = 'AS' . substr($rst->con_asiento, 2, (10 - strlen($sec))) . $sec;
            } else {
                $n_sec = 'AS0000000001';
            }
            return $n_sec;
        
    }

    public function insert($data){
		
		return $this->db->insert("erp_asientos_contables",$data);
			
	}


	public function lista_asientos_modulo($id,$mod){
		$this->db->where('doc_id',$id);
		$this->db->where('mod_id',$mod);
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}


	public function ultimo_asiento_manual(){
		$query ="SELECT * FROM erp_asientos_contables where con_asiento like 'AM%' ORDER BY con_asiento DESC LIMIT 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function siguiente_asiento_manual() {
        
            $rst = $this->ultimo_asiento_manual();
            if (!empty($rst)) {
                $sec = (substr($rst->con_asiento, -10) + 1);
                $n_sec = 'AM' . substr($rst->con_asiento, 2, (10 - strlen($sec))) . $sec;
            } else {
                $n_sec = 'AM0000000001';
            }
            return $n_sec;
        
    }

    public function lista_un_asiento($id){
		$this->db->where('con_asiento',$id);
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->row();
			
	}
	public function lista_un_asiento_cruce($id){
		$this->db->where('con_documento',$id);
		$this->db->where('con_concepto','CRUCE DE CUENTAS');
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->row();
		//echo $this->db->last_query().'<br>';
			
	}
	public function lista_detalle_asiento_cruce($id){
		$this->db->where('con_documento',$id);
		$this->db->where('con_concepto','CRUCE DE CUENTAS');
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
		//echo $this->db->last_query().'<br>';
			
	}

	public function lista_detalle_asiento($id){
		$this->db->where('con_asiento',$id);
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}

	public function delete($id){
		$this->db->where('con_asiento',$id);
		return $this->db->delete("erp_asientos_contables");
			
	}

	public function listar_asientos_debe($as, $cuenta, $id) {
		$query ="select * from erp_asientos_contables where con_concepto_debe='$cuenta' and con_asiento='$as' and con_id='$id'";
        $resultado=$this->db->query($query);
        return $resultado->row();
    }

    public function listar_asientos_haber($as, $cuenta, $id) {
    	$query ="select * from erp_asientos_contables where con_concepto_haber='$cuenta' and con_asiento='$as' and con_id='$id'";
        $resultado=$this->db->query($query);
        return $resultado->row();
        
    }	

    public function asientos_pago($con_documento)
    {
    	$query="select *from erp_asientos_contables where con_documento='$con_documento'";
    	$resultado =$this->db->query($query);
    	return $resultado->result();
			
    }
}

?>