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
		$this->load->model('empresa_model');
		
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
   		$v  = $this->inicio_model->datos_ventas($f1,$f2);
   		$ventas=array();
   		 $meses=array();
   		$tventas=0;
   		$tventa=0;

		$meses = array('Enero', 'Febrero', 'Marzo','Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$ventas = array('0', '0', '0','0', '0', '0', '0', '0', '0', '0', '0', '0');


   		// foreach ($meses as $ve){
		for ($i = 0; $i < 12; ++$i){
			$tventa=0;

if( isset($v[$i]->fech )){

	// if($meses[$i] == $v[$i]->fech )
	// {	
	// 	$tventa = $v[$i]->total;
	// }else{
	// 	$tventa = 0;
	// }

   			switch ($v[$i]->fech) {
		        case 'Enero':
		        	$indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Febrero':
		        	$indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Marzo':
		        	$indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Abril':
		        	$indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Mayo':
		        	$indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Junio':
			       
			       $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Julio':
			       
			       $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Agosto':
			       
			       $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Septiembre':
			        
			       $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Octubre':
			       
			      $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Noviembre':
			        
			        $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        case 'Diciembre':
			       
			       $indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        break;
		        default:
       				$indice = array_search($v[$i]->fech,$meses,false);
				   $ventas[$indice]= $v[$i]->total;
		        }
		   
		        $tventas+=$v[$i]->total;

		        //array_push($ventas,$tventa);
		        //array_push($meses,$mes);
   		}else{
   			 array_push($ventas,0);
   		}
   	}

   		$datos2=array(
   			'totales'  =>$ventas,
   			'meses'     =>$meses,
   			'total_g'   =>$tventas,
   		);
		
   		

		$data=array(
					'clientes'   =>  $this->inicio_model->count('erp_i_cliente'),
					'productos'  =>  $this->inicio_model->count('erp_mp'),
					'facturas'   =>  $this->inicio_model->count('erp_factura'),
					'productos_g'=>  $datos,
					'ventas_g'   =>  $datos2

				);
		
		// $emp = $this->empresa_model->lista_una_empresa(1);
			
		// $data2=array(
		// 			'menus'   =>  $this->menus(),
		// 			'nombre'     => $emp->emp_nombre
		// 		);
		
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('layout/inicio',$data);
		$this->load->view('layout/footer');
	}


}
