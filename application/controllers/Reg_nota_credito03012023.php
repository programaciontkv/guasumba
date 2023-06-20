<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_nota_credito extends CI_Controller {

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
		$this->load->model('reg_nota_credito_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->model('ctasxpagar_model');
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
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_notas=$this->reg_nota_credito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_notas=$this->reg_nota_credito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'notas'=>$cns_notas,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('reg_nota_credito/lista',$data);
		$modulo=array('modulo'=>'reg_nota_credito');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){

			//valida cuentas asientos completos
			$cuentas='';
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuenta=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuenta)){
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
						'cns_productos'=>$this->reg_nota_credito_model->lista_productos('1'),
						'cns_cuentas'=>$cuentas,
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=> (object) array(
											'rnc_fec_registro'=>date('Y-m-d'),
											'rnc_fecha_emision'=>date('Y-m-d'),
											'rnc_fec_autorizacion'=>date('Y-m-d'),
											'rnc_fec_caducidad'=>date('Y-m-d'),
											'rnc_numero'=>'',
											'rnc_autorizacion'=>'',
											'rnc_num_comp_modifica'=>'',
											'rnc_fecha_emi_comp'=>'',
											'reg_id'=>'0',
					                        'cli_id'=>'',
					                        'trs_id'=>'6',
					                        'rnc_identificacion'=>'',
					                        'rnc_nombre'=>'',
					                        'rnc_motivo'=>'',
					                        'rnc_subtotal12'=>'0',
					                        'rnc_subtotal0'=>'0',
					                        'rnc_subtotal_ex_iva'=>'0',
					                        'rnc_subtotal_no_iva'=>'0',
					                        'rnc_subtotal'=>'0',
					                        'rnc_total_descuento'=>'0',
					                        'rnc_total_ice'=>'0',
					                        'rnc_total_iva'=>'0',
					                        'rnc_total_propina'=>'0',
					                        'rnc_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'rnc_id'=>'',
										),
						'cns_det'=>'',
						'emi_id'=>$rst_cja->emi_id,
						'action'=>base_url().'reg_nota_credito/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						);
			$this->load->view('reg_nota_credito/form',$data);
			$modulo=array('modulo'=>'reg_nota_credito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$deci=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$deci->con_valor;

		$rnc_fec_registro = $this->input->post('rnc_fec_registro');
		$rnc_fecha_emision = $this->input->post('rnc_fecha_emision');
		$rnc_fec_autorizacion = $this->input->post('rnc_fec_autorizacion');
		$rnc_fec_caducidad = $this->input->post('rnc_fec_caducidad');
		$rnc_numero= $this->input->post('rnc_numero');
		$rnc_autorizacion= $this->input->post('rnc_autorizacion');
		$reg_id= $this->input->post('reg_id');
		$rnc_num_comp_modifica= $this->input->post('rnc_num_comp_modifica');
		$rnc_fecha_emi_comp= $this->input->post('rnc_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$rnc_motivo = $this->input->post('rnc_motivo');
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
		$trs_id = $this->input->post('trs_id');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$count_det=$this->input->post('count_detalle');
		$verifica_cuenta=$this->input->post('verifica_cuenta');
		
		$this->form_validation->set_rules('rnc_fec_registro','Fecha de Registro','required');
		$this->form_validation->set_rules('rnc_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rnc_fec_autorizacion','Fecha de Autoriacion','required');
		$this->form_validation->set_rules('rnc_fec_caducidad','Fecha de Caducidad','required');
		$this->form_validation->set_rules('rnc_numero','Nota de Credito No','required');
		$this->form_validation->set_rules('rnc_autorizacion','Autorizacion No','required');
		$this->form_validation->set_rules('rnc_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('rnc_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('rnc_motivo','Motivo','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');

		if($this->form_validation->run()){
			
		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'reg_id'=>$reg_id,
							'trs_id'=>$trs_id,
							'rnc_fec_registro'=>$rnc_fec_registro,
							'rnc_fec_autorizacion'=>$rnc_fec_autorizacion,
							'rnc_fec_caducidad'=>$rnc_fec_caducidad,
							'rnc_numero'=>$rnc_numero,
							'rnc_autorizacion'=>$rnc_autorizacion,
							'rnc_denominacion_comprobante'=>'1',
							'rnc_fecha_emision'=>$rnc_fecha_emision,
							'rnc_numero'=>$rnc_numero, 
							'rnc_nombre'=>$nombre, 
							'rnc_identificacion'=>$identificacion, 
							'rnc_motivo'=>$rnc_motivo, 
							'rnc_num_comp_modifica'=>$rnc_num_comp_modifica, 
							'rnc_fecha_emi_comp'=>$rnc_fecha_emi_comp, 
							'rnc_subtotal12'=>$subtotal12, 
							'rnc_subtotal0'=>$subtotal0, 
							'rnc_subtotal_ex_iva'=>$subtotalex, 
							'rnc_subtotal_ex_iva'=>$subtotalno, 
							'rnc_total_descuento'=>$total_descuento, 
							'rnc_total_ice'=>$total_ice, 
							'rnc_total_iva'=>$total_iva, 
							'rnc_total_valor'=>$total_valor,
							'rnc_subtotal'=>$subtotal,
							'rnc_estado'=>'4'
		    );


		    $rnc_id=$this->reg_nota_credito_model->insert($data);
		    if(!empty($rnc_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("pro_aux$n")!=null && $this->input->post("cantidad$n")>0){
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
		    			$drc_codigo_cta = $this->input->post("drc_codigo_cta$n");
		    			$pln_id = $this->input->post("pln_id$n");
		    			$dt_det=array(
		    							'rnc_id'=>$rnc_id,
	                                    'pro_id'=>$pro_id,
	                                    'drc_codigo'=>$dfc_codigo,
	                                    'drc_cod_aux'=>$dfc_cod_aux,
	                                    'drc_cantidad'=>$dfc_cantidad,
	                                    'drc_descripcion'=>$dfc_descripcion,
	                                    'drc_precio_unit'=>$dfc_precio_unit,
	                                    'drc_porcentaje_descuento'=>$dfc_porcentaje_descuento,
	                                    'drc_val_descuento'=>$dfc_val_descuento,
	                                    'drc_precio_total'=>$dfc_precio_total,
	                                    'drc_iva'=>$dfc_iva,
	                                    'drc_codigo_cta'=>$drc_codigo_cta,
	                                    'pln_id'=>$pln_id
		    						);
		    			$this->reg_nota_credito_model->insert_detalle($dt_det);
		    		}
		    	

		    	//movimientos
		    		$inven=$this->configuracion_model->lista_una_configuracion('3');
		    		if ($inven->con_valor == 0) {
		    			if($trs_id!=1){
	                        $k = 0;
	                        while ($k < $count_det) {
	                        	$k++;
	                        	if($this->input->post("pro_aux$k")!= null && $this->input->post("cantidad$k")>0){
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
		                                                    'trs_id'=>$trs_id,
		                                                    'cli_id'=>$cli_id,
		                                                    'bod_id'=>$emi_id,
		                                                    'mov_documento'=>$rnc_numero,
		                                                    'mov_num_factura'=>$rnc_numero,
		                                                    'mov_fecha_trans'=>$rnc_fecha_emision,
		                                                    'mov_fecha_registro'=>$fec_mov,
		                                                    'mov_hora_registro'=>$hor_mov, 
		                                                    'mov_cantidad'=>$dfc_cantidad,
		                                                    'mov_fecha_entrega'=>$fec_mov,
		                                                    'mov_val_unit'=>$mov_cost_unit,
		                                                    'mov_val_tot'=>$mov_cost_tot,
		                                                    'emp_id'=>$emp_id,
		                                                    'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
		                                        			);

		                                $this->reg_nota_credito_model->insert_movimientos($dt_movimientos);
		                            }
		                        }
	                        }
	                    }    
                    }
		    	}

		    	//genera asientos
		    	$pln_id=0;
		    	$banco='';
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		        if($conf_as->con_valor==0){
		        	///validacion de cuentas completas en reg.nota credito
		        	if($verifica_cuenta==0){
		        		$this->asientos($rnc_id,$opc_id);
		        	}

		        	$ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('45',$emi_id);
		        	$ccxp =$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('44',$emi_id);

		        	$pln_id=$ccli->pln_id;
		        	$banco=$ccxp->pln_codigo;
		        
		        }

		        ///ctasxpagar
		        $rst_sal = $this->reg_nota_credito_model->lista_saldo_factura($reg_id);
		        $saldo=0;
		        if(!empty($rst_sal)){
                	$saldo = round($rst_sal->total,$dec) - round($rst_sal->credito,$dec);
            	}

            	if(round($saldo,$dec)>=round($total_valor,$dec)){
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
                    $rst_pag = $this->reg_nota_credito_model->lista_pagos_factura($reg_id);

                    $dt_cxp=array(
                    				'reg_id'=>$reg_id,
                    				'ctp_fecha'=>date('Y-m-d'),
                    				'ctp_monto'=>$total_valor,
                    				'ctp_forma_pago'=>'8',
                    				'ctp_banco'=>$banco,
                    				'pln_id'=>$pln_id,
                    				'pag_id'=>$rst_pag->pag_id,
                    				'ctp_fecha_pago'=>$rnc_fecha_emision,
                    				'num_documento'=>$rnc_numero,
                    				'ctp_concepto'=>'ABONO REG_FACTURA '.$rnc_num_comp_modifica,
                    				'doc_id'=>$rnc_id,
                    				'ctp_estado'=>'1',
                    				'ctp_secuencial'=>$secuencial_cxp,
                    				'emp_id'=>$emp_id,
                    );

                    $this->ctasxpagar_model->insert($dt_cxp);

                    //asiento ctasxpagar
                    if($conf_as->con_valor==0){
                    	$this->asientos_pagos($rnc_id);
                	}
            	}else{
            		///modifica estado pendiente cobro
            		$upd_est=array('rnc_estado'=>21);
            		$this->reg_nota_credito_model->update($rnc_id,$upd_est);
            	}

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO NOTA DE CREDITO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'reg_nota_credito/show_frame/'. $rnc_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_nota_credito/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$rst=$this->reg_nota_credito_model->lista_una_nota($id);
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){

			//valida cuentas asientos completos
			$cuentas="";
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuenta=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuenta)){
					$valida_asiento=1;
				}
			
				$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1');
			}

			///recupera detalle
			$cns_dt=$this->reg_nota_credito_model->lista_detalle_nota($id);
			$cns_det=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			$inven=$this->configuracion_model->lista_una_configuracion('3');
			
			foreach ($cns_dt as $rst_dt) {
				if ($inven->con_valor == 0) {
					 if ($ctrl_inv->con_valor == 0) {
	                    	$fra = "and emp_id=$rst->emp_id";
		                } else {
		                    $fra = "and m.bod_id=$rst_cja->emi_id";
		                }
					$rst1 =$this->reg_nota_credito_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                $rst2 = $this->reg_nota_credito_model->lista_costos_mov($rst_dt->pro_id,$fra); 
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
		    $rst_df= $this->reg_nota_credito_model->lista_un_detalle_factura($rst->reg_id,$rst_dt->pro_id);    
	        if(empty($rst_df)){
	        	$cantidadf=0;
	        }else{
				$cantidadf=$rst_df->det_cantidad;
	        }

	        $rst_dn=$this->reg_nota_credito_model->lista_suma_detalle_edit($rst->reg_id,$rst_dt->pro_id,$rst->rnc_id); 
		    if(empty($rst_dn)){
		       	$cnt_nc=0;
		    }else{
		        $cnt_nc=$rst_dn->drc_cantidad;
		    }

			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_precio'=>$rst_dt->drc_precio_unit,
						'pro_iva'=>$rst_dt->drc_iva,
						'pro_descuento'=>$rst_dt->drc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->drc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'inventario'=>$inv,
						'cantidadf'=>$cantidadf,
						'cantidadn'=>$cnt_nc,
						'cantidad'=>$rst_dt->drc_cantidad,
						'cost_unit'=>$cost_unit,
						'precio_tot'=>$rst_dt->drc_precio_total,
						'pln_id'=>$rst_dt->pln_id,
						'drc_codigo_cta'=>$rst_dt->drc_codigo_cta,
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
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_productos'=>$this->reg_nota_credito_model->lista_productos('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cns_cuentas'=>$cuentas,
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=>$this->reg_nota_credito_model->lista_una_nota($id),
						'cns_det'=>$cns_det,
						'emi_id'=>$rst_cja->emi_id,
						'action'=>base_url().'reg_nota_credito/actualizar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						'conf_as'=>$conf_as->con_valor,
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reg_nota_credito/form',$data);
			$modulo=array('modulo'=>'reg_nota_credito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$deci=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$deci->con_valor;

		$id = $this->input->post('rnc_id');
		$rnc_fec_registro = $this->input->post('rnc_fec_registro');
		$rnc_fecha_emision = $this->input->post('rnc_fecha_emision');
		$rnc_fec_autorizacion = $this->input->post('rnc_fec_autorizacion');
		$rnc_fec_caducidad = $this->input->post('rnc_fec_caducidad');
		$rnc_numero= $this->input->post('rnc_numero');
		$rnc_autorizacion= $this->input->post('rnc_autorizacion');
		$reg_id= $this->input->post('reg_id');
		$rnc_num_comp_modifica= $this->input->post('rnc_num_comp_modifica');
		$rnc_fecha_emi_comp= $this->input->post('rnc_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$rnc_motivo = $this->input->post('rnc_motivo');
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
		$trs_id = $this->input->post('trs_id');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$count_det=$this->input->post('count_detalle');
		$verifica_cuenta=$this->input->post('verifica_cuenta');
		
		$this->form_validation->set_rules('rnc_fec_registro','Fecha de Registro','required');
		$this->form_validation->set_rules('rnc_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rnc_fec_autorizacion','Fecha de Autoriacion','required');
		$this->form_validation->set_rules('rnc_fec_caducidad','Fecha de Caducidad','required');
		$this->form_validation->set_rules('rnc_numero','Nota de Credito No','required');
		$this->form_validation->set_rules('rnc_autorizacion','Autorizacion No','required');
		$this->form_validation->set_rules('rnc_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('rnc_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('rnc_motivo','Motivo','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		
		if($this->form_validation->run()){

			$rst_ncr=$this->reg_nota_credito_model->lista_una_nota($id);

		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'reg_id'=>$reg_id,
							'trs_id'=>$trs_id,
							'rnc_fec_registro'=>$rnc_fec_registro,
							'rnc_fec_autorizacion'=>$rnc_fec_autorizacion,
							'rnc_fec_caducidad'=>$rnc_fec_caducidad,
							'rnc_numero'=>$rnc_numero,
							'rnc_autorizacion'=>$rnc_autorizacion,
							'rnc_denominacion_comprobante'=>'1',
							'rnc_fecha_emision'=>$rnc_fecha_emision,
							'rnc_numero'=>$rnc_numero, 
							'rnc_nombre'=>$nombre, 
							'rnc_identificacion'=>$identificacion, 
							'rnc_motivo'=>$rnc_motivo, 
							'rnc_num_comp_modifica'=>$rnc_num_comp_modifica, 
							'rnc_fecha_emi_comp'=>$rnc_fecha_emi_comp, 
							'rnc_subtotal12'=>$subtotal12, 
							'rnc_subtotal0'=>$subtotal0, 
							'rnc_subtotal_ex_iva'=>$subtotalex, 
							'rnc_subtotal_ex_iva'=>$subtotalno, 
							'rnc_total_descuento'=>$total_descuento, 
							'rnc_total_ice'=>$total_ice, 
							'rnc_total_iva'=>$total_iva, 
							'rnc_total_valor'=>$total_valor,
							'rnc_subtotal'=>$subtotal,
							'rnc_estado'=>'4'
		    );


			if($this->reg_nota_credito_model->update($id,$data)){
				if($this->reg_nota_credito_model->delete_detalle($id)){
			    	$n=0;
			    	while($n<$count_det){
			    		$n++;
				    		if($this->input->post("pro_aux$n")!=null && $this->input->post("cantidad$n")>0){
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
			    			$drc_codigo_cta = $this->input->post("drc_codigo_cta$n");
		    				$pln_id = $this->input->post("pln_id$n");
			    			$dt_det=array(
			    							'rnc_id'=>$id,
		                                    'pro_id'=>$pro_id,
		                                    'drc_codigo'=>$dfc_codigo,
		                                    'drc_cod_aux'=>$dfc_cod_aux,
		                                    'drc_cantidad'=>$dfc_cantidad,
		                                    'drc_descripcion'=>$dfc_descripcion,
		                                    'drc_precio_unit'=>$dfc_precio_unit,
		                                    'drc_porcentaje_descuento'=>$dfc_porcentaje_descuento,
		                                    'drc_val_descuento'=>$dfc_val_descuento,
		                                    'drc_precio_total'=>$dfc_precio_total,
		                                    'drc_iva'=>$dfc_iva,
		                                    'drc_codigo_cta'=>$drc_codigo_cta,
	                                    	'pln_id'=>$pln_id
			    						);
				    			$this->reg_nota_credito_model->insert_detalle($dt_det);
				    		}
			    	}
		    	}
		    	
		    	//movimientos
		    		$inven=$this->configuracion_model->lista_una_configuracion('3');
		    		if ($inven->con_valor == 0) {
		    			$up_dt=array('mov_estado'=>3);
		    			$this->reg_nota_credito_model->update_movimientos($rst_ncr->rnc_numero,$emi_id,$up_dt);
                        if($trs_id!=1){
	                        $k = 0;
	                        while ($k < $count_det) {
	                        	$k++;
	                        	if($this->input->post("pro_aux$k")!=null && $this->input->post("cantidad$k")>0){
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
		                                                    'trs_id'=>$trs_id,
		                                                    'cli_id'=>$cli_id,
		                                                    'bod_id'=>$emi_id,
		                                                    'mov_documento'=>$rst_ncr->rnc_numero,
		                                                    'mov_num_factura'=>$rst_ncr->rnc_numero,
		                                                    'mov_fecha_trans'=>$rst_ncr->rnc_fecha_emision,
		                                                    'mov_fecha_registro'=>$fec_mov,
		                                                    'mov_hora_registro'=>$hor_mov, 
		                                                    'mov_cantidad'=>$dfc_cantidad,
		                                                    'mov_fecha_entrega'=>$fec_mov,
		                                                    'mov_val_unit'=>$mov_cost_unit,
		                                                    'mov_val_tot'=>$mov_cost_tot,
		                                                    'emp_id'=>$emp_id,
		                                                    'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
		                                        			);

		                                $this->reg_nota_credito_model->insert_movimientos($dt_movimientos);
		                            }
		                        }
	                        }
	                    }
                    }
		    	

		    	//genera asientos
                $pln_id=0;
                $banco=0;
		    	$conf_as=$this->configuracion_model->lista_una_configuracion('4');
		        if($conf_as->con_valor==0){
		        	///validacion de cuentas completas en reg.nota credito
		        	if($verifica_cuenta==0){
		        		$this->asientos($id,$opc_id);
		        	}

		        	$ccli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('45',$emi_id);
		        	$ccxp =$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('44',$emi_id);

		        	$pln_id=$ccli->pln_id;
		        	$banco=$ccxp->pln_codigo;

		        	//eliminar asiento_pago
		        	$rst_cxp=$this->reg_nota_credito_model->lista_pagos_ctasxcob($id);
		        	$this->reg_nota_credito_model->delete_asiento_pago($rst_cxp[0]->ctp_id);
		        
		        }

		        ///ctasxpagar
		        //elimina ctaxpagar
		        $this->reg_nota_credito_model->delete_ctasxcob($id);

		        //inserta ctasxpagar
		        $rst_sal = $this->reg_nota_credito_model->lista_saldo_factura($reg_id);
		        $saldo=0;
		        if(!empty($rst_sal)){
                	$saldo = round($rst_sal->total,$dec) - round($rst_sal->credito,$dec);
            	}

            	if(round($saldo,$dec)>=round($total_valor,$dec)){
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
                    $rst_pag = $this->reg_nota_credito_model->lista_pagos_factura($reg_id);

                    $dt_cxp=array(
                    				'reg_id'=>$reg_id,
                    				'ctp_fecha'=>date('Y-m-d'),
                    				'ctp_monto'=>$total_valor,
                    				'ctp_forma_pago'=>'8',
                    				'ctp_banco'=>$banco,
                    				'pln_id'=>$pln_id,
                    				'pag_id'=>$rst_pag->pag_id,
                    				'ctp_fecha_pago'=>$rnc_fecha_emision,
                    				'num_documento'=>$rnc_numero,
                    				'ctp_concepto'=>'ABONO REG_FACTURA '.$rnc_num_comp_modifica,
                    				'doc_id'=>$id,
                    				'ctp_estado'=>'1',
                    				'ctp_secuencial'=>$secuencial_cxp,
                    				'emp_id'=>$emp_id,
                    );

                    $this->ctasxpagar_model->insert($dt_cxp);

                    //asiento ctasxpagar
                    if($conf_as->con_valor==0){
                    	$this->asientos_pagos($id);
                	}
            	}else{
            		///modifica estado pendiente cobro
            		$upd_est=array('rnc_estado'=>21);
            		$this->reg_nota_credito_model->update($id,$upd_est);
            	}



				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO NOTA DE CREDITO',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'reg_nota_credito/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'reg_nota_credito/editar'.$id.'/'.$opc_id);
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
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$rst_ncr=$this->reg_nota_credito_model->lista_una_nota($id);
			$up_dt=array('mov_estado'=>3);
		    $this->reg_nota_credito_model->update_movimientos($rst_ncr->rnc_numero,$rst_cja->emi_id,$up_dt);
		    $up_dtf=array('rnc_estado'=>3);
			if($this->reg_nota_credito_model->update($id,$up_dtf)){
				//anular ctasxpagar
				$cns_cxp=$this->reg_nota_credito_model->lista_pagos_ctasxcob($id);
				foreach ($cns_cxp as $rst_cxp) {
					$dt_cxp=array('ctp_estado'=>3);
					$this->ctasxpagar_model->update_cta($rst_cxp->ctp_id,$dt_cxp);
					if($cnf_as==0){
						$this->asiento_anulacion($rst_cxp->ctp_id,'11');
					}
				}

				//asiento reg nota credito
				if($cnf_as==0){
					$this->asiento_anulacion($id,'6');
				}

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO NOTA DE CREDITO',
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
						'cost_unit'=>$cost_unit,
						'ice_p'=>$rst->mp_j,
						'ice_cod'=>$rst->mp_l,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	
	function clave_acceso($cja,$doc_numero,$doc_fecha){
		$cod_doc='04';
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
					'titulo'=>'Registro Nota de Credito '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_nota_credito/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'reg_nota_credito');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id){
    		$rst=$this->reg_nota_credito_model->lista_una_nota($id);
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			///recupera detalle
			$cns_dt=$this->reg_nota_credito_model->lista_detalle_nota($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
	        
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_precio'=>$rst_dt->drc_precio_unit,
						'pro_iva'=>$rst_dt->drc_iva,
						'pro_descuento'=>$rst_dt->drc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->drc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'cantidad'=>$rst_dt->drc_cantidad,
						'precio_tot'=>$rst_dt->drc_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=>$this->reg_nota_credito_model->lista_una_nota($id),
						'cns_det'=>$cns_det,
						);
			$this->html2pdf->filename('reg_nota_credito.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_reg_nota_credito', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    }

    

	public function traer_facturas($num,$emp){
		$rst=$this->reg_factura_model->lista_factura_numero($num,$emp);
		echo json_encode($rst);
	}

	public function load_factura($id,$inven,$ctrl_inv,$dec,$dcc,$emi_id){
		$rst=$this->reg_factura_model->lista_una_factura($id);
		$cns=$this->reg_factura_model->lista_detalle_factura($id);
		$n=0;
		$detalle='';
		foreach ($cns as $rst_det) {
			$n++;
				if ($inven == 0) {
					$hid_inv='';
					 if ($ctrl_inv == 0) {
		                   	$rst_emp=$this->emisor_model->lista_un_emisor($rst->emi_id);
	                    	$fra = "and emp_id=$rst_emp->emp_id";
		                } else {
		                    $fra = "and m.bod_id=$emi_id";
		                }
					$rst1 =$this->reg_nota_credito_model->total_ingreso_egreso_fact($rst_det->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                $rst2 = $this->reg_nota_credito_model->lista_costos_mov($rst_det->pro_id,$fra); 
		                if(!empty($rst2)){
		                	$cnt_inv=$rst2->ingreso - $rst2->egreso;
		                	$prec_inv=$rst2->icnt - $rst2->ecnt;
		                	if($cnt_inv==0 || $prec_inv==0){
		                		$cost_unit=0;
		                	}else{
		                		$cost_unit = (($cnt_inv) / ($prec_inv));
		                	}
		                	$cost_tot =$cost_unit*$rst_det->det_cantidad;
		                }else{
		                	$cost_unit =0;
		                	$cost_tot =0;
		                }
		            }else{
		            	$inv=0;
		            	$cost_unit =0;
		            	$cost_tot =0;
		            }
		        }else{
		        	$hid_inv='hidden';
		        	$inv=0;
		            $cost_unit =0;
		            $cost_tot =0;

		        }  
		        
		        $rst_dn=$this->reg_nota_credito_model->lista_suma_detalle($rst->reg_id,$rst_det->pro_id); 
		        if(empty($rst_dn)){
		        	$cnt_nc=0;
		        }else{
		        	$cnt_nc=$rst_dn->drc_cantidad;
		        }
		        $cantidad=$rst_det->det_cantidad-$cnt_nc;
		        $a='"';
		        $conf_as=$this->configuracion_model->lista_una_configuracion('4');
				if($conf_as->con_valor==0){
		        	$hidden_as='';
		        }else{
		        	$hidden_as='hidden';
		        }
		        $detalle.="<tr>
                                            <td id='item$n' name='item$n' lang='$n' align='center'>$n</td>
                                            <td $hidden_as>
                                            <input style='text-align:left' type='text' size='40' class='form-control' id='drc_codigo_cta$n' name='drc_codigo_cta$n'  value='$rst_det->reg_codigo_cta' lang='$n'   maxlength='14' list='list_cuentas' onchange='load_cuenta(this,0)'/>
                                            <input type='hidden' name='pln_id$n' id='pln_id$n' lang='$n' value='$rst_det->pln_id'>
                                        </td>
                                            <td id='pro_descripcion$n' name='pro_descripcion$n' lang='$n'>$rst_det->mp_c</td>
                                            <td id='pro_referencia$n' name='pro_referencia$n' lang='$n'>$rst_det->mp_d
                                                <input type='hidden' size='7' id='pro_ids$n' name='pro_ids$n' value='$rst_det->ids' lang='$n'/>
                                                <input type='hidden' size='7' id='pro_aux$n' name='pro_aux$n' value='$rst_det->pro_id' lang='$n'/>
                                                <input type='hidden' size='7' id='mov_cost_unit$n' name='mov_cost_unit$n' value='".str_replace(',', '', number_format($cost_unit, $dec))."' lang='$n'/>
                                                <input type='hidden' size='7' id='mov_cost_tot$n' name='mov_cost_tot$n' value='".str_replace(',', '', number_format($cost_tot, $dec))."' lang='$n'/>
                                            </td>
                                            <td id='unidad$n' name='unidad$n' lang='$n'>$rst_det->mp_q</td>
                                            <td id='inventario$n' name='inventario$n' lang='$n' $hid_inv  style='text-align:right'>".str_replace(',', '', number_format($inv, $dcc))."</td>
                                            <td hidden><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidadn$n' name='cantidadn$n' value='".str_replace(',', '', number_format($cnt_nc, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidadf$n' name='cantidadf$n' value='".str_replace(',', '', number_format($rst_det->det_cantidad, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidad$n' name='cantidad$n' value='".str_replace(',', '', number_format($cantidad, $dec))."' lang='$n' onchange='calculo(this), validar_cantfactura(this), costo_det(this)' onkeyup='this.value = this.value.replace(/[^0-9.]/, $a$a)'  /></td>
                                            <td><input type ='text' size='7' style='text-align:right' class='form-control decimal' id='pro_precio$n' name='pro_precio$n' value='".str_replace(',', '', number_format($rst_det->det_vunit, $dec))."' lang='$n' onchange='calculo(this)' readonly /></td>
                                            <td>
                                                <input type ='text' size='7' style='text-align:right' class='form-control decimal' id='descuento$n' name='descuento$n'  value='".str_replace(',', '', number_format($rst_det->det_descuento_porcentaje, $dec))."' lang='$n' onchange='calculo(this)'  readonly />
                                            </td>
                                            <td hidden>
                                                <input type ='text' size='7' style='text-align:right' class='form-control decimal' id='descuent$n' name='descuent$n'  value='".str_replace(',', '', number_format($rst_det->det_descuento_moneda, $dec))."' lang='$n'  readonly/>
                                            </td>
                                            <td><input type='text' id='iva$n' name='iva$n' size='5' style='text-align:right' class='form-control' value='$rst_det->det_impuesto' lang='$n' readonly /></td>
                                            <td>
                                                <input type ='text' size='9' style='text-align:right' class='form-control' id='valor_total$n' name='valor_total$n'  value='".str_replace(',', '', number_format($rst_det->det_total, $dec))."' readonly lang='$n'/>
                                                
                                            </td>
                                            <td onclick='elimina_fila_det(this)' align='center' ><span class='btn btn-danger fa fa-trash'></span></td>
                                        </tr>";
				
			}

			$data= array(
						'reg_id'=>$rst->reg_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'fac_fecha_emision'=>$rst->reg_fregistro,
						'fac_numero'=>$rst->reg_num_documento,
						'fac_subtotal'=>$rst->reg_sbt,
						'fac_subtotal12'=>$rst->reg_sbt12,
						'fac_subtotal0'=>$rst->reg_sbt0,
						'fac_subtotal_ex_iva'=>$rst->reg_sbt_excento,
						'fac_subtotal_no_iva'=>$rst->reg_sbt_noiva,
						'fac_total_descuento'=>$rst->reg_tdescuento,
						'fac_total_iva'=>$rst->reg_iva12,
						'fac_total_ice'=>$rst->reg_ice,
						'fac_total_valor'=>$rst->reg_total,
						'detalle'=>$detalle,
						'cnt_detalle'=>$n
						);	

		echo json_encode($data);
	} 
	
	public function doc_duplicado($id,$num){
		$rst=$this->reg_nota_credito_model->lista_doc_duplicado($id,$num);
		if(!empty($rst)){
			echo $rst->rnc_id;
		}else{
			echo "";
		}
	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Registro Nota de Credito '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="reg_nota_credito".date('Ymd');
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

        $rst=$this->reg_nota_credito_model->lista_una_nota($id);
        $cns=$this->reg_nota_credito_model->lista_sum_cuentas($id);

        
        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('38',$emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('2',$emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('39',$emi_id);
        $des=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('41',$emi_id);
        $pro=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('43',$emi_id);
        
        $rst_as=$this->asiento_model->lista_asientos_modulo($rst->rnc_id,'6');
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
                    'con_concepto'=>'DEVOLUCION COMPRA',
                    'con_documento'=>$rst->rnc_numero,
                    'con_fecha_emision'=>$rst->rnc_fecha_emision,
                    'con_concepto_debe'=>$ccli->pln_codigo,
                    'con_concepto_haber'=>'',
                    'con_valor_debe'=>round($rst->rnc_total_valor,$dec),
                    'con_valor_haber'=>'0.00',
                    'mod_id'=>'6',
                    'doc_id'=>$rst->rnc_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );


        if ($rst->rnc_total_iva != 0) {
            $dat1 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'DEVOLUCION COMPRA',
                        'con_documento'=>$rst->rnc_numero,
                    	'con_fecha_emision'=>$rst->rnc_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$iva->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->rnc_total_iva, $dec),
                        'mod_id'=>'6',
                        'doc_id'=>$rst->rnc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->rnc_total_descuento != 0) {
            $dat3 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'DEVOLUCION COMPRA',
                        'con_documento'=>$rst->rnc_numero,
                    	'con_fecha_emision'=>$rst->rnc_fecha_emision,
                        'con_concepto_debe'=>$des->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($rst->rnc_total_descuento, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'6',
                        'doc_id'=>$rst->rnc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->rnc_total_propina != 0) {
            $dat2 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'DEVOLUCION COMPRA',
                        'con_documento'=>$rst->rnc_numero,
                    	'con_fecha_emision'=>$rst->rnc_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$pro->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->rnc_total_propina, $dec),
                        'mod_id'=>'6',
                        'doc_id'=>$rst->rnc_id,
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
                        'con_concepto'=>'DEVOLUCION COMPRA',
                        'con_documento'=>$rst->rnc_numero,
                    	'con_fecha_emision'=>$rst->rnc_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$det->drc_codigo_cta,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($det->dtot,$dec) + round($det->ddesc, $dec),
                        'mod_id'=>'6',
                        'doc_id'=>$rst->rnc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
            array_push($array, $dat4);
        }


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
        
        $nota=$this->reg_nota_credito_model->lista_una_nota($id);

        $cns=$this->reg_nota_credito_model->lista_pagos_ctasxcob($id);

        foreach ($cns as $rst) {
            	
            $asiento = $asiento =$this->asiento_model->siguiente_asiento();
            $ccli=$this->plan_cuentas_model->lista_un_plan_cuentas($rst->pln_id);
            $cban=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo($rst->ctp_banco);
            
            $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->ctp_concepto,
                        'con_documento'=>$rst->num_documento,
                        'con_fecha_emision'=>$rst->ctp_fecha,
                        'con_concepto_debe'=>$cban->pln_codigo,
                        'con_concepto_haber'=>$ccli->pln_codigo,
                        'con_valor_debe'=>round($rst->ctp_monto, $dec),
                        'con_valor_haber'=>round($rst->ctp_monto, $dec),
                        'mod_id'=>'11',
                        'doc_id'=>$rst->ctp_id,
                        'cli_id'=>$nota->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$nota->emp_id,
                    );

            $this->asiento_model->insert($data);
        }           
    }


    public function asiento_anulacion($id,$mod){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $cns=$this->asiento_model->lista_asientos_modulo($id,$mod);
        $asiento = $this->asiento_model->siguiente_asiento();

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
