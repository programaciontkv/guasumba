<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {


	public function ingresar($usuario,$clave){
		
		$this->db->where('usu_login',$usuario);
		$this->db->where('usu_pass',md5($clave));
		$this->db->where('usu_estado','1'); ///estado activo
		$resultado=$this->db->get('erp_users');

		if($resultado->num_rows()>0){
			return $resultado->row();
		}else{
			return false;
		}
	}

	public function insert_sesion($data){
		 
		return $this->db->insert("erp_sesion",$data);
	}

	public function lista_intentos($id,$f){
       $query="select * from erp_sesion where ses_usuario='$id' and cast (ses_fecha ||' '|| ses_hora as timestamp) >= cast('$f' as timestamp)";
       $resultado=$this->db->query($query);
		return $resultado->num_rows();
      
    }

    public function update_usuario($id,$data){
		$this->db->where('usu_login',$id);
		return $this->db->update("erp_users",$data);
			
	}
}
?>