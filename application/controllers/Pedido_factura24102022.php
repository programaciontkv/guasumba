<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_factura extends CI_Controller {

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
		$this->load->model('pedido_model');
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
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");

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
		$txt_est="and (ped_estado=13 or ped_estado=15) and tipo_cliente=0";
		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
				
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$txt_est);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$txt_est);
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
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pedido_factura/lista',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		if($permisos->rop_insertar){

			$usu_id=$this->session->userdata('s_idusuario');
			// $rst_vnd=$this->vendedor_model->lista_un_vendedor($usu_id);
			
			// if(empty($rst_vnd)){
			// 	$vnd='';
			// }else{
			// 	$vnd=$rst_vnd->vnd_id;
			// }
			$rst=$this->pedido_model->lista_un_pedido($id);
			$vnd=$rst->ped_vendedor;
			
			///recupera detalle
			$cns_dt=$this->pedido_model->lista_un_pedido_detalle($id);
			$cns_det=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			$inven=$this->configuracion_model->lista_una_configuracion('3');
			// $emi=$rst->emi_id;
			foreach ($cns_dt as $rst_dt) {
				if ($inven->con_valor == 0) {
					 if ($ctrl_inv->con_valor == 0) {
		                   	$rst_emp=$this->emisor_model->lista_un_emisor($rst_cja->emi_id);
	                    	$fra = "and emp_id=$rst_emp->emp_id";
		                } else {
		                    $fra = "and m.bod_id=$rst_cja->emi_id";
		                }
					$rst1 =$this->factura_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                $rst2 = $this->factura_model->lista_costos_mov($rst_dt->pro_id,$fra); 
		                if(!empty($rst2)){
		                	$cnt_inv=$rst2->ingreso - $rst2->egreso;
		                	$prec_inv=$rst2->icnt - $rst2->ecnt;
		                	if($cnt_inv==0 || $prec_inv==0){
		                		$cost_unit=0;
		                	}else{
		                		$cost_unit = (($cnt_inv) / ($prec_inv));
		                	}
		                }else{
		                	$cost_unit =0;
		                }
		            }else{
		            	$inv=0;
		            	$cost_unit =0;
		            }
		        }else{
		        	$inv=0;
		            $cost_unit =0;

		        }  
	        $rst_fact=$this->pedido_model->lista_facturado_producto($id,$rst_dt->pro_id);
	        if(!empty($rst_fact->facturado)){
	        	$entr=$rst_fact->facturado;
	        }else{
	        	$entr=0;
	        }

	        $cnt=$rst_dt->det_cantidad-$entr;
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->det_descripcion,
						'pro_codigo'=>$rst_dt->det_cod_producto,
						'pro_precio'=>$rst_dt->det_vunit,
						'pro_iva'=>$rst_dt->det_impuesto,
						'pro_descuento'=>$rst_dt->det_descuento_porcentaje,
						'pro_descuent'=>$rst_dt->det_descuento_moneda,
						'pro_unidad'=>$rst_dt->mp_q,
						'inventario'=>$inv,
						'solicitado'=>$rst_dt->det_cantidad,
						'entregado'=>$entr,
						'cantidad'=>$cnt,
						'cost_unit'=>$cost_unit,
						'ice'=>$rst_dt->det_val_ice,
						'ice_p'=>$rst_dt->det_p_ice,
						'ice_cod'=>$rst_dt->det_cod_ice,
						'precio_tot'=>$rst_dt->det_total,
						);	
				
				array_push($cns_det, $dt_det);
			}
////pagos del pedido
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
							'pag_id_chq'=>0,
							'pag_dias'=>$rst_pg->pag_dias,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
							'contado'=>$lista,
								);
				array_push($cns_pag, $dt_pg);
		}

		
			$data=array(
				        'cns_pag'=>$cns_pag,
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						// 'cns_productos'=>$this->factura_model->lista_productos('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						// 'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'factura'=> (object) array(
											'fac_fecha_emision'=>date('Y-m-d'),
											'fac_numero'=>'',
					                        'cli_id'=>$rst->cli_id,
					                        'vnd_id'=>$vnd,
					                        'fac_identificacion'=>$rst->ped_ruc_cc_cliente,
					                        'fac_nombre'=>$rst->ped_nom_cliente,
					                        'fac_direccion'=>$rst->ped_dir_cliente,
					                        'fac_telefono'=>$rst->ped_tel_cliente,
					                        'fac_email'=>$rst->ped_email_cliente,
					                        'cli_parroquia'=>$rst->ped_parroquia_cliente,
					                        'cli_canton'=>$rst->ped_ciu_cliente,
					                        'cli_pais'=>$rst->ped_pais_cliente,
					                        'fac_id'=>'',
					                        'fac_observaciones'=>$rst->ped_observacion,
					                        'fac_subtotal12'=>$rst->ped_sbt12,
					                        'fac_subtotal0'=>$rst->ped_sbt0,
					                        'fac_subtotal_ex_iva'=>$rst->ped_sbt_excento,
					                        'fac_subtotal_no_iva'=>$rst->ped_sbt_noiva,
					                        'fac_subtotal'=>$rst->ped_sbt,
					                        'fac_total_descuento'=>$rst->ped_tdescuento,
					                        'fac_total_ice'=>$rst->ped_ice,
					                        'fac_total_iva'=>$rst->ped_iva12,
					                        'fac_total_propina'=>$rst->ped_propina,
					                        'fac_total_valor'=>$rst->ped_total,
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'ped_id'=>$rst->ped_id,
					                        'pag_cantidad1'=>$rst->ped_total,
					                        
										),
						'cns_det'=>$cns_det,
						'action'=>base_url().'pedido_factura/guardar/'.$opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());

			$we =  intval($this->session->userdata('s_we'));
			if($we>=760){
				$this->load->view('pedido_factura/form',$data);
			}else{
				$this->load->view('pedido_factura/form_movil',$data);
			}
			//$this->load->view('pedido_factura/form',$data);
			$modulo=array('modulo'=>'pedido_factura');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		$fac_fecha_emision = $this->input->post('fac_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		//$cli_parroquia = $this->input->post('cli_parroquia');
		$cli_ciudad = $this->input->post('cli_ciudad');
		$cli_pais = $this->input->post('cli_pais');
		$observacion = $this->input->post('observacion');
		$subtotal12 = $this->input->post('subtotal12');
		$subtotal0 = $this->input->post('subtotal0');
		$subtotalex = $this->input->post('subtotalex');
		$subtotalno = $this->input->post('subtotalno');
		$subtotal = $this->input->post('subtotal');
		$total_descuento = $this->input->post('total_descuento');
		$total_ice = $this->input->post('total_ice');
		$total_iva = $this->input->post('total_iva');
		$total_propina = $this->input->post('total_propina');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$ped_id = $this->input->post('ped_id');
		$count_det=$this->input->post('count_detalle');
		$count_pag=$this->input->post('count_pagos');
		
		$this->form_validation->set_rules('fac_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		//$this->form_validation->set_rules('cli_parroquia','Parroquia','required');
		$this->form_validation->set_rules('cli_ciudad','Ciudad','required');
		$this->form_validation->set_rules('cli_pais','Pais','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){

			///secuencial de Factura
			$rst_pto = $this->emisor_model->lista_un_emisor($emi_id);
			if ($rst_pto->emi_cod_punto_emision > 99) {
			    $ems = $rst_pto->emi_cod_punto_emision;
			} else if ($rst_pto->emi_cod_punto_emision < 100 && $rst_pto->emi_cod_punto_emision > 9) {
			    $ems = '0' .$rst_pto->emi_cod_punto_emision;
			} else {
			    $ems = '00' . $rst_pto->emi_cod_punto_emision;
			}

			$rst_cja = $this->caja_model->lista_una_caja($cja_id);
			if ($rst_cja->cja_codigo > 99) {
			    $caja = $rst_cja->cja_codigo;
			} else if ($rst_cja->cja_codigo < 100 && $rst_cja->cja_codigo > 9) {
			    $caja = '0' .$rst_cja->cja_codigo;
			} else {
			    $caja = '00' . $rst_cja->cja_codigo;
			}

			
			$rst_sec = $this->factura_model->lista_secuencial_documento($emi_id,$cja_id);
		    if (empty($rst_sec)) {
		        $sec = $rst_cja->cja_sec_factura;
		    } else {
		    	$sc=explode('-',$rst_sec->fac_numero);
		        $sec = ($sc[2] + 1);
		    }
		    if ($sec >= 0 && $sec < 10) {
		        $tx = '00000000';
		    } else if ($sec >= 10 && $sec < 100) {
		        $tx = '0000000';
		    } else if ($sec >= 100 && $sec < 1000) {
		        $tx = '000000';
		    } else if ($sec >= 1000 && $sec < 10000) {
		        $tx = '00000';
		    } else if ($sec >= 10000 && $sec < 100000) {
		        $tx = '0000';
		    } else if ($sec >= 100000 && $sec < 1000000) {
		        $tx = '000';
		    } else if ($sec >= 1000000 && $sec < 10000000) {
		        $tx = '00';
		    } else if ($sec >= 10000000 && $sec < 100000000) {
		        $tx = '0';
		    } else if ($sec >= 100000000 && $sec < 1000000000) {
		        $tx = '';
		    }
		    $fac_numero = $ems . '-'.$caja.'-' . $tx . $sec;

		    $clave_acceso=$this->clave_acceso($cja_id,$fac_numero,$fac_fecha_emision);

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
		    				'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'ped_id'=>$ped_id,
							'fac_fecha_emision'=>$fac_fecha_emision,
							'fac_numero'=>$fac_numero, 
							'fac_nombre'=>$nombre, 
							'fac_identificacion'=>$identificacion, 
							'fac_email'=>$email_cliente, 
							'fac_direccion'=>$direccion_cliente, 
							'fac_subtotal12'=>$subtotal12, 
							'fac_subtotal0'=>$subtotal0, 
							'fac_subtotal_ex_iva'=>$subtotalex, 
							'fac_subtotal_no_iva'=>$subtotalno, 
							'fac_total_descuento'=>$total_descuento, 
							'fac_total_ice'=>$total_ice, 
							'fac_total_iva'=>$total_iva, 
							'fac_total_propina'=>$total_propina,
							'fac_telefono'=>$telefono_cliente,
							'fac_observaciones'=>$observacion,
							'fac_total_valor'=>$total_valor,
							'fac_subtotal'=>$subtotal,
							'fac_clave_acceso'=>$clave_acceso,
							'fac_estado'=>'4'
		    );


			// if($this->factura_model->insert($data)){
		    $fac_id=$this->factura_model->insert($data);
		    if(!empty($fac_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("pro_aux$n")!=''){
		    			$pro_id = $this->input->post("pro_aux$n");
		    			$dfc_codigo = $this->input->post("pro_descripcion$n");
		    			$dfc_cod_aux = $this->input->post("pro_descripcion$n");
		    			$dfc_cantidad = $this->input->post("cantidad$n");
		    			$dfc_descripcion = $this->input->post("pro_referencia$n");
		    			$dfc_precio_unit = $this->input->post("pro_precio$n");
		    			$dfc_porcentaje_descuento = $this->input->post("descuento$n");
		    			$dfc_val_descuento = $this->input->post("descuent$n");
		    			$dfc_precio_total = $this->input->post("valor_total$n");
		    			$dfc_iva = $this->input->post("iva$n");
		    			$dfc_ice = $this->input->post("ice$n");
		    			$dfc_p_ice = $this->input->post("ice_p$n");
		    			$dfc_cod_ice = $this->input->post("ice_cod$n");
		    			if($dfc_cantidad>0){
		    				$dt_det=array(
		    							'fac_id'=>$fac_id,
	                                    'pro_id'=>$pro_id,
	                                    'dfc_codigo'=>$dfc_codigo,
	                                    'dfc_cod_aux'=>$dfc_cod_aux,
	                                    'dfc_cantidad'=>$dfc_cantidad,
	                                    'dfc_descripcion'=>$dfc_descripcion,
	                                    'dfc_precio_unit'=>$dfc_precio_unit,
	                                    'dfc_porcentaje_descuento'=>$dfc_porcentaje_descuento,
	                                    'dfc_val_descuento'=>$dfc_val_descuento,
	                                    'dfc_precio_total'=>$dfc_precio_total,
	                                    'dfc_iva'=>$dfc_iva,
	                                    'dfc_ice'=>$dfc_ice, 
	                                    'dfc_p_ice'=>$dfc_p_ice, 
	                                    'dfc_cod_ice'=>$dfc_cod_ice,
		    						);
		    			$this->factura_model->insert_detalle($dt_det);
		    			}
		    			
		    		}
		    	}

		    	$m=0;
		    	while($m<$count_pag){
		    		$m++;
		    		if($this->input->post("pag_descripcion$m")!=''){
                        $pag_tipo = $this->input->post("pag_tipo$m");
		    			$pag_forma = $this->input->post("pag_forma$m");
		    			$pag_cant = $this->input->post("pag_cantidad$m");
		    			$pag_plazo = $this->input->post("pag_plazo$m");
		    			$pag_banco = $this->input->post("pag_banco$m");
		    			$pag_tarjeta = $this->input->post("pag_tarjeta$m");
		    			$chq_numero = $this->input->post("pag_documento$m");
		    			$pag_id_chq = $this->input->post("id_nota_credito$m");
		    			if ($pag_tipo == '9' || $pag_tipo == '7' ) {
		    				$rst_plz=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($pag_plazo);
                            $nf = strtotime("+$rst_plz->btr_dias day", strtotime($fac_fecha_emision));
                            $pag_plazo = $rst_plz->btr_dias;
                            $fec = date('Y-m-d', $nf);
                        } else {
                            $fec = $fac_fecha_emision;
                        }

                        if(empty($pag_plazo)){
                        	$pag_plazo='0';
                        }

		    			$dt_det=array(
		    							'com_id'=>$fac_id,
                                        'pag_fecha_v'=>$fec,
                                        'pag_forma'=>$pag_forma,
                                        'pag_cant'=>$pag_cant,
                                        'pag_banco'=>$pag_banco,
                                        'pag_tarjeta'=>$pag_tarjeta,
                                        'pag_contado'=>$pag_plazo,
                                        'chq_numero'=>$chq_numero,
                                        'pag_id_chq'=>$pag_id_chq,
                                        'pag_estado'=>'1',
		    						);
		    			
		    			$this->factura_model->insert_pagos($dt_det);
						if ($pag_tipo == '8') {
		    				///ctasxcobrar 
		    				///nota de credito
			    			$ctaxcob=array(	
					    				'com_id'=>$fac_id,
					    				'cta_fecha_pago'=>$fec,
										'cta_forma_pago'=>$pag_tipo,
										'num_documento'=>$chq_numero, 
										'cta_concepto'=>'ABONO DESDE FACTURA', 
										'cta_monto'=>$pag_cant,
										'cta_fecha'=>date('Y-m-d'),
										'chq_id'=>$pag_id_chq,
										'cta_estado'=>'1'
								    );
								
							$cta_id=$this->ctasxcobrar_model->insert($ctaxcob);

							///modificar estado cheque
						    	$chq_cobro=$this->ctasxcobrar_model->lista_ctasxcobrar_notcre($pag_id_chq);
						    	$cheque=$this->cheque_model->lista_un_cheque($pag_id_chq);
								$chq_monto=$cheque->chq_monto;
								$chq_saldo=round($chq_monto,$dec)-round($chq_cobro->sum,$dec);
								if($chq_saldo>0){
									$chq_estado=8;
								}else{
									$chq_estado=9;
								}
								$data_chq=array(
												'chq_cobro'=>$chq_cobro->sum,
												'chq_estado'=>$chq_estado,
												);	
								$this->cheque_model->update($pag_id_chq,$data_chq);
		    			}else if ($pag_tipo != '9') {
		    				///control de cobros
		    				$fp=$this->forma_pago_model->lista_una_forma_pago_id($pag_forma);
		    				if($pag_banco == 0){
		    					$banco ='';
		    				}else{
		    					$bnc=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($pag_banco);
		    					$banco=$bnc->btr_descripcion;
		    				}
		    				
		    				$data_chq=array(	
				    				'emp_id'=>$emp_id,
				    				'cli_id'=>$cli_id,
				    				'chq_recepcion'=>$fac_fecha_emision,
									'chq_fecha'=>$fac_fecha_emision,
									'chq_tipo_doc'=>$pag_tipo, 
									'chq_nombre'=>$fp->fpg_descripcion, 
									'chq_concepto'=>'ABONO DESDE FACTURA',
									'chq_banco'=>$banco,
									'chq_numero'=>$chq_numero,
									'chq_monto'=>$pag_cant,
									'chq_estado'=>'9',
									'chq_estado_cheque'=>'11'
		    				);
		    				$chq_id=$this->cheque_model->insert($data_chq);

		    				///ctasxcobrar
		    				$ctaxcob=array(	
			    				'com_id'=>$fac_id,
			    				'cta_fecha_pago'=>$fec,
								'cta_forma_pago'=>$pag_tipo,
								'num_documento'=>$chq_numero, 
								'cta_concepto'=>'ABONO DESDE FACTURA', 
								'cta_monto'=>$pag_cant,
								'cta_fecha'=>date('Y-m-d'),
								'chq_id'=>$chq_id,
								'cta_estado'=>'1'
						    );
							
						    $cta_id=$this->ctasxcobrar_model->insert($ctaxcob);
		    			}
		    		}

		    		//movimientos
		    		$inven=$this->configuracion_model->lista_una_configuracion('3');
		    		if ($inven->con_valor == 0) {
                        $k = 0;
                        while ($k < $count_det) {
                        	$k++;
                        	if($this->input->post("pro_aux$k")!=''){
						    	$pro_id = $this->input->post("pro_aux$k");
						    	$dfc_cantidad = $this->input->post("cantidad$k");
						    	$mov_cost_unit = $this->input->post("mov_cost_unit$k");
						    	$mov_cost_tot = $this->input->post("mov_cost_tot$k");
	                            $rst_ids = $this->producto_comercial_model->lista_un_producto($pro_id);
	                            $p_ids = $rst_ids->ids;
	                            if ($p_ids != 79 && $p_ids != 80) {
	                                $fec_mov = date('Y-m-d');
	                                $hor_mov = date('H:i:s');
	                                $dt_movimientos=array(
	                                        			'pro_id'=>$pro_id,
	                                                    'trs_id'=>'25',
	                                                    'cli_id'=>$cli_id,
	                                                    'bod_id'=>$emi_id,
	                                                    'mov_documento'=>$fac_numero,
	                                                    'mov_num_factura'=>$fac_numero,
	                                                    'mov_fecha_trans'=>$fac_fecha_emision,
	                                                    'mov_fecha_registro'=>$fec_mov,
	                                                    'mov_hora_registro'=>$hor_mov, 
	                                                    'mov_cantidad'=>$dfc_cantidad,
	                                                    'mov_fecha_entrega'=>$fec_mov,
	                                                    'mov_val_unit'=>$mov_cost_unit,
	                                                    'mov_val_tot'=>$mov_cost_tot,
	                                                    'emp_id'=>$emp_id,
	                                                    'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
	                                        			);

	                                $this->factura_model->insert_movimientos($dt_movimientos);
	                            }
	                        }
	                        
                        }
                    }
		    	}
		    	//modificar estado pedido
		    	$rst_fact=$this->pedido_model->lista_facturado($ped_id);
		    	$rst_sol=$this->pedido_model->lista_solicitado($ped_id);
		    	$est="15";
		    	if(!empty($rst_fact->facturado) && !empty($rst_sol->solicitado)){
		    		if($rst_fact->facturado>=$rst_sol->solicitado){
		    			$est="16";
		    		}	
		    	}
		    	$dt_ped=array('ped_estado'=>$est);
		    	$this->pedido_model->update($ped_id,$dt_ped);	

		    	//generar_xml
		    	$this->generar_xml($fac_id);
		    	

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'FACTURA PEDIDO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'factura/show_frame/'. $fac_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'factura/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	
	
	public function traer_forma($id){
		$rst=$this->forma_pago_model->lista_una_forma_pago($id);
		if(!empty($rst)){
			$lista="<option value='0'>SELECCIONE</option>";
			$cns=$this->bancos_tarjetas_model->lista_bancos_tarjetas_plazo('2',$rst->fpg_tipo,'1');
			if(!empty($cns)){
				foreach ($cns as $rst_plz) {
					$lista.="<option value='$rst_plz->btr_id'>$rst_plz->btr_descripcion</option>";
				}
			}
			$data=array(
						'fpg_id'=>$rst->fpg_id,
						'fpg_descripcion'=>$rst->fpg_descripcion,
						'fpg_tipo'=>$rst->fpg_tipo,
						'lista'=>$lista,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function traer_plazo($id){
		$rst=$this->forma_pago_model->lista_una_forma_pago_des($id);
		
		if(!empty($rst)){

			$lista="<option value='0'>SELECCIONE</option>";
			$cns=$this->bancos_tarjetas_model->lista_bancos_tarjetas_plazo('2',$rst->fpg_tipo,'1');
			if(!empty($cns)){
				foreach ($cns as $rst_plz) {
					//$lista.="<option value='$rst_plz->btr_dias'>$rst_plz->btr_descripcion</option>";
					$lista.="<option value='$rst_plz->btr_id'>$rst_plz->btr_descripcion</option>";
				}
			}
			$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}
	public function traer_banco($id){
		$rst=$this->forma_pago_model->lista_una_forma_pago_des($id);
		
		if(!empty($rst)){

			$lista="<option value='0'>SELECCIONE</option>";
			$cns=$this->bancos_tarjetas_model->lista_bancos_tarjetas_plazo('0',$rst->fpg_tipo,'1');
			if(!empty($cns)){
				foreach ($cns as $rst_plz) {
					$lista.="<option value='$rst_plz->btr_id'>$rst_plz->btr_descripcion</option>";
				}
			}
			$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}
	public function traer_tarjeta($id){
		$rst=$this->forma_pago_model->lista_una_forma_pago_des($id);
		
		if(!empty($rst)){

			$lista="<option value='0'>SELECCIONE</option>";
			$cns=$this->bancos_tarjetas_model->lista_bancos_tarjetas_plazo('1',$rst->fpg_tipo,'1');
			if(!empty($cns)){
				foreach ($cns as $rst_plz) {
					$lista.="<option value='$rst_plz->btr_id'>$rst_plz->btr_descripcion</option>";
				}
			}
			$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}

	function buscar_notas($id){
		$cns_chq=$this->factura_model->lista_notcre_cliente($id);
		$lista="";
			if(!empty($cns_chq)){
				foreach ($cns_chq as $rst_nc) {
					$lista.="<option value='$rst_nc->chq_id'>$rst_nc->chq_numero</option>";
				}
				$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
			}else{
				echo "";
			}
	}

	function load_nota($id){
		$rst=$this->factura_model->lista_un_cheque($id);
			if(!empty($rst)){
				$tot_cant = $rst->chq_monto - $rst->chq_cobro;
				$data=array(
						'chq_id'=>$rst->chq_id,
						'chq_numero'=>$rst->chq_numero,
						'chq_valor'=>$tot_cant,
						);
			echo json_encode($data);
			}else{
				echo "1";
			}
	}

	function clave_acceso($cja,$doc_numero,$doc_fecha){
		$cod_doc='01';
		$rst=$this->caja_model->lista_una_caja($cja);
		$rst_am=$this->configuracion_model->lista_una_configuracion('5');
		$ambiente = $rst_am->con_valor; //Pruebas 1    Produccion 2
		$codigo = "12345678"; //Del ejemplo del SRI
		$tp_emison = "1"; //Emision Normal

	    $ndoc = explode('-', $doc_numero);
	    $nfact = str_replace('-', '', $doc_numero);
	    $ems = $ndoc[0];
	    $emisor = intval($ndoc[0]);
	    $pt_ems = $ndoc[1];
	    $secuencial = $ndoc[2];
	    
	    $f=explode('-', $doc_fecha);
	    $fecha = "$f[2]/$f[1]/$f[0]";
	    $f2 = str_replace('/', '',$fecha);
	    
	    $clave1 = trim($f2 . $cod_doc . $rst->emp_identificacion . $ambiente . $ems . $pt_ems . $secuencial . $codigo . $tp_emison);
	    $cla = strrev($clave1);
	    $n = 0;
	    $p = 1;
	    $i = strlen($clave1);
	    $m = 0;
	    $s = 0;
	    $j = 2;
	    while ($n < $i) {
	        $d = substr($cla, $n, 1);
	        $m = $d * $j;
	        $s = $s + $m;
	        $j++;
	        if ($j == 8) {
	            $j = 2;
	        }
	        $n++;
	    }
	    $div = $s % 11;
	    $digito = 11 - $div;
	    if ($digito < 10) {
	        $digito = $digito;
	    } else if ($digito == 10) {
	        $digito = 1;
	    } else if ($digito == 11) {
	        $digito = 0;
	    }


	    $clave = trim($f2 . $cod_doc . $rst->emp_identificacion . $ambiente . $ems . $pt_ems . $secuencial . $codigo . $tp_emison . $digito);
	    return $clave;
	}
	
	public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Factura '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"factura/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

    public function show_frame2($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"factura/show_pdf/$id/$opc_id",
				);
			$this->show_pdf2($id,$opc_id);
		}
    	


    }

    public function show_pdf($id,$opc_id){
    		$rst=$this->factura_model->lista_una_factura($id);
    		$imagen=$this->set_barcode($rst->fac_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
			$cns_pag=array();
			foreach ($cns_pg as $rst_pg) {
				$dt_pg= (object) array(
							'pag_forma'=>$rst_pg->pag_forma,
							'fpg_descripcion'=>$rst_pg->fpg_descripcion,
							'pag_tipo'=>$rst_pg->fpg_tipo,
							'chq_numero'=>$rst_pg->chq_numero,
							'pag_id_chq'=>$rst_pg->pag_id_chq,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
								);
				array_push($cns_pag, $dt_pg);
			}

			///recupera detalle
			$cns_dt=$this->factura_model->lista_detalle_factura($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_precio'=>$rst_dt->dfc_precio_unit,
						'pro_iva'=>$rst_dt->dfc_iva,
						'pro_descuento'=>$rst_dt->dfc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->dfc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'cantidad'=>$rst_dt->dfc_cantidad,
						'ice'=>$rst_dt->dfc_ice,
						'ice_p'=>$rst_dt->dfc_p_ice,
						'ice_cod'=>$rst_dt->dfc_cod_ice,
						'precio_tot'=>$rst_dt->dfc_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=>$this->factura_model->lista_una_factura($id),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						);
			$this->html2pdf->filename('factura.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_factura', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    	
    }  

    public function set_barcode($code)
	{
		// //load library
		// $this->load->library('zend');
		// //load in folder Zend
		// $this->zend->load('Zend/Barcode');
		// //generate barcode
		// Zend_Barcode::factory('code39', 'image', array('text'=>$code), array());

		// $this->load->library('zend');
  //       $this->zend->load('Zend/Barcode');
  //       $barcodeOptions = array('text' => $code);
  //       $rendererOptions = array('imageType'=>'png');
  //       $imageResource=Zend_Barcode::factory('code39', 'image', $barcodeOptions, $rendererOptions)->render();
  //       // $this->subir_codigo($imageResource,$code);  
  //       // imagepng($imageResource,"./barcodes/$code.png"); 
        $this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$imageResource = Zend_Barcode::factory('code39', 'image', array('text' => "$code", 'barHeight'=> 50,'factor'=> 1, 'drawText'=>false), array())->draw();
		$path="./barcodes/$code.png";
		imagepng($imageResource, $path);
	}  

	
	function barcode() { 
		$this->load->library('zend'); 
		$this->zend->load('Zend/Barcode'); 
		Zend_Barcode::draw('code39', 'image', array('text' => '1234565'), array()); 
		// var_dump($test); 
		// imagejpeg($test, 'barcode.jpg', 100); 
	} 

	public function show_pdf2($id,$opc_id){
    		$rst=$this->factura_model->lista_una_factura($id);
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
			$cns_pag=array();
			foreach ($cns_pg as $rst_pg) {
				$lista="<option value='0'>SELECCIONE</option>";
				$rst_fp=$this->forma_pago_model->lista_una_forma_pago($rst_pg->pag_forma);
				$dt_pg= (object) array(
							'pag_forma'=>$rst_pg->pag_forma,
							'fpg_descripcion'=>$rst_pg->fpg_descripcion,
							'pag_tipo'=>$rst_pg->fpg_tipo,
							'chq_numero'=>$rst_pg->chq_numero,
							'pag_id_chq'=>$rst_pg->pag_id_chq,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
								);
				array_push($cns_pag, $dt_pg);
			}

			///recupera detalle
			$cns_dt=$this->factura_model->lista_detalle_factura($id);
			$cns_det=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			$inven=$this->configuracion_model->lista_una_configuracion('3');
			foreach ($cns_dt as $rst_dt) {
				if ($inven->con_valor == 0) {
					 if ($ctrl_inv->con_valor == 0) {
		                   	$rst_emp=$this->emisor_model->lista_un_emisor($rst->emi_id);
	                    	$fra = "and emp_id=$rst_emp->emp_id";
		                } else {
		                    $fra = "and m.bod_id=$rst->emi_id";
		                }
					$rst1 =$this->factura_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                $rst2 = $this->factura_model->lista_costos_mov($rst_dt->pro_id,$fra); 
		                if(!empty($rst2)){
		                	$cnt_inv=$rst2->ingreso - $rst2->egreso;
		                	$prec_inv=$rst2->icnt - $rst2->ecnt;
		                	if($cnt_inv==0 || $prec_inv==0){
		                		$cost_unit=0;
		                	}else{
		                		$cost_unit = (($cnt_inv) / ($prec_inv));
		                	}
		                }else{
		                	$cost_unit =0;
		                }
		            }else{
		            	$inv=0;
		            	$cost_unit =0;
		            }
		        }else{
		        	$inv=0;
		            $cost_unit =0;

		        }  
	        
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_precio'=>$rst_dt->dfc_precio_unit,
						'pro_iva'=>$rst_dt->dfc_iva,
						'pro_descuento'=>$rst_dt->dfc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->dfc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'inventario'=>$inv,
						'cantidad'=>$rst_dt->dfc_cantidad,
						'cost_unit'=>$cost_unit,
						'ice'=>$rst_dt->dfc_ice,
						'ice_p'=>$rst_dt->dfc_p_ice,
						'ice_cod'=>$rst_dt->dfc_cod_ice,
						'precio_tot'=>$rst_dt->dfc_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}
			// 
			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_productos'=>$this->factura_model->lista_productos('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=>$this->factura_model->lista_una_factura($id),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						// 'action'=>base_url().'factura/actualizar/'.$opc_id
						);
			
		$this->load->view('pdf/pdf_factura',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
    	
    } 

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Pedidos a Despachar ';
    	$file="pedidos_despachar".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function consulta_sri(){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
	    $ambiente=$amb->con_valor;

    	if($ambiente!=0){
	    	$factura=$this->factura_model->lista_factura_sin_autorizar();
	        set_time_limit(0);
	         if ($ambiente == 2) { //Produccion
	            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $factura->fac_clave_acceso]);
	        
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($ambiente,$factura->fac_id); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
	        	if($res['estado']!='AUTORIZADO'){
	        		$this->generar_xml($ambiente,$factura->fac_id); 
	        	}else{
	            	$data = array(
	            					'fac_autorizacion'=>$res['numeroAutorizacion'], 
	            					'fac_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'fac_xml_doc'=>$res['comprobante'], 
	            					'fac_estado'=>'6'
	            				);
	            	$this->factura_model->update($factura->fac_id,$data);
	        	}
	        }
	    }    

    }
    

    function generar_xml($id){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
	    $ambiente=$amb->con_valor;  
    	if($ambiente!=0){
    	$xml="";    
    	$progr=$this->configuracion_model->lista_una_configuracion('15');
    	$programa=$progr->con_valor2;
    	$credencial=$this->configuracion_model->lista_una_configuracion('13');
    	$cred=explode('&',$credencial->con_valor2);
    	$firma=$cred[2];
		$pass=$cred[1];
    	$factura=$this->factura_model->lista_una_factura($id);
    	$detalle=$this->factura_model->lista_detalle_factura($factura->fac_id);
    	$pagos=$this->factura_model->lista_pagos_factura($factura->fac_id);
        $dec = $this->configuracion_model->lista_una_configuracion('2');
        $round=$dec->con_valor;
        $codigo='12345678';  
        $tp_emison='1';  
        $empresa=$this->empresa_model->lista_una_empresa($factura->emp_id);    
        $emisor=$this->emisor_model->lista_un_emisor($factura->emi_id);    
        $ndoc = explode('-', $factura->fac_numero);
        $nfact = str_replace('-', '', $factura->fac_numero);
        $ems = $ndoc[0];
        $emi = intval($ndoc[0]);
        $pt_ems = $ndoc[1];
        $secuencial = $ndoc[2];
        $cod_doc = '01'; //01= factura, 02=nota de credito tabla 4
        $fecha = date_format(date_create($factura->fac_fecha_emision), 'd/m/Y');
        $f2 = date_format(date_create($factura->fac_fecha_emision), 'dmY');
        $dir_cliente = $factura->fac_direccion;
        $telf_cliente = $factura->fac_telefono;
        $email_cliente = $factura->fac_email;
        $contabilidad = $empresa->emp_obligado_llevar_contabilidad;
        $razon_soc_comprador = $factura->fac_nombre;
        $id_comprador = $factura->fac_identificacion;;
        if (strlen($id_comprador) == 13 && $id_comprador != '9999999999999' && substr($id_comprador, -3) == '001') {
            $tipo_id_comprador = "04"; //RUC 04 
        } else if (strlen($id_comprador) == 10) {
            $tipo_id_comprador = "05"; //CEDULA 05 
        } else if ($id_comprador == '9999999999999') {
            $tipo_id_comprador = "07"; //VENTA A CONSUMIDOR FINAL
        } else {
            $tipo_id_comprador = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
        }
        

        $clave = $factura->fac_clave_acceso;

        $xml.="<?xml version='1.0' encoding='UTF-8'?>" . chr(13);
        $xml.="<factura version='1.1.0' id='comprobante'>" . chr(13);
        $xml.="<infoTributaria>" . chr(13);
        $xml.="<ambiente>" . $ambiente . "</ambiente>" . chr(13);
        $xml.="<tipoEmision>" . $tp_emison . "</tipoEmision>" . chr(13);
        $xml.="<razonSocial>" . $empresa->emp_nombre . "</razonSocial>" . chr(13);
        $xml.="<nombreComercial>" . $emisor->emi_nombre . "</nombreComercial>" . chr(13);
        $xml.="<ruc>" . $empresa->emp_identificacion . "</ruc>" . chr(13);
        $xml.="<claveAcceso>" . $clave . "</claveAcceso>" . chr(13);
        $xml.="<codDoc>" . $cod_doc . "</codDoc>" . chr(13);
        $xml.="<estab>" . $ems . "</estab>" . chr(13);
        $xml.="<ptoEmi>" . $pt_ems . "</ptoEmi>" . chr(13);
        $xml.="<secuencial>" . $secuencial . "</secuencial>" . chr(13);
        $xml.="<dirMatriz>" . $empresa->emp_direccion . "</dirMatriz>" . chr(13);
        $xml.="</infoTributaria>" . chr(13);
    //ENCABEZADO
        $xml.="<infoFactura>" . chr(13);
        $xml.="<fechaEmision>" . $fecha . "</fechaEmision>" . chr(13);
        $xml.="<dirEstablecimiento>" . $emisor->emi_dir_establecimiento_emisor . "</dirEstablecimiento>" . chr(13);
        if(!empty($empresa->emp_contribuyente_especial)){
        	$xml.="<contribuyenteEspecial>$empresa->emp_contribuyente_especial</contribuyenteEspecial>" . chr(13);
    	}

        $xml.="<obligadoContabilidad>" . $contabilidad . "</obligadoContabilidad>" . chr(13);
        $xml.="<tipoIdentificacionComprador>" . $tipo_id_comprador . "</tipoIdentificacionComprador>" . chr(13);
        $xml.="<razonSocialComprador>" . $razon_soc_comprador . "</razonSocialComprador>" . chr(13);
        $xml.="<identificacionComprador>" . $id_comprador . "</identificacionComprador>" . chr(13);
        $xml.="<totalSinImpuestos>" . round($factura->fac_subtotal, $round) . "</totalSinImpuestos>" . chr(13);
        $xml.="<totalDescuento>" . round($factura->fac_total_descuento, $round) . "</totalDescuento>" . chr(13);
        $xml.="<totalConImpuestos>" . chr(13);
        ////******TODOS LOS IVA****************/////
        if ($factura->fac_subtotal0 > 0) {//IVA 0
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>0</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->fac_subtotal0, $round) . "</baseImponible>" . chr(13);
            $xml.="<valor>0.00</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }
        if ($factura->fac_subtotal12 > 0) {//IVA 12
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>2</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->fac_subtotal12 + $factura->fac_total_ice,$round) . "</baseImponible>" . chr(13);
            $xml.="<valor>" . round($factura->fac_total_iva, $round) . "</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }
        if ($factura->fac_subtotal_no_iva > 0) { //NO OBJ
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>6</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->fac_subtotal_no_iva, $round) . "</baseImponible>" . chr(13);
            $xml.="<valor>0.00</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }
        if ($factura->fac_subtotal_ex_iva > 0) { //EXC
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>7</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->fac_subtotal_ex_iva, $round) . "</baseImponible>" . chr(13);
            $xml.="<valor>0.00</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }

        $xml.="</totalConImpuestos>" . chr(13);
        $xml.="<propina>0.00</propina>" . chr(13);
        $xml.="<importeTotal>" . round($factura->fac_total_valor, $round) . "</importeTotal>" . chr(13);
        $xml.="<moneda>DOLAR</moneda>" . chr(13);

        ////pagos
        $xml.="<pagos>" . chr(13);
        $m = 0;
        
        foreach ($pagos as $pag) {
        	$rst_fp=$this->forma_pago_model->lista_una_forma_pago($pag->pag_forma);
                $xml.="<pago>" . chr(13);
                $xml.="<formaPago>$rst_fp->fpg_codigo</formaPago>" . chr(13); ///pago sin utilizacion del sistema financiero
                $xml.="<total>" . round($pag->pag_cant, 2) . "</total>" . chr(13);
                $xml.="</pago>" . chr(13);
        }
            
        $xml.="</pagos>" . chr(13);
        $xml.="</infoFactura>" . chr(13);
        $xml.="<detalles>" . chr(13);

        foreach ($detalle as $det) {
            
            $xml.="<detalle>" . chr(13);
            $xml.="<codigoPrincipal>" . trim($det->mp_c) . "</codigoPrincipal>" . chr(13);
			if (trim($det->mp_n) != '') {
	            $xml.="<codigoAuxiliar>" . trim($det->mp_n) . "</codigoAuxiliar>" . chr(13);
	        }
            $xml.="<descripcion>" . trim($det->mp_d) . "</descripcion>" . chr(13);
            $xml.="<cantidad>" . round($det->dfc_cantidad, $round) . "</cantidad>" . chr(13);
            $xml.="<precioUnitario>" . round($det->dfc_precio_unit, $round) . "</precioUnitario>" . chr(13);
            $xml.="<descuento>" . round($det->dfc_val_descuento, $round) . "</descuento>" . chr(13);
            $xml.="<precioTotalSinImpuesto>" . round($det->dfc_precio_total, $round) . "</precioTotalSinImpuesto>" . chr(13);
            $xml.="<impuestos>" . chr(13);

            $xml.="<impuesto>" . chr(13);

            $xml.="<codigo>2</codigo>" . chr(13);
            $base_imp=$det->dfc_precio_total + $det->dfc_ice;

            if ($det->dfc_iva == '12') {
                $tarifa = 12;
                $codPorc = 2;
                $valo_iva = round( $base_imp * 12 / 100, 2);
            }

            if ($det->dfc_iva == '0') {
                $tarifa = 0;
                $codPorc = 0;
                $valo_iva = 0.00;
            }
            if ($det->dfc_iva == 'NO') {
                $tarifa = 0;
                $codPorc = 6;
                $valo_iva = 0.00;
            }
            if ($det->dfc_iva == 'EX') {
                $tarifa = 0;
                $codPorc = 7;
                $valo_iva = 0.00;
            }
            $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13);
            $xml.="<tarifa>" . $tarifa . "</tarifa>" . chr(13);
            $xml.="<baseImponible>" . round($base_imp, 2) . "</baseImponible>" . chr(13);
            $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
            $xml.="</impuesto>" . chr(13);
            $xml.="</impuestos>" . chr(13);
            $xml.="</detalle>" . chr(13);
        }
        $xml.="</detalles>" . chr(13);

        $xml.="<infoAdicional>" . chr(13);
        $xml.="<campoAdicional nombre='Direccion'>" . $dir_cliente . "</campoAdicional>" . chr(13);
        $xml.="<campoAdicional nombre='Telefono'>" . $telf_cliente . "</campoAdicional>" . chr(13);
        $xml.="<campoAdicional nombre='Email'>" . strtolower($email_cliente) . "</campoAdicional>" . chr(13);
        $xml.="</infoAdicional>" . chr(13);
        $xml.="</factura>" . chr(13);
        $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);

		header("Location: http://localhost:8080/central_xml_local/envio_sri/firmar.php?clave=$clave&programa=$programa&firma=$firma&password=$pass&ambiente=$ambiente");
		}
    } 


}
