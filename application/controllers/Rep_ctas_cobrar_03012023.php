<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ctas_cobrar extends CI_Controller {

	private $permisos;

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->library('form_validation');
		$this->load->model('emisor_model');
		$this->load->model('rep_ctas_cobrar_model');
		$this->load->model('cliente_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('configuracion_model');
		$this->load->model('empresa_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->library('export_excel');
		$this->load->library('html2pdf');
		$this->load->model('credito_dias_model');
	}

	public function _remap($method, $params = array()){
    
	    if(!method_exists($this, $method))
	      {
	       $this->index($method, $params);
	    }else{
	      return call_user_func_array(array($this, $method), $params);
	    }
  	}


	public function menus()
	{
		$menu=array(
					'menus' =>  $this->menu_model->lista_opciones_principal('1',$this->session->userdata('s_idusuario')),
					'sbmopciones' =>  $this->menu_model->lista_opciones_submenu('1',$this->session->userdata('s_idusuario'),$this->permisos->sbm_id),
					'actual'=>$this->permisos->men_id,
					'actual_sbm'=>$this->permisos->sbm_id,
					'actual_opc'=>$this->permisos->opc_id
				);
		return $menu;
	}
	

	public function index($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);		
		$mensaje='';
		 $leyenda1='Por vencer';
	    $leyenda2='Vencido';
	    $txt='';
		$we =  intval($this->session->userdata('s_we'));
			if($we>=760){
				$mensaje='';
			}else{
				$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			}

		///buscador 
		if($_POST){
			$mensaje='';
			$text= $this->input->post('txt');
			$txt=$text;
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$vencer= $this->input->post('vencer');
			$vencido= $this->input->post('vencido');
			if (!empty($text)) {
			$text = "where fac_identificacion like '%$text%' or fac_nombre like '%$text%' or fac_numero like '%$text%'";
			}
			$facturas=$this->rep_ctas_cobrar_model->lista_documentos_buscador($text);
			$creditos = $this->credito_dias_model->lista_credito_dias_estado(1);
			$cns_ctas=array();
			$n=0;
			$tot_ven=0;
			$leyenda2='';
			$leyenda1='';
			if($vencer=='on'){
				$vencer= 'checked';
			    $vencido= '';
			    $leyenda2='Por vencer';
			    $leyenda1='Vencido';

			    foreach($facturas as $fac){
					$fec_act = date("Y-m-d");
					$dt_f= (object) array(
									'fac_nombre'        =>$fac->fac_nombre,
									'fac_identificacion'=>$fac->fac_identificacion,
									'fac_total_valor'   =>$fac->fac_total_valor,
									'fac_numero'        =>$fac->fac_numero,
									'fac_fecha_emision' =>$fac->fac_fecha_emision,
									'total_g'           =>0,
									'total_vencer'      =>0,
										);
					$n=0;
					if (!empty($creditos)) {
						foreach($creditos as $credi){

						$n++;
						$v=0;
						$tot_ven=0;
		                   $fec_ant   = date("Y-m-d", strtotime("$fec_act +$credi->cre_dias day")); // vencido 30
		                   $ven          = 'ven'.$n;
		                   $t_ve         = 'total'.$n;
		                   $v            = $this->rep_ctas_cobrar_model->lista_pagos_vencer($fac->cli_id, $fec_act, $fec_ant, $fac->fac_id);
		                   $dt_f->pag_fecha_v=$fac->fac_fecha_emision;
		                   

		                  if(!empty ($v)){		                  	
		                  	$tot_ven      =   $v->fac_total_valor -($v->pago);
		                  	$dt_f->pag_fecha_v   = $v->pag_fecha_v ;
		                  }
		                    $dt_f->$t_ve   = $tot_ven ;
		                    $dt_f->total_g+= $tot_ven ; 
		                    $fec_act = $fec_ant;           
					}


		               $fec_ant   = date("Y-m-d", strtotime("$fec_act +$credi->cre_dias day")); // vencido 30
		               $ven          = 'ven'.$n;
		               $t_ve         = 'total'.($n+1);;
		               $v            = $this->rep_ctas_cobrar_model->lista_pag_porvencer($fac->cli_id, $fec_ant, $fac->fac_id);
		               $dt_f->pag_fecha_v=$fac->fac_fecha_emision;
		              if(!empty ($v)){
		              	$tot_ven      =   $v->fac_total_valor -($v->pago);
		              	$dt_f->pag_fecha_v   = $v->pag_fecha_v ;
		              }

		              	$fec_act = date("Y-m-d");
		                $dt_f->$t_ve   = $tot_ven ;
		                $tot_ven=0;
		                $por_vencer = $this->rep_ctas_cobrar_model->lista_pagos_vencidos_mdias($fac->cli_id, $fec_act, $fac->fac_id);
		                 if(!empty ($por_vencer)){
		                $tot_por_vencer = $por_vencer->fac_total_valor -($por_vencer->pago);
		                $dt_f->total_vencer+= $tot_por_vencer ; 
		                $dt_f->total_g+=$tot_por_vencer;
		            }

					array_push($cns_ctas, $dt_f);
					}

					


			}

			//var_dump($cns_ctas);



			}elseif ($vencido=='on') {
				$vencer= '';
			    $vencido= 'checked';
			    $leyenda1='Por vencer';
			    $leyenda2='Vencido';

			    foreach($facturas as $fac){
					$fec_act = date("Y-m-d");
					$dt_f= (object) array(
									'fac_nombre'        =>$fac->fac_nombre,
									'fac_identificacion'=>$fac->fac_identificacion,
									'fac_total_valor'   =>$fac->fac_total_valor,
									'fac_numero'        =>$fac->fac_numero,
									'fac_fecha_emision' =>$fac->fac_fecha_emision,
									'total_g'           =>0,
									'total_vencer'      =>0,
										);
					$n=0;
					if (!empty($creditos)) {
						foreach($creditos as $credi){
						$n++;
						$v=0;
						$tot_ven=0;
		                   $fec_ant   = date("Y-m-d", strtotime("$fec_act -$credi->cre_dias day")); // vencido 30
		                   $ven          = 'ven'.$n;
		                   $t_ve         = 'total'.$n;
		                   $v            = $this->rep_ctas_cobrar_model->lista_pagos_vencidos($fac->cli_id, $fec_ant, $fec_act, $fac->fac_id);
		                   $dt_f->pag_fecha_v=$fac->fac_fecha_emision;
		                  if(!empty ($v)){
		                  	$tot_ven      =   $v->fac_total_valor -($v->pago);
		                  	$dt_f->pag_fecha_v   = $v->pag_fecha_v ;
		                  }
		                    $dt_f->$t_ve   = $tot_ven ;
		                    $dt_f->total_g+= $tot_ven ; 
		                    $tot_ven=0;
		                    $fec_act = $fec_ant;           
					}
					   $tot_ven=0;
		               $fec_ant   = date("Y-m-d", strtotime("$fec_act -$credi->cre_dias day")); // vencido 30
		               $ven          = 'ven'.$n;
		               $t_ve         = 'total'.($n+1);;
		               $v            = $this->rep_ctas_cobrar_model->lista_pagos_vencidos_mdias($fac->cli_id, $fec_ant, $fac->fac_id);
		               $dt_f->pag_fecha_v=$fac->fac_fecha_emision;
		              if(!empty ($v)){

		              	$tot_ven      =   $v->fac_total_valor -($v->pago);
		              	$dt_f->pag_fecha_v   = $v->pag_fecha_v ;
		              }
		                $dt_f->$t_ve   = $tot_ven ;
		                $tot_ven=0;
					/////
		                $fec_act = date("Y-m-d");
		                $por_vencer = $this->rep_ctas_cobrar_model->lista_pag_porvencer($fac->cli_id, $fec_act, $fac->fac_id);
		                 if(!empty ($por_vencer)){
		                $tot_por_vencer = $por_vencer->fac_total_valor -($por_vencer->pago);
		                //$dt_f->total_vencer+= $tot_ven ; 
		                $dt_f->total_vencer+= $tot_por_vencer ; 
		                $dt_f->total_g+=$tot_por_vencer;
		            }

					array_push($cns_ctas, $dt_f);
					
					}

					


			}
			}
										
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');		
			//$facturas=$this->rep_ctas_cobrar_model->lista_documentos_buscador($text);					
			$facturas='';					
			$cns_ctas='';					
			$vencer= '';;
			$vencido= 'checked';
		}
		
	
		$data=array(
					'permisos'=>$this->permisos,
					'leyenda1'=>$leyenda1,
					'leyenda2'=>$leyenda2,
					'creditos'=>$this->credito_dias_model->lista_credito_dias_estado(1),
					'mensaje'=> $mensaje,
					'documentos'=>$cns_ctas,
					'opc_id'=>$rst_opc->opc_id,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$txt,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'vencer'=>$vencer,
					'vencido'=>$vencido,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('rep_ctas_cobrar/lista',$data);
		$modulo=array('modulo'=>'rep_ctas_cobrar');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Inventario ';
    	$file="inventario".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel3($data,$file,$titulo,$fec1,$fec2);
    }


    public function reporte($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Inventario '.ucfirst(strtolower($rst_cja->emi_nombre));
    	$file="inventario".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel3($data,$file,$titulo,$fec1,$fec2);
    }

    public function show_frame($opc_id){
    	$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		///buscador 
		
			$text= $this->input->post('txt2');
			$ids= '26';
			$f2= $this->input->post('fecha');
			$fam= $this->input->post('familia2');
			$tip= $this->input->post('tip2');	
			$det= $this->input->post('detalle2');

			if($fam=='on'){
				$fam=1;
			}else{
				$fam=0;
			}
			if($tip=='on'){
				$tip=1;
			}else{
				$tip=0;
			}
			if($det=='on'){
				$det=1;
			}else{
				$det=0;
			}
			
			
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Inventario ',
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"inventario/show_pdf/$opc_id/$ids/$f2/$fam/$tip/$det/$text",
				);

			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'inventario');
			$this->load->view('layout/footer',$modulo);

		}
    	
    }

    
    public function show_pdf($opc_id,$ids,$f2,$fam,$tip,$det,$text=''){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emisor=$this->emisor_model->lista_un_emisor($rst_cja->emi_id);

    		$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}
			$cns_mov=$this->inventario_model->lista_inventarios_buscador($text,$ids,$f2,$txt);	
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inventarios'=>$cns_mov,
						'fam'=>$fam,
						'tip'=>$tip,
						'det'=>$det,
						'fecha'=>$f2,
						'empresa'=>$emisor,
						);					
			$this->load->view('pdf/pdf_inventario', $data);
			// $this->html2pdf->filename('inventario.pdf');
			// $this->html2pdf->paper('a4', 'landscape');
   //  		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_inventario', $data, true)));
			// $this->html2pdf->output(array("Attachment" => 0));	
			
		
    }


}
