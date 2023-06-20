<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retencion extends CI_Controller {

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
		$this->load->model('reg_factura_model');
		$this->load->model('retencion_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('impuesto_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->model('reg_factura_model');
		$this->load->model('ctasxpagar_model');
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
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_retenciones=$this->retencion_model->lista_retencion_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_retenciones=$this->retencion_model->lista_retencion_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'rentenciones'=>$cns_retenciones,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('retencion/lista',$data);
		$modulo=array('modulo'=>'retencion');
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
			$rst_vnd=$this->vendedor_model->lista_un_vendedor($usu_id);
			
			if(empty($rst_vnd)){
				$vnd='0';
			}else{
				$vnd=$rst_vnd->vnd_id;
			}
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_impuestos'=>$this->impuesto_model->lista_impuestos_retencion(),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=> (object) array(
											'ret_fecha_emision'=>date('Y-m-d'),
											'ret_numero'=>'',
											'ret_num_comp_retiene'=>'',
											'reg_id'=>'0',
					                        'cli_id'=>'',
					                        'vnd_id'=>$vnd,
					                        'ret_identificacion'=>'',
					                        'ret_nombre'=>'',
					                        'ret_direccion'=>'',
					                        'ret_telefono'=>'',
					                        'ret_email'=>'',
					                        'ret_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'ret_id'=>'',
					                        'reg_sbt'=>'0',
					                        'reg_iva12'=>'0',
										),
						'cns_det'=>'',
						'action'=>base_url().'retencion/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						);
			$this->load->view('retencion/form',$data);
			$modulo=array('modulo'=>'retencion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		
		$vnd_id= $this->input->post('vnd_id');
		$reg_id= $this->input->post('reg_id');
		$ret_num_comp_retiene= $this->input->post('ret_num_comp_retiene');
		$ret_fecha_emision= $this->input->post('ret_fecha_emision');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('ret_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('ret_num_comp_retiene','Factura No','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
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

			
			$rst_sec = $this->retencion_model->lista_secuencial_documento($emi_id,$cja_id);
		    if (empty($rst_sec)) {
		        $sec = $rst_cja->cja_sec_retencion;
		    } else {
		    	$sc=explode('-',$rst_sec->ret_numero);
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
		    $ret_numero = $ems . '-'.$caja.'-' . $tx . $sec;

		    $clave_acceso=$this->clave_acceso($cja_id,$ret_numero,$ret_fecha_emision);

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
		    				'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'reg_id'=>$reg_id,
							'ret_denominacion_comp'=>'1',
							'ret_fecha_emision'=>$ret_fecha_emision,
							'ret_numero'=>$ret_numero, 
							'ret_nombre'=>$nombre, 
							'ret_identificacion'=>$identificacion, 
							'ret_email'=>$email_cliente, 
							'ret_direccion'=>$direccion_cliente, 
							'ret_telefono'=>$telefono_cliente, 
							'ret_num_comp_retiene'=>$ret_num_comp_retiene, 
							'ret_total_valor'=>$total_valor,
							'ret_clave_acceso'=>$clave_acceso,
							'ret_estado'=>'4'
		    );


		    $ret_id=$this->retencion_model->insert($data);
		    if(!empty($ret_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("dtr_base_imponible$n")!=''){
		    			$por_id = $this->input->post("por_id$n");
		    			$dtr_ejercicio_fiscal = $this->input->post("dtr_ejercicio_fiscal$n");
		    			$dtr_base_imponible = $this->input->post("dtr_base_imponible$n");
		    			$dtr_codigo_impuesto = $this->input->post("dtr_codigo_impuesto$n");
		    			$dtr_procentaje_retencion = $this->input->post("dtr_procentaje_retencion$n");
		    			$dtr_valor = $this->input->post("dtr_valor$n");
		    			$dtr_tipo_impuesto = $this->input->post("dtr_tipo_impuesto$n");
		    			$dt_det=array(
		    							'ret_id'=>$ret_id,
	                                    'por_id'=>$por_id,
	                                    'dtr_ejercicio_fiscal'=>$dtr_ejercicio_fiscal,
	                                    'dtr_base_imponible'=>$dtr_base_imponible,
	                                    'dtr_codigo_impuesto'=>$dtr_codigo_impuesto,
	                                    'dtr_procentaje_retencion'=>$dtr_procentaje_retencion,
	                                    'dtr_valor'=>$dtr_valor,
	                                    'dtr_tipo_impuesto'=>$dtr_tipo_impuesto,
		    						);
		    			$this->retencion_model->insert_detalle($dt_det);
		    		}
		    	
		    	}

		    	//generar_xml
		    	$this->generar_xml($ret_id);
		    	//genera asientos
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		    	$pln_id=0;
		    	$cta_banco='';
		        if($conf_as->con_valor==0){
		        	$ctas_con=$this->reg_factura_model->lista_cuentas_factura($reg_id);
		        	///validacion de cuentas completas en factura
		        	if(empty($ctas_con)){
		        		$this->asientos($reg_id,$opc_id,$ret_id);
		        	}

		        	$ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('18',$emi_id);
		        	$ccxp=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('21',$emi_id);
		        	$pln_id=$ccli->pln_id;
		        	$cta_banco=$ccxp->pln_codigo;
		        }

		        ///inserta ctasxpagar
		        $rst_sec_cxp=$this->ctasxpagar_model->lista_secuencial_ctasxpagar();
		        if (empty($rst_sec_cxp)) {
	                $sec = 1;
	            } else {
	                $sec = str_replace('E', '', $rst_sec_cxp->ctp_secuencial) + 1;
	            }
	            if ($sec >= 0 && $sec < 10) {
	                $tx = '000000000';
	            } else if ($sec >= 10 && $sec < 100) {
	                $tx = '00000000';
	            } else if ($sec >= 100 && $sec < 1000) {
	                $tx = '0000000';
	            } else if ($sec >= 1000 && $sec < 10000) {
	                $tx = '000000';
	            } else if ($sec >= 10000 && $sec < 100000) {
	                $tx = '00000';
	            } else if ($sec >= 100000 && $sec < 1000000) {
	                $tx = '0000';
	            } else if ($sec >= 1000000 && $sec < 10000000) {
	                $tx = '000';
	            } else if ($sec >= 10000000 && $sec < 100000000) {
	                $tx = '00';
	            } else if ($sec >= 100000000 && $sec < 1000000000) {
	                $tx = '0';
	            } else if ($sec >= 1000000000 && $sec < 10000000000) {
	                $tx = '';
	            }
	            $secuencial_cxp = 'E' . $tx . $sec;
		    	$dt_cxp=array(
	                        'reg_id'=>$reg_id,
	                        'ctp_fecha'=>date('Y-m-d'),
	                        'ctp_monto'=>$total_valor,
	                        'ctp_forma_pago'=>'7',
	                        'ctp_banco'=>$cta_banco,
	                        'pln_id'=>$pln_id,
	                        'ctp_fecha_pago'=>$ret_fecha_emision,
	                        'num_documento'=>$ret_numero,
	                        'ctp_concepto'=>'ABONO REG_FAC. ' . $ret_num_comp_retiene,
	                        'doc_id'=>$ret_id,
	                        'ctp_estado'=>'1',
	                        'ctp_secuencial'=>$secuencial_cxp,
	                        'emp_id'=>$emp_id
		    				);
		    	$this->ctasxpagar_model->insert($dt_cxp);

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'RETENCION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'retencion/show_frame/'. $ret_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'retencion/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_impuestos'=>$this->impuesto_model->lista_impuestos_estado('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=>$this->retencion_model->lista_una_retencion($id),
						'cns_det'=>$this->retencion_model->lista_detalle_retencion($id),
						'action'=>base_url().'retencion/actualizar/'.$opc_id
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('retencion/form',$data);
			$modulo=array('modulo'=>'retencion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
			
		$id = $this->input->post('ret_id');
		$vnd_id= $this->input->post('vnd_id');
		$reg_id= $this->input->post('reg_id');
		$ret_num_comp_retiene= $this->input->post('ret_num_comp_retiene');
		$ret_fecha_emision= $this->input->post('ret_fecha_emision');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('ret_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('ret_num_comp_retiene','Factura No','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');

		if($this->form_validation->run()){
			$rst_ret=$this->retencion_model->lista_una_retencion($id);
		    $clave_acceso=$this->clave_acceso($cja_id,$rst_ret->ret_numero,$ret_fecha_emision);

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
		    				'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'reg_id'=>$reg_id,
							'ret_denominacion_comp'=>'1',
							'ret_fecha_emision'=>$ret_fecha_emision,
							'ret_nombre'=>$nombre, 
							'ret_identificacion'=>$identificacion, 
							'ret_email'=>$email_cliente, 
							'ret_direccion'=>$direccion_cliente, 
							'ret_telefono'=>$telefono_cliente, 
							'ret_num_comp_retiene'=>$ret_num_comp_retiene, 
							'ret_total_valor'=>$total_valor,
							'ret_clave_acceso'=>$clave_acceso,
							'ret_estado'=>'4'
		    );


			if($this->retencion_model->update($id,$data)){
				if($this->retencion_model->delete_detalle($id)){
			    	$n=0;
			    	while($n<$count_det){
			    		$n++;
			    		if($this->input->post("dtr_base_imponible$n")!=''){
			    			$por_id = $this->input->post("por_id$n");
			    			$dtr_ejercicio_fiscal = $this->input->post("dtr_ejercicio_fiscal$n");
			    			$dtr_base_imponible = $this->input->post("dtr_base_imponible$n");
			    			$dtr_codigo_impuesto = $this->input->post("dtr_codigo_impuesto$n");
			    			$dtr_procentaje_retencion = $this->input->post("dtr_procentaje_retencion$n");
			    			$dtr_valor = $this->input->post("dtr_valor$n");
			    			$dtr_tipo_impuesto = $this->input->post("dtr_tipo_impuesto$n");
			    			$dt_det=array(
			    							'ret_id'=>$id,
		                                    'por_id'=>$por_id,
		                                    'dtr_ejercicio_fiscal'=>$dtr_ejercicio_fiscal,
		                                    'dtr_base_imponible'=>$dtr_base_imponible,
		                                    'dtr_codigo_impuesto'=>$dtr_codigo_impuesto,
		                                    'dtr_procentaje_retencion'=>$dtr_procentaje_retencion,
		                                    'dtr_valor'=>$dtr_valor,
		                                    'dtr_tipo_impuesto'=>$dtr_tipo_impuesto,
			    						);
			    			$this->retencion_model->insert_detalle($dt_det);
			    		}
			    	
			    	}
		    	}

		    	//generar_xml
		    	$this->generar_xml($id);

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'RETENCION',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'retencion/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'retencion/editar'.$id.'/'.$opc_id);
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
			$rst_ret=$this->retencion_model->lista_una_retencion($id);
			//anula ctasxpagar
			$cxp=$this->ctasxpagar_model->lista_ctasxpagar_retencion($id);
			$up_cxp=array('ctp_estado'=>3);
			$this->ctasxpagar_model->update_cta($cxp->ctp_id,$up_cxp);

			//anula retencion
		    $up_dtf=array('ret_estado'=>3);
			if($this->retencion_model->update($id,$up_dtf)){
				//asiento factura
				$conf_as=$this->configuracion_model->lista_una_configuracion('4');
				$cnf_as=$conf_as->con_valor;
				if($cnf_as==0){
					$ctas_con=$this->reg_factura_model->lista_cuentas_factura($rst_ret->reg_id);
		        	///validacion de cuentas completas en factura
		        	if(empty($ctas_con)){
						$this->asientos_factura($rst_ret->reg_id,$opc_id,$id);
					}
				}	

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'RETENCION',
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

	
	function clave_acceso($cja,$doc_numero,$doc_fecha){
		$cod_doc='07';
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
					'titulo'=>'Retencion '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"retencion/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'retencion');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id){
    		$rst=$this->retencion_model->lista_una_retencion($id);
    		$imagen=$this->set_barcode($rst->ret_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=>$this->retencion_model->lista_una_retencion($id),
						'cns_det'=>$this->retencion_model->lista_detalle_retencion($id),
						);
			// $this->load->view('pdf/pdf_retencion', $data);
			$this->html2pdf->filename('retencion.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_retencion', $data, true)));
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

	public function traer_facturas($num,$emp){
		$rst=$this->reg_factura_model->lista_factura_numero($num,$emp);
		echo json_encode($rst);
	}

	public function load_factura($id,$dec,$dcc){
		$rst=$this->reg_factura_model->lista_una_factura($id);

			$data= array(
						'reg_id'=>$rst->reg_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_email'=>$rst->cli_email,
						'reg_femision'=>$rst->reg_femision,
						'reg_num_documento'=>$rst->reg_num_documento,
						'reg_sbt'=>$rst->reg_sbt,
						'reg_iva12'=>$rst->reg_iva12,
						);	

		echo json_encode($data);
	} 

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Retencion '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="retenciones".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }
	
	public function consulta_sri(){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
	    $ambiente=$amb->con_valor;

    	if($ambiente!=0){
	    	$retencion=$this->retencion_model->lista_retencion_sin_autorizar();
	        set_time_limit(0);
	         if ($ambiente == 2) { //Produccion
	            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $retencion->ret_clave_acceso]);
	        
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($ambiente,$retencion->ret_id); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
	        	if($res['estado']!='AUTORIZADO'){
	        		$this->generar_xml($ambiente,$retencion->ret_id); 
	        	}else{
	            	$data = array(
	            					'ret_autorizacion'=>$res['numeroAutorizacion'], 
	            					'ret_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'ret_xml_doc'=>$res['comprobante'], 
	            					'ret_estado'=>'6'
	            				);
	            	$this->retencion_model->update($retencion->ret_id,$data);
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
    	$retencion=$this->retencion_model->lista_una_retencion($id);
    	$detalle=$this->retencion_model->lista_detalle_retencion($retencion->ret_id);
        $dec = $this->configuracion_model->lista_una_configuracion('2');
        $round=$dec->con_valor;
        $codigo='12345678';  
        $tp_emison='1';  
        $empresa=$this->empresa_model->lista_una_empresa($retencion->emp_id);    
        $emisor=$this->emisor_model->lista_un_emisor($retencion->emi_id);    
        $ndoc = explode('-', $retencion->ret_numero);
        $nfact = str_replace('-', '', $retencion->ret_numero);
        $ems = $ndoc[0];
        $emi = intval($ndoc[0]);
        $pt_ems = $ndoc[1];
        $secuencial = $ndoc[2];
        $cod_doc = '07'; //01= factura, 02=nota de credito tabla 4
        $fecha = date_format(date_create($retencion->ret_fecha_emision), 'd/m/Y');
        $f2 = date_format(date_create($retencion->ret_fecha_emision), 'dmY');
        $dir_cliente = $retencion->cli_calle_prin;
        $telf_cliente = $retencion->cli_telefono;
        $email_cliente = $retencion->cli_email;
        $contabilidad = $empresa->emp_obligado_llevar_contabilidad;
        $razon_soc_comprador = $retencion->ret_nombre;
        $id_comprador = $retencion->ret_identificacion;;
        if (strlen($id_comprador) == 13 && $id_comprador != '9999999999999' && substr($id_comprador, -3) == '001') {
            $tipo_id_comprador = "04"; //RUC 04 
        } else if (strlen($id_comprador) == 10) {
            $tipo_id_comprador = "05"; //CEDULA 05 
        } else if ($id_comprador == '9999999999999') {
            $tipo_id_comprador = "07"; //VENTA A CONSUMIDOR FINAL
        } else {
            $tipo_id_comprador = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
        }
        

        $clave = $retencion->ret_clave_acceso;

        $xml.="<?xml version='1.0' encoding='UTF-8'?>" . chr(13);
	    $xml.="<comprobanteRetencion version='1.0.0' id='comprobante'>" . chr(13);
	    $xml.="<infoTributaria>" . chr(13);
	    $xml.="<ambiente>" . $ambiente . "</ambiente>" . chr(13);
	    $xml.="<tipoEmision>" . $tp_emison . "</tipoEmision>" . chr(13);
	    $xml.="<razonSocial>" . $empresa->emp_nombre . "</razonSocial>" . chr(13);
	    $xml.="<nombreComercial>" . $emisor->emi_nombre . "</nombreComercial>" . chr(13);
	    $xml.="<ruc>" . trim($empresa->emp_identificacion) . "</ruc>" . chr(13);
	    $xml.="<claveAcceso>" . $clave . "</claveAcceso>" . chr(13);
	    $xml.="<codDoc>" . $cod_doc . "</codDoc>" . chr(13);
	    $xml.="<estab>" . $ems . "</estab>" . chr(13);
	    $xml.="<ptoEmi>" . $pt_ems . "</ptoEmi>" . chr(13);
	    $xml.="<secuencial>" . $secuencial . "</secuencial>" . chr(13);
	    $xml.="<dirMatriz>" . $empresa->emp_direccion . "</dirMatriz>" . chr(13);
	    $xml.="</infoTributaria>" . chr(13);
	//ENCABEZADO
	    $xml.="<infoCompRetencion>" . chr(13);
	    $xml.="<fechaEmision>" . $fecha . "</fechaEmision>" . chr(13);
	    $xml.="<dirEstablecimiento>" . $emisor->emi_dir_establecimiento_emisor . "</dirEstablecimiento>" . chr(13);
		if(!empty($empresa->emp_contribuyente_especial)){
        	$xml.="<contribuyenteEspecial>$empresa->emp_contribuyente_especial</contribuyenteEspecial>" . chr(13);
    	}
	    $xml.="<obligadoContabilidad>" . $contabilidad . "</obligadoContabilidad>" . chr(13);
	    $xml.="<tipoIdentificacionSujetoRetenido>" . $tipo_id_comprador . "</tipoIdentificacionSujetoRetenido>" . chr(13);
	    $xml.="<razonSocialSujetoRetenido>" . $razon_soc_comprador . "</razonSocialSujetoRetenido>" . chr(13);
	    $xml.="<identificacionSujetoRetenido>" . $id_comprador . "</identificacionSujetoRetenido>" . chr(13);
	    $xml.="<periodoFiscal>" . date_format(date_create($retencion->ret_fecha_emision), 'm/Y') . "</periodoFiscal>" . chr(13);
	    $xml.="</infoCompRetencion>" . chr(13);

	    $xml.="<impuestos>" . chr(13);
	    foreach ($detalle  as $det) {
	        if ($det->dtr_tipo_impuesto == 'IR') {
	            $impuesto = '1';
	        } else if ($det->dtr_tipo_impuesto == 'IV') {
	            $impuesto = '2';
	        } else {
	            $impuesto = '3';
	        }
	        $xml.="<impuesto>" . chr(13);
	        $xml.="<codigo>" . $impuesto . "</codigo>" . chr(13);
	        $xml.="<codigoRetencion>" . $det->dtr_codigo_impuesto . "</codigoRetencion>" . chr(13);
	        $xml.="<baseImponible>" . round($det->dtr_base_imponible, $round) . "</baseImponible>" . chr(13);
	        $xml.="<porcentajeRetener>" . round($det->dtr_procentaje_retencion, $round) . "</porcentajeRetener>" . chr(13);
	        $xml.="<valorRetenido>" . round($det->dtr_valor, $round) . "</valorRetenido>" . chr(13);
	        $xml.="<codDocSustento>0" . $retencion->ret_denominacion_comp . "</codDocSustento>" . chr(13);
	        $xml.="<numDocSustento>" . str_replace('-', '', $retencion->ret_num_comp_retiene) . "</numDocSustento>" . chr(13);
	        $xml.="<fechaEmisionDocSustento>" . $fecha . "</fechaEmisionDocSustento>" . chr(13);
	        $xml.="</impuesto>" . chr(13);
	    }
	    $xml.="</impuestos>" . chr(13);
	    $xml.="<infoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Direccion'>" . $dir_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Telefono'>" . $telf_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Email'>" . strtolower(utf8_decode($email_cliente)) . "</campoAdicional>" . chr(13);
	    $xml.="</infoAdicional>" . chr(13);
	    $xml.="</comprobanteRetencion>" . chr(13);
        $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);

		// header("Location: http://localhost:8080/central_xml_local/envio_sri/firmar.php?clave=$clave&programa=$programa&firma=$firma&password=$pass&ambiente=$ambiente");
		}
    } 

    public function asientos($id,$opc_id,$ret_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emi_id=$rst_cja->emi_id;

        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->reg_factura_model->lista_una_factura($id);
        $cns=$this->reg_factura_model->lista_sum_cuentas($id);

        $rst_ret=$this->retencion_model->lista_una_retencion($ret_id);
        $cns_ret=$this->retencion_model->lista_detalle_retencion($ret_id);
        $emi_id=$rst_ret->emi_id;

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
        
        //FACTURA COMPRA Y RETENCION
        $dat0 = array();
        $dat1 = array();
        $dat2 = array();
        $dat3 = array();

        if($rst->cli_tipo_cliente==0){
        	$ccli=$cli;
        }else{
        	$ccli=$cex;
        }
        $total=round($rst->reg_total, $dec)-round($rst_ret->ret_total_valor, $dec);
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
         //asientos detalles factura
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

         //asientos detalle retencion
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

        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
        }
    }


    public function asientos_factura($id,$opc_id,$ret_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emi_id=$rst_cja->emi_id;

        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->reg_factura_model->lista_una_factura($id);
        $cns=$this->reg_factura_model->lista_sum_cuentas($id);
        $rst_ret=$this->retencion_model->lista_una_retencion($ret_id);

        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('26',$emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('2',$emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('27',$emi_id);
        $des=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('30',$emi_id);
        $pro=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('31',$emi_id);
        
        $rst_as=$this->asiento_model->lista_asientos_modulo($rst->reg_id,'5');
        $cns_as2=$this->asiento_model->lista_asientos_modulo($ret_id,'4');

        $asiento2 =$this->asiento_model->siguiente_asiento();

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
        $dat0 = Array(
                    'con_asiento'=>$asiento,
                    'con_concepto'=>$rst->reg_concepto,
                    'con_documento'=>$rst->reg_num_documento,
                    'con_fecha_emision'=>$rst->reg_femision,
                    'con_concepto_debe'=>'',
                    'con_concepto_haber'=>$ccli->pln_codigo,
                    'con_valor_debe'=>'0.00',
                    'con_valor_haber'=>round($rst->reg_total, $dec),
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

        ///anulacion retencion	
        
        $dat5 = Array(
                    'con_asiento'=>$asiento2,
                    'con_concepto'=>'ANULACION RETENCION '.$rst->reg_concepto,
                    'con_documento'=>$rst_ret->ret_numero,
                    'con_fecha_emision'=>date('Y-m-d'),
                    'con_concepto_debe'=>'',
                    'con_concepto_haber'=>$ccli->pln_codigo,
                    'con_valor_debe'=>'0.00',
                    'con_valor_haber'=>round($rst_ret->ret_total_valor, $dec),
                    'mod_id'=>'4',
                    'doc_id'=>$rst_ret->ret_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );
        array_push($array, $dat5);

        foreach ($cns_as2 as $rst_as2) {
        	$dat6 = Array(
                    'con_asiento'=>$asiento2,
                    'con_concepto'=>'ANULACION RETENCION '.$rst_as2->con_concepto,
                    'con_documento'=>$rst_as2->con_documento,
                    'con_fecha_emision'=>date('Y-m-d'),
                    'con_concepto_debe'=>$rst_as2->con_concepto_haber,
                    'con_concepto_haber'=>$rst_as2->con_concepto_debe,
                    'con_valor_debe'=>round($rst_as2->con_valor_haber,$dec),
                    'con_valor_haber'=>round($rst_as2->con_valor_debe,$dec),
                    'mod_id'=>$rst_as2->mod_id,
                    'doc_id'=>$rst_as2->doc_id,
                    'cli_id'=>$rst_as2->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst_as2->emp_id,
                );
        	array_push($array, $dat6);
        		
        }	

        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
        }

        

    }
	
}
