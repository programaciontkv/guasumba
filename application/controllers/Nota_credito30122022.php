<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_credito extends CI_Controller {

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
		$this->load->model('nota_credito_model');
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
		$this->load->model('cheque_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");
		$this->load->library('email');
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
			$cns_notas=$this->nota_credito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_notas=$this->nota_credito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'notas'=>$cns_notas,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('nota_credito/lista',$data);
		$modulo=array('modulo'=>'nota_credito');
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
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_productos'=>$this->nota_credito_model->lista_productos('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'nota'=> (object) array(
											'ncr_fecha_emision'=>date('Y-m-d'),
											'ncr_numero'=>'',
											'ncr_num_comp_modifica'=>'',
											'ncr_fecha_emi_comp'=>'',
											'fac_id'=>'0',
					                        'cli_id'=>'',
					                        'vnd_id'=>$vnd,
					                        'trs_id'=>'12',
					                        'ncr_identificacion'=>'',
					                        'ncr_nombre'=>'',
					                        'ncr_direccion'=>'',
					                        'nrc_telefono'=>'',
					                        'ncr_email'=>'',
					                        'ncr_motivo'=>'',
					                        'ncr_subtotal12'=>'0',
					                        'ncr_subtotal0'=>'0',
					                        'ncr_subtotal12'=>'0',
					                        'ncr_subtotal0'=>'0',
					                        'ncr_subtotal12'=>'0',
					                        'ncr_subtotal0'=>'0',
					                        'ncr_subtotal_ex_iva'=>'0',
					                        'ncr_subtotal_no_iva'=>'0',
					                        'ncr_subtotal'=>'0',
					                        'ncr_total_descuento'=>'0',
					                        'ncr_total_ice'=>'0',
					                        'ncr_total_iva'=>'0',
					                        'ncr_total_propina'=>'0',
					                        'nrc_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'ncr_id'=>'',
										),
						'cns_det'=>'',
						'action'=>base_url().'nota_credito/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						);
			$we =  intval($this->session->userdata('s_we'));
			if($we>=760){

				$this->load->view('nota_credito/form',$data);
			}else{

				$this->load->view('nota_credito/form_movil',$data);
			}
			$modulo=array('modulo'=>'nota_credito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$ncr_fecha_emision = $this->input->post('ncr_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$fac_id= $this->input->post('fac_id');
		$ncr_num_comp_modifica= $this->input->post('ncr_num_comp_modifica');
		$ncr_fecha_emi_comp= $this->input->post('ncr_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$ncr_motivo = $this->input->post('ncr_motivo');
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
		$cja_id = $this->input->post('cja_id');
		$ped_id = $this->input->post('ped_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('ncr_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('ncr_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('ncr_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('ncr_motivo','Motivo','required');
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

			
			$rst_sec = $this->nota_credito_model->lista_secuencial_documento($emi_id,$cja_id);
		    if (empty($rst_sec)) {
		        $sec = $rst_cja->cja_sec_nota_credito;
		    } else {
		    	$sc=explode('-',$rst_sec->ncr_numero);
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
		    $ncr_numero = $ems . '-'.$caja.'-' . $tx . $sec;

		    $clave_acceso=$this->clave_acceso($cja_id,$ncr_numero,$ncr_fecha_emision);

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
		    				'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'fac_id'=>$fac_id,
							'trs_id'=>$trs_id,
							'ncr_denominacion_comprobante'=>'1',
							'ncr_fecha_emision'=>$ncr_fecha_emision,
							'ncr_numero'=>$ncr_numero, 
							'ncr_nombre'=>$nombre, 
							'ncr_identificacion'=>$identificacion, 
							'ncr_email'=>$email_cliente, 
							'ncr_direccion'=>$direccion_cliente, 
							'ncr_motivo'=>$ncr_motivo, 
							'ncr_num_comp_modifica'=>$ncr_num_comp_modifica, 
							'ncr_fecha_emi_comp'=>$ncr_fecha_emi_comp, 
							'ncr_subtotal12'=>$subtotal12, 
							'ncr_subtotal0'=>$subtotal0, 
							'ncr_subtotal_ex_iva'=>$subtotalex, 
							'ncr_subtotal_no_iva'=>$subtotalno, 
							'ncr_total_descuento'=>$total_descuento, 
							'ncr_total_ice'=>$total_ice, 
							'ncr_total_iva'=>$total_iva, 
							'nrc_telefono'=>$telefono_cliente,
							'nrc_total_valor'=>$total_valor,
							'ncr_subtotal'=>$subtotal,
							'ncr_clave_acceso'=>$clave_acceso,
							'ncr_estado'=>'4'
		    );


		    $ncr_id=$this->nota_credito_model->insert($data);
		    if(!empty($ncr_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("pro_aux$n")!='' && $this->input->post("cantidad$n")>0){
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
		    							'ncr_id'=>$ncr_id,
	                                    'pro_id'=>$pro_id,
	                                    'dnc_codigo'=>$dfc_codigo,
	                                    'dnc_cod_aux'=>$dfc_cod_aux,
	                                    'dnc_cantidad'=>$dfc_cantidad,
	                                    'dnc_descripcion'=>$dfc_descripcion,
	                                    'dnc_precio_unit'=>$dfc_precio_unit,
	                                    'dnc_porcentaje_descuento'=>$dfc_porcentaje_descuento,
	                                    'dnc_val_descuento'=>$dfc_val_descuento,
	                                    'dnc_precio_total'=>$dfc_precio_total,
	                                    'dnc_iva'=>$dfc_iva,
	                                    'dnc_ice'=>$dfc_ice, 
	                                    'dnc_p_ice'=>$dfc_p_ice, 
	                                    'dnc_cod_ice'=>$dfc_cod_ice,
		    						);
		    			$this->nota_credito_model->insert_detalle($dt_det);
		    		}
		    	

		    	//movimientos
		    		if($trs_id!='1'){
			    		$inven=$this->configuracion_model->lista_una_configuracion('3');
			    		if ($inven->con_valor == 0) {
			    			if($trs_id!=1){
		                        $k = 0;
		                        while ($k < $count_det) {
		                        	$k++;
		                        	if($this->input->post("pro_aux$k")!='' && $this->input->post("cantidad$k")>0){
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
		                                                    'mov_documento'=>$ncr_numero,
		                                                    'mov_num_factura'=>$ncr_numero,
		                                                    'mov_fecha_trans'=>$ncr_fecha_emision,
		                                                    'mov_fecha_registro'=>$fec_mov,
		                                                    'mov_hora_registro'=>$hor_mov, 
		                                                    'mov_cantidad'=>$dfc_cantidad,
		                                                    'mov_fecha_entrega'=>$fec_mov,
		                                                    'mov_val_unit'=>$mov_cost_unit,
		                                                    'mov_val_tot'=>$mov_cost_tot,
		                                                    'emp_id'=>$emp_id,
		                                                    'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
		                                        			);

		                                $this->nota_credito_model->insert_movimientos($dt_movimientos);
		                            	}
		                            }
		                        }
	                        }
	                    }    
                    }
		    		
		    	}
		    	///CHEQUES
		    	$cuenta="1.01.01.01.002";
		    	if($conf_as->con_valor==0){
		    		$cta=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('99',$emi_id);
		    		$cuenta=$cta->pln_codigo;
		    	}

		    	$dt_cheque=array(	
		    				'emp_id'=>$emp_id,
		    				'cli_id'=>$cli_id,
		    				'chq_recepcion'=>$ncr_fecha_emision,
							'chq_fecha'=>$ncr_fecha_emision,
							'chq_tipo_doc'=>'8', 
							'chq_nombre'=>'NOTA DE CREDITO', 
							'chq_concepto'=>'NOTA DE CREDITO',
							'chq_banco'=>'',
							'chq_numero'=>$ncr_numero,
							'chq_monto'=>$total_valor,
							'chq_estado'=>'7',
							'chq_estado_cheque'=>'11',
							'doc_id'=>$ncr_id,
							'chq_cuenta'=>$cuenta
		    		);
		    		$this->cheque_model->insert($dt_cheque);

		    	$this->generar_xml($ncr_id);
		    	
		    	//genera asientos
		    	
		        if($conf_as->con_valor==0){
		        	$this->asientos($ncr_id);
		        }

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'NOTA DE CREDITO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$ncr_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'nota_credito/show_frame/'. $ncr_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'nota_credito/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$rst=$this->nota_credito_model->lista_una_nota($id);
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){

			///recupera detalle
			$cns_dt=$this->nota_credito_model->lista_detalle_nota($id);
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
					$rst1 =$this->nota_credito_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                $rst2 = $this->nota_credito_model->lista_costos_mov($rst_dt->pro_id,$fra); 
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
		    $rst_df= $this->nota_credito_model->lista_un_detalle_factura($rst->fac_id,$rst_dt->pro_id);    
	        if(empty($rst_df)){
	        	$cantidadf=0;
	        }else{
				$cantidadf=$rst_df->dfc_cantidad;
	        }

	        $rst_dn=$this->nota_credito_model->lista_suma_detalle_edit($rst->fac_id,$rst_dt->pro_id,$rst->ncr_id); 
		    if(empty($rst_dn)){
		       	$cnt_nc=0;
		    }else{
		        $cnt_nc=$rst_dn->dnc_cantidad;
		    }


			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->dnc_descripcion,
						'pro_codigo'=>$rst_dt->dnc_codigo,
						'pro_precio'=>$rst_dt->dnc_precio_unit,
						'pro_iva'=>$rst_dt->dnc_iva,
						'pro_descuento'=>$rst_dt->dnc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->dnc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'inventario'=>$inv,
						'cantidadf'=>$cantidadf,
						'cantidadn'=>$cnt_nc,
						'cantidad'=>$rst_dt->dnc_cantidad,
						'cost_unit'=>$cost_unit,
						'ice'=>$rst_dt->dnc_ice,
						'ice_p'=>$rst_dt->dnc_p_ice,
						'ice_cod'=>$rst_dt->dnc_cod_ice,
						'precio_tot'=>$rst_dt->dnc_precio_total,
						
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
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_productos'=>$this->factura_model->lista_productos('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=>$this->nota_credito_model->lista_una_nota($id),
						'cns_det'=>$cns_det,
						'action'=>base_url().'nota_credito/actualizar/'.$opc_id
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('nota_credito/form',$data);
			$modulo=array('modulo'=>'nota_credito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('ncr_id');
		$ncr_fecha_emision = $this->input->post('ncr_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$fac_id= $this->input->post('fac_id');
		$ncr_num_comp_modifica= $this->input->post('ncr_num_comp_modifica');
		$ncr_fecha_emi_comp= $this->input->post('ncr_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$ncr_motivo = $this->input->post('ncr_motivo');
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
		$cja_id = $this->input->post('cja_id');
		$ped_id = $this->input->post('ped_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('ncr_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('ncr_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('ncr_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('ncr_motivo','Motivo','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			

			$rst_ncr=$this->nota_credito_model->lista_una_nota($id);
		    $clave_acceso=$this->clave_acceso($cja_id,$rst_ncr->ncr_numero,$ncr_fecha_emision);

		    $data=array(	
		    				// 'emp_id'=>$emp_id,
		    				// 'emi_id'=>$emi_id,
		    				// 'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'fac_id'=>$fac_id,
							'trs_id'=>$trs_id,
							'ncr_denominacion_comprobante'=>'1',
							'ncr_fecha_emision'=>$ncr_fecha_emision,
							// 'ncr_numero'=>$ncr_numero, 
							'ncr_nombre'=>$nombre, 
							'ncr_identificacion'=>$identificacion, 
							'ncr_email'=>$email_cliente, 
							'ncr_direccion'=>$direccion_cliente, 
							'ncr_motivo'=>$ncr_motivo, 
							'ncr_num_comp_modifica'=>$ncr_num_comp_modifica, 
							'ncr_fecha_emi_comp'=>$ncr_fecha_emi_comp, 
							'ncr_subtotal12'=>$subtotal12, 
							'ncr_subtotal0'=>$subtotal0, 
							'ncr_subtotal_ex_iva'=>$subtotalex, 
							'ncr_subtotal_no_iva'=>$subtotalno, 
							'ncr_total_descuento'=>$total_descuento, 
							'ncr_total_ice'=>$total_ice, 
							'ncr_total_iva'=>$total_iva, 
							'nrc_telefono'=>$telefono_cliente,
							'nrc_total_valor'=>$total_valor,
							'ncr_subtotal'=>$subtotal,
							'ncr_clave_acceso'=>$clave_acceso,
							'ncr_estado'=>'4'
		    );


			if($this->nota_credito_model->update($id,$data)){
				if($this->nota_credito_model->delete_detalle($id)){
			    	$n=0;
			    	while($n<$count_det){
			    		$n++;
				    		if($this->input->post("pro_aux$n")!='' && $this->input->post("cantidad$n")>0){
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
			    							'ncr_id'=>$id,
		                                    'pro_id'=>$pro_id,
		                                    'dnc_codigo'=>$dfc_codigo,
		                                    'dnc_cod_aux'=>$dfc_cod_aux,
		                                    'dnc_cantidad'=>$dfc_cantidad,
		                                    'dnc_descripcion'=>$dfc_descripcion,
		                                    'dnc_precio_unit'=>$dfc_precio_unit,
		                                    'dnc_porcentaje_descuento'=>$dfc_porcentaje_descuento,
		                                    'dnc_val_descuento'=>$dfc_val_descuento,
		                                    'dnc_precio_total'=>$dfc_precio_total,
		                                    'dnc_iva'=>$dfc_iva,
		                                    'dnc_ice'=>$dfc_ice, 
		                                    'dnc_p_ice'=>$dfc_p_ice, 
		                                    'dnc_cod_ice'=>$dfc_cod_ice,
			    						);
			    			$this->nota_credito_model->insert_detalle($dt_det);
			    		}
			    	}
		    	}


		    	//cheque
		    	$dt_cheque=array(	
		    				'emp_id'=>$emp_id,
		    				'cli_id'=>$cli_id,
		    				'chq_recepcion'=>$ncr_fecha_emision,
							'chq_fecha'=>$ncr_fecha_emision,
							'chq_tipo_doc'=>'8', 
							'chq_nombre'=>'NOTA DE CREDITO', 
							'chq_concepto'=>'NOTA DE CREDITO',
							'chq_banco'=>'',
							'chq_numero'=>$rst_ncr->ncr_numero,
							'chq_monto'=>$total_valor,
							'chq_estado'=>'7',
							'chq_estado_cheque'=>'11',
							'doc_id'=>$id
		    		);

		    	$this->cheque_model->update_chq_nota($id,$dt_cheque);
		    	
		    	//movimientos
		    		$inven=$this->configuracion_model->lista_una_configuracion('3');
		    		if ($inven->con_valor == 0) {
		    			$up_dt=array('mov_estado'=>3);
		    			$this->nota_credito_model->update_movimientos($rst_ncr->ncr_numero,$rst_ncr->emi_id,$up_dt);
                        if($trs_id!=1){
	                        $k = 0;
	                        while ($k < $count_det) {
	                        	$k++;
	                        	if($this->input->post("pro_aux$k")!='' && $this->input->post("cantidad$k")>0){
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
		                                                    'mov_documento'=>$rst_ncr->ncr_numero,
		                                                    'mov_num_factura'=>$rst_ncr->ncr_numero,
		                                                    'mov_fecha_trans'=>$rst_ncr->ncr_fecha_emision,
		                                                    'mov_fecha_registro'=>$fec_mov,
		                                                    'mov_hora_registro'=>$hor_mov, 
		                                                    'mov_cantidad'=>$dfc_cantidad,
		                                                    'mov_fecha_entrega'=>$fec_mov,
		                                                    'mov_val_unit'=>$mov_cost_unit,
		                                                    'mov_val_tot'=>$mov_cost_tot,
		                                                    'emp_id'=>$emp_id,
		                                                    'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
		                                        			);

		                                $this->nota_credito_model->insert_movimientos($dt_movimientos);
		                            }
		                        }
	                        }
	                    }
                    }
		    	
                $this->generar_xml($id);    
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'NOTA DE CREDITO',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_ncr->ncr_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'nota_credito/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'nota_credito/editar'.$id.'/'.$opc_id);
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

			$rst_chq=$this->cheque_model->lista_cheque_nota_ctaxcob($id);
			if(empty($rst_chq)){
				$rst_ncr=$this->nota_credito_model->lista_una_nota($id);
				$up_dt=array('mov_estado'=>3);
			    $this->nota_credito_model->update_movimientos($rst_ncr->ncr_numero,$rst_ncr->emi_id,$up_dt);
			    $up_dtf=array('ncr_estado'=>3);
				if($this->nota_credito_model->update($id,$up_dtf)){
					$up_chq=array('chq_estado_cheque'=>3,'chq_estado'=>3);
					$this->cheque_model->update_chq_nota($id,$up_chq);
					//asiento anulacion nota
					if($cnf_as==0){
						$this->asiento_anulacion($id,'2');
					}

					$data_aud=array(
									'usu_id'=>$this->session->userdata('s_idusuario'),
									'adt_date'=>date('Y-m-d'),
									'adt_hour'=>date('H:i'),
									'adt_modulo'=>'NOTA DE CREDITO',
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

				}else{
					$data=array(
							'estado'=>1,
							'sms'=>'No se anulo la Nota de Credito',
							'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
					);
				}
			}else{
				$data=array(
							'estado'=>1,
							'sms'=>'No se puede anular. La Nota de Credito esta utilizada como Forma de Pago',
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
		$etiqueta='Nota_credito.pdf';
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Nota de Credito '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"nota_credito/show_pdf/$id/$opc_id/0/$etiqueta",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'nota_credito');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id,$correo,$etiqueta){
    		$rst=$this->nota_credito_model->lista_una_nota($id);
    		$imagen=$this->set_barcode($rst->ncr_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			///recupera detalle
			$cns_dt=$this->nota_credito_model->lista_detalle_nota($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
	        
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'pro_descripcion'=>$rst_dt->dnc_descripcion,
						'pro_codigo'=>$rst_dt->dnc_codigo,
						'pro_precio'=>$rst_dt->dnc_precio_unit,
						'pro_iva'=>$rst_dt->dnc_iva,
						'pro_descuento'=>$rst_dt->dnc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->dnc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'cantidad'=>$rst_dt->dnc_cantidad,
						'ice'=>$rst_dt->dnc_ice,
						'ice_p'=>$rst_dt->dnc_p_ice,
						'ice_cod'=>$rst_dt->dnc_cod_ice,
						'precio_tot'=>$rst_dt->dnc_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=>$this->nota_credito_model->lista_una_nota($id),
						'cns_det'=>$cns_det,
						);
			$this->html2pdf->filename('nota_credito.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_nota_credito', $data, true)));
    		$this->html2pdf->folder('./pdfs/');
            $this->html2pdf->filename($rst->ncr_clave_acceso.'.pdf');
            $this->html2pdf->create('save');
            
			if($correo==1){
				$datos_mail=(object) array('tipo' =>'NOTA DE CREDITO' ,
                              'cliente'=>$rst->ncr_nombre,
                              'emisor'=>$rst->emp_nombre,
                              'numero'=>$rst->ncr_numero,
                              'fecha'=>$rst->ncr_fecha_emision,
                              'total'=>$rst->nrc_total_valor,
                              'correo'=>$rst->ncr_email,
                              'ncr_id'=>$rst->ncr_id,
                              'logo'=>$rst->emp_logo,
                              'clave'=>$rst->ncr_clave_acceso,
                                 );
				$this->envio_mail($datos_mail);
			}else{
				$this->html2pdf->output(array("Attachment" => 0));	
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

	public function traer_facturas($num,$emi){
		$rst=$this->factura_model->lista_factura_numero($num,$emi);
		echo json_encode($rst);
	}

	public function load_factura($id,$inven,$ctrl_inv,$dec,$dcc){
		$rst=$this->factura_model->lista_una_factura($id);
		$cns=$this->factura_model->lista_detalle_factura($id);
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
		                    $fra = "and m.bod_id=$rst->emi_id";
		                }
					$rst1 =$this->nota_credito_model->total_ingreso_egreso_fact($rst_det->pro_id,$fra); 
					if(!empty($rst1)){
		                
		                $inv = $rst1->ingreso - $rst1->egreso;
		                $rst2 = $this->nota_credito_model->lista_costos_mov($rst_det->pro_id,$fra); 
		                if(!empty($rst2)){
		                	$cnt_inv=$rst2->ingreso - $rst2->egreso;
		                	$prec_inv=$rst2->icnt - $rst2->ecnt;
		                	if($cnt_inv==0 || $prec_inv==0){
		                		$cost_unit=0;
		                	}else{
		                		$cost_unit = (($cnt_inv) / ($prec_inv));
		                	}
		                	$cost_tot =$cost_unit*$rst_det->dfc_cantidad;
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
		        
		        $rst_dn=$this->nota_credito_model->lista_suma_detalle($rst->fac_id,$rst_det->pro_id); 
		        if(empty($rst_dn)){
		        	$cnt_nc=0;
		        }else{
		        	$cnt_nc=$rst_dn->dnc_cantidad;
		        }
		        $cantidad=$rst_det->dfc_cantidad-$cnt_nc;
		        $a='"';
		        $we =  intval($this->session->userdata('s_we'));
				if($we>=760){
					$w_movil='';
				}else{

					$w_movil='hidden';
				}
		        $detalle.="<tr>
                                            <td $w_movil id='item$n' name='item$n' lang='$n' align='center'>$n</td>
                                            <td $w_movil ><input type ='text' size='10'  style='text-align:right' class='form-control' id='pro_descripcion$n' name='pro_descripcion$n' value='$rst_det->mp_c' lang='$n' readonly/></td>
                                            </td>
                                            <td width='150px' ><input type ='text'   style='text-align:left' class='form-control' id='pro_referencia$n' name='pro_referencia$n' value='$rst_det->mp_d' lang='$n' readonly/>
                                                <input type='hidden' size='7' id='pro_aux$n' name='pro_aux$n' value='$rst_det->pro_id' lang='$n'/>
                                                <input type='hidden' size='7' id='mov_cost_unit$n' name='mov_cost_unit$n' value='".str_replace(',', '', number_format($cost_unit, $dec))."' lang='$n'/>
                                                <input type='hidden' size='7' id='mov_cost_tot$n' name='mov_cost_tot$n' value='".str_replace(',', '', number_format($cost_tot, $dec))."' lang='$n'/>
                                            </td>
                                            <td $w_movil id='unidad$n' name='unidad$n' lang='$n'>$rst_det->mp_q</td>
                                            <td $w_movil id='inventario$n' name='inventario$n' lang='$n' $hid_inv  style='text-align:right'>".str_replace(',', '', number_format($inv, $dcc))."</td>
                                            <td hidden><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidadn$n' name='cantidadn$n' value='".str_replace(',', '', number_format($cnt_nc, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidadf$n' name='cantidadf$n' value='".str_replace(',', '', number_format($rst_det->dfc_cantidad, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidad$n' name='cantidad$n' value='".str_replace(',', '', number_format($cantidad, $dec))."' lang='$n' onchange='calculo(this), validar_cantfactura(this), costo_det(this)' onkeyup='this.value = this.value.replace(/[^0-9.]/, $a$a)'  /></td>
                                            <td><input type ='text'  style='text-align:right' width='50px' class='form-control decimal' id='pro_precio$n' name='pro_precio$n' value='".str_replace(',', '', number_format($rst_det->dfc_precio_unit, $dec))."' lang='$n' onchange='calculo(this)' readonly /></td>
                                            <td>
                                                <input type ='text' size='15' style='text-align:right' class='form-control decimal' id='descuento$n' name='descuento$n'  value='".str_replace(',', '', number_format($rst_det->dfc_porcentaje_descuento, $dec))."' lang='$n' onchange='calculo(this)'  readonly />
                                            </td>
                                            <td $w_movil>
                                                <input type ='text' size='7' style='text-align:right' class='form-control decimal' id='descuent$n' name='descuent$n'  value='".str_replace(',', '', number_format($rst_det->dfc_val_descuento, $dec))."' lang='$n'  readonly/>
                                            </td>
                                            <td width='5px' ><input type='text' id='iva$n' name='iva$n'  style='text-align:right' class='form-control' value='$rst_det->dfc_iva' lang='$n' readonly /></td>
                                            <td hidden><input type='text' id='ice_p$n' name='ice_p$n' size='5' value='".str_replace(',', '', number_format($rst_det->dfc_p_ice, $dec))."' lang='$n' readonly /></td>
                                            <td hidden><input type='text' id='ice$n' name='ice$n' size='5' class='form-control' value='".str_replace(',', '', number_format($rst_det->dfc_ice, $dec))."' readonly lang='$n'/>
                                                <input type='hidden' id='ice_cod$n' name='ice_cod$n' size='5' class='form-control' value='$rst_det->dfc_cod_ice' lang='$n'readonly />
                                            </td>
                                            <td width='100px'>
                                                <input type ='text' style='text-align:right' class='form-control' id='valor_total$n' name='valor_total$n'  value='".str_replace(',', '', number_format($rst_det->dfc_precio_total, $dec))."' readonly lang='$n'/>
                                                
                                            </td>
                                            <td onclick='elimina_fila_det(this)' align='center' ><span class='btn btn-danger fa fa-trash'></span></td>
                                        </tr>";
				
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
						'fac_subtotal'=>$rst->fac_subtotal,
						'fac_subtotal12'=>$rst->fac_subtotal12,
						'fac_subtotal0'=>$rst->fac_subtotal0,
						'fac_subtotal_ex_iva'=>$rst->fac_subtotal_ex_iva,
						'fac_subtotal_no_iva'=>$rst->fac_subtotal_no_iva,
						'fac_total_descuento'=>$rst->fac_total_descuento,
						'fac_total_iva'=>$rst->fac_total_iva,
						'fac_total_ice'=>$rst->fac_total_ice,
						'fac_total_valor'=>$rst->fac_total_valor,
						'detalle'=>$detalle,
						'cnt_detalle'=>$n
						);	

		echo json_encode($data);
	} 

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Nota de Credito '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="nota_credito".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function consulta_sri($id,$opc_id){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
    	$ambiente=$amb->con_valor;
    	if($ambiente!=0){
	    	$nota=$this->nota_credito_model->lista_una_nota($id);
	        set_time_limit(0);
	        if ($ambiente == 2) { //Produccion
            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $nota->ncr_clave_acceso]);
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($nota->ncr_id); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];

	        	if($res['estado']=='AUTORIZADO'){
	        		$data = array(
	            					'ncr_autorizacion'=>$res['numeroAutorizacion'], 
	            					'ncr_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'ncr_xml_doc'=>$res['comprobante'], 
	            					'ncr_estado'=>'6'
	            				);
	            	$this->nota_credito_model->update($nota->ncr_id,$data);

	        		$data_xml = array(
                					'estado'=>$res['estado'], 
                                    'autorizacion'=>$res['numeroAutorizacion'], 
                					'fecha'=>$res['fechaAutorizacion'], 
                					'comprobante'=>$res['comprobante'], 
                                    'ambiente'=>$res['ambiente'], 
                                    'clave'=>$nota->ncr_clave_acceso
                				);
	        		$this->generar_xml_autorizado($data_xml,$nota->ncr_id,$opc_id); 
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
    	$nota=$this->nota_credito_model->lista_una_nota($id);
    	$detalle=$this->nota_credito_model->lista_detalle_nota($nota->ncr_id);
        $dec = $this->configuracion_model->lista_una_configuracion('2');
        $round=$dec->con_valor;
        $codigo='12345678';  
        $tp_emison='1';  
        $empresa=$this->empresa_model->lista_una_empresa($nota->emp_id);    
        $emisor=$this->emisor_model->lista_un_emisor($nota->emi_id);    
        $ndoc = explode('-', $nota->ncr_numero);
        $nfact = str_replace('-', '', $nota->ncr_numero);
        $ems = $ndoc[0];
        $emi = intval($ndoc[0]);
        $pt_ems = $ndoc[1];
        $secuencial = $ndoc[2];
        $cod_doc = '04'; //01= factura, 02=nota de credito tabla 4
        $fecha = date_format(date_create($nota->ncr_fecha_emision), 'd/m/Y');
        $f2 = date_format(date_create($nota->ncr_fecha_emision), 'dmY');
        $dir_cliente = $nota->ncr_direccion;
        $telf_cliente = $nota->nrc_telefono;
        $email_cliente = $nota->ncr_email;
        $contabilidad = $empresa->emp_obligado_llevar_contabilidad;
        $razon_soc_comprador = $nota->ncr_nombre;
        $id_comprador = $nota->ncr_identificacion;;
        if (strlen($id_comprador) == 13 && $id_comprador != '9999999999999' && substr($id_comprador, -3) == '001') {
            $tipo_id_comprador = "04"; //RUC 04 
        } else if (strlen($id_comprador) == 10) {
            $tipo_id_comprador = "05"; //CEDULA 05 
        } else if ($id_comprador == '9999999999999') {
            $tipo_id_comprador = "07"; //VENTA A CONSUMIDOR FINAL
        } else {
            $tipo_id_comprador = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
        }
        

        $clave = $nota->ncr_clave_acceso;

        $xml.="<?xml version='1.0' encoding='UTF-8'?>" . chr(13);
	    $xml.="<notaCredito version='1.1.0' id='comprobante'>" . chr(13);
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
	    $xml.="<infoNotaCredito>" . chr(13);
	    $xml.="<fechaEmision>" . $fecha . "</fechaEmision>" . chr(13);
	    $xml.="<dirEstablecimiento>" . $emisor->emi_dir_establecimiento_emisor . "</dirEstablecimiento>" . chr(13);
	    $xml.="<tipoIdentificacionComprador>" . $tipo_id_comprador . "</tipoIdentificacionComprador>" . chr(13);
	    $xml.="<razonSocialComprador>" . $razon_soc_comprador . "</razonSocialComprador>" . chr(13);
	    $xml.="<identificacionComprador>" . $id_comprador . "</identificacionComprador>" . chr(13);
	    if(!empty($empresa->emp_contribuyente_especial)){
        	$xml.="<contribuyenteEspecial>$empresa->emp_contribuyente_especial</contribuyenteEspecial>" . chr(13);
    	}
	    $xml.="<obligadoContabilidad>" . $contabilidad . "</obligadoContabilidad>" . chr(13);
	    $xml.="<codDocModificado>0" . $nota->ncr_denominacion_comprobante . "</codDocModificado>" . chr(13);
	    $xml.="<numDocModificado>" . $nota->ncr_num_comp_modifica . "</numDocModificado>" . chr(13);
	    $xml.="<fechaEmisionDocSustento>" . date_format(date_create($nota->ncr_fecha_emi_comp), 'd/m/Y') . "</fechaEmisionDocSustento>" . chr(13);
	    $xml.="<totalSinImpuestos>" . round($nota->ncr_subtotal, $round) . "</totalSinImpuestos>" . chr(13);
	    $xml.="<valorModificacion>" . round($nota->nrc_total_valor, $round) . "</valorModificacion>" . chr(13);
	    $xml.="<moneda>DOLAR</moneda>" . chr(13);
	    $xml.="<totalConImpuestos>" . chr(13);

	    $base = 0;

	    if ($nota->ncr_subtotal12 != 0) {
	        $codPorc = 2;
	        $base = $nota->ncr_subtotal12;
	        $valo_iva = round($base * 12 / 100, $round);
	        $xml.="<totalImpuesto>" . chr(13);
	        $xml.="<codigo>2</codigo>" . chr(13); //Tipo de Impuesto
	        $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13); //Codigo del
	        $xml.="<baseImponible>" . round($base, $round) . "</baseImponible>" . chr(13);
	        $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
	        $xml.="</totalImpuesto>" . chr(13);
	    }
	    if ($nota->ncr_subtotal0 != 0) {
	        $codPorc = 0;
	        $base = $nota->ncr_subtotal0;
	        $valo_iva = 0;
	        $xml.="<totalImpuesto>" . chr(13);
	        $xml.="<codigo>2</codigo>" . chr(13); //Tipo de Impuesto
	        $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13); //Codigo del
	        $xml.="<baseImponible>" . round($base, $round) . "</baseImponible>" . chr(13);
	        $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
	        $xml.="</totalImpuesto>" . chr(13);
	    }
	    if ($nota->ncr_subtotal_no_iva != 0) {
	        $codPorc = 6;
	        $base = $nota->ncr_subtotal_no_iva;
	        $valo_iva = 0;
	        $xml.="<totalImpuesto>" . chr(13);
	        $xml.="<codigo>2</codigo>" . chr(13); //Tipo de Impuesto
	        $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13); //Codigo del
	        $xml.="<baseImponible>" . round($base, $round) . "</baseImponible>" . chr(13);
	        $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
	        $xml.="</totalImpuesto>" . chr(13);
	    }
	    if ($nota->ncr_subtotal_ex_iva != 0) {
	        $codPorc = 7;
	        $base = $nota->ncr_subtotal_ex_iva;
	        $valo_iva = 0;
	        $xml.="<totalImpuesto>" . chr(13);
	        $xml.="<codigo>2</codigo>" . chr(13); //Tipo de Impuesto
	        $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13); //Codigo del
	        $xml.="<baseImponible>" . round($base, $round) . "</baseImponible>" . chr(13);
	        $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
	        $xml.="</totalImpuesto>" . chr(13);
	    }

	    $xml.="</totalConImpuestos>" . chr(13);
	    $xml.="<motivo>" . $nota->ncr_motivo . "</motivo>" . chr(13);
	    $xml.="</infoNotaCredito>" . chr(13);
	    $xml.="<detalles>" . chr(13);
	    foreach ($detalle as $det) {
	        $xml.="<detalle>" . chr(13);
	        $xml.="<codigoInterno>" . trim($det->dnc_codigo) . "</codigoInterno >" . chr(13);
	        // if (trim($det->mp_n) != '') {
	        //     $xml.="<codigoAdicional>" . trim($det->mp_n) . "</codigoAdicional>" . chr(13);
	        // }
	        $xml.="<descripcion>" . trim($det->dnc_descripcion) . "</descripcion>" . chr(13);
	        $xml.="<cantidad>" . round($det->dnc_cantidad, $round) . "</cantidad>" . chr(13);
	        $xml.="<precioUnitario>" . round($det->dnc_precio_unit, $round) . "</precioUnitario>" . chr(13);
	        $xml.="<descuento>" . round($det->dnc_val_descuento, $round) . "</descuento>" . chr(13);
	        $xml.="<precioTotalSinImpuesto>" . round($det->dnc_precio_total, $round) . "</precioTotalSinImpuesto>" . chr(13);
	        $xml.="<impuestos>" . chr(13);
	        $xml.="<impuesto>" . chr(13);
	        $xml.="<codigo>2</codigo>" . chr(13);
	        if ($det->dnc_iva == '12') {
	            $codPorc = 2;
	            $valo_iva = round($det->dnc_precio_total + $det->dnc_ice * 12 / 100, $round);
	            $tarifa = 12;
	        } else if ($det->dnc_iva == '0') {
	            $codPorc = 0;
	            $valo_iva = 0.00;
	            $tarifa = 0;
	        } else if ($det->dnc_iva == 'NO') {
	            $codPorc = 6;
	            $valo_iva = 0.00;
	            $tarifa = 0;
	        } else if ($det->dnc_iva == 'EX') {
	            $codPorc = 7;
	            $valo_iva = 0.00;
	            $tarifa = 0;
	        }
	        $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13);
	        $xml.="<tarifa>" . $tarifa . "</tarifa>" . chr(13);
	        $xml.="<baseImponible>" . round($det->dnc_precio_total + $det->dnc_ice, $round) . "</baseImponible>" . chr(13);
	        $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
	        $xml.="</impuesto>" . chr(13);
	        $xml.="</impuestos>" . chr(13);
	        $xml.="</detalle>" . chr(13);
	    }
	    $xml.="</detalles>" . chr(13);
	    $xml.="<infoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Direccion'>" . $dir_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Telefono'>" . $telf_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Email'>" . strtolower(utf8_decode($email_cliente)) . "</campoAdicional>" . chr(13);
	    if(!empty($nota->emp_leyenda_sri)){
        	$xml.="<campoAdicional nombre='Observaciones'> " .$nota->emp_leyenda_sri. "</campoAdicional>" . chr(13);
        }
	    $xml.="</infoAdicional>" . chr(13);
	    $xml.="</notaCredito>" . chr(13);
	    
        $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);

		// header("Location: http://localhost:8080/central_xml_local/envio_sri/firmar.php?clave=$clave&programa=$programa&firma=$firma&password=$pass&ambiente=$ambiente");
		}
    }


    public function generar_xml_autorizado($dt,$id,$opc_id){
    	if (!empty($dt)) {
            $xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
              <ns2:RespuestaAutorizacion xsi:type='ns2:autorizacion' xmlns:ns2='http://ec.gob.sri.ws.autorizacion' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
              <estado>" . $dt['estado'] . "</estado>
              <numeroAutorizacion>" . $dt['autorizacion'] . "</numeroAutorizacion>
              <fechaAutorizacion>" . $dt['fecha'] . "</fechaAutorizacion>
              <ambiente>" . $dt['ambiente'] . "</ambiente>
              <comprobante><![CDATA[" . $dt['comprobante'] . "]]></comprobante>
              <mensajes/>
              </ns2:RespuestaAutorizacion>";
                $fch = fopen("./xml_docs/" . $dt['clave'] . ".xml", "w+o");
                fwrite($fch, $xml);
                fclose($fch);
                $etiqueta='Nota_credito.pdf';
            $this->show_pdf($id,$opc_id,1,$etiqueta);    
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

        $this->email->initialize($config);

        $this->email->from($cred[3], $cred[5]);
        $mls = explode(';', $datos->correo);
        $x = 0;
        $correos='';
        if(count($mls)<1){
	        while ($x <= count($mls)) {
	          if($x==0){
	            $correos.=$mls[$x];
	          }else{
	            $correos.=','.$mls[$x];
	          }
	          $x++;
	        }
    	}else{
    		$correos=$mls;
    	}
        $this->email->to($correos);
        $this->email->cc($cred[3]);

        $this->email->attach("./pdfs/$datos->clave.pdf");
        $this->email->attach("./xml_docs/$datos->clave.xml");
        $this->email->subject("DOCUMENTOS ELECTRONICOS $datos->tipo No: $datos->numero Cliente : $datos->cliente ");
        $img_logo=base_url().'imagenes/'.$datos->logo;
        $img_mail=base_url().'imagenes/mail2.png';
        $img_whatsapp=base_url().'imagenes/whatsapp2.png';
        $img_telefono=base_url().'imagenes/telefono2.png';
        $datos_sms = "<html>
              <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                 <style>
                      td {
                          color: #828282;
                          font-family: Arial, Helvetica, sans-serif;
                          font-size: 14px;
                          text-align: center;
                          font-weight: bolder;
                      }

                      
                  </style>
             </head>
             <body>
               <table width='100%'>
                
                  <tr><td><img  height='150px' width='300px' src='$img_logo'/></td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td>Hola $datos->cliente, </td></tr>
                  <tr><td>Has recibido un nuevo documento electronico.</td></tr>
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
                      
                        <img src='$img_mail' width='20px'><a href='https://www.tivkas.com'>www.tivkas.com</a> 
                        <img src='$img_whatsapp' width='20px'> +593 999404989 / +593 991815559
                      </td>
                  </tr>
                  <tr><td style='font-size:10px'>Copyright &copy; 2022 Todos los derechos reservados <a href='https://www.tivkas.com'>TIKVASYST S.A.S</a></td></tr>
               </table>
             </body>
           </html>";
        $this->email->message(utf8_decode($datos_sms));

        if($this->email->send()){
            $data= array('ncr_estado_correo' =>'ENVIADO');
            if($this->nota_credito_model->update($datos->ncr_id,$data)){
            	echo "Nota Credito Enviada Correctamente";
            }
            
        }else{
            echo "no enviado";
        }

    }

    public function asientos($id){
        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->nota_credito_model->lista_una_nota($id);
        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('8',$rst->emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('9',$rst->emi_id);
        $vta=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('7',$rst->emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('10',$rst->emi_id);
        $des=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('84',$rst->emi_id);
        $pro=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('75',$rst->emi_id);
        
        $asiento =$this->asiento_model->siguiente_asiento();
        
      
        //cliente y DEVOLUCION
        $dat0 = array();
        $dat1 = array();
        $dat2 = array();
        $dat3 = array();

        $sub=round($rst->ncr_subtotal, $dec)+round($rst->ncr_total_descuento, $dec);

        if($rst->cli_tipo_cliente==0){
        	$ccli=$cli;
        }else{
        	$ccli=$cex;
        }
        $dat0 = Array(
                    'con_asiento'=>$asiento,
                    'con_concepto'=>'DEVOLUCION VENTA',
                    'con_documento'=>$rst->ncr_numero,
                    'con_fecha_emision'=>$rst->ncr_fecha_emision,
                    'con_concepto_debe'=>$vta->pln_codigo,
                    'con_concepto_haber'=>$ccli->pln_codigo,
                    'con_valor_debe'=>round($sub, $dec),
                    'con_valor_haber'=>round($rst->nrc_total_valor, $dec),
                    'mod_id'=>'2',
                    'doc_id'=>$rst->ncr_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );

        if ($rst->ncr_subtotal12 != 0) {
            $dat1 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'DEVOLUCION VENTA',
                        'con_documento'=>$rst->ncr_numero,
                        'con_fecha_emision'=>$rst->ncr_fecha_emision,
                        'con_concepto_debe'=>$iva->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($rst->ncr_total_iva, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'2',
                        'doc_id'=>$rst->ncr_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->ncr_total_descuento != 0) {
            $dat3 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'DEVOLUCION VENTA',
                        'con_documento'=>$rst->ncr_numero,
                        'con_fecha_emision'=>$rst->ncr_fecha_emision,
                        'con_concepto_debe'=>'',
                        'con_concepto_haber'=>$des->pln_codigo,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->ncr_total_descuento, $dec),
                        'mod_id'=>'2',
                        'doc_id'=>$rst->ncr_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        if ($rst->ncr_total_propina != 0) {
            $dat2 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'DEVOLUCION VENTA',
                        'con_documento'=>$rst->ncr_numero,
                        'con_fecha_emision'=>$rst->ncr_fecha_emision,
                        'con_concepto_debe'=>$pro->pln_codigo,
                        'con_concepto_haber'=>'',
                        'con_valor_debe'=>round($rst->fac_total_propina, $dec),
                        'con_valor_haber'=>'0.00',
                        'mod_id'=>'2',
                        'doc_id'=>$rst->ncr_id,
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
