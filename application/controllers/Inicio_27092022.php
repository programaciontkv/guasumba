<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {


	function __construct(){
		
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}

		$this->load->model('inicio_model');
		$this->load->model('menu_model');
		
	}

	public function menus()
	{
		$menu=array(

					'menus' =>  $this->menu_model->lista_opciones_principal('1',$this->session->userdata('s_idusuario')),
					'sbmopciones' =>  '',
					'actual'=>0,
					'actual_sbm'=>0,
					'actual_opc'=>0
				);
		return $menu;
	}
	 

	public function index()
	{
		$data=array(
					'clientes'  =>  $this->inicio_model->count('erp_i_cliente'),
					'productos' =>  $this->inicio_model->count('erp_mp'),
					'facturas'  =>  $this->inicio_model->count('erp_factura'),
				);
		
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('layout/inicio',$data);
		$this->load->view('layout/footer');
	}


}
