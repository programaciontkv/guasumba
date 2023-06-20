<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_retencion extends CI_Controller {

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
		$this->load->model('factura_model');
		$this->load->model('reg_retencion_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('impuesto_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
		$this->load->model('ctasxcobrar_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->library('html2pdf');
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
			$text= trim($this->input->post('txt'));
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_retenciones=$this->reg_retencion_model->lista_retencion_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_retenciones=$this->reg_retencion_model->lista_retencion_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'rentenciones'=>$cns_retenciones,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('reg_retencion/lista',$data);
		$modulo=array('modulo'=>'reg_retencion');
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

			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_impuestos'=>$this->impuesto_model->lista_impuestos_reg_retencion(),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=> (object) array(
											'rgr_fec_registro'=>date('Y-m-d'),
											'rgr_fecha_emision'=>date('Y-m-d'),
											'rgr_fec_autorizacion'=>date('Y-m-d'),
											'rgr_fec_caducidad'=>date('Y-m-d'),
											'rgr_numero'=>'',
											'rgr_autorizacion'=>'',
											'rgr_num_comp_retiene'=>'',
											'fac_id'=>'0',
					                        'cli_id'=>'',
					                        'rgr_identificacion'=>'',
					                        'rgr_nombre'=>'',
					                        'rgr_direccion'=>'',
					                        'rgr_telefono'=>'',
					                        'rgr_email'=>'',
					                        'rgr_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'rgr_id'=>'',
					                        'fac_subtotal'=>'0',
					                        'fac_total_iva'=>'0',
										),
						'cns_det'=>'',
						'action'=>base_url().'reg_retencion/guardar/'.$opc_id,
						'saldo'=>0,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						);
			$this->load->view('reg_retencion/form',$data);
			$modulo=array('modulo'=>'reg_retencion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$fac_id= $this->input->post('fac_id');
		$rgr_numero= $this->input->post('rgr_numero');
		$rgr_autorizacion= $this->input->post('rgr_autorizacion');
		$rgr_num_comp_retiene= $this->input->post('rgr_num_comp_retiene');
		$rgr_fec_registro= $this->input->post('rgr_fec_registro');
		$rgr_fecha_emision= $this->input->post('rgr_fecha_emision');
		$rgr_fec_autorizacion= $this->input->post('rgr_fec_autorizacion');
		$rgr_fec_caducidad= $this->input->post('rgr_fec_caducidad');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('rgr_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rgr_num_comp_retiene','Factura No','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'fac_id'=>$fac_id,
							'rgr_denominacion_comp'=>'1',
							'rgr_fecha_emision'=>$rgr_fecha_emision,
							'rgr_fec_registro'=>$rgr_fec_registro,
							'rgr_fec_autorizacion'=>$rgr_fec_autorizacion,
							'rgr_fec_caducidad'=>$rgr_fec_caducidad,
							'rgr_numero'=>$rgr_numero, 
							'rgr_autorizacion'=>$rgr_autorizacion, 
							'rgr_nombre'=>$nombre, 
							'rgr_identificacion'=>$identificacion, 
							'rgr_num_comp_retiene'=>$rgr_num_comp_retiene, 
							'rgr_total_valor'=>$total_valor,
							'rgr_estado'=>'4'
		    );


		    $rgr_id=$this->reg_retencion_model->insert($data);
		    if(!empty($rgr_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("drr_base_imponible$n")!=null){
		    			$por_id = $this->input->post("por_id$n");
		    			$drr_ejercicio_fiscal = $this->input->post("drr_ejercicio_fiscal$n");
		    			$drr_base_imponible = $this->input->post("drr_base_imponible$n");
		    			$drr_codigo_impuesto = $this->input->post("drr_codigo_impuesto$n");
		    			$drr_procentaje_retencion = $this->input->post("drr_procentaje_retencion$n");
		    			$drr_valor = $this->input->post("drr_valor$n");
		    			$drr_tipo_impuesto = $this->input->post("drr_tipo_impuesto$n");
		    			$dt_det=array(
		    							'rgr_id'=>$rgr_id,
	                                    'por_id'=>$por_id,
	                                    'drr_ejercicio_fiscal'=>$drr_ejercicio_fiscal,
	                                    'drr_base_imponible'=>$drr_base_imponible,
	                                    'drr_codigo_impuesto'=>$drr_codigo_impuesto,
	                                    'drr_procentaje_retencion'=>$drr_procentaje_retencion,
	                                    'drr_valor'=>$drr_valor,
	                                    'drr_tipo_impuesto'=>$drr_tipo_impuesto,
		    						);
		    			$this->reg_retencion_model->insert_detalle($dt_det);
		    		}
		    	
		    	}

		    	///genera asiento
		    	$pln_id=0;
		    	$banco='';
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		        if($conf_as->con_valor==0){
		        	$this->asientos($rgr_id,$opc_id);

		        	$ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('65',$rst_cja->emi_id);
		        	$ccxc=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('21',$rst_cja->emi_id);
		        	$pln_id=$ccli->pln_id;
		        	$banco=$ccxc->pln_codigo; 
		        }

		    	///control cobros
		    	$data_chq=array(	
				    				'emp_id'=>$emp_id,
				    				'cli_id'=>$cli_id,
				    				'chq_recepcion'=>date('Y-m-d'),
									'chq_fecha'=>$rgr_fecha_emision,
									'chq_tipo_doc'=>'7', 
									'chq_nombre'=>'RETENCION', 
									'chq_concepto'=>'ABONO FAC.'.$rgr_num_comp_retiene,
									'chq_banco'=>'',
									'chq_numero'=>$rgr_numero,
									'chq_monto'=>$total_valor,
									'chq_cobro'=>$total_valor,
									'chq_estado'=>'9',
									'chq_estado_cheque'=>'11',
									'doc_id'=>$rgr_id
		    	);
		    	$chq_id=$this->cheque_model->insert($data_chq);

		    	///ctasxcobrar
		    	$ctaxcob=array(	
			    				'com_id'=>$fac_id,
			    				'cta_fecha_pago'=>$rgr_fecha_emision,
								'cta_forma_pago'=>'7',
								'num_documento'=>$rgr_numero, 
								'cta_concepto'=>'ABONO FAC.'.$rgr_num_comp_retiene, 
								'cta_monto'=>$total_valor,
								'cta_fecha'=>date('Y-m-d'),
								'chq_id'=>$chq_id,
								'cta_estado'=>'1',
								'pln_id'=>$pln_id,
								'cta_banco'=>$banco,
								'emp_id'=>$emp_id,
				);
				$cta_id=$this->ctasxcobrar_model->insert($ctaxcob);	

		    	
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO RETENCION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'reg_retencion/show_frame/'. $rgr_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_retencion/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

		if($permisos->rop_actualizar){
			
			//valida cuentas asientos completos
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuentas)){
					$valida_asiento=1;
				}
			}
			$rst_ret=$this->reg_retencion_model->lista_una_retencion($id);
			$rst_sal=$this->reg_retencion_model->lista_saldo_factura($rst_ret->fac_id);
		
			if(!empty($rst_sal->credito)){
				$saldo=round($rst_sal->total,$dec)+round($rst_ret->rgr_total_valor,$dec)-round($rst_sal->credito,$dec);
			}else{
				$saldo=round($rst_sal->total,$dec);
			}
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_impuestos'=>$this->impuesto_model->lista_impuestos_reg_retencion(),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=>$this->reg_retencion_model->lista_una_retencion($id),
						'cns_det'=>$this->reg_retencion_model->lista_detalle_retencion($id),
						'action'=>base_url().'reg_retencion/actualizar/'.$opc_id,
						'saldo'=>$saldo,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reg_retencion/form',$data);
			$modulo=array('modulo'=>'reg_retencion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$id = $this->input->post('rgr_id');
		$fac_id= $this->input->post('fac_id');
		$rgr_numero= $this->input->post('rgr_numero');
		$rgr_autorizacion= $this->input->post('rgr_autorizacion');
		$rgr_num_comp_retiene= $this->input->post('rgr_num_comp_retiene');
		$rgr_fec_registro= $this->input->post('rgr_fec_registro');
		$rgr_fecha_emision= $this->input->post('rgr_fecha_emision');
		$rgr_fec_autorizacion= $this->input->post('rgr_fec_autorizacion');
		$rgr_fec_caducidad= $this->input->post('rgr_fec_caducidad');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('rgr_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rgr_num_comp_retiene','Factura No','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');

		if($this->form_validation->run()){
		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'fac_id'=>$fac_id,
							'rgr_denominacion_comp'=>'1',
							'rgr_fecha_emision'=>$rgr_fecha_emision,
							'rgr_fec_registro'=>$rgr_fec_registro,
							'rgr_fec_autorizacion'=>$rgr_fec_autorizacion,
							'rgr_fec_caducidad'=>$rgr_fec_caducidad,
							'rgr_numero'=>$rgr_numero, 
							'rgr_autorizacion'=>$rgr_autorizacion, 
							'rgr_nombre'=>$nombre, 
							'rgr_identificacion'=>$identificacion, 
							'rgr_num_comp_retiene'=>$rgr_num_comp_retiene, 
							'rgr_total_valor'=>$total_valor,
							'rgr_estado'=>'4'
		    );

			if($this->reg_retencion_model->update($id,$data)){
				if($this->reg_retencion_model->delete_detalle($id)){
			    	$n=0;
			    	while($n<$count_det){
			    		$n++;
			    		if($this->input->post("drr_base_imponible$n")!=null){
			    			$por_id = $this->input->post("por_id$n");
			    			$drr_ejercicio_fiscal = $this->input->post("drr_ejercicio_fiscal$n");
			    			$drr_base_imponible = $this->input->post("drr_base_imponible$n");
			    			$drr_codigo_impuesto = $this->input->post("drr_codigo_impuesto$n");
			    			$drr_procentaje_retencion = $this->input->post("drr_procentaje_retencion$n");
			    			$drr_valor = $this->input->post("drr_valor$n");
			    			$drr_tipo_impuesto = $this->input->post("drr_tipo_impuesto$n");
			    			$dt_det=array(
			    							'rgr_id'=>$id,
		                                    'por_id'=>$por_id,
		                                    'drr_ejercicio_fiscal'=>$drr_ejercicio_fiscal,
		                                    'drr_base_imponible'=>$drr_base_imponible,
		                                    'drr_codigo_impuesto'=>$drr_codigo_impuesto,
		                                    'drr_procentaje_retencion'=>$drr_procentaje_retencion,
		                                    'drr_valor'=>$drr_valor,
		                                    'drr_tipo_impuesto'=>$drr_tipo_impuesto,
			    						);
		    				$this->reg_retencion_model->insert_detalle($dt_det);
		    			}
			    	
			    	}
		    	}			
		    	
		    	//anular ctasxcobrar
		    	$cheque=$this->cheque_model->lista_cheque_retencion($id);
		    	if(!empty($cheque)){
			    	$dt_cta=array('cta_estado'=>'3');
			    	$this->ctasxcobrar_model->update_ctascob_cheque($cheque->chq_id,$dt_cta);
		    	}

		    	//anular cheque
		    	$dt_chq=array('chq_estado'=>'3',
							  'chq_estado_cheque'=>'3',);
		    	$this->cheque_model->update_chq_retencion($id,$dt_chq);

		    	///genera asiento

				$pln_id=0;
				$banco='';
				$conf_as=$this->configuracion_model->lista_una_configuracion('4');
				if($conf_as->con_valor==0){
					$this->asientos($id,$opc_id);

					$ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('65',$rst_cja->emi_id);
					$ccxc=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('21',$rst_cja->emi_id);
					$pln_id=$ccli->pln_id;
					$banco=$ccxc->pln_codigo; 
				}

		    	///control cobros
		    	$data_chq=array(	
				    	'emp_id'=>$emp_id,
				    	'cli_id'=>$cli_id,
				    	'chq_recepcion'=>date('Y-m-d'),
						'chq_fecha'=>$rgr_fecha_emision,
						'chq_tipo_doc'=>'7', 
						'chq_nombre'=>'RETENCION', 
						'chq_concepto'=>'ABONO FAC.'.$rgr_num_comp_retiene,
						'chq_banco'=>'',
						'chq_numero'=>$rgr_numero,
						'chq_monto'=>$total_valor,
						'chq_cobro'=>$total_valor,
						'chq_estado'=>'9',
						'chq_estado_cheque'=>'11',
						'doc_id'=>$id
		    	);
		    	$chq_id=$this->cheque_model->insert($data_chq);

		    	///ctasxcobrar
		    	$ctaxcob=array(	
			    	'com_id'=>$fac_id,
			    	'cta_fecha_pago'=>$rgr_fecha_emision,
					'cta_forma_pago'=>'7',
					'num_documento'=>$rgr_numero, 
					'cta_concepto'=>'ABONO FAC.'.$rgr_num_comp_retiene,
					'cta_monto'=>$total_valor,
					'cta_fecha'=>date('Y-m-d'),
					'chq_id'=>$chq_id,
					'cta_estado'=>'1',
					'pln_id'=>$pln_id,
					'cta_banco'=>$banco,
					'emp_id'=>$emp_id,
				);
							
				$cta_id=$this->ctasxcobrar_model->insert($ctaxcob);	

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO RETENCION',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'reg_retencion/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'reg_retencion/editar'.$id.'/'.$opc_id);
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
		    $up_dtf=array('rgr_estado'=>3);
			if($this->reg_retencion_model->update($id,$up_dtf)){
				//anular ctasxcobrar
		    	$cheque=$this->cheque_model->lista_cheque_retencion($id);
		    	$dt_cta=array('cta_estado'=>'3');
		    	$this->ctasxcobrar_model->update_ctascob_cheque($cheque->chq_id,$dt_cta);

		    	//anular cheque
		    	$dt_chq=array('chq_estado'=>'3',
							  'chq_estado_cheque'=>'3',);
		    	$this->cheque_model->update_chq_retencion($id,$dt_chq);

		    	///anular asiento
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
				$cnf_as=$conf_as->con_valor;
				if($cnf_as==0){
					$this->asiento_anulacion($id,'8');
				}

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO RETENCION',
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
				echo json_encode($data);
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
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function load_impuesto($id){

		$rst=$this->impuesto_model->lista_un_impuesto_cod($id);
		if(empty($rst)){
			$rst=$this->impuesto_model->lista_un_impuesto($id);
		}
		if(!empty($rst)){
			$data=array(
						'por_id'=>$rst->por_id,
						'por_descripcion'=>$rst->por_descripcion,
						'por_codigo'=>$rst->por_codigo,
						'por_porcentage'=>$rst->por_porcentage,
						'por_siglas'=>$rst->por_siglas,
						'cta_id'=>$rst->cta_id,
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
					'titulo'=>'Registro Retencion '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_retencion/show_pdf/$id/$opc_id",
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
			$modulo=array('modulo'=>'reg_retencion');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id){
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=>$this->reg_retencion_model->lista_una_retencion($id),
						'cns_det'=>$this->reg_retencion_model->lista_detalle_retencion($id),
						);
			// $this->load->view('pdf/pdf_reg_retencion', $data);
			$this->html2pdf->filename('reg_retencion.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_reg_retencion', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    }


	public function traer_facturas($num,$emp){
		$rst=$this->factura_model->lista_factura_num_empresa($num,$emp);
		echo json_encode($rst);
	}

	public function load_factura($id,$dec,$dcc){
		$rst_dec=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$rst_dec->con_valor;
		$rst=$this->factura_model->lista_una_factura($id);
		$rst_sal=$this->reg_retencion_model->lista_saldo_factura($id);
		
		if(!empty($rst_sal->credito)){
			$saldo=round($rst_sal->total,$dec)-round($rst_sal->credito,$dec);
		}else{
			$saldo=round($rst->fac_total_valor,$dec);
		}
			$data= array(
						'fac_id'=>$rst->fac_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_email'=>$rst->cli_email,
						'fac_fecha_emision'=>$rst->fac_fecha_emision,
						'fac_numero'=>$rst->fac_numero,
						'fac_subtotal'=>round($rst->fac_subtotal,$dec),
						'fac_total_iva'=>round($rst->fac_total_iva,$dec),
						'saldo'=>$saldo,
						);	
		

		echo json_encode($data);
	} 

	public function doc_duplicado($id,$num){
		$rst=$this->reg_retencion_model->lista_doc_duplicado($id,$num);
		if(!empty($rst)){
			echo $rst->rgr_id;
		}else{
			echo "";
		}
	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Registro Retencion '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="reg_retenciones".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function asientos($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emi_id=$rst_cja->emi_id;

        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->reg_retencion_model->lista_una_retencion($id);
        $cns_ret=$this->reg_retencion_model->lista_detalle_retencion($id);
        $emi_id=$rst->emi_id;

        $ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('65',$emi_id);
        
        $rst_as=$this->asiento_model->lista_asientos_modulo($id,'8');
        if(empty($rst_as[0]->con_asiento)){
        	$asiento =$this->asiento_model->siguiente_asiento();
    	}else{
    		///elimina asiento
    		$asiento=$rst_as[0]->con_asiento;
    		$this->asiento_model->delete($rst_as[0]->con_asiento);
    	}
        
        //REG.RETENCION
        $array= array();
        $dat1 = array();
        $dat2 = array();

        $dat1 = Array(
        			'con_asiento'=>$asiento,
                    'con_concepto'=>'REGISTRO RETENCION',
                    'con_documento'=>$rst->rgr_numero,
                    'con_fecha_emision'=>$rst->rgr_fecha_emision,
                    'con_concepto_debe'=>'',
                    'con_concepto_haber'=>$ccli->pln_codigo,
                    'con_valor_debe'=>'0.00',
                    'con_valor_haber'=>$rst->rgr_total_valor,
                    'mod_id'=>'8',
                    'doc_id'=>$rst->rgr_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );

         array_push($array, $dat1);

         //asientos detalle retencion
        foreach ($cns_ret as $dtr) {
        	$imp=$this->impuesto_model->lista_un_impuesto($dtr->por_id);
        	$dat2 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'REGISTRO RETENCION',
                        'con_documento'=>$rst->rgr_numero,
                        'con_fecha_emision'=>$rst->rgr_fecha_emision,
                        'con_concepto_debe'=>$imp->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($dtr->drr_valor,$dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'8',
                        'doc_id'=>$rst->rgr_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
            array_push($array, $dat2);
        }

        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
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
	
}
