<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Factura extends CI_Controller {

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
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");
		$this->load->library('email');
		$this->load->model('usuario_model');

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
			$text= trim($this->input->post('txt'));
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_facturas=$this->factura_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_facturas=$this->factura_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}

			$data=array(
						'permisos'=>$this->permisos,
						'facturas'=>$cns_facturas,
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
			$this->load->view('factura/lista',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			//valida cuentas asientos completos
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuentas)){
					$valida_asiento=1;
				}
			}

			$usu_id=$this->session->userdata('s_idusuario');
			$rst_vnd=$this->vendedor_model->lista_un_vendedor_2($usu_id);
			
			if(empty($rst_vnd)){
				$vnd='';
			}else{
				$vnd=$rst_vnd->vnd_id;
				if ($vnd==1) {
					$vnd="";
				}
			}
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'm_prec'=>$this->configuracion_model->lista_una_configuracion('25'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_productos'=>$this->factura_model->lista_productos('1'),
						'usuario'=>$this->session->userdata('s_idusuario'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'factura'=> (object) array(
											'fac_fecha_emision'=>date('Y-m-d'),
											'fac_numero'=>'',
					                        'cli_id'=>'',
					                        'vnd_id'=>$vnd,
					                        'fac_identificacion'=>'',
					                        'fac_nombre'=>'',
					                        'fac_direccion'=>'',
					                        'fac_telefono'=>'',
					                        'fac_email'=>'',
					                        //'cli_parroquia'=>'',
					                        'cli_canton'=>'Quito',
					                        'cli_pais'=>'Ecuador',
					                        'fac_id'=>'',
					                        'fac_observaciones'=>'',
					                        'fac_subtotal12'=>'0',
					                        'fac_subtotal0'=>'0',
					                        'fac_subtotal_ex_iva'=>'0',
					                        'fac_subtotal_no_iva'=>'0',
					                        'fac_subtotal'=>'0',
					                        'fac_total_descuento'=>'0',
					                        'fac_total_ice'=>'0',
					                        'fac_total_iva'=>'0',
					                        'fac_total_propina'=>'0',
					                        'fac_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'ped_id'=>'0',
					                        
										),
						'cns_det'=>'',
						'cns_pag'=>'',
						'action'=>base_url().'factura/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						);
			$we =  intval($this->session->userdata('s_we'));
			if($we>=760){
				$this->load->view('factura/form',$data);
			}else{
				$this->load->view('factura/form_movil',$data);
			}
			
			
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$fac_fecha_emision = $this->input->post('fac_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$pasaporte = $this->input->post('pas_aux'); 
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		///$cli_parroquia = $this->input->post('cli_parroquia');
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
			///auditoria
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'FACTURA',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
							$this->auditoria_model->insert($data_aud);

			///inserccion y actualizacion del cliente
			if(empty($cli_id)){
						if (strlen($identificacion) < 11) {
                            $tipo = 'CN';
                        } else {
                            $tipo = 'CJ';
                        }
                        $rst_cod = $this->cliente_model->lista_secuencial_cliente($tipo);
						if(!empty($rst_cod)){
                        	$sec = (substr($rst_cod->cli_codigo, 2, 6) + 1);
                    	}else{
                    		$sec=1;
                    	}

                        if ($sec >= 0 && $sec < 10) {
                            $txt = '0000';
                        } else if ($sec >= 10 && $sec < 100) {
                            $txt = '000';
                        } else if ($sec >= 100 && $sec < 1000) {
                            $txt = '00';
                        } else if ($sec >= 1000 && $sec < 10000) {
                            $txt = '0';
                        } else if ($sec >= 10000 && $sec < 100000) {
                            $txt = '';
                        }

                        $retorno = $tipo . $txt . $sec;

				$dat_cl=array(
							  'cli_apellidos'=>$nombre,
							  'cli_raz_social'=>$nombre,
							  'cli_nom_comercial'=>$nombre,
							  'cli_fecha'=>$fac_fecha_emision,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'2',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$identificacion,
							  'cli_calle_prin'=>$direccion_cliente,
							  'cli_telefono'=>$telefono_cliente,
							  'cli_email'=>$email_cliente,
							  'cli_canton'=>$cli_ciudad,
							  'cli_pais'=>$cli_pais,
							  'cli_codigo'=>$retorno,
							  //'cli_parroquia'=>$cli_parroquia,
							  'cli_tipo_cliente'=>$pasaporte

								);
				$cli_id=$this->factura_model->insert_cliente($dat_cl);
			}else{
				$dat_cl=array(
								'cli_calle_prin'=>$direccion_cliente,
					            'cli_email'=>$email_cliente,
					            'cli_telefono'=>$telefono_cliente,
					            'cli_canton'=>$cli_ciudad,
					            'cli_pais'=>$cli_pais,
					            //'cli_parroquia'=>$cli_parroquia,
					        ); 
				$this->cliente_model->update($cli_id,$dat_cl);	 
			}

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
							'fac_observaciones'=>preg_replace("/[\r\n|\n|\r]+/", " ", $observacion),
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
	                                    'dfc_ice'=>0, 
	                                    'dfc_p_ice'=>0, 
	                                    'dfc_cod_ice'=>0,
		    						);
		    			$this->factura_model->insert_detalle($dt_det);
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
		    			
		    			$pag_id=$this->factura_model->insert_pagos($dt_det);

		    			$fp=$this->forma_pago_model->lista_una_forma_pago_id($pag_forma);
		    			
		    			if($conf_as->con_valor==0){
			    			$cli_as = 1;
		                    $tc = 76;
		                    $td = 77;
		                    $ch = 78;
		                    $ef = 79;
		                    $rt = 80;
		                    $nc = 81;
		                    $ct = 82;
		                    $bn = 83;
			    			switch ($fp->fpg_tipo) {
					            case 1:
					            	// 'TARJETA DE CREDITO';
					                $cts = $tc;
					                break;
					            case 2:
					                // 'TARJETA DE DEBITO';
					                $cts = $td;
					                break;
					            case 3:
					                // 'CHEQUE';
					                $cts = $ch;
					                break;
					            case 4:
					                //'EFECTIVO';
					                $cts = $ef;
					                break;
					            case 5:
					                // 'CERTIFICADOS';
					                $cts = $ct;
					                break;
					            case 6:
					                // 'TRANSFERENCIAS';
					                $cts = $bn;
					                break;
					            case 7:
					               // 'RETENCION';
					                $cts = $rt;
					               break;
					            case 8:
					               // 'Nota de Credito';
					                $cts = $nc;
					                break; 
					            case 9:
					               // 'Credito';
					                $cts = 0;
					                break;        
					        }
					        $rst_cli=$this->cliente_model->lista_un_cliente($cli_id);
					        $ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('1',$emi_id);
        					$ccex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('2',$emi_id);
        					$cban=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta($cts,$emi_id);
        					if($rst_cli->cli_tipo_cliente==0){
						        $pln_id=$ccli->pln_id;
						    }else{
						        $pln_id=$ccex->pln_id;
						    }
						    
						    $pln_codigo=$cban->pln_codigo;
					    }else{
					       	$pln_id=0;
					        $pln_codigo='';
					    }

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
										'cta_estado'=>'1',
										'pln_id'=>$pln_id,
										'cta_banco'=>$pln_codigo,
										'pag_id'=>$pag_id,
										'emp_id'=>$emp_id,
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
		    				
		    				$bnc=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($fp->fpg_banco);
		    				$data_chq=array(	
				    				'emp_id'=>$emp_id,
				    				'cli_id'=>$cli_id,
				    				'chq_recepcion'=>$fac_fecha_emision,
									'chq_fecha'=>$fac_fecha_emision,
									'chq_tipo_doc'=>$pag_tipo, 
									'chq_nombre'=>$fp->fpg_descripcion, 
									'chq_concepto'=>'ABONO DESDE FACTURA',
									'chq_banco'=>$bnc->btr_descripcion,
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
								'cta_estado'=>'1',
								'pln_id'=>$pln_id,
								'cta_banco'=>$pln_codigo,
								'pag_id'=>$pag_id,
								'emp_id'=>$emp_id,
						    );
							
						    $cta_id=$this->ctasxcobrar_model->insert($ctaxcob);

		    			}
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
		    	//generar_xml
                    ///enviari sri 1 y no enviar 2 
                    if($rst_cja->cja_envio_sri==1){
                    	$this->generar_xml($fac_id,0);
                    }else{
                    	$data=array(
						'fac_estado'=>'6',
						'fac_estado_correo'=>'ENVIADO'
					);
                    	$this->factura_model->update($fac_id,$data);
                    }


		        //genera asientos
		        if($conf_as->con_valor==0){
		        	$this->asientos($fac_id);
		        	$this->asientos_pagos($fac_id);
		        }

		        $rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'factura/show_ftalon/'. $fac_id.'/'.$opc_id);
				//redirect(base_url().'factura/show_frame/'. $fac_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'factura/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$rst=$this->factura_model->lista_una_factura($id);
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
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
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
							'contado'=>$lista,
								);
				array_push($cns_pag, $dt_pg);
			}

			///recupera detalle
			$cns_dt=$this->factura_model->lista_detalle_factura($id);
			$cns_det=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			$inven=$this->configuracion_model->lista_una_configuracion('3');
			// $emi=$rst->emi_id;
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
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'm_prec'=>$this->configuracion_model->lista_una_configuracion('25'),
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
						'action'=>base_url().'factura/actualizar/'.$opc_id
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('factura/form',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		
		$id = $this->input->post('fac_id');
		$fac_fecha_emision = $this->input->post('fac_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$cli_parroquia = $this->input->post('cli_parroquia');
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
		$this->form_validation->set_rules('cli_ciudad','Ciudad','required');
		$this->form_validation->set_rules('cli_pais','Pais','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			///inserccion y actualizacion del cliente

			if(empty($cli_id)){
						if (strlen($identificacion) < 11) {
                            $tipo = 'CN';
                        } else {
                            $tipo = 'CJ';
                        }
                        $rst_cod = $this->Cliente_model->lista_secuencial_cliente($tipo);
						if(!empty($rst_cod)){
                        	$sec = (substr($rst_cod->cli_codigo, 2, 6) + 1);
                    	}else{
                    		$sec=1;
                    	}

                        if ($sec >= 0 && $sec < 10) {
                            $txt = '0000';
                        } else if ($sec >= 10 && $sec < 100) {
                            $txt = '000';
                        } else if ($sec >= 100 && $sec < 1000) {
                            $txt = '00';
                        } else if ($sec >= 1000 && $sec < 10000) {
                            $txt = '0';
                        } else if ($sec >= 10000 && $sec < 100000) {
                            $txt = '';
                        }

                        $retorno = $tipo . $txt . $sec;

				$dat_cl=array(
							  'cli_apellidos'=>$nombre,
							  'cli_raz_social'=>$nombre,
							  'cli_fecha'=>$fac_fecha_emision,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'0',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$identificacion,
							  'cli_calle_prin'=>$direccion_cliente,
							  'cli_telefono'=>$telefono_cliente,
							  'cli_email'=>$email_cliente,
							  'cli_canton'=>$cli_ciudad,
							  'cli_pais'=>$cli_pais,
							  'cli_codigo'=>$retorno,
							  'cli_parroquia'=>$cli_parroquia
								);
				$cli_id=$this->factura_model->insert_cliente($dat_cl);
			}else{
				$dat_cl=array(
								'cli_calle_prin'=>$direccion_cliente,
					            'cli_email'=>$email_cliente,
					            'cli_telefono'=>$telefono_cliente,
					            'cli_canton'=>$cli_ciudad,
					            'cli_pais'=>$cli_pais,
					            'cli_parroquia'=>$cli_parroquia
					        ); 
				$this->cliente_model->update($cli_id,$dat_cl);	 
			}

			$rst_fac=$this->factura_model->lista_una_factura($id);
		    $clave_acceso=$this->clave_acceso($cja_id,$rst_fac->fac_numero,$fac_fecha_emision);

		    $data=array(	
		    				// 'emp_id'=>$emp_id,
		    				// 'emi_id'=>$emi_id,
		    				// 'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'ped_id'=>$ped_id,
							'fac_fecha_emision'=>$fac_fecha_emision,
							// 'fac_numero'=>$fac_numero, 
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
							'fac_observaciones'=>preg_replace("/[\r\n|\n|\r]+/", " ", $observacion),
							'fac_total_valor'=>$total_valor,
							'fac_subtotal'=>$subtotal,
							'fac_clave_acceso'=>$clave_acceso,
							'fac_estado'=>'4'
		    );


			if($this->factura_model->update($id,$data)){
				if($this->factura_model->delete_detalle($id)){
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
			    			$dt_det=array(
			    							'fac_id'=>$id,
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
		    	$up_dtp=array('pag_estado'=>3);
		    	

		    	if($this->factura_model->update_pagos($id,$up_dtp)){
			    	$m=0;
			    	while($m<$count_pag){
			    		$m++;
			    		if($this->input->post("pag_forma$m")!=''){
	                        $pag_tipo = $this->input->post("pag_tipo$m");
			    			$pag_forma = $this->input->post("pag_forma$m");
			    			$pag_cant = $this->input->post("pag_cantidad$m");
			    			$pag_contado = $this->input->post("pag_contado$m");
			    			$chq_numero = $this->input->post("pag_documento$m");
			    			$pag_id_chq = $this->input->post("id_nota_credito$m");
			    			if ($pag_tipo == '9') {
			    				$rst_plz=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($pag_contado);
	                            $nf = strtotime("+$rst_plz->btr_dias day", strtotime($fac_fecha_emision));
	                            $fec = date('Y-m-j', $nf);
	                        } else {
	                            $fec = $fac_fecha_emision;
	                        }

	                        if(empty($pag_contado)){
	                        	$pag_contado='0';
	                        }

			    			$dt_det=array(
			    							'com_id'=>$id,
	                                        'pag_fecha_v'=>$fec,
	                                        'pag_forma'=>$pag_forma,
	                                        'pag_cant'=>$pag_cant,
	                                        'pag_contado'=>$pag_contado,
	                                        'chq_numero'=>$chq_numero,
	                                        'pag_id_chq'=>$pag_id_chq,
	                                        'pag_estado'=>'1',
			    						);
			    			$this->factura_model->insert_pagos($dt_det);

			    			$up_cta=array('cta_estado'=>3);
			    			$this->ctasxcobrar_model->update($id,$up_cta);
			    			
			    			if ($pag_tipo == '8') {
			    				//ctasxcobrar
			    				//nota de credito
			    				$ctaxcob=array(	
				    				'com_id'=>$id,
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
			    				//anular cheques
						    	$ctaxcb=$this->ctasxcobrar_model->lista_ctasxcobrar($id);
						    	$up_chq=array(
									    		'chq_estado'=>3,
									    		'chq_estado_cheque'=>3
									    	);
							    	if(!empty($ctaxcb)){
							    		foreach ($ctaxcb as $cxc) {
							    			$this->cheque_model->update($cxc->chq_id,$up_chq);	
							    		}
							    	}
			    				$fp=$this->forma_pago_model->lista_una_forma_pago_id($pag_forma);
			    				$bnc=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($fp->fpg_banco);
			    				$data_chq=array(	
					    				'emp_id'=>$emp_id,
					    				'cli_id'=>$cli_id,
					    				'chq_recepcion'=>$fac_fecha_emision,
										'chq_fecha'=>$fac_fecha_emision,
										'chq_tipo_doc'=>$pag_tipo, 
										'chq_nombre'=>$fp->fpg_descripcion, 
										'chq_concepto'=>'ABONO DESDE FACTURA',
										'chq_banco'=>$bnc->btr_descripcion,
										'chq_numero'=>$chq_numero,
										'chq_monto'=>$pag_cant,
										'chq_estado'=>'9',
										'chq_estado_cheque'=>'11'
			    				);
			    				$chq_id=$this->cheque_model->insert($data_chq);

			    				//ctasxcobrar
			    				$ctaxcob=array(	
				    				'com_id'=>$id,
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
		    		}
		    	}	

		    	//movimientos
		    	
		    		$inven=$this->configuracion_model->lista_una_configuracion('3');
		    		if ($inven->con_valor == 0) {
		    			$up_dt=array('mov_estado'=>3);
		    			$this->factura_model->update_movimientos($rst_fac->fac_numero,$rst_fac->emi_id,$up_dt);
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
	                                                    'bod_id'=>$rst_fac->emi_id,
	                                                    'mov_documento'=>$rst_fac->fac_numero,
	                                                    'mov_num_factura'=>$rst_fac->fac_numero,
	                                                    'mov_fecha_trans'=>$fac_fecha_emision,
	                                                    'mov_fecha_registro'=>$fec_mov,
	                                                    'mov_hora_registro'=>$hor_mov, 
	                                                    'mov_cantidad'=>$dfc_cantidad,
	                                                    'mov_fecha_entrega'=>$fec_mov,
	                                                    'mov_val_unit'=>$mov_cost_unit,
	                                                    'mov_val_tot'=>$mov_cost_tot,
	                                                    'emp_id'=>$rst_fac->emp_id,
	                                                    'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
	                                        			);
	                                $this->factura_model->insert_movimientos($dt_movimientos);
	                            }
	                        }
	                       
                        }
                    }

		    	$this->generar_xml($id,0);
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'FACTURA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);

				redirect(base_url().'factura/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'factura/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'producto'=>$this->factura_model->lista_un_producto($id)
						);
			$this->load->view('factura/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}
public function buscar_cliente($txt){

	
	$lista=" <tr>   <th></th>
                    <th>Identificación</th>
                    <th>Nombres y Apellidos</th>
                  </tr>";
	$cns=$this->cliente_model->lista_un_cliente2($txt);
	$n=1;
	
	if(!empty($cns)){

		foreach ($cns as $rst) {
			$n++;
			$nm = $rst->cli_raz_social;
			$lista.="<tr ><td><input type='button' class='btn btn-success' value='&#8730;' onclick=" . "traer_cliente('$rst->cli_ced_ruc')" . " /></td><td>$rst->cli_ced_ruc</td><td>$nm</td></tr>";
		}
	$data=array(
				'lista'=>$lista,
				);
	echo json_encode($data);
	}else{
		echo "";
	}
	
		

}

	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;

			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_nc=$this->factura_model->lista_nota_credito_factura($id);
			$rst_gui=$this->factura_model->lista_guia_factura($id);
			$rst_ret=$this->factura_model->lista_retencion_factura($id);
			if(empty($rst_nc)){
				if(empty($rst_gui)){
						if(empty($rst_ret)){
						$rst_fac=$this->factura_model->lista_una_factura($id);
						$up_dt=array('mov_estado'=>3);
					    $this->factura_model->update_movimientos($rst_fac->fac_numero,$rst_fac->emi_id,$up_dt);
					    $up_dtp=array('pag_estado'=>3);
					    $this->factura_model->update_pagos($id,$up_dtp);
					    $up_dtf=array('fac_estado'=>3);
						if($this->factura_model->update($id,$up_dtf)){
							//asiento anulacion factura
							if($cnf_as==0){
								$this->asiento_anulacion($id,'1');
							}

							//anular cheques
					    	$ctaxcb=$this->ctasxcobrar_model->lista_ctasxcobrar($id);
					    	//anular ctasxcobrar
							$up_cta=array('cta_estado'=>3);
			    			$this->ctasxcobrar_model->update($id,$up_cta);

					    		$up_chq=array(
					    					'chq_estado'=>3,
					    					'chq_estado_cheque'=>3,
					    					);
						    	if(!empty($ctaxcb)){
						    		foreach ($ctaxcb as $cxc) {
						    			if($cxc->cta_forma_pago!='8'){
						    				$this->cheque_model->update($cxc->chq_id,$up_chq);	
						    			}else{
									    	///modificar estado cheque
									    	$chq_cobro=$this->ctasxcobrar_model->lista_ctasxcobrar_notcre($cxc->chq_id);
									    	$cheque=$this->cheque_model->lista_un_cheque($cxc->chq_id);
											$chq_monto=$cheque->chq_monto;
											$chq_saldo=round($chq_monto,$dec)-round($chq_cobro->sum,$dec);
											if(empty($chq_cobro->sum)){
												$chq_estado=7;
											}else if($chq_saldo>0){
												$chq_estado=8;
											}else{
												$chq_estado=9;
											}
											$data_chq=array(
															'chq_cobro'=>$chq_cobro->sum,
															'chq_estado'=>$chq_estado,
															);	
											$this->cheque_model->update($cxc->chq_id,$data_chq);
									    }	
									    if($cnf_as==0){
							    			///asientos de anulacion pagos
							    			$this->asiento_anulacion($cxc->cta_id,'10');
										    
										}


						    		}
						    	}
			    			
							$data_aud=array(
											'usu_id'=>$this->session->userdata('s_idusuario'),
											'adt_date'=>date('Y-m-d'),
											'adt_hour'=>date('H:i'),
											'adt_modulo'=>'FACTURA',
											'adt_accion'=>'ANULAR',
											'adt_ip'=>$_SERVER['REMOTE_ADDR'],
											'adt_documento'=>$num,
											'usu_login'=>$this->session->userdata('s_usuario'),
											);

							$this->auditoria_model->insert($data_aud);
							$data=array(
											'estado'=>0,
											'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
										);
						}
					}else{
						$data=array(
									'estado'=>1,
									'sms'=>'No se puede anular. La Factura tiene una Retencion',
									'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);
					}	
				}else{
					$data=array(
								'estado'=>1,
								'sms'=>'No se puede anular. La Factura tiene una Guia de Remision',
								'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);
				}	
			}else{
					$data=array(
									'estado'=>1,
									'sms'=>'No se puede anular. La Factura tiene una Nota de Credito',
									'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);
				
			}
			echo json_encode($data);	
		}else{
			redirect(base_url().'inicio');
		}	
	}

    public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente_cedula($id);
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
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function load_producto($id,$inven,$ctr_inv,$fpag,$emi,$lang){

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
	                $rst2 = $this->factura_model->lista_costos_mov($rst->id,$fra); 
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
	        // if($fpag!=0){
	        // 	$rst_fp=$this->forma_pago_model->lista_una_forma_pago_id($fpag);
	        // 	if($rst_fp->fpg_precio==1){
	        // 		$precio=$rst->mp_e;	
	        // 	}else{
	        // 		$precio=$rst->mp_f;	
	        // 	}
	        // }else{
	        // 	$precio=$rst->mp_e;	
	        // }

			$data=array(
						'pro_id'=>$rst->id,
						'ids'=>$rst->ids,
						'pro_descripcion'=>$rst->mp_d,
						'pro_codigo'=>$rst->mp_c,
						'pro_precio'=>$rst->mp_e,
						'pro_precio2'=>$rst->mp_f,
						'pro_iva'=>$rst->mp_h,
						'pro_descuento'=>$rst->mp_g,
						'pro_unidad'=>$rst->mp_q,
						'inventario'=>$inv,
						'cost_unit'=>$cost_unit,
						'ice_p'=>$rst->mp_j,
						'ice_cod'=>$rst->mp_l,
						'lang'=>$lang,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	public function traer_forma($id){
		$rst=$this->forma_pago_model->lista_una_forma_pago_des($id);

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
		$lista=" <tr>   <th></th>
                    <th>Nota Credito No</th>
                    <th>Total Nota Cred.$</th>
                  </tr>";

		// $lista="";
			if(!empty($cns_chq)){
				foreach ($cns_chq as $rst_nc) {
					// $lista.="<option value='$rst_nc->chq_id'>$rst_nc->chq_numero</option>";

						$lista.="<tr ><td><input type='button' class='btn btn-success' value='&#8730;' onclick=" . "load_notas_credito('$rst_nc->chq_id')" . " /></td><td>$rst_nc->chq_numero</td><td>$rst_nc->chq_monto</td></tr>";
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
	function load_nota_numero($id){
		$rst=$this->factura_model->lista_un_cheque_numero($id);
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
		$vencido='';
		$vencer='';
		$pagado='';

		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			if(!empty($this->input->post('vencido')) || !empty($this->input->post('vencer')) || !empty($this->input->post('pagado')) ){
				$vencido=$this->input->post('vencido');
				$vencer=$this->input->post('vencer');
				$pagado=$this->input->post('pagado');
			}
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			
		}

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$etiqueta='Factura.pdf';
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Factura '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"factura/show_pdf/$id/$opc_id/0/$etiqueta",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

    public function show_pdf($id,$opc_id,$correo,$etiqueta){
    		$rst=$this->factura_model->lista_una_factura($id);
    		$imagen=$this->set_barcode($rst->fac_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
			$cns_pag=array();
			$cl=$this->cliente_model->lista_un_cliente($rst->cli_id);

			foreach ($cns_pg as $rst_pg) {
				$dt_pg= (object) array(
							'pag_forma'=>$rst_pg->pag_forma,
							'fpg_descripcion'=>$rst_pg->fpg_descripcion,
							'fpg_codigo'=>$rst_pg->fpg_codigo,
							'fpg_descripcion_sri'=>$rst_pg->fpg_descripcion_sri,
							'pag_tipo'=>$rst_pg->fpg_tipo,
							'chq_numero'=>$rst_pg->chq_numero,
							'pag_id_chq'=>$rst_pg->pag_id_chq,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
							'fpg_tipo'=>$rst_pg->fpg_tipo,
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
    		$this->html2pdf->folder('./pdfs/');
            $this->html2pdf->filename($rst->fac_clave_acceso.'.pdf');
            $this->html2pdf->create('save');
            
			if(empty($correo)){
				$this->html2pdf->output(array("Attachment" => 0));	
			}else if($correo==1){
				$datos_mail=(object) array('tipo' =>'FACTURA' ,
                              'cliente'=>$rst->fac_nombre,
                              'clave_usu'=>$cl->cli_codigo,
                              'emisor'=>$rst->emp_nombre,
                              'numero'=>$rst->fac_numero,
                              'fecha'=>$rst->fac_fecha_emision,
                              'total'=>$rst->fac_total_valor,
                              'correo'=>$rst->fac_email,
                              'fac_id'=>$rst->fac_id,
                              'logo'=>$rst->emp_logo,
                              'clave'=>$rst->fac_clave_acceso,
                                 );
				$this->envio_mail($datos_mail);
			}
    	
    }  


    public function show_ftalon($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$etiqueta='Talon.pdf';
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Factura '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"factura/show_talon/$id/$opc_id/0/$etiqueta",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }
    public function show_talon($id,$opc_id,$correo,$etiqueta){
    		$this->show_pdf($id,$opc_id,3,$etiqueta);
    		$rst=$this->factura_model->lista_una_factura($id);
    		$imagen=$this->set_barcode($rst->fac_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
			$cns_pag=array();
			$cl=$this->cliente_model->lista_un_cliente($rst->cli_id);

			foreach ($cns_pg as $rst_pg) {
				$dt_pg= (object) array(
							'pag_forma'=>$rst_pg->pag_forma,
							'fpg_descripcion'=>$rst_pg->fpg_descripcion,
							'fpg_codigo'=>$rst_pg->fpg_codigo,
							'fpg_descripcion_sri'=>$rst_pg->fpg_descripcion_sri,
							'pag_tipo'=>$rst_pg->fpg_tipo,
							'chq_numero'=>$rst_pg->chq_numero,
							'pag_id_chq'=>$rst_pg->pag_id_chq,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
							'fpg_tipo'=>$rst_pg->fpg_tipo,
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
			$this->html2pdf->filename('talon.pdf');
			$this->html2pdf->paper(array(0,0,204.75,533.66), 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_talon', $data, true)));
    		// $this->html2pdf->folder('./pdfs/');
            // $this->html2pdf->filename($rst->fac_clave_acceso.'.pdf');
            // $this->html2pdf->create('save');
            
			if($correo==1){
				$datos_mail=(object) array('tipo' =>'FACTURA' ,
                              'cliente'=>$rst->fac_nombre,
                              'clave_usu'=>$cl->cli_codigo,
                              'emisor'=>$rst->emp_nombre,
                              'numero'=>$rst->fac_numero,
                              'fecha'=>$rst->fac_fecha_emision,
                              'total'=>$rst->fac_total_valor,
                              'correo'=>$rst->fac_email,
                              'fac_id'=>$rst->fac_id,
                              'logo'=>$rst->emp_logo,
                              'clave'=>$rst->fac_clave_acceso,
                                 );
				$this->envio_mail($datos_mail);
			}else{
				$this->html2pdf->output(array("Attachment" => 0));	
			}
    	
    }  

    public function consulta_api($dato){

		$rst = $this->api($dato);
		$longitud = strlen($dato);
		if($longitud==13){
			$ci=$rst->ruc;
		}else{
			$ci=$dato;
		}

		if(($rst)!=false){
			$nom = str_replace('�','Ñ',$rst->names);
			$data=array(
						'cli_raz_social'   =>$nom,
						'cli_ced_ruc'   =>$ci,
						'cli_nom_comercial'=>$nom
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function api($dato){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.excellentsoft.net/v1/services/verify-cardid/consume?token=dEd38fqr7hkTUj7JNu8ib8Lxd/SMZDeQCM6beNxd40=&ci='.$dato,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$result = curl_exec($curl);
		curl_close($curl);
		$resu   =ob_get_clean();
		$obj    =json_decode($result);
		$json   =array();
		$code   =$obj->code;
		$success=$obj->success;
		if ($success) {
			
			$code   =$obj->code;
			$success=$obj->success;
			//$message=$obj->message;
			$json=$obj->data;
			return $json;
		}else{
			return false;
		}

	}

    public function set_barcode($code)
	{
 
        $this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$imageResource = Zend_Barcode::factory('code39', 'image', array('text' => "$code", 'barHeight'=> 50,'factor'=> 1, 'drawText'=>false), array())->draw();
		$path="./barcodes/$code.png";
		imagepng($imageResource, $path);
	}  

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Factura '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="facturas".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    function zip_facturas(){

			$zip = new ZipArchive();
			$name='facturas'.date('Ymdhms');
			$filename = "./$name.zip";
			$data=$_POST['data'];

			
		if ($zip->open($filename, ZipArchive::CREATE)===TRUE) {
			$longitud = count($data);
					foreach ($data as $dt)
					{
					$rst=$this->factura_model->lista_una_factura($dt);
					if ($rst->fac_clave_acceso!=null) {
					$zip->addFile("./pdfs/$rst->fac_clave_acceso.pdf","$rst->fac_clave_acceso.pdf");
					}
					}
			$zip->close();
			echo $name;}

			else{
			echo "";
			}
		
		
	}


    public function consulta_sri($id,$opc_id,$env){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
	    $ambiente=$amb->con_valor;

    	if($ambiente!=0){
	    	$factura=$this->factura_model->lista_una_factura($id);
	        set_time_limit(0);
	        if ($ambiente == 2) { //Produccion
            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $factura->fac_clave_acceso]);
	        
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($factura->fac_id,$env); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
	        	if($res['estado']=='AUTORIZADO'){
	        		$data = array(
	            					'fac_autorizacion'=>$res['numeroAutorizacion'], 
	            					'fac_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'fac_xml_doc'=>$res['comprobante'], 
	            					'fac_estado'=>'6',

	            				);
	            	$this->factura_model->update($factura->fac_id,$data);

	        		$data_xml = (object) array(
	        						'ambiente'=>$res['ambiente'], 
                                    'clave'=>$factura->fac_clave_acceso,
                					'estado'=>$res['estado'], 
                                    'autorizacion'=>$res['numeroAutorizacion'], 
                					'fecha'=>$res['fechaAutorizacion'], 
                					'comprobante'=>$res['comprobante'], 
                                    'descarga'=>$env,
                				);

	        		$this->generar_xml_autorizado($data_xml,$factura->fac_id,$opc_id); 
	        	}else{
	        		$this->generar_xml($factura->fac_id,$env); 
	        	}
	        }
	    }    

    }
    


    function generar_xml($id,$d){
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
        	$rst_fp=$this->forma_pago_model->lista_una_forma_pago_id($pag->pag_forma);
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
        if(!empty($factura->emp_leyenda_sri) || !empty($factura->fac_observaciones)){
        	$xml.="<campoAdicional nombre='Observaciones'> " .$factura->emp_leyenda_sri.' '.substr($factura->fac_observaciones,0,250) . "</campoAdicional>" . chr(13);
        }

        $xml.="</infoAdicional>" . chr(13);
        $xml.="</factura>" . chr(13);
        $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);
		if($d==1){
			$file = "./xml_docs/$clave.xml";
		        header("Content-type:xml");
		        header("Content-length:" . filesize($file));
		        header("Content-Disposition: attachment; filename= $clave.xml");
		        readfile($file);
		}
		// header("Location: http://localhost:8080/central_xml_local/envio_sri/firmar.php?clave=$clave&programa=$programa&firma=$firma&password=$pass&ambiente=$ambiente");
		}
    } 

    public function generar_xml_autorizado($dt,$id,$opc_id){
    	if (!empty($dt)) {
            $xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
                    <autorizacion>
		              <estado>" . $dt->estado . "</estado>
		              <numeroAutorizacion>" . $dt->autorizacion . "</numeroAutorizacion>
		              <fechaAutorizacion>" . $dt->fecha . "</fechaAutorizacion>
		              <ambiente>" . $dt->ambiente . "</ambiente>
		              <comprobante><![CDATA[" . $dt->comprobante . "]]></comprobante>
                    	<mensajes/>
                    </autorizacion>";
               	$fch = fopen("./xml_docs/$dt->clave.xml", "w+o");
        
				fwrite($fch, $xml);
				fclose($fch);
				if($dt->descarga==1){
	                $file = "./xml_docs/$dt->clave.xml";
			        header("Content-type:xml");
			        header("Content-length:" . filesize($file));
			        header("Content-Disposition: attachment; filename= $dt->clave.xml");
			        readfile($file);
		    	}else if($dt->descarga==0){
			        $etiqueta='Factura.pdf';
	            	$this->show_pdf($id,$opc_id,1,$etiqueta); 
	            }
        }

    }

    function envio_mail($datos){
        $credencial=$this->configuracion_model->lista_una_configuracion('8');
        $cred=explode('&',$credencial->con_valor2);
        $config['smtp_port'] = $cred[1];//'587';
        $config['smtp_host'] = $cred[2];//'mail.tivkas.com';
        $config['smtp_user'] = $cred[3];//'info@tivkas.com';
        $config['smtp_pass'] = $cred[4];//'tvk*36146';
        $config['protocol'] = 'smtp';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['smtp_crypto'] = 'ssl';

        $this->email->initialize($config);

        $this->email->from($cred[3], strtolower($cred[5]));
        $correos = str_replace(';',',', strtolower($datos->correo));
        
        $this->email->to($correos);
        $this->email->cc($cred[3]);
        

        $this->email->attach("./pdfs/$datos->clave.pdf");
        $this->email->attach("./xml_docs/$datos->clave.xml");

        if($datos->cliente=='CONSUMIDOR FINAL'){
          $ncliente="";
        }else{
          $ncliente=$datos->cliente;
        }
        
        $this->email->subject("DOCUMENTOS ELECTRONICOS $datos->tipo No: $datos->numero Cliente : $ncliente ");
        $img_logo=base_url().'imagenes/'.$datos->logo;
        $img_mail=base_url().'imagenes/mail2.png';
        $img_whatsapp=base_url().'imagenes/whatsapp2.png';
        $img_telefono=base_url().'imagenes/telefono2.png';
        $mensaje = str_replace('-', '<br>', $cred[7]);
        //$mensaje = $cred[7];

        $datos_sms = "<html>
              <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                 <style>
                      td {
                          color: #070707;
                          font-family: Arial, Helvetica, sans-serif;
                          font-size: 14px;
                          text-align: center;
                          font-weight: bolder;
                      }
                       .mensaje {
						color: #070707;
						font-family: Arial, Helvetica, sans-serif;
						font-size: 14px;
						justify-content: left;
						// font-weight: bolder;
          			 }


                      
                  </style>
             </head>
             <body>
               <table width='100%'>
                
                  <tr><td><img  height='150px' width='300px' src='$img_logo'/></td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr style='with:60px' ><td>Hola $datos->cliente, </td></tr>
                  <tr class='mensaje'><td class='mensaje' > <p class='mensaje'>$mensaje </p>  </td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td>Fecha: $datos->fecha </td></tr>
                  <tr><td>Emisor: $datos->emisor </td></tr>
                  <tr><td>Tipo de Documento: $datos->tipo </td></tr>
                  <tr><td>Numero de Documento: $datos->numero </td></tr>
                  <tr><td>Total $: ". number_format($datos->total,2)."</td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td></br></br> </td></tr>
                 <tr><td>Adjuntamos el comprobante en formato PDF y XML </td></tr>
                 <tr><td>Para consultar documentos electronicos :</td></tr>
               		<tr><td>Usuario : Su numero de identificacion</td></tr>
					<tr><td>Clave :$datos->clave_usu</td></tr>
                   <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                 <tr>
                      <td style='font-size:16px'>FACTURACION ELECTRONICA POR TIKVASYST S.A.S</td>
                  </tr>
                  <tr><td></br></br> </td></tr>
                   
                  <tr><td></br></br> </td></tr>
                  <tr>
                      <td style='font-size:12px'></td>
                  </tr>
                  <tr>   
                       <td style='font-size:12px'>
                      
                        
                        <img src='$img_whatsapp' width='20px'> +593 999404989 / +593 991815559
                        
                      </td>
                  </tr>
                  <tr><td style='font-size:10px'>Copyright &copy; 2022 Todos los derechos reservados TIKVASYST S.A.S</td></tr>
               </table>
             </body>
           </html>";
        $this->email->message(utf8_decode($datos_sms));

        if($this->email->send()){
            $data= array('fac_estado_correo' =>'ENVIADO');
            if($this->factura_model->update($datos->fac_id,$data)){
            	echo "Factura Enviada Correctamente";
            }
            
        }else{

            echo "no enviado ".$this->email->print_debugger(array('headers'));;
        }

    }


    public function asientos($id){
        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->factura_model->lista_una_factura($id);
        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('1',$rst->emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('2',$rst->emi_id);
        $vta=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('3',$rst->emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('4',$rst->emi_id);
        $des=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('5',$rst->emi_id);
        $pro=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('72',$rst->emi_id);
        
        $asiento =$this->asiento_model->siguiente_asiento();
        
      
        //cliente y FACTURA VENTA
        $dat0 = array();
        $dat1 = array();
        $dat2 = array();
        $dat3 = array();

        $sub=round($rst->fac_subtotal, $dec)+round($rst->fac_total_descuento, $dec);

        if($rst->cli_tipo_cliente==0){
        	$ccli=$cli;
        }else{
        	$ccli=$cex;
        }
        $dat0 = Array(
                    'con_asiento'=>$asiento,
                    'con_concepto'=>'FACTURA VENTA',
                    'con_documento'=>$rst->fac_numero,
                    'con_fecha_emision'=>$rst->fac_fecha_emision,
                    'con_concepto_debe'=>$ccli->pln_codigo,
                    'con_concepto_haber'=>$vta->pln_codigo,
                    'con_valor_debe'=>round($rst->fac_total_valor, $dec),
                    'con_valor_haber'=>round($sub, $dec),
                    'mod_id'=>'1',
                    'doc_id'=>$rst->fac_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );

        if ($rst->fac_subtotal12 != 0) {
            $dat1 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'FACTURA VENTA',
                        'con_documento'=>$rst->fac_numero,
                        'con_fecha_emision'=>$rst->fac_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$iva->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->fac_total_iva, $dec),
                        'mod_id'=>'1',
                        'doc_id'=>$rst->fac_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->fac_total_descuento != 0) {
            $dat3 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'FACTURA VENTA',
                        'con_documento'=>$rst->fac_numero,
                        'con_fecha_emision'=>$rst->fac_fecha_emision,
                        'con_concepto_debe'=>$des->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($rst->fac_total_descuento, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'1',
                        'doc_id'=>$rst->fac_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->fac_total_propina != 0) {
            $dat2 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'FACTURA VENTA',
                        'con_documento'=>$rst->fac_numero,
                        'con_fecha_emision'=>$rst->fac_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$pro->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->fac_total_propina, $dec),
                        'mod_id'=>'1',
                        'doc_id'=>$rst->fac_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        $array = array($dat0, $dat1, $dat2, $dat3);
        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
        }

    }

    public function asientos_pagos($id){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $factura=$this->factura_model->lista_una_factura($id);

        $cns=$this->factura_model->lista_pagos_factura_ctasxcob($id);

        foreach ($cns as $rst) {
            
            $asiento = $asiento =$this->asiento_model->siguiente_asiento();
            $ccli=$this->plan_cuentas_model->lista_un_plan_cuentas($rst->pln_id);
            $cban=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo($rst->cta_banco);
            
            $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->cta_concepto,
                        'con_documento'=>$factura->fac_numero,
                        'con_fecha_emision'=>$rst->cta_fecha,
                        'con_concepto_debe'=>$cban->pln_codigo,
                        'con_concepto_haber'=>$ccli->pln_codigo,
                        'con_valor_debe'=>round($rst->cta_monto, $dec),
                        'con_valor_haber'=>round($rst->cta_monto, $dec),
                        'mod_id'=>'10',
                        'doc_id'=>$rst->cta_id,
                        'cli_id'=>$factura->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$factura->emp_id,
                    );

            $this->asiento_model->insert($data);
                   
        }


    }

   
	public function asiento_anulacion($id,$mod){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $cns=$this->asiento_model->lista_asientos_modulo($id,$mod);
        $asiento = $asiento =$this->asiento_model->siguiente_asiento();

        foreach ($cns as $rst) {
            
            $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'ANULACION '.$rst->con_concepto,
                        'con_documento'=>$rst->con_documento,
                        'con_fecha_emision'=>date('Y-m-d'),
                        'con_concepto_debe'=>$rst->con_concepto_haber,
                        'con_concepto_haber'=>$rst->con_concepto_debe,
                        'con_valor_debe'=>round($rst->con_valor_haber, $dec),
                        'con_valor_haber'=>round($rst->con_valor_debe, $dec),
                        'mod_id'=>$rst->mod_id,
                        'doc_id'=>$rst->doc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
                    );

            $this->asiento_model->insert($data);
                   
        }

    } 
   
   public function show_pdf_2($id,$opc_id,$correo,$etiqueta,$tip){
     	   $email= $this->input->post('email');
    		$rst=$this->factura_model->lista_una_factura($id);
    		$imagen=$this->set_barcode($rst->fac_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
			$cns_pag=array();
			$cl=$this->cliente_model->lista_un_cliente($rst->cli_id);

            
			if(empty($correo)){
				$this->html2pdf->output(array("Attachment" => 0));	
			}else if($correo==1){
				$datos_mail=(object) array('tipo' =>'FACTURA' ,
                              'cliente'=>$rst->fac_nombre,
                              'clave_usu'=>$cl->cli_codigo,
                              'emisor'=>$rst->emp_nombre,
                              'numero'=>$rst->fac_numero,
                              'fecha'=>$rst->fac_fecha_emision,
                              'total'=>$rst->fac_total_valor,
                              'correo'=>$email,
                              'fac_id'=>$rst->fac_id,
                              'logo'=>$rst->emp_logo,
                              'clave'=>$rst->fac_clave_acceso,
                                 );
			if($tip==2)
			{
				$dat_cl=array(
							 'cli_email'=>$email,
					        ); 
				$this->cliente_model->update($rst->cli_id,$dat_cl);	 

				
			}
			$this->envio_mail($datos_mail);
			}
    	
    }


}
