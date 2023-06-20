<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_factura extends CI_Controller {

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
		$this->load->model('reg_factura_model');
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
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('tipo_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->library('html2pdf');
		$this->load->library('html4pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
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
		if(!empty($_POST)){
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_facturas=$this->reg_factura_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_facturas=$this->reg_factura_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
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
		$this->load->view('reg_factura/lista',$data);
		$modulo=array('modulo'=>'reg_factura');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id,$registro){
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
				//$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1');
				$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo_2('1','1');
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
						'cns_productos'=>$this->reg_factura_model->lista_productos('1'),
						'cns_cuentas'=>$cuentas,
						'tipo_documentos'=>$this->reg_factura_model->lista_tipo_documentos('1'),
						'cns_sustento'=>$this->reg_factura_model->lista_sustento_documentos('1'),
						'paises'=>$this->reg_factura_model->lista_paises('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'categorias'=>$this->reg_factura_model->lista_categorias(),
						'familias'=>$this->tipo_model->lista_familias_todos(),
						'tipos'=>$this->tipo_model->lista_tipos_todos(),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=> (object) array(
											'reg_fregistro'=>date('Y-m-d'),
											'reg_femision'=>'',
											'reg_fautorizacion'=>date('Y-m-d'),
											'reg_fcaducidad'=>date('Y-m-d'),
											'reg_tipo_documento'=>'0',
					                        'reg_sustento'=>'0',
					                        'reg_num_documento'=>'',
					                        'reg_num_autorizacion'=>'',
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
					                        'reg_observaciones'=>'',
										),
						'cns_det'=>'',
						'cns_pag'=>'',
						'action'=>base_url().'reg_factura/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						'retencion'=>0,
						'ctaxpagar'=>0,
						'ret_url'=>base_url().'retencion/nuevo_reg/'.$opc_id,
						'registro'=>$registro,
						);
			$this->load->view('reg_factura/form',$data);
			$modulo=array('modulo'=>'reg_factura');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$emp_id = $this->input->post('emp_id');
		$reg_fregistro = $this->input->post('reg_fregistro');
		$reg_femision = $this->input->post('reg_femision');
		$reg_fautorizacion = $this->input->post('reg_fautorizacion');
		$reg_fcaducidad = $this->input->post('reg_fcaducidad');
		$reg_tipo_documento= $this->input->post('reg_tipo_documento');
		$reg_sustento= $this->input->post('reg_sustento');
		$reg_pais_importe= $this->input->post('reg_pais_importe');
		$reg_tipo_pago= $this->input->post('reg_tipo_pago');
		$reg_forma_pago= $this->input->post('reg_forma_pago');
		$reg_num_documento= $this->input->post('reg_num_documento');
		$reg_num_autorizacion= $this->input->post('reg_num_autorizacion');
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
		$reg_observaciones=$this->input->post('reg_observaciones');
		
		$this->form_validation->set_rules('reg_fregistro','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_tipo_documento','Tipo Documento','required');
		$this->form_validation->set_rules('reg_sustento','Sustento','required');
		$this->form_validation->set_rules('reg_tipo_pago','Tipo Pago','required');
		$this->form_validation->set_rules('reg_forma_pago','Forma de Pago','required');
		$this->form_validation->set_rules('reg_relacionado','Documento Relacionado','required');
		$this->form_validation->set_rules('reg_num_documento','Numero Documento','required');
		$this->form_validation->set_rules('reg_num_autorizacion','Numero Autorizacion','required');
		$this->form_validation->set_rules('reg_tpcliente','Tipo Proveedor','required');
		$this->form_validation->set_rules('reg_ruc_cliente','Proveedor RUC/CI','required');
		$this->form_validation->set_rules('nombre','Proveedor Razon Social','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('reg_concepto','Concepto','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			///auditoria
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO FACTURA',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$reg_num_documento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);

			///inserccion y actualizacion del cliente

			if(empty($cli_id)){
						if (strlen($reg_ruc_cliente) < 11) {
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
                        if($reg_tpcliente=='LOCAL'){
                        	$tpc=0;
                        }else{
                        	$tpc=1;
                        }
				$dat_cl=array(
							  'cli_apellidos'=>$nombre,
							  'cli_raz_social'=>$nombre,
							  'cli_nom_comercial'=>$nombre,
							  'cli_fecha'=>$reg_fregistro,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'2',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$reg_ruc_cliente,
							  'cli_calle_prin'=>$direccion_cliente,
							  'cli_telefono'=>$telefono_cliente,
							  'cli_email'=>$email_cliente,
							  'cli_codigo'=>$retorno,
							  'cli_tipo_cliente'=>$tpc

								);
				$cli_id=$this->reg_factura_model->insert_cliente($dat_cl);
			}else{
				$dat_cl=array(
								'cli_calle_prin'=>$direccion_cliente,
					            'cli_email'=>$email_cliente,
					            'cli_telefono'=>$telefono_cliente,
					        ); 
				$this->cliente_model->update($cli_id,$dat_cl);	 
			}


		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'reg_fregistro'=>$reg_fregistro, 
							'reg_femision'=>$reg_femision,
							'reg_fautorizacion'=>$reg_fautorizacion,
							'reg_fcaducidad'=>$reg_fcaducidad,
							'reg_tipo_documento'=>$reg_tipo_documento,
							'reg_sustento'=>$reg_sustento, 
							'reg_pais_importe'=>$reg_pais_importe, 
							'reg_tipo_pago'=>$reg_tipo_pago,
							'reg_forma_pago'=>$reg_forma_pago,
							'reg_num_documento'=>$reg_num_documento,
							'reg_num_autorizacion'=>$reg_num_autorizacion, 
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
							'reg_observaciones'=>$reg_observaciones,
		    );


		    $fac_id=$this->reg_factura_model->insert($data);
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
		    			$this->reg_factura_model->insert_detalle($dt_det);
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
		    			$this->reg_factura_model->insert_pagos($dt_pag);
		    		}
		    	}		
		    	
		    	//genera asientos
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		        if($conf_as->con_valor==0){
		        	if($verifica_cuenta==0){
		        		$this->asientos($fac_id,$opc_id);
		        	}
		        }
		    	

				
				// $rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				$this->nuevo($opc_id,$fac_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_factura/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id,0);
		}	

	}

	public function editar($id,$opc_id){
		$rst=$this->reg_factura_model->lista_una_factura($id);
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
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

			///recupera detalle
			$cns_dt=$this->reg_factura_model->lista_detalle_factura($id);
			$cns_det=array();
			
			foreach ($cns_dt as $rst_dt) {
			if($rst_dt->ids=='26'){
				$ids='2';
			}else{
				$ids='9';
			}
			$rst_ct=$this->reg_factura_model->lista_una_categoria($ids);
			$rst_fm=$this->tipo_model->lista_un_tipo($rst_dt->mp_a);
			$rst_tp=$this->tipo_model->lista_un_tipo($rst_dt->mp_b);
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
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
						'pln_id'=>$rst_dt->pln_id,
						'reg_codigo_cta'=>$rst_dt->reg_codigo_cta,
						);	
				
				array_push($cns_det, $dt_det);
			}
			$rst_ret=$this->retencion_model->lista_una_retencion_factura($id);
			$retencion=0;
			if(!empty($rst_ret)){
				$retencion=1;
			}

			$rst_cxp=$this->ctasxpagar_model->lista_ctasxpagar($id);
			$cxp=0;
			if(!empty($rst_cxp)){
				$cxp=1;
			}
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_productos'=>$this->reg_factura_model->lista_productos('1'),
						'cns_cuentas'=>$cuentas,
						'tipo_documentos'=>$this->reg_factura_model->lista_tipo_documentos('1'),
						'cns_sustento'=>$this->reg_factura_model->lista_sustento_documentos('1'),
						'paises'=>$this->reg_factura_model->lista_paises('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'categorias'=>$this->reg_factura_model->lista_categorias(),
						'familias'=>$this->tipo_model->lista_familias_todos(),
						'tipos'=>$this->tipo_model->lista_tipos_todos(),
						'factura'=>$this->reg_factura_model->lista_una_factura($id),
						'cns_det'=>$cns_det,
						'cns_pagos'=>$this->reg_factura_model->lista_pagos_factura($id),
						'action'=>base_url().'reg_factura/actualizar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						'retencion'=>$retencion,
						'ctaxpagar'=>$cxp,
						'ret_url'=>base_url().'retencion/nuevo_reg/'.$opc_id,
						'registro'=>0,
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reg_factura/form',$data);
			$modulo=array('modulo'=>'reg_factura');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('reg_id');
		$emp_id = $this->input->post('emp_id');
		$reg_fregistro = $this->input->post('reg_fregistro');
		$reg_femision = $this->input->post('reg_femision');
		$reg_fautorizacion = $this->input->post('reg_fautorizacion');
		$reg_fcaducidad = $this->input->post('reg_fcaducidad');
		$reg_tipo_documento= $this->input->post('reg_tipo_documento');
		$reg_sustento= $this->input->post('reg_sustento');
		$reg_pais_importe= $this->input->post('reg_pais_importe');
		$reg_tipo_pago= $this->input->post('reg_tipo_pago');
		$reg_forma_pago= $this->input->post('reg_forma_pago');
		$reg_num_documento= $this->input->post('reg_num_documento');
		$reg_num_autorizacion= $this->input->post('reg_num_autorizacion');
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
		$verifica_cuenta=$this->input->post('verifica_cuenta');
		$reg_relacionado=$this->input->post('reg_relacionado');
		$count_pag=$this->input->post('count_pagos');
		$reg_observaciones=$this->input->post('reg_observaciones');
		$retencion=$this->input->post('retencion');
		$ctaxpagar=$this->input->post('ctaxpagar');
		
		$this->form_validation->set_rules('reg_fregistro','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_tipo_documento','Tipo Documento','required');
		$this->form_validation->set_rules('reg_sustento','Sustento','required');
		$this->form_validation->set_rules('reg_tipo_pago','Tipo Pago','required');
		$this->form_validation->set_rules('reg_forma_pago','Forma de Pago','required');
		$this->form_validation->set_rules('reg_relacionado','Documento Relacionado','required');
		$this->form_validation->set_rules('reg_num_documento','Numero Documento','required');
		$this->form_validation->set_rules('reg_num_autorizacion','Numero Autorizacion','required');
		$this->form_validation->set_rules('reg_tpcliente','Tipo Proveedor','required');
		$this->form_validation->set_rules('reg_ruc_cliente','Proveedor RUC/CI','required');
		$this->form_validation->set_rules('nombre','Proveedor Razon Social','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('reg_concepto','Concepto','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			//auditoria
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO FACTURA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$reg_num_documento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);

			///inserccion y actualizacion del cliente

			if(empty($cli_id)){
						if (strlen($reg_ruc_cliente) < 11) {
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
                        if($reg_tpcliente=='LOCAL'){
                        	$tpc=0;
                        }else{
                        	$tpc=1;
                        }
				$dat_cl=array(
							  'cli_apellidos'=>$nombre,
							  'cli_raz_social'=>$nombre,
							  'cli_nom_comercial'=>$nombre,
							  'cli_fecha'=>$reg_fregistro,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'2',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$reg_ruc_cliente,
							  'cli_calle_prin'=>$direccion_cliente,
							  'cli_telefono'=>$telefono_cliente,
							  'cli_email'=>$email_cliente,
							  'cli_codigo'=>$retorno,
							  'cli_tipo_cliente'=>$tpc

								);
				$cli_id=$this->reg_factura_model->insert_cliente($dat_cl);
			}else{
				$dat_cl=array(
								'cli_calle_prin'=>$direccion_cliente,
					            'cli_email'=>$email_cliente,
					            'cli_telefono'=>$telefono_cliente,
					        ); 
				$this->cliente_model->update($cli_id,$dat_cl);	 
			}

		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'reg_fregistro'=>$reg_fregistro, 
							'reg_femision'=>$reg_femision,
							'reg_fautorizacion'=>$reg_fautorizacion,
							'reg_fcaducidad'=>$reg_fcaducidad,
							'reg_tipo_documento'=>$reg_tipo_documento,
							'reg_sustento'=>$reg_sustento, 
							'reg_pais_importe'=>$reg_pais_importe, 
							'reg_tipo_pago'=>$reg_tipo_pago,
							'reg_forma_pago'=>$reg_forma_pago,
							'reg_num_documento'=>$reg_num_documento,
							'reg_num_autorizacion'=>$reg_num_autorizacion, 
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
							'reg_observaciones'=>$reg_observaciones,
		    );


			if($this->reg_factura_model->update($id,$data)){
				if($this->reg_factura_model->delete_detalle($id)){
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
		    							'reg_id'=>$id,
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
		    			$this->reg_factura_model->insert_detalle($dt_det);
		    		}
		    	}
		    }	

		    ///pagos
		    if($retencion==0 && $ctaxpagar==0){
			    if($this->reg_factura_model->delete_pagos($id)){
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
								            'reg_id'=>$id,
								            'pag_estado'=>1
			    						);
			    			$this->reg_factura_model->insert_pagos($dt_pag);
			    		}
			    	}			
			    }
			}    

		    	//genera asientos
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		        if($conf_as->con_valor==0){
		        	if($verifica_cuenta==0){
		        		$this->asientos($id,$opc_id);
		        	}	
		        }

				
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

				///validacion cuando tiene retencion 
				$rst_reg=$this->reg_factura_model->lista_retencion_factura($id);	
				if(!empty($rst_reg->reg_id)){
					redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				}else{
					$this->nuevo($opc_id,$id);
				}
			
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'reg_factura/editar'.$id.'/'.$opc_id);
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


	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;

			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_nc=$this->reg_factura_model->lista_nota_credito_factura($id);
			$rst_ret=$this->reg_factura_model->lista_retencion_factura($id);
			if(empty($rst_ret)){
				if(empty($rst_nc)){
					$rst_fac=$this->reg_factura_model->lista_una_factura($id);
					$ctaxpg=$this->ctasxpagar_model->lista_ctasxpagar($id);

				    $up_dtp=array('pag_estado'=>3);
				    $this->reg_factura_model->update_pagos($id,$up_dtp);
				    ///anular asientos pagos factura
				    if(!empty($ctaxpg)){
						foreach ($ctaxpg as $cxp) {
							if($cnf_as==0){
								$this->asiento_anulacion($cxp->ctp_id,'11');
							}
						}
					}	

				    $up_dtf=array('reg_estado'=>3);
					if($this->reg_factura_model->update($id,$up_dtf)){
						//asiento anulacion factura
						if($cnf_as==0){
							$this->asiento_anulacion($id,'5');
						}

						$data_aud=array(
										'usu_id'=>$this->session->userdata('s_idusuario'),
										'adt_date'=>date('Y-m-d'),
										'adt_hour'=>date('H:i'),
										'adt_modulo'=>'REGISTO FACTURA',
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
		$rst=$this->reg_factura_model->lista_doc_duplicado($id,$num,$tip);
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
			$rst_cta=$this->reg_factura_model->lista_ultima_cta_prod($rst->id);
			if(!empty($rst_cta)){
				$pln_id=$rst_cta->pln_id;
				$reg_codigo_cta=$rst_cta->reg_codigo_cta;
			}else{
				$pln_id=0;
				$reg_codigo_cta='';
			}
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
						'pln_id'=>$pln_id,
						'reg_codigo_cta'=>$reg_codigo_cta,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Registo Factura '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_factura/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'reg_factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

	

    public function show_pdf($id,$opc_id){
    		$rst=$this->reg_factura_model->lista_una_factura($id);
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			
			///recupera detalle
			$cns_dt=$this->reg_factura_model->lista_detalle_factura($id);
			$cns_det=array();
			$factura = $this->reg_factura_model->lista_una_factura_emp($id);
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
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=>$this->reg_factura_model->lista_una_factura_emp($id),
						'asientos'=>$this->asiento_model->asiento_reg_fac($id),
            			'ret' =>$this->retencion_model->lista_una_retencion_fac($factura->reg_num_documento),
						'cns_det'=>$cns_det,
						);
			$this->html4pdf->filename('reg_factura.pdf');
			$this->html4pdf->paper('a4', 'portrait');
    		$this->html4pdf->html(utf8_decode($this->load->view('pdf/pdf_reg_factura', $data, true)));
    		$this->html4pdf->output(array("Attachment" => 0));
			// $this->load->view('pdf/pdf_reg_factura',$data);
			
    	
		
    	
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
				$cns_productos=$this->reg_factura_model->lista_productos('1');
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
    	$file="reg_facturas".date('Ymd');
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

        $rst=$this->reg_factura_model->lista_una_factura($id);
        $cns=$this->reg_factura_model->lista_sum_cuentas($id);

        
        $rst_ret=$this->retencion_model->lista_una_retencion_factura($id);
        if(!empty($rst_ret)){
        	$cns_ret=$this->retencion_model->lista_detalle_retencion($rst_ret->ret_id);
    	}

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

    public function subir_xml($xml,$opc_id){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emp_id=$rst_cja->emp_id;
		$emi_id=$rst_cja->emi_id;
		$cja_id=$rst_cja->cja_id;

		$config['upload_path']='./reg_facturas/';
		$config['allowed_types']='xml';
		$config['max_size']='50000';
		$config['file_name']=date('Ymd') . '_reg_factura.xml';
		$this->load->library('upload',$config);
		if($this->upload->do_upload($xml)){
			$info=$this->upload->data();
			$file_info=$info['file_name'];
			$sms=0;

			///verificar si existe cliente o si la factura ya esta registrada
			$check = $this->check_file($file_info,$emp_id);
		    if (strlen($check) == 1) {
		       echo $this->load_file($file_info,$emp_id,$emi_id,$cja_id);
		    } else {
		    	if (!unlink('./reg_facturas/'.$file_info)) {
		          // Error al borrar.
			          echo 'Error al borrar';
			    }
		        echo $check;
		    }

		}else{
			echo $file_info=$this->upload->display_errors();
		}
		

	}

	public function check_file($archivo,$emp_id) {
		$direccion="./reg_facturas/".$archivo;
	    $sms=0;

	    ///remplazo de tildes;
	    $arch = fopen($direccion,'r');
	    $texto="";
	    while ($linea = fgets($arch, 1024)){
		      $texto.= utf8_encode($linea);
		   }
	    $fch = fopen($direccion, "w+o");
        
		fwrite($fch, $texto);
		fclose($fch);

	    $xml = simplexml_load_file($direccion, 'SimpleXMLElement', LIBXML_NOCDATA) or die("No lee");
	    $comp=simplexml_load_string($xml->comprobante[0]);

	    $ruc=$comp->infoTributaria->ruc;
	    $num_factura=$comp->infoTributaria->estab.'-'.$comp->infoTributaria->ptoEmi.'-'.$comp->infoTributaria->secuencial;
	    $rst_cli=$this->cliente_model->lista_un_cliente_cedula($ruc);
	    $cod_doc=$comp->infoTributaria->codDoc;
	    if($cod_doc!='01'){
	    	$sms = "El Documento no es una Factura";
	    }else{
		    if(empty($rst_cli)){


		        $sms = "1";

		        if (strlen($ruc) < 11) {
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
                        // if($reg_tpcliente=='LOCAL'){
                        // 	$tpc=0;
                        // }else{
                        // 	$tpc=1;
                        // }
                      $reg_fregistro = date("Y-m-d");
				$dat_cl=array(
							  'cli_apellidos'=>$comp->infoTributaria->razonSocial,
							  'cli_raz_social'=>$comp->infoTributaria->razonSocial,
							  'cli_nom_comercial'=>$comp->infoTributaria->nombreComercial,
							  'cli_fecha'=>$reg_fregistro,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'2',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$comp->infoTributaria->ruc,
							  'cli_calle_prin'=>$comp->infoTributaria->dirMatriz,
							  'cli_codigo'=>$retorno,
							  'cli_tipo_cliente'=>0

								);
				$cli_id=$this->reg_factura_model->insert_cliente($dat_cl);
				if(!empty($cli_id))
				{

				}



		    }

		    $rst_reg=$this->reg_factura_model->lista_factura_numero_cedula($num_factura,$ruc,$emp_id);
		    if(!empty($rst_reg->reg_id)){
		        $sms = "Numero de Documento ya ingresado";
		    }
		}



    	return $sms;
	}


	public function load_file($archivo,$emp_id,$emi_id,$cja_id) {
    	$direccion="./reg_facturas/".$archivo;
	    $xml = simplexml_load_file($direccion, 'SimpleXMLElement', LIBXML_NOCDATA) or die("No lee");
	    $comp=simplexml_load_string($xml->comprobante[0]);
	    
	    $ruc=$comp->infoTributaria->ruc;
	    $rst_cli=$this->cliente_model->lista_un_cliente_cedula($ruc);

	    $num_factura=$comp->infoTributaria->estab.'-'.$comp->infoTributaria->ptoEmi.'-'.$comp->infoTributaria->secuencial;
	    $iva='0';
	    $sub12='0';
	    $sub0='0';
	    foreach ($comp->infoFactura->totalConImpuestos->totalImpuesto as $fimp) {
	        if($fimp->codigo=='2'){
	            if($fimp->codigoPorcentaje == '2') {
	                $iva = $fimp->valor;
	                $sub12=$fimp->baseImponible;
	            }
	            
	            if($fimp->codigoPorcentaje == '0') {
	                $sub0= $fimp->baseImponible;
	            }
	        }
	    }

	    if($rst_cli->cli_tipo_cliente==0){
	    	$tipo_prov='LOCAL';
	    }else{
	    	$tipo_prov='EXTRANJERO';
	    }

	    $encabezado=array(
                        'reg_fregistro'=>date('Y-m-d'),
                        'reg_femision'=>$comp->infoFactura->fechaEmision,
                        'reg_num_documento'=>$num_factura,
                        'reg_num_autorizacion'=>$xml->numeroAutorizacion,
                        'reg_fautorizacion'=>'1900-01-01',//fec_aut
                        'reg_fcaducidad'=>'1900-01-01',//fec_aut_hasta
                        'reg_sbt12'=>$sub12,//sub12
                        'reg_sbt0'=>$sub0,//sub0
                        'reg_sbt'=>$comp->infoFactura->totalSinImpuestos,//subtotal
                        'reg_tdescuento'=>$comp->infoFactura->totalDescuento,
                        'reg_iva12'=>$iva,
                        'reg_total'=>$comp->infoFactura->importeTotal,//total
                        'reg_ruc_cliente'=>$comp->infoTributaria->ruc,
                        'cli_id'=>$rst_cli->cli_id,
                        'reg_estado'=>'7',//estado
                        'reg_tipo_documento'=>round($comp->infoTributaria->codDoc),
                        'reg_sustento'=>'0',
                        'emp_id'=>$emp_id,
                        'emi_id'=>$emi_id,
                        'cja_id'=>$cja_id,
                        'reg_tpcliente'=>$tipo_prov,
                        'reg_tipo_pago'=>'01',
                        'reg_forma_pago'=>'1',
                        'reg_pais_importe'=>'241',
                        'reg_relacionado'=>'NO',

                        
                    );
    
    	$fac_id=$this->reg_factura_model->insert($encabezado);
    	
    	foreach ($comp->detalles->detalle as $det) {
	        $codigo=str_replace("'",'', $det->codigoPrincipal);   
	        $descripcion=str_replace("'",'', $det->descripcion);   
	        $codigo=str_replace('"','', $codigo);  
	        $descripcion=str_replace('"','', $descripcion); 
	        $rst_pro=$this->producto_comercial_model->lista_un_producto_codigo_aux($codigo); 

	        $codImp='0';    
	        foreach ($det->impuestos->impuesto as $imp) {
	            if($imp->codigo=='2'){
	                if($imp->codigoPorcentaje == '2') {
	                    $codImp = '12';
	                }
	                else if($imp->codigoPorcentaje == '0') {
	                    $codImp = '0';
	                }
	                else if($imp->codigoPorcentaje == '6') {
	                    $codImp = 'NO';
	                }
	                else if($imp->codigoPorcentaje == '7') {
	                    $codImp = 'EX';
	                }
	            }
	        }

	        if(empty($rst_pro->id)){
	        	//codigo producto
	        	$rst_s1 = $this->tipo_model->lista_un_tipo('127');
	            $rst_s2 = $this->tipo_model->lista_un_tipo('126');
		        $pro    = $this->producto_comercial_model->lista_by_tipo_count('126','127');
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
	            } 
	         	$codigo_prod= $rst_s1->tps_siglas . '.' . $rst_s2->tps_siglas . '.' . $txt . $sec;


	           $dt_pro=array(  'ids'=>'80',  //ids,
	                           'mp_c'=>$codigo_prod, //mp_c,
	                           'mp_n'=>strtoupper($codigo), //mp_n,
	                           'mp_d'=>strtoupper($descripcion), //mp_d,
	                           'mp_a'=>'126', //mp_a,
	                           'mp_b'=>'127',//mp_b,
	                           'mp_e'=>$det->precioUnitario,//mp_b,
	                           'mp_i'=>'0',//mp_i
	                           'mp_q'=>'UNIDAD',
	                           'mp_f'=>'0',
	                           'mp_g'=>'0',
	                           'mp_h'=>$codImp,
	                        );  
	           $this->producto_comercial_model->insert($dt_pro);
	        }else{
	        	$codigo_prod=$rst_pro->mp_c;
	        }   

	        $rst_pro=$this->producto_comercial_model->lista_un_producto_codigo($codigo_prod); 
	        

	        $detalle=array(     
	                             'det_cantidad'=>$det->cantidad,
	                             'det_vunit'=>$det->precioUnitario,
	                             'det_descuento_porcentaje'=>0,
	                             'det_descuento_moneda'=>$det->descuento,
	                             'det_total'=>$det->precioTotalSinImpuesto,
	                             'det_impuesto'=>$codImp,
	                             'det_tipo'=>'80',
	                             'pln_id'=>'0',
	                             'reg_codigo_cta'=>'',
	                             'pro_id'=>$rst_pro->id,
	                             'reg_id'=>$fac_id
	                         );

            $this->reg_factura_model->insert_detalle($detalle);
        }

        $f=explode('/', $comp->infoFactura->fechaEmision);
        $fecha =$f[2].'-'.$f[1].'-'.$f[0];
        $fecha_venc = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $fecha_venc = date ( 'Y-m-d' , $fecha_venc );
         
        $pagos= array(	'pag_tipo'=>'9',
        				'pag_porcentage'=>'100',
                        'pag_dias'=>'1',
                        'pag_valor'=>$comp->infoFactura->importeTotal,
                        'pag_fecha_v'=>$fecha_venc, 
                        'reg_id'=>$fac_id, 
                        'pag_estado'=>'1'
                    );
        $this->reg_factura_model->insert_pagos($pagos);
        $sms = "Subido correctamente";

        if (!unlink($direccion)) {
          // Error al borrar.
	          echo 'Error al borrar';
	    } 
        return $sms;
	}

	public function show_rpt_pdf($opc_id){

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$text= $this->input->post('txt');
		$ids= $this->input->post('tipo');
		$f1= $this->input->post('fec1');
		$f2= $this->input->post('fec2');	


		if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_factura/show_pdf_2/$opc_id/$f1/$f2/$text",
					'permisos'=>$this->permisos,
					'opc_id'=>$rst_opc->opc_id	
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'ctasxcobrar');
			$this->load->view('layout/footer',$modulo);
		}



	}

	public function show_pdf_2($opc_id,$f1,$f2,$text=''){


		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$cns_facturas=$this->reg_factura_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);

		$data=array(
					'permisos'=>$this->permisos,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'facturas'=>$cns_facturas,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					
				);
		$this->load->view('pdf/pdf_regfac_rpt',$data);

		// $this->html2pdf->filename('regfac_rpt.pdf');
		// $this->html2pdf->paper('a4', 'portrait');
		// $this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_regfac_rpt', $data, true)));
		// $this->html2pdf->output(array("Attachment" => 0));




	}
}
 