<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_liquidacion extends CI_Controller {

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
		$this->load->model('reg_liquidacion_model');
		$this->load->model('retencion_model');
		$this->load->model('impuesto_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('forma_pago_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('ctasxpagar_model');
		$this->load->model('empresa_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('tipo_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('asiento_model');
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
		///buscador 
		if($_POST){
			$text= trim($this->input->post('txt'));
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_facturas=$this->reg_liquidacion_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_facturas=$this->reg_liquidacion_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'facturas'=>$cns_facturas,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
					
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('reg_liquidacion/lista',$data);
		$modulo=array('modulo'=>'reg_liquidacion');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			//valida cuentas asientos completos
			$cuentas="";
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuentas)){
					$valida_asiento=1;
				}
				$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1');
			}
			$usu_id=$this->session->userdata('s_idusuario');
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());

			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_productos'=>$this->reg_liquidacion_model->lista_productos('1'),
						'cns_cuentas'=>$cuentas,
						'tipo_documentos'=>$this->reg_liquidacion_model->lista_tipo_documentos('1'),
						'cns_sustento'=>$this->reg_liquidacion_model->lista_sustento_documentos('1'),
						'paises'=>$this->reg_liquidacion_model->lista_paises('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'categorias'=>$this->reg_liquidacion_model->lista_categorias(),
						'familias'=>$this->tipo_model->lista_familias_todos(),
						'tipos'=>$this->tipo_model->lista_tipos_todos(),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=> (object) array(
											'reg_fregistro'=>date('Y-m-d'),
											'reg_femision'=>date('Y-m-d'),
											'reg_fecha_comprobante'=>'',
											'reg_fautorizacion'=>date('Y-m-d'),
											'reg_fcaducidad'=>date('Y-m-d'),
											'reg_tipo_comprobante'=>'99',
					                        'reg_sustento'=>'0',
					                        'reg_num_comprobante'=>'',
					                        'reg_aut_comprobante'=>'',
					                        'reg_tpcliente'=>'LOCAL',
					                        'cli_ced_ruc'=>'',
					                        'cli_raz_social'=>'',
					                        'cli_id'=>'',
					                        'cli_calle_prin'=>'',
					                        'cli_telefono'=>'',
					                        'cli_email'=>'',
					                        'reg_id'=>'',
					                        'reg_concepto'=>'',
					                        'reg_importe'=>'',
					                        'reg_pais_importe'=>'241',
					                        'reg_tipo_pago'=>'01',
					                        'reg_forma_pago'=>'1',
					                        'reg_relacionado'=>'NO',
					                        'reg_sbt12'=>'0',
											'reg_sbt0'=>'0',
											'reg_sbt_noiva'=>'0',
											'reg_sbt_excento'=>'0',
											'reg_sbt'=>'0',
											'reg_tdescuento'=>'0',
											'reg_ice'=>'0',
											'reg_irbpnr'=>'0',
											'reg_iva12'=>'0',
											'reg_propina'=>'0',
											'reg_total'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
										),
						'cns_det'=>'',
						'cns_pag'=>'',
						'action'=>base_url().'reg_liquidacion/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						'retencion'=>0,
						);
			$this->load->view('reg_liquidacion/form',$data);
			$modulo=array('modulo'=>'reg_liquidacion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$emi_id=$rst_cja->emi_id;
		$cja_id=$rst_cja->cja_id;
		$emp_id = $this->input->post('emp_id');
		$reg_fregistro = $this->input->post('reg_fregistro');
		$reg_femision = $this->input->post('reg_femision');
		$reg_fautorizacion = $this->input->post('reg_fautorizacion');
		$reg_fcaducidad = $this->input->post('reg_fcaducidad');
		$reg_tipo_comprobante= $this->input->post('reg_tipo_comprobante');
		$reg_sustento= $this->input->post('reg_sustento');
		$reg_pais_importe= $this->input->post('reg_pais_importe');
		$reg_tipo_pago= $this->input->post('reg_tipo_pago');
		$reg_forma_pago= $this->input->post('reg_forma_pago');
		$reg_num_comprobante= $this->input->post('reg_num_comprobante');
		$reg_aut_comprobante= $this->input->post('reg_aut_comprobante');
		$reg_tpcliente= $this->input->post('reg_tpcliente');
		$reg_ruc_cliente = $this->input->post('reg_ruc_cliente');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$reg_concepto = $this->input->post('reg_concepto');
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
		$count_det=$this->input->post('count_detalle');
		$count_pag=$this->input->post('count_pagos');
		$verifica_cuenta=$this->input->post('verifica_cuenta');
		$reg_relacionado=$this->input->post('reg_relacionado');
		$reg_fecha_comprobante = $this->input->post('reg_fecha_comprobante');
		
		$this->form_validation->set_rules('reg_fregistro','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_fecha_comprobante','Fecha de Emision de Comprobante','required');
		$this->form_validation->set_rules('reg_sustento','Sustento','required');
		$this->form_validation->set_rules('reg_tipo_pago','Tipo Pago','required');
		$this->form_validation->set_rules('reg_forma_pago','Forma de Pago','required');
		$this->form_validation->set_rules('reg_relacionado','Documento Relacionado','required');
		$this->form_validation->set_rules('reg_tpcliente','Tipo Proveedor','required');
		$this->form_validation->set_rules('reg_ruc_cliente','Proveedor RUC/CI','required');
		$this->form_validation->set_rules('nombre','Proveedor Razon Social','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('reg_concepto','Concepto','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){

			///secuencial de Liquidacion
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

			
			$rst_sec = $this->reg_liquidacion_model->lista_secuencial_documento($emi_id,$cja_id);
		    if (empty($rst_sec)) {
		        $sec = $rst_cja->cja_sec_liquidacion;
		    } else {
		    	$sc=explode('-',$rst_sec->reg_num_documento);
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
		    $reg_num_documento = $ems . '-'.$caja.'-' . $tx . $sec;

		    $clave_acceso=$this->clave_acceso($cja_id,$reg_num_documento,$reg_femision);

		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'reg_fregistro'=>$reg_fregistro, 
							'reg_femision'=>$reg_femision,
							'reg_fautorizacion'=>$reg_fautorizacion,
							'reg_fcaducidad'=>$reg_fcaducidad,
							'reg_tipo_documento'=>'3',
							'reg_sustento'=>$reg_sustento, 
							'reg_pais_importe'=>$reg_pais_importe, 
							'reg_tipo_pago'=>$reg_tipo_pago,
							'reg_forma_pago'=>$reg_forma_pago,
							'reg_num_documento'=>$reg_num_documento,
							'reg_tpcliente'=>$reg_tpcliente, 
							'reg_ruc_cliente'=>$reg_ruc_cliente, 
							'reg_concepto'=>$reg_concepto, 
							'reg_sbt12'=>$subtotal12, 
							'reg_sbt0'=>$subtotal0, 
							'reg_sbt_noiva'=>$subtotalex, 
							'reg_sbt_excento'=>$subtotalno, 
							'reg_tdescuento'=>$total_descuento, 
							'reg_ice'=>$total_ice, 
							'reg_iva12'=>$total_iva, 
							'reg_sbt'=>$subtotal, 
							'reg_propina'=>$total_propina,
							'reg_total'=>$total_valor,
							'reg_estado'=>'4',
							'reg_relacionado'=>$reg_relacionado,
							'reg_tipo_comprobante'=>$reg_tipo_comprobante,
							'reg_num_comprobante'=>$reg_num_comprobante,
							'reg_fecha_comprobante'=>$reg_fecha_comprobante,
							'reg_aut_comprobante'=>$reg_aut_comprobante,
							'reg_clave_acceso'=>$clave_acceso,
							'emi_id'=>$emi_id,
							'cja_id'=>$cja_id,
		    );


		    $fac_id=$this->reg_liquidacion_model->insert($data);
		    if(!empty($fac_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("pro_aux$n")!=null){
		    			$pro_id = $this->input->post("pro_aux$n");
		    			$dfc_codigo = $this->input->post("pro_descripcion$n");
		    			$dfc_cod_aux = $this->input->post("pro_descripcion$n");
		    			$dfc_cantidad = $this->input->post("cantidad$n");
		    			$dfc_precio_unit = $this->input->post("pro_precio$n");
		    			$dfc_porcentaje_descuento = $this->input->post("descuento$n");
		    			$dfc_val_descuento = $this->input->post("descuent$n");
		    			$dfc_precio_total = $this->input->post("valor_total$n");
		    			$dfc_iva = $this->input->post("iva$n");
		    			$pln_id = $this->input->post("pln_id$n");
		    			$reg_codigo_cta = $this->input->post("reg_codigo_cta$n");
		    			$dt_det=array(
		    							'reg_id'=>$fac_id,
	                                    'pro_id'=>$pro_id,
	                                    'det_codigo_empresa'=>$dfc_codigo,
	                                    'det_codigo_externo'=>$dfc_cod_aux,
	                                    'det_cantidad'=>$dfc_cantidad,
	                                    'det_vunit'=>$dfc_precio_unit,
	                                    'det_descuento_porcentaje'=>$dfc_porcentaje_descuento,
	                                    'det_descuento_moneda'=>$dfc_val_descuento,
	                                    'det_total'=>$dfc_precio_total,
	                                    'det_impuesto'=>$dfc_iva,
	                                    'pln_id'=>$pln_id,
	                                    'reg_codigo_cta'=>$reg_codigo_cta,
		    						);
		    			$this->reg_liquidacion_model->insert_detalle($dt_det);
		    		}
		    	}

		    	///pagos
		    	$m=0;
		    	while($m<$count_pag){
		    		$m++;
		    		if($this->input->post("pag_porcentage$m")!=null){
		    			$pag_porcentage=$this->input->post("pag_porcentage$m");
		    			$pag_dias=$this->input->post("pag_dias$m");
		    			$pag_valor=$this->input->post("pag_valor$m");
		    			$pag_fecha_v=$this->input->post("pag_fecha_v$m");
		    			$dt_pag=array(
		    							'pag_tipo'=>9,
							            'pag_porcentage'=>$pag_porcentage,
							            'pag_dias'=>$pag_dias,
							            'pag_valor'=>$pag_valor, 
							            'pag_fecha_v'=>$pag_fecha_v,
							            'reg_id'=>$fac_id,
							            'pag_estado'=>1
		    						);
		    			$this->reg_liquidacion_model->insert_pagos($dt_pag);
		    		}
		    	}		
		    	//genera xml
		    	$this->generar_xml($fac_id,0);

		    	//genera asientos
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		        if($conf_as->con_valor==0){
		        	if($verifica_cuenta==0){
		        		$this->asientos($fac_id,$opc_id);
		        	}
		        }
		    	

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO LIQUIDACION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_liquidacion/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
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


	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;

			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_nc=$this->reg_liquidacion_model->lista_nota_credito_factura($id);
			$rst_ret=$this->reg_liquidacion_model->lista_retencion_factura($id);
			if(empty($rst_ret)){
				if(empty($rst_nc)){
					$rst_fac=$this->reg_liquidacion_model->lista_una_factura($id);
					$ctaxpg=$this->ctasxpagar_model->lista_ctasxpagar($id);

				    $up_dtp=array('pag_estado'=>3);
				    $this->reg_liquidacion_model->update_pagos($id,$up_dtp);
				    ///anular asientos pagos factura
				    if(!empty($ctaxpg)){
						foreach ($ctaxpg as $cxp) {
							if($cnf_as==0){
								$this->asiento_anulacion($cxp->ctp_id,'11');
							}
						}
					}	

				    $up_dtf=array('reg_estado'=>3);
					if($this->reg_liquidacion_model->update($id,$up_dtf)){
						//asiento anulacion factura
						if($cnf_as==0){
							$this->asiento_anulacion($id,'5');
						}

						$data_aud=array(
										'usu_id'=>$this->session->userdata('s_idusuario'),
										'adt_date'=>date('Y-m-d'),
										'adt_hour'=>date('H:i'),
										'adt_modulo'=>'REGISTO LIQUIDACION',
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
										'sms'=>'No se puede anular. El Documento tiene una Nota de Credito',
										'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
									);
					
				}
			}else{
					$data=array(
									'estado'=>1,
									'sms'=>'No se puede anular. El Documento tiene una Retencion',
									'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);
				
			}
			echo json_encode($data);	
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
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function doc_duplicado($id,$num,$tip){
		$rst=$this->reg_liquidacion_model->lista_doc_duplicado($id,$num,$tip);
		if(!empty($rst)){
			echo $rst->reg_id;
		}else{
			echo "";
		}
	}
	public function load_producto($id){

		$rst=$this->producto_comercial_model->lista_un_producto_cod($id);
		if(empty($rst)){
			$rst=$this->producto_comercial_model->lista_un_producto($id);
		}
		if(!empty($rst)){
			$data=array(
						'pro_id'=>$rst->id,
						'pro_descripcion'=>$rst->mp_d,
						'pro_codigo'=>$rst->mp_c,
						'pro_precio'=>$rst->mp_e,
						'pro_iva'=>$rst->mp_h,
						'pro_descuento'=>$rst->mp_g,
						'pro_unidad'=>$rst->mp_q,
						'ice_p'=>$rst->mp_j,
						'ice_cod'=>$rst->mp_l,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Liquidacion de Compras '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_liquidacion/show_pdf/$id/$opc_id",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'reg_liquidacion');
			$this->load->view('layout/footer',$modulo);
		}
    }

	

    public function show_pdf($id,$opc_id){
    		$rst=$this->reg_liquidacion_model->lista_una_factura($id);
    		$imagen=$this->set_barcode($rst->reg_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			
			///recupera detalle
			$cns_dt=$this->reg_liquidacion_model->lista_detalle_factura($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_precio'=>$rst_dt->det_vunit,
						'pro_iva'=>$rst_dt->det_impuesto,
						'pro_descuento'=>$rst_dt->det_descuento_porcentaje,
						'pro_descuent'=>$rst_dt->det_descuento_moneda,
						'pro_unidad'=>$rst_dt->mp_q,
						'cantidad'=>$rst_dt->det_cantidad,
						'ice'=>'0',
						'ice_p'=>'0',
						'ice_cod'=>'0',
						'precio_tot'=>$rst_dt->det_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			///recupera pagos
			$cns_pg=$this->reg_liquidacion_model->lista_forma_pagos_factura($id);
			$cns_pag=array();
			foreach ($cns_pg as $rst_pg) {
			$dt_pag=(object) array(
						'pag_id'=>$rst_pg->pag_id,
						'fpg_codigo'=>$rst_pg->fpg_codigo,
						'fpg_descripcion_sri'=>$rst_pg->fpg_descripcion_sri,
						'pag_cant'=>$rst_pg->pag_valor,
						);	
				
				array_push($cns_pag, $dt_pag);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=>$this->reg_liquidacion_model->lista_una_factura($id),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						);
			$this->html2pdf->filename('reg_liquidacion.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_liquidacion', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));	
    }  

    public function set_barcode($code)
	{
 
        $this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$imageResource = Zend_Barcode::factory('code39', 'image', array('text' => "$code", 'barHeight'=> 50,'factor'=> 1, 'drawText'=>false), array())->draw();
		$path="./barcodes/$code.png";
		imagepng($imageResource, $path);
	}  


    
    public function traer_familias($categoria){
		$familias=$this->tipo_model->lista_familia_categoria($categoria);
		$lista="<option value='0'>SELECCIONE</option>";
		foreach ($familias as $rst) {
			$lista.="<option value='$rst->tps_id'>$rst->tps_nombre</option>";
		}
		 echo $lista;
	}

	public function nuevo_producto(){
		
		$data=$_REQUEST['data'];
		if($data['ids']==2){
			$ids='26';
		}else{
			$ids='79';
		}

		$dt_prod=array(
											'ids'=>$ids,
											'mp_a'=>$data['mp_a'],
					                        'mp_b'=>$data['mp_b'],
					                        'mp_c'=>$data['mp_c'],
					                        'mp_d'=>$data['mp_d'],
					                        'mp_q'=>$data['mp_q'],
					                        'mp_e'=>$data['mp_e'],
					                        'mp_f'=>'0',
					                        'mp_g'=>'0',
					                        'mp_h'=>$data['mp_h'],
					                        'mp_i'=>'1',
			);	
		

			if($this->producto_comercial_model->insert($dt_prod)){
				$rst=$this->producto_comercial_model->lista_un_producto_cod($data['mp_c']);
				$lista="";
				$cns_productos=$this->reg_liquidacion_model->lista_productos('1');
				if(!empty($cns_productos)){
          			foreach ($cns_productos as $rst_pro) {
						$lista.="<option value='$rst_pro->id'>$rst_pro->mp_c $rst_pro->mp_d</option>";
					}
				}
				$dt_resp=array(
					'id'=>$rst->id,
					'lista'=>$lista	
				);

				echo json_encode($dt_resp);
			}else{
				$resp_no=array('id'=>'0');
				echo json_encode($resp_no);
			}

	}

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Registro Factura '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="reg_liquidacions".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function traer_cuenta($id){
		$rst=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo(trim($id));
		
		if(!empty($rst)){

			$data=array(
						'pln_id'=>$rst->pln_id,
						'pln_codigo'=>$rst->pln_codigo,
						'pln_descripcion'=>$rst->pln_descripcion,
						);
			echo json_encode($data);
		
		
		}else{
			echo "";
		}

	}

	public function asientos($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emi_id=$rst_cja->emi_id;

        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->reg_liquidacion_model->lista_una_factura($id);
        $cns=$this->reg_liquidacion_model->lista_sum_cuentas($id);

        
        $rst_ret=$this->retencion_model->lista_una_retencion_factura($id);
        $cns_ret=$this->retencion_model->lista_detalle_retencion($rst_ret->ret_id);

        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('26',$emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('2',$emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('27',$emi_id);
        $des=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('30',$emi_id);
        $pro=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('31',$emi_id);
        
        $rst_as=$this->asiento_model->lista_asientos_modulo($rst->reg_id,'5');
        if(empty($rst_as[0]->con_asiento)){
        	$asiento =$this->asiento_model->siguiente_asiento();
    	}else{
    		///elimina asiento
    		$asiento=$rst_as[0]->con_asiento;
    		$this->asiento_model->delete($rst_as[0]->con_asiento);
    	}
        
        //PROVEEDOR y FACTURA COMPRA
        $dat0 = array();
        $dat1 = array();
        $dat2 = array();
        $dat3 = array();

        if($rst->cli_tipo_cliente==0){
        	$ccli=$cli;
        }else{
        	$ccli=$cex;
        }

        if(!empty($rst_ret)){
        	$total=round($rst->reg_total, $dec)-round($rst_ret->ret_total_valor, $dec);
        }else{
        	$total=round($rst->reg_total, $dec);
        }
        $dat0 = Array(
                    'con_asiento'=>$asiento,
                    'con_concepto'=>$rst->reg_concepto,
                    'con_documento'=>$rst->reg_num_documento,
                    'con_fecha_emision'=>$rst->reg_femision,
                    'con_concepto_debe'=>'',
                    'con_concepto_haber'=>$ccli->pln_codigo,
                    'con_valor_debe'=>'0.00',
                    'con_valor_haber'=>$total,
                    'mod_id'=>'5',
                    'doc_id'=>$rst->reg_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );


        if ($rst->reg_iva12 != 0) {
            $dat1 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->reg_concepto,
                        'con_documento'=>$rst->reg_num_documento,
                        'con_fecha_emision'=>$rst->reg_femision,
                        'con_concepto_debe'=>$iva->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($rst->reg_iva12, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'5',
                        'doc_id'=>$rst->reg_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->reg_tdescuento != 0) {
            $dat3 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->reg_concepto,
                        'con_documento'=>$rst->reg_num_documento,
                        'con_fecha_emision'=>$rst->reg_femision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$des->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->reg_tdescuento, $dec),
                        'mod_id'=>'5',
                        'doc_id'=>$rst->reg_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->reg_propina != 0) {
            $dat2 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->reg_concepto,
                        'con_documento'=>$rst->reg_num_documento,
                        'con_fecha_emision'=>$rst->reg_femision,
                        'con_concepto_debe'=>$pro->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($rst->reg_propina, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'5',
                        'doc_id'=>$rst->reg_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }


        $array = array($dat0, $dat1, $dat2, $dat3);
         //asientos detalles
        foreach ($cns as $det) {
        	$dat4 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->reg_concepto,
                        'con_documento'=>$rst->reg_num_documento,
                        'con_fecha_emision'=>$rst->reg_femision,
                        'con_concepto_debe'=>$det->reg_codigo_cta,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($det->dtot,$dec) + round($det->ddesc, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'5',
                        'doc_id'=>$rst->reg_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
            array_push($array, $dat4);
        }

        if(!empty($cns_ret)){
        	foreach ($cns_ret as $dtr) {
        		$imp=$this->impuesto_model->lista_un_impuesto($dtr->por_id);
        		$dat5 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->reg_concepto,
                        'con_documento'=>$rst_ret->ret_numero,
                        'con_fecha_emision'=>$rst_ret->ret_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$imp->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($dtr->dtr_valor,$dec),
                        'mod_id'=>'4',
                        'doc_id'=>$rst_ret->ret_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
			        );
				array_push($array, $dat5);
			}
        }

        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
        }
    }

    public function traer_tipos(){
		$tipos=$this->tipo_model->lista_tipos_familia_2();
		$lista="<option value='0'>SELECCIONE</option>";
		foreach ($tipos as $rst) {
			$lista.="<option value='$rst->tps_id'>$rst->tps_nombre</option>";
		}
		 echo $lista;
	}

	public function traer_codigo($id1,$id2){

    	$conf=$this->configuracion_model->lista_una_configuracion('24');
    	if($conf->con_valor==0){
    		//$pro    = $this->producto_comercial_model->lista_by_tipo($id1,$id2);
    		$pro    = $this->producto_comercial_model->lista_by_tipo_count($id1,$id2);
	        $rst_s1 = $this->tipo_model->lista_un_tipo($id1);
            $rst_s2 = $this->tipo_model->lista_un_tipo($id2);
	        
           if(!empty($pro->n)){
           	 $sec = $pro->n + 1;
           }else{
           	$sec=1;
           }
            if ($sec > 0 && $sec < 10) {
                $txt = '00';
            } else if ($sec >= 10 && $sec < 100) {
                $txt = '0';
            } else if ($sec >=100) {
                $txt = '';
            } else if ($sec == '') {
                $txt = '001';
            }
         	echo $rst_s1->tps_siglas . '.' . $rst_s2->tps_siglas . '.' . $txt . $sec;
	    }else{
	     	echo "1";
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

    function clave_acceso($cja,$doc_numero,$doc_fecha){
		$cod_doc='03';
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


	    echo $clave = trim($f2 . $cod_doc . $rst->emp_identificacion . $ambiente . $ems . $pt_ems . $secuencial . $codigo . $tp_emison . $digito);
	    return $clave;
	}

	public function consulta_sri($id,$opc_id,$env){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
    	$ambiente=$amb->con_valor;
    	if($ambiente!=0){
	    	$liquidacion=$this->reg_liquidacion_model->lista_una_factura($id);
	        set_time_limit(0);
	        if ($ambiente == 2) { //Produccion
            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $liquidacion->reg_clave_acceso]);
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($liquidacion->reg_id,$env); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];

	        	if($res['estado']=='AUTORIZADO'){
	        		$data = array(
	            					'ret_autorizacion'=>$res['numeroAutorizacion'], 
	            					'ret_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'ret_xml_doc'=>$res['comprobante'], 
	            					'ret_estado'=>'6'
	            				);
	            	$this->reg_liquidacion_model->update($retencion->reg_id,$data);

	        		$data_xml = (object) array(
                					'estado'=>$res['estado'], 
                                    'autorizacion'=>$res['numeroAutorizacion'], 
                					'fecha'=>$res['fechaAutorizacion'], 
                					'comprobante'=>$res['comprobante'], 
                                    'ambiente'=>$res['ambiente'], 
                                    'clave'=>$liquidacion->reg_clave_acceso,
                                    'descarga'=>$env,
                				);
	        		$this->generar_xml_autorizado($data_xml,$liquidacion->reg_id,$opc_id); 
	        	}else{
	        		$this->generar_xml($liquidacion->reg_id,$env); 
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
    	$factura=$this->reg_liquidacion_model->lista_una_factura($id);
    	$detalle=$this->reg_liquidacion_model->lista_detalle_factura($factura->reg_id);
    	$pagos=$this->reg_liquidacion_model->lista_pagos_factura($factura->reg_id);
        $dec = $this->configuracion_model->lista_una_configuracion('2');
        $round=$dec->con_valor;
        $codigo='12345678';  
        $tp_emison='1';  
        $empresa=$this->empresa_model->lista_una_empresa($factura->emp_id);    
        $emisor=$this->emisor_model->lista_un_emisor($factura->emi_id);    
        $ndoc = explode('-', $factura->reg_num_documento);
        $nfact = str_replace('-', '', $factura->reg_num_documento);
        $ems = $ndoc[0];
        $emi = intval($ndoc[0]);
        $pt_ems = $ndoc[1];
        $secuencial = $ndoc[2];
        $cod_doc = '03'; //01= factura, 02=nota de credito tabla 4
        $fecha = date_format(date_create($factura->reg_femision), 'd/m/Y');
        $f2 = date_format(date_create($factura->reg_femision), 'dmY');
        $dir_cliente = $factura->cli_calle_prin;
        $telf_cliente = $factura->cli_telefono;
        $email_cliente = $factura->cli_email;
        $contabilidad = $empresa->emp_obligado_llevar_contabilidad;
        $razon_soc_comprador = $factura->cli_raz_social;
        $id_comprador = $factura->cli_ced_ruc;
        if (strlen($id_comprador) == 13 && $id_comprador != '9999999999999' && substr($id_comprador, -3) == '001') {
            $tipo_id_comprador = "04"; //RUC 04 
        } else if (strlen($id_comprador) == 10) {
            $tipo_id_comprador = "05"; //CEDULA 05 
        } else if ($id_comprador == '9999999999999') {
            $tipo_id_comprador = "07"; //VENTA A CONSUMIDOR FINAL
        } else {
            $tipo_id_comprador = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
        }
        

        $clave = $factura->reg_clave_acceso;

        $xml.="<?xml version='1.0' encoding='UTF-8'?>" . chr(13);
    	$xml.="<liquidacionCompra version='1.1.0' id='comprobante'>" . chr(13);
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
        $xml.="<infoLiquidacionCompra>" . chr(13);
        $xml.="<fechaEmision>" . $fecha . "</fechaEmision>" . chr(13);
        $xml.="<dirEstablecimiento>" . $emisor->emi_dir_establecimiento_emisor . "</dirEstablecimiento>" . chr(13);
        if(!empty($empresa->emp_contribuyente_especial)){
        	$xml.="<contribuyenteEspecial>$empresa->emp_contribuyente_especial</contribuyenteEspecial>" . chr(13);
    	}

        $xml.="<obligadoContabilidad>" . $contabilidad . "</obligadoContabilidad>" . chr(13);
        $xml.="<tipoIdentificacionProveedor>" . $tipo_id_comprador . "</tipoIdentificacionProveedor>" . chr(13);
        $xml.="<razonSocialProveedor>" . $razon_soc_comprador . "</razonSocialProveedor>" . chr(13);
        $xml.="<identificacionProveedor>" . $id_comprador . "</identificacionProveedor>" . chr(13);
        $xml.="<direccionProveedor>" . $dir_cliente . "</direccionProveedor>" . chr(13);
        $xml.="<totalSinImpuestos>" . round($factura->reg_sbt, $round) . "</totalSinImpuestos>" . chr(13);
        $xml.="<totalDescuento>" . round($factura->reg_tdescuento, $round) . "</totalDescuento>" . chr(13);

        $rst_comp=$this->reg_liquidacion_model->lista_un_documento($factura->reg_tipo_comprobante);
	    if(strlen($rst_comp->tdc_codigo)==1){
	            $rst_comp->tdc_codigo='0'.$rst_comp->tdc_codigo;
	        }
	    if($factura->reg_num_comprobante!=''){

	        $xml.="<codDocReembolso>" . $rst_comp->tdc_codigo . "</codDocReembolso>" . chr(13);
	        $xml.="<totalComprobantesReembolso>" . round($factura->reg_total,$round) . "</totalComprobantesReembolso>" . chr(13);
	        $xml.="<totalBaseImponibleReembolso>" . round($factura->reg_sbt,$round) . "</totalBaseImponibleReembolso>" . chr(13);
	        $xml.="<totalImpuestoReembolso>" . round($factura->reg_iva12,$round) . "</totalImpuestoReembolso>" . chr(13);
	    }
        $xml.="<totalConImpuestos>" . chr(13);
        ////******TODOS LOS IVA****************/////
        if ($factura->reg_sbt0 > 0) {//IVA 0
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>0</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->reg_sbt0, $round) . "</baseImponible>" . chr(13);
            $xml.="<valor>0.00</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }
        if ($factura->reg_sbt12 > 0) {//IVA 12
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>2</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->reg_sbt12 + $factura->reg_ice,$round) . "</baseImponible>" . chr(13);
            $xml.="<valor>" . round($factura->reg_iva12, $round) . "</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }
        if ($factura->reg_sbt_noiva > 0) { //NO OBJ
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>6</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->reg_sbt_noiva, $round) . "</baseImponible>" . chr(13);
            $xml.="<valor>0.00</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }
        if ($factura->reg_sbt_excento > 0) { //EXC
            $xml.="<totalImpuesto>" . chr(13);
            $xml.="<codigo>2</codigo>" . chr(13);
            $xml.="<codigoPorcentaje>7</codigoPorcentaje>" . chr(13);
            $xml.="<baseImponible>" . round($factura->reg_sbt_excento, $round) . "</baseImponible>" . chr(13);
            $xml.="<valor>0.00</valor>" . chr(13);
            $xml.="</totalImpuesto>" . chr(13);
        }

        $xml.="</totalConImpuestos>" . chr(13);
        $xml.="<importeTotal>" . round($factura->reg_total, $round) . "</importeTotal>" . chr(13);
        $xml.="<moneda>DOLAR</moneda>" . chr(13);

        ////pagos
        $xml.="<pagos>" . chr(13);
        $m = 0;
        
        foreach ($pagos as $pag) {
        	$rst_fp=$this->forma_pago_model->lista_una_forma_pago_id($factura->reg_forma_pago);
                $xml.="<pago>" . chr(13);
                $xml.="<formaPago>$rst_fp->fpg_codigo</formaPago>" . chr(13); ///pago sin utilizacion del sistema financiero
                $xml.="<total>" . round($pag->pag_valor, $round) . "</total>" . chr(13);
                $xml.="<plazo>" . round($pag->pag_dias) . "</plazo>" . chr(13);
                $xml.="</pago>" . chr(13);
        }
            
        $xml.="</pagos>" . chr(13);
        $xml.="</infoLiquidacionCompra>" . chr(13);
        $xml.="<detalles>" . chr(13);

        foreach ($detalle as $det) {
            
            $xml.="<detalle>" . chr(13);
            $xml.="<codigoPrincipal>" . trim($det->mp_c) . "</codigoPrincipal>" . chr(13);
			if (trim($det->mp_n) != '') {
	            $xml.="<codigoAuxiliar>" . trim($det->mp_n) . "</codigoAuxiliar>" . chr(13);
	        }
            $xml.="<descripcion>" . trim($det->mp_d) . "</descripcion>" . chr(13);
            $xml.="<cantidad>" . round($det->det_cantidad, $round) . "</cantidad>" . chr(13);
            $xml.="<precioUnitario>" . round($det->det_vunit, $round) . "</precioUnitario>" . chr(13);
            $xml.="<descuento>" . round($det->det_descuento_moneda, $round) . "</descuento>" . chr(13);
            $xml.="<precioTotalSinImpuesto>" . round($det->det_total, $round) . "</precioTotalSinImpuesto>" . chr(13);
            $xml.="<impuestos>" . chr(13);

            $xml.="<impuesto>" . chr(13);

            $xml.="<codigo>2</codigo>" . chr(13);
            $base_imp=$det->det_total;

            if ($det->det_impuesto == '12') {
                $tarifa = 12;
                $codPorc = 2;
                $valo_iva = round( $base_imp * 12 / 100, 2);
            }

            if ($det->det_impuesto == '0') {
                $tarifa = 0;
                $codPorc = 0;
                $valo_iva = 0.00;
            }
            if ($det->det_impuesto == 'NO') {
                $tarifa = 0;
                $codPorc = 6;
                $valo_iva = 0.00;
            }
            if ($det->det_impuesto == 'EX') {
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
        if($factura->reg_num_comprobante!=''){
	        $ex_nc=explode('-',$factura->reg_num_comprobante);
	        $fec=date_format(date_create($factura->reg_fecha_comprobante), 'd/m/Y');
	        $rst_pais=$this->reg_liquidacion_model->lista_un_pais($factura->reg_pais_importe);
	        
	        
	        $xml.="<reembolsos>" . chr(13);
	        $xml.="<reembolsoDetalle>" . chr(13);
	        $xml.="<tipoIdentificacionProveedorReembolso>" . $tipo_id_comprador . "</tipoIdentificacionProveedorReembolso>" . chr(13);

	        $xml.="<identificacionProveedorReembolso>" . $id_comprador . "</identificacionProveedorReembolso>" . chr(13);
	        $xml.="<codPaisPagoProveedorReembolso>" . $rst_pais->pai_codigo . "</codPaisPagoProveedorReembolso>" . chr(13);
	        $xml.="<tipoProveedorReembolso>0" . $factura->cli_categoria . "</tipoProveedorReembolso>" . chr(13);
	        $xml.="<codDocReembolso>" . $rst_comp->tdc_codigo . "</codDocReembolso>" . chr(13);
	        $xml.="<estabDocReembolso>" . $ex_nc[0] . "</estabDocReembolso>" . chr(13);
	        $xml.="<ptoEmiDocReembolso>" . $ex_nc[1] . "</ptoEmiDocReembolso>" . chr(13);
	        $xml.="<secuencialDocReembolso>" . $ex_nc[02] . "</secuencialDocReembolso>" . chr(13);
	        $xml.="<fechaEmisionDocReembolso>" . $fec . "</fechaEmisionDocReembolso>" . chr(13);
	        $xml.="<numeroautorizacionDocReemb>" . $factura->reg_aut_comprobante. "</numeroautorizacionDocReemb >" . chr(13);
	        $xml.="<detalleImpuestos>" . chr(13);
	        
	        if ($factura->reg_sbt0 > 0) {//IVA 0
	            $xml.="<detalleImpuesto>" . chr(13);
	            $xml.="<codigo>2</codigo>" . chr(13);
	            $xml.="<codigoPorcentaje>0</codigoPorcentaje>" . chr(13);
	            $xml.="<tarifa>0</tarifa>" . chr(13);
	            $xml.="<baseImponibleReembolso>" . round($factura->reg_sbt0, $round) . "</baseImponibleReembolso>" . chr(13);
	            $xml.="<impuestoReembolso>0.00</impuestoReembolso>" . chr(13);
	            $xml.="</detalleImpuesto>" . chr(13);
	        }
	        if ($factura->reg_sbt12 > 0) {//IVA 12
	            $xml.="<detalleImpuesto>" . chr(13);
	            $xml.="<codigo>2</codigo>" . chr(13);
	            $xml.="<codigoPorcentaje>2</codigoPorcentaje>" . chr(13);
	            $xml.="<tarifa>12</tarifa>" . chr(13);
	            $xml.="<baseImponibleReembolso>" . round($factura->reg_sbt12 + $factura->reg_ice, $round) . "</baseImponibleReembolso>" . chr(13);
	            $xml.="<impuestoReembolso>" . round($factura->reg_iva12, $round) . "</impuestoReembolso>" . chr(13);
	            $xml.="</detalleImpuesto>" . chr(13);
	        }
	        if ($factura->reg_sbt_noiva > 0) { //NO OBJ
	            $xml.="<detalleImpuesto>" . chr(13);
	            $xml.="<codigo>2</codigo>" . chr(13);
	            $xml.="<codigoPorcentaje>6</codigoPorcentaje>" . chr(13);
	            $xml.="<tarifa>0</tarifa>" . chr(13);
	            $xml.="<baseImponibleReembolso>" . round($factura->reg_sbt_noiva, $round) . "</baseImponibleReembolso>" . chr(13);
	            $xml.="<impuestoReembolso>0.00</impuestoReembolso>" . chr(13);
	            $xml.="</detalleImpuesto>" . chr(13);
	        }
	        if ($factura->reg_sbt_excento > 0) { //EXC
	            $xml.="<detalleImpuesto>" . chr(13);
	            $xml.="<codigo>2</codigo>" . chr(13);
	            $xml.="<codigoPorcentaje>7</codigoPorcentaje>" . chr(13);
	            $xml.="<tarifa>0</tarifa>" . chr(13);
	            $xml.="<baseImponibleReembolso>" . round($factura->reg_sbt_excento, $round) . "</baseImponibleReembolso>" . chr(13);
	            $xml.="<impuestoReembolso>0.00</impuestoReembolso>" . chr(13);
	            $xml.="</detalleImpuesto>" . chr(13);
	        }
	        $xml.="</detalleImpuestos>" . chr(13);
	        $xml.="</reembolsoDetalle>" . chr(13);
	        $xml.="</reembolsos>" . chr(13);
	    }
        $xml.="<infoAdicional>" . chr(13);
        $xml.="<campoAdicional nombre='Direccion'>" . $dir_cliente . "</campoAdicional>" . chr(13);
        $xml.="<campoAdicional nombre='Telefono'>" . $telf_cliente . "</campoAdicional>" . chr(13);
        $xml.="<campoAdicional nombre='Email'>" . strtolower($email_cliente) . "</campoAdicional>" . chr(13);
        if(!empty($factura->emp_leyenda_sri)){
        	$xml.="<campoAdicional nombre='Observaciones'> " .$factura->emp_leyenda_sri. "</campoAdicional>" . chr(13);
        }

        $xml.="</infoAdicional>" . chr(13);
        $xml.="</liquidacionCompra>" . chr(13);
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
			        $etiqueta='Retencion.pdf';
	            	$this->show_pdf($id,$opc_id,1,$etiqueta); 
	            }
        }

    }


}
 