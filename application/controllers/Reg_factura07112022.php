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
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('tipo_model');
		$this->load->library('html2pdf');
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
		if($_POST){
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


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
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
											'reg_femision'=>date('Y-m-d'),
											'reg_fautorizacion'=>date('Y-m-d'),
											'reg_fcaducidad'=>date('Y-m-d'),
											'reg_tipo_documento'=>'0',
					                        'reg_sustento'=>'0',
					                        'reg_num_documento'=>'',
					                        'reg_num_autorizacion'=>'',
					                        'reg_tpcliente'=>'0',
					                        'cli_ced_ruc'=>'',
					                        'cli_raz_social'=>'',
					                        'cli_id'=>'',
					                        'cli_calle_prin'=>'',
					                        'cli_telefono'=>'',
					                        'cli_email'=>'',
					                        'reg_id'=>'',
					                        'reg_concepto'=>'',
					                        'reg_importe'=>'',
					                        'reg_pais_importe'=>'0',
					                        'reg_tipo_pago'=>'0',
					                        'reg_forma_pago'=>'0',
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
						'action'=>base_url().'reg_factura/guardar/'.$opc_id
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
		
		$this->form_validation->set_rules('reg_fregistro','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_tipo_documento','Tipo Documento','required');
		$this->form_validation->set_rules('reg_sustento','Sustento','required');
		$this->form_validation->set_rules('reg_tipo_pago','Tipo Pago','required');
		$this->form_validation->set_rules('reg_forma_pago','Forma de Pago','required');
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
							'reg_estado'=>'4'
		    );


			// if($this->factura_model->insert($data)){
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
		    						);
		    			$this->reg_factura_model->insert_detalle($dt_det);
		    		}
		    	}

		    	///pago

		    			$dt_det=array(
		    							'pag_tipo'=>9,
							            'pag_porcentage'=>100,
							            'pag_dias'=>0,
							            'pag_valor'=>$total_valor, 
							            'pag_fecha_v'=>$reg_fcaducidad,
							            'reg_id'=>$fac_id,
							            'pag_estado'=>1
		    						);
		    			$this->reg_factura_model->insert_pagos($dt_det);
		    	
		    	

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO FACTURA',
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
				redirect(base_url().'reg_factura/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$rst=$this->reg_factura_model->lista_una_factura($id);
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){

			///recupera detalle
			$cns_dt=$this->reg_factura_model->lista_detalle_factura($id);
			$cns_det=array();
			
			foreach ($cns_dt as $rst_dt) {
			$rst_ct=$this->reg_factura_model->lista_una_categoria($rst_dt->ids);
			$rst_fm=$this->tipo_model->lista_un_tipo($rst_dt->mp_a);
			$rst_tp=$this->tipo_model->lista_un_tipo($rst_dt->mp_b);
			$ct=explode('&',$rst_ct->mp_tipo);
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'categoria'=>$ct[9],
						'mp_a'=>$rst_fm->tps_nombre,
						'mp_b'=>$rst_tp->tps_nombre,
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
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_productos'=>$this->reg_factura_model->lista_productos('1'),
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
						'action'=>base_url().'reg_factura/actualizar/'.$opc_id
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
		
		$this->form_validation->set_rules('reg_fregistro','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_tipo_documento','Tipo Documento','required');
		$this->form_validation->set_rules('reg_sustento','Sustento','required');
		$this->form_validation->set_rules('reg_tipo_pago','Tipo Pago','required');
		$this->form_validation->set_rules('reg_forma_pago','Forma de Pago','required');
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
							'reg_estado'=>'4'
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
		    						);
		    			$this->reg_factura_model->insert_detalle($dt_det);
		    		}
		    	}

		    	///pago
		    			$dt_det=array(
		    							'pag_tipo'=>9,
							            'pag_porcentage'=>100,
							            'pag_dias'=>0,
							            'pag_valor'=>$total_valor, 
							            'pag_fecha_v'=>$reg_fcaducidad,
							            'reg_id'=>$id,
							            'pag_estado'=>1
		    						);
		    			$this->reg_factura_model->update_pagos($id,$dt_det);	
		    	}

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO FACTURA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
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
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_nc=$this->reg_factura_model->lista_nota_credito_factura($id);
			if(empty($rst_nc)){
				$rst_fac=$this->reg_factura_model->lista_una_factura($id);
			    $up_dtp=array('pag_estado'=>3);
			    $this->reg_factura_model->update_pagos($id,$up_dtp);
			    $up_dtf=array('reg_estado'=>3);
				if($this->reg_factura_model->update($id,$up_dtf)){
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
			$rst_ct=$this->reg_factura_model->lista_una_categoria($rst->ids);
			$dt=explode('&',$rst_ct->mp_tipo);
			$data=array(
						'pro_id'=>$rst->id,
						'ids'=>$rst->ids,
						'pro_cat'=>$dt[9],
						'mp_a'=>$rst->mp_a,
						'pro_a'=>$rst->tps_nombre,
						'mp_b'=>$rst->mp_b,
						'pro_b'=>$rst->tip_nombre,
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
						'factura'=>$this->reg_factura_model->lista_una_factura($id),
						'cns_det'=>$cns_det,
						);
			$this->html2pdf->filename('reg_factura.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_reg_factura', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
			// $this->load->view('pdf/pdf_reg_factura',$data);
			
    	
		
    	
    }  

    
    public function traer_tipos($f){
		$familias=$this->tipo_model->lista_tipos_familia($f);
		$lista="<option value='0'>SELECCIONE</option>";
		foreach ($familias as $rst) {
			$lista.="<option value='$rst->tps_id'>$rst->tps_nombre</option>";
		}
		 echo $lista;
	}

	public function nuevo_producto(){
		
		$data=$_POST['data'];
		$dt_prod=array(
											'ids'=>$data['ids'],
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
				$rst_ct=$this->reg_factura_model->lista_una_categoria($rst->ids);
				$dt=explode('&',$rst_ct->mp_tipo);
				$lista="";
				$cns_productos=$this->reg_factura_model->lista_productos('1');
				if(!empty($cns_productos)){
          			foreach ($cns_productos as $rst_pro) {
						$lista.="<option value='$rst_pro->id'>$rst_pro->mp_c $rst_pro->mp_d</option>";
					}
				}
				$dt_resp=array(
					'ids'=>$rst->id,
					'pro_cat'=>strtoupper($dt[9]),
					'pro_a'=>$rst->tps_nombre,
					'pro_b'=>$rst->tip_nombre,
					'lista'=>$lista	
				);

				echo json_encode($dt_resp);
			}else{
				$resp_no=array('ids'=>'0');
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
}
 