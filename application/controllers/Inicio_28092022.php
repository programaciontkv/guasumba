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
		$fecha_actual = date("Y-m-d");
   		$fecha=date("Y-m-d",strtotime($fecha_actual."- 3 month")); 
   		$p=$this->inicio_model->datos_pro($fecha);
   		$etiquetas =array();
   		$cantidad =array();
   		$tventa="";
   		$mes="";


   		foreach($p as $pro){

   			array_push($etiquetas,$pro->mp_d);
   			array_push($cantidad,$pro->nu);
   		}
   		

   		$datos=array(
   			'cantidad_p'=>$cantidad,
   			'etiquetas_p'=>$etiquetas,
   		);

   		$f1 = date("Y").'-01-01';
   		$f2 = date("Y").'-12-31';
   		$v=$this->inicio_model->datos_ventas($f1,$f2);
   		$ventas=array();
   		$meses=array();


   		foreach ($v as $ve){
   			switch ($ve->fech) {
		        case '01':
		        	$mes   = 'Enero';
		        	$tventa= $ve->total;
		        break;
		        case '02':
			        $mes = 'Febrero';
			        $tventa= $ve->total;
		        break;
		        case '03':
			        $mes = 'Marzo';
			        $tventa= $ve->total;
		        break;
		        case '04':
		        	$mes = 'Abril';
		        	$tventa= $ve->total;
		        break;
		        case '05':
			        $mes = 'Mayo';
			        $tventa= $ve->total;
		        break;
		        case '06':
			        $mes = 'Junio';
			        $tventa= $ve->total;
		        break;
		        case '07':
			        $mes = 'Julio';
			        $tventa= $ve->total;
		        break;
		        case '08':
			        $mes = 'Agosto';
			        $tventa= $ve->total;
		        break;
		        case '09':
			        $mes = 'Septiembre';
			        $tventa= $ve->total;
		        break;
		        case '10':
			        $mes = 'Octubre';
			        $tventa= $ve->total;
		        break;
		        case '11':
			        $mes = 'Noviembre';
			        $tventa= $ve->total;
		        break;
		        case '12':
			        $mes = 'Diciembre';
			        $tventa= $ve->total;
		        break;
		        }

		        array_push($ventas,$tventa);
		        array_push($meses,$mes);
   		}

   		$datos2=array(
   			'totales'  =>$ventas,
   			'meses'     =>$meses,
   		);
		
   		

		$data=array(
					'clientes'   =>  $this->inicio_model->count('erp_i_cliente'),
					'productos'  =>  $this->inicio_model->count('erp_mp'),
					'facturas'   =>  $this->inicio_model->count('erp_factura'),
					'productos_g'=>  $datos,
					'ventas_g'   =>  $datos2

				);
		
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('layout/inicio',$data);
		$this->load->view('layout/footer');
	}


}
