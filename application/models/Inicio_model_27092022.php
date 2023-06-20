<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {

	public function count($table){
		$this->db->select('count(*) as contador');
		$resultado=$this->db->get($table);
		return $resultado->row();
			
	}
	
}

?>