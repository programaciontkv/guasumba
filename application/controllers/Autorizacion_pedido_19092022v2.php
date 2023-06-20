<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autorizacion_pedido extends CI_Controller {

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
		$this->load->model('empresa_model');
		$this->load->model('emisor_model');
		$this->load->model('factura_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('forma_pago_model');	
		$this->load->model('ctasxcobrar_model');			
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
		$this->load->library('html2pdf');
		$this->load->library('export_excel');
		$this->load->model('pedido_model');

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
		
		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$est= $this->input->post('estado');	
			if($est!=""){
				$txt_est="and ped_estado=$est and tipo_cliente=0";
			}else{
				$txt_est="and tipo_cliente=0";
			}	
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$txt_est);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$est="and tipo_cliente=0";
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador_2($text,$f1,$f2,$rst_cja->emp_id,$est);
		}

			$data=array(
						'permisos'=>$this->permisos,
						'pedidos'=>$cns_pedidos,
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'txt'=>$text,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('autorizacion_pedido/lista',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer',$modulo);
		
	}


	
	public function editar($id,$opc_id){
		if($this->permisos->rop_actualizar){
			$rst=$this->pedido_model->lista_un_pedido($id);
			$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			if($permisos->rop_actualizar){

			
		


			///detalle pedido
			
			$cns_det=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			$inven=$this->configuracion_model->lista_una_configuracion('3');
			$cns_dt=$this->pedido_model->lista_un_pedido_detalle($id);
			foreach ($cns_dt as $rst_dt) {

				if ($inven->con_valor == 0) {
					 if ($ctrl_inv->con_valor == 0) {
		                   	$rst_emp=$this->emisor_model->lista_un_emisor($rst->ped_local);
	                    	$fra = "and emp_id=$rst_emp->emp_id";
		                } else {
		                    $fra = "and m.bod_id=$rst->ped_local";
		                }
					$rst1 =$this->factura_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                
		            }else{
		            	$inv=0;
		            	
		            }
		        }else{
		        	$inv=0;
		        }  
	        
				$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'det_descripcion'=>$rst_dt->det_descripcion,
						'det_cod_producto'=>$rst_dt->det_cod_producto,
						'det_vunit'=>$rst_dt->det_vunit,
						'det_impuesto'=>$rst_dt->det_impuesto,
						'det_descuento_porcentaje'=>$rst_dt->det_descuento_porcentaje,
						'det_descuento_moneda'=>$rst_dt->det_descuento_moneda,
						'det_unidad'=>$rst_dt->det_unidad,
						'inventario'=>$inv,
						'det_cantidad'=>$rst_dt->det_cantidad,
						'det_val_ice'=>$rst_dt->det_val_ice,
						'det_p_ice'=>$rst_dt->det_p_ice,
						'det_cod_ice'=>$rst_dt->det_cod_ice,
						'det_total'=>$rst_dt->det_total,
						);	
				
				array_push($cns_det, $dt_det);
			}
			
			///pagos pedido
			$cns_pg=$this->pedido_model->lista_pagos_pedidos($id);
			$cns_pag=array();

			$lista="<option value='0'>SELECCIONE</option>";
			foreach ($cns_pg as $rst_pg) {
				$rst_fp=$this->forma_pago_model->lista_una_forma_pago_id($rst_pg->pag_forma);

				if(!empty($rst_fp)){
					$cns_bt=$this->bancos_tarjetas_model->lista_bancos_tarjetas_plazo('2',$rst_fp->fpg_tipo,'1');
					if(!empty($cns_bt)){
						foreach ($cns_bt as $rst_plz) {

							$lista.="<option value='$rst_plz->btr_id'>$rst_plz->btr_descripcion</option>";

						}
					}
				}
				

				$dt_pg= (object) array(
							'pag_forma'=>$rst_pg->pag_forma,
							'fpg_descripcion'=>$rst_pg->fpg_descripcion,
							'pag_tipo'=>$rst_pg->fpg_tipo,
							'chq_numero'=>$rst_pg->chq_numero,
							'pag_id_chq'=>$rst_pg->pag_id_chq,
							'pag_dias'=>$rst_pg->pag_dias,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
							'contado'=>$lista,
								);
				array_push($cns_pag, $dt_pg);
		}
		

			$data=array(
						'opc_id'=>$opc_id,
						'locales'=>$this->emisor_model->lista_emisores_empresa_estado($rst_cja->emp_id,'1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'cns_productos'=>$this->factura_model->lista_productos('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'pedido'=> $this->pedido_model->lista_un_pedido($id),
						'titulo'=>'Autorizacion de Pedidos de Venta',
						'action'=>base_url().'autorizacion_pedido/actualizar/'.$opc_id,
						'mod'=>1,
						'aut'=>1
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pedido/form',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
		}	
	}

	public function actualizar($id,$opc_id,$est){
			$rst=$this->pedido_model->lista_un_pedido($id);
			$data=array(
		    			'ped_estado'=>$est, 
		    );

			$data_audito=array(
		    			'ped_id'=>$id, 
		    			'ped_num_registro'=>$rst->ped_num_registro,
		    			'ped_estado'=>$est, 

		    );




		    if($this->pedido_model->update($id,$data)){

		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'AUTORIZACION PEDIDO DE VENTA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst->ped_num_registro,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'pedido/editar/'.$id.'/'.$opc_id);
			}
		
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'pedido'=>$this->pedido_model->lista_un_pedido($id)
						);
			$this->load->view('pedido/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->pedido_model->delete($id)){
			
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PEDIDOS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				echo 'pedido';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	
	public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente($id);
		if(!empty($rst)){
			$data=array(
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_parroquia'=>$rst->cli_parroquia,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_canton'=>$rst->cli_canton,
						'cli_email'=>$rst->cli_email,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_pais'=>$rst->cli_pais,
						'tipo_cliente'=>$rst->tipo_cliente,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function load_producto($id,$inven,$ctr_inv,$emi){

		$rst=$this->producto_comercial_model->lista_un_producto_cod($id);
		if(empty($rst)){
			$rst=$this->producto_comercial_model->lista_un_producto($id);
		}
		if(!empty($rst)){
			if ($inven == 0) {
				 if ($ctr_inv == 0) {
				 	$rst_emp=$this->emisor_model->lista_un_emisor($emi);
	                    $fra = "and emp_id=$rst_emp->emp_id";
	                } else {
	                    $fra = "and m.bod_id=$emi";
	                }
				$rst1 =$this->factura_model->total_ingreso_egreso_fact($rst->id,$fra); 
				if(!empty($rst1)){
	                $inv = $rst1->ingreso - $rst1->egreso;
	            }else{
	            	$inv=0;
	            }
	        }else{
	        	$inv=0;
	        }    
	        
	        $precio=$rst->mp_e;	
	        
			$data=array(
						'pro_id'=>$rst->id,
						'ids'=>$rst->ids,
						'pro_descripcion'=>$rst->mp_d,
						'pro_codigo'=>$rst->mp_c,
						'pro_precio'=>$precio,
						'pro_iva'=>$rst->mp_h,
						'pro_descuento'=>$rst->mp_g,
						'pro_unidad'=>$rst->mp_q,
						'inventario'=>$inv,
						'ice_p'=>$rst->mp_j,
						'ice_cod'=>$rst->mp_l,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	public function traer_emisor($id){
		$rst=$this->emisor_model->lista_un_emisor($id);
		if(!empty($rst)){
			$data=array(
						'emi_cod_cli'=>$rst->emi_cod_cli,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

    public function show_frame($id,$opc_id){
    	$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Pedidos',
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"pedido/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer',$modulo);
		}
    	


    }

    public function show_pdf($id,$opc_id){
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			///pagos pedido
			$cns_pg=$this->pedido_model->lista_un_pedido_pagos($id);
			$cns_pag=array();
			foreach ($cns_pg as $rst_pg) {
				$dt_pg= (object) array(
							'pag_porcentage'=>$rst_pg->pag_porcentage,
							'pag_dias'=>$rst_pg->pag_dias,
								);
				array_push($cns_pag, $dt_pg);
			}


			///detalle pedido
			
			$cns_det=array();
			$cns_dt=$this->pedido_model->lista_un_pedido_detalle($id);
			foreach ($cns_dt as $rst_dt) {
				$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'det_descripcion'=>$rst_dt->det_descripcion,
						'det_cod_producto'=>$rst_dt->det_cod_producto,
						'det_vunit'=>$rst_dt->det_vunit,
						'det_impuesto'=>$rst_dt->det_impuesto,
						'det_descuento_porcentaje'=>$rst_dt->det_descuento_porcentaje,
						'det_descuento_moneda'=>$rst_dt->det_descuento_moneda,
						'det_unidad'=>$rst_dt->det_unidad,
						'det_cantidad'=>$rst_dt->det_cantidad,
						'det_val_ice'=>$rst_dt->det_val_ice,
						'det_p_ice'=>$rst_dt->det_p_ice,
						'det_cod_ice'=>$rst_dt->det_cod_ice,
						'det_total'=>$rst_dt->det_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>'Pedido',
						'pedido'=> $this->pedido_model->lista_un_pedido($id),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						);			
			$this->html2pdf->filename('pedido.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_pedido', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    	
    }

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Autorizacion de Pedidos de Venta';
    	$file="autorizacion_pedidos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }
    

    
    
}
