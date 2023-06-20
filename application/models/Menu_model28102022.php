<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

	public function lista_menus(){
		$this->db->select('m.*,e.est_descripcion'); 
		$this->db->from('erp_menus m'); 
		$this->db->join('erp_estados e', 'e.est_id=m.men_estado'); 
		$this->db->order_by('m.men_nombre', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function insert($data){
		
		return $this->db->insert("erp_menus",$data);
			
	}

	public function lista_un_menu($id){
		$this->db->where('men_id',$id);
		$this->db->order_by('men_nombre', 'asc'); 
		$resultado=$this->db->get('erp_menus');
		return $resultado->row();
			
	}

	public function lista_menus_estado($estado){
		$this->db->where('men_estado',$estado);
		$this->db->order_by('men_nombre', 'asc'); 
		$resultado=$this->db->get('erp_menus');
		return $resultado->result();
			
	}

	public function lista_menus_principal($estado,$usu){
		$this->db->select('u.usu_id, m.men_id, m.men_nombre, m.men_orden');
		$this->db->from('erp_roles_opcion ro');
		$this->db->join('erp_menus m', 'm.men_id=ro.men_id');
		$this->db->join('erp_roles r', 'r.rol_id=ro.rol_id');
		$this->db->join('erp_users u', 'u.rol_id=r.rol_id');
		$this->db->where('u.usu_id',$usu);
		$this->db->where('m.men_estado',$estado);
		$this->db->group_by('u.usu_id'); 
		$this->db->group_by('m.men_id'); 
		$this->db->group_by('m.men_nombre'); 
		$this->db->group_by('m.men_orden'); 
		$this->db->order_by('m.men_orden', 'asc'); 
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_opciones_principal($estado,$usu){
		// $this->db->select('o.opc_nombre,o.opc_direccion, m.men_id,m.men_nombre,o.opc_caja,o.opc_id');
		// $this->db->from('erp_roles_opcion ro');
		// $this->db->join('erp_roles r', 'r.rol_id=ro.rol_id');
		// $this->db->join('erp_users u', 'u.rol_id=r.rol_id');
		// $this->db->join('erp_menus m', 'm.men_id=ro.men_id');
		// $this->db->join('erp_opciones o', 'o.opc_id=ro.opc_id');
		// $this->db->where('u.usu_id',$usu);
		// $this->db->where('m.men_estado',$estado);
		// $this->db->where('o.opc_estado',$estado);
		// $this->db->order_by('m.men_orden', 'asc'); 
		// $this->db->order_by('m.men_nombre', 'asc'); 
		// $this->db->order_by('o.opc_orden', 'asc'); 
		$query ="select u.usu_id,usu_login, r.rol_id,rol_nombre, m.men_id,men_nombre, s.sbm_id,sbm_nombre, 
(select o.opc_id from erp_opciones o, erp_roles_opcion ro2 where ro2.opc_id=o.opc_id and ro2.rol_id=r.rol_id and ro2.men_id=m.men_id and ro2.sbm_id=s.sbm_id  order by o.opc_orden limit 1) as opc_id,
(select o.opc_direccion from erp_opciones o, erp_roles_opcion ro2 where ro2.opc_id=o.opc_id and ro2.rol_id=r.rol_id and ro2.men_id=m.men_id and ro2.sbm_id=s.sbm_id  order by o.opc_orden limit 1) as opc_direccion
from erp_roles_opcion ro, erp_roles r, erp_users u,erp_menus m,erp_submenus s
where r.rol_id=ro.rol_id and u.rol_id=r.rol_id and m.men_id=ro.men_id and ro.sbm_id=s.sbm_id and u.usu_id='$usu' and m.men_estado=$estado and sbm_estado=$estado
group by 
u.usu_id,usu_login, r.rol_id, rol_nombre,m.men_id, men_nombre, s.sbm_id,sbm_nombre
order by m.men_orden, s.sbm_orden";

		// $resultado=$this->db->get();
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_una_opcion($opc,$rol){
		$this->db->select('o.opc_nombre,o.opc_direccion, m.men_id,m.men_nombre,o.opc_caja,o.opc_id');
		$this->db->from('erp_roles_opcion ro');
		$this->db->join('erp_roles r', 'r.rol_id=ro.rol_id');
		$this->db->join('erp_users u', 'u.rol_id=r.rol_id');
		$this->db->join('erp_menus m', 'm.men_id=ro.men_id');
		$this->db->join('erp_opciones o', 'o.opc_id=ro.opc_id');
		$this->db->where('ro.opc_id',$opc);
		$this->db->where('ro.rol_id',$rol);
		$resultado=$this->db->get();

		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('men_id',$id);
		return $this->db->update("erp_menus",$data);
			
	}

	public function delete($id){
		$this->db->where('men_id',$id);
		return $this->db->delete("erp_menus");
			
	}

	public function lista_opciones_submenu($estado,$usu,$sbm){
		
		$query ="select u.usu_id,usu_login, r.rol_id,rol_nombre, m.men_id,men_nombre, s.sbm_id,sbm_nombre, o.opc_id, o.opc_nombre, o.opc_direccion, o.opc_orden
			from erp_roles_opcion ro, erp_roles r, erp_users u,erp_menus m,erp_submenus s, erp_opciones o
			where r.rol_id=ro.rol_id and u.rol_id=r.rol_id and m.men_id=ro.men_id and ro.sbm_id=s.sbm_id and ro.opc_id=o.opc_id and 
			u.usu_id='$usu' and m.men_estado=$estado and sbm_estado=$estado and s.sbm_id=$sbm and opc_estado=$estado
			group by 
			u.usu_id,usu_login, r.rol_id, rol_nombre,m.men_id, men_nombre, s.sbm_id,sbm_nombre, o.opc_id, o.opc_nombre, o.opc_direccion, o.opc_orden
			order by m.men_orden, s.sbm_orden, o.opc_orden";

		// $resultado=$this->db->get();
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}
}

?>