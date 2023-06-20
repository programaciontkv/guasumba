<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido extends CI_Controller {

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
				$txt_est="and ped_estado=$est";
			}else{
				$txt_est="";
			}
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$txt_est);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$est="";
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$est);
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
						'estado'=>$est,
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
						
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pedido/lista',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer',$modulo);
		
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


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());

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
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cns_det'=>'',
						'cns_pag'=>'',
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'cnt_detalle'=>0,
						'cnt_pagos'=>0,
						'aut'=>0,
						'pedido'=> (object) array(
											'ped_femision'=>date('Y-m-d'),
											'ped_local'=>'6',
											'ped_vendedor'=>$vnd,
											'ped_ruc_cc_cliente'=>'',
											'ped_nom_cliente'=>'',
											'cli_id'=>'0',
											'ped_dir_cliente'=>'',
											'ped_tel_cliente'=>'',
											'ped_email_cliente'=>'',
											'ped_parroquia_cliente'=>'',
											'ped_ciu_cliente'=>'Quito',
											'ped_pais_cliente'=>'Ecuador',
											'det_cantidad'=>'0',
											'det_cod_producto'=>'',
					                        'det_descripcion'=>'',
					                        'det_vunit'=>'0',
					                        'det_descuento_porcentaje'=>'0',
					                        'det_descuento_moneda'=>'0',
					                        'det_total'=>'0',
					                        'det_impuesto'=>'0',
					                        'pro_id'=>'0', 
					                        'det_val_ice'=>'0', 
					                        'det_cod_ice'=>'0',
					                        'det_p_ice'=>'0',
					                        'det_unidad'=>'0',
					                        'ped_sbt12'=>'0',
					                        'ped_sbt0'=>'0',
					                        'ped_sbt_noiva'=>'0',
					                        'ped_sbt_excento'=>'0',
					                        'ped_id'=>'',
					                        'ped_sbt'=>'0',
					                        'ped_tdescuento'=>'0',
					                        'ped_ice'=>'0',
					                        'ped_iva12'=>'0',
					                        'ped_propina'=>'0',
					                        'ped_total'=>'0',
					                        'ped_observacion'=>'',
					                        'tipo_cliente'=>'0',

										),
						
			
						'action'=>base_url().'pedido/guardar/'.$opc_id,
						'titulo'=>'Pedidos',
						'mod'=>'0',
						);
			$this->load->view('pedido/form',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		
		$ped_femision = $this->input->post('ped_femision');
		$ped_local= $this->input->post('ped_local');
		$ped_vendedor= $this->input->post('ped_vendedor');
		$ped_ruc_cc_cliente = $this->input->post('ped_ruc_cc_cliente');
		$ped_nom_cliente = $this->input->post('ped_nom_cliente');
		$cli_id = $this->input->post('cli_id');
		$pasaporte = $this->input->post('pas_aux'); 
		$ped_dir_cliente = $this->input->post('ped_dir_cliente');
		$ped_tel_cliente = $this->input->post('ped_tel_cliente');
		$ped_email_cliente = $this->input->post('ped_email_cliente');
		$ped_parroquia_cliente = $this->input->post('ped_parroquia_cliente');
		$ped_ciu_cliente = $this->input->post('ped_ciu_cliente');
		$ped_pais_cliente = $this->input->post('ped_pais_cliente');
		$ped_observacion = $this->input->post('ped_observacion');
		$ped_sbt12 = $this->input->post('ped_sbt12');
		$ped_sbt0 = $this->input->post('ped_sbt0');
		$ped_sbt_excento = $this->input->post('ped_sbt_excento');
		$ped_sbt_noiva = $this->input->post('ped_sbt_noiva');
		$ped_sbt = $this->input->post('ped_sbt');
		$ped_tdescuento = $this->input->post('ped_tdescuento');
		$ped_ice = $this->input->post('ped_ice');
		$ped_iva12 = $this->input->post('ped_iva12');
		$ped_propina = $this->input->post('ped_propina');
		$ped_total = $this->input->post('ped_total');
		$ped_id = $this->input->post('ped_id');
		$tipo_cliente = $this->input->post('tipo_cliente');
		$count_det=$this->input->post('count_detalle');
		$count_pag=$this->input->post('count_pagos');
		
		$this->form_validation->set_rules('ped_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('ped_local','Local','required');
		$this->form_validation->set_rules('ped_vendedor','Vendedor','required');
		$this->form_validation->set_rules('ped_ruc_cc_cliente','Identificacion','required');
		$this->form_validation->set_rules('ped_nom_cliente','Nombre','required');
		$this->form_validation->set_rules('ped_dir_cliente','Direccion','required');
		$this->form_validation->set_rules('ped_tel_cliente','Telefono','required');
		$this->form_validation->set_rules('ped_email_cliente','Email','required');
		//$this->form_validation->set_rules('ped_parroquia_cliente','Parroquia','required');
		$this->form_validation->set_rules('ped_ciu_cliente','Ciudad','required');
		$this->form_validation->set_rules('ped_pais_cliente','Pais','required');
		$this->form_validation->set_rules('ped_total','Total Valor','required');
		if($this->form_validation->run()){
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
							  'cli_apellidos'=>$ped_nom_cliente,
							  'cli_raz_social'=>$ped_nom_cliente,
							  'cli_nom_comercial'=>$ped_nom_cliente,
							  'cli_fecha'=>$ped_femision,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'0',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$ped_ruc_cc_cliente,
							  'cli_calle_prin'=>$ped_dir_cliente,
							  'cli_telefono'=>$ped_tel_cliente,
							  'cli_email'=>$ped_email_cliente,
							  'cli_canton'=>$ped_ciu_cliente,
							  'cli_pais'=>$ped_pais_cliente,
							  'cli_codigo'=>$retorno,
							  'cli_parroquia'=>$ped_parroquia_cliente,
							  'cli_tipo_cliente'=>$pasaporte

								);
				$cli_id=$this->pedido_model->insert_cliente($dat_cl);
			}else{
				$dat_cl=array(
								'cli_calle_prin'=>$ped_dir_cliente,
					            'cli_email'=>$ped_email_cliente,
					            'cli_telefono'=>$ped_tel_cliente,
					            'cli_canton'=>$ped_ciu_cliente,
					            'cli_pais'=>$ped_pais_cliente,
					            'cli_parroquia'=>$ped_parroquia_cliente,
					        ); 
				$this->cliente_model->update($cli_id,$dat_cl);	 
			}

			///secuencial pedido
			$txt = '000000000';
		    $rst = $this->pedido_model->lista_ultimo_registro_sec();
		    if(!empty($rst)){
		    	$num_doc = $rst->ped_num_registro;
			}else{
				$num_doc = 0;
			}
		    $num_doc = intval($num_doc + 1);
		    $num_doc = substr($txt, 0, (10 - strlen($num_doc))) . $num_doc;
		    $rst_emp=$this->emisor_model->lista_un_emisor($ped_local);

		    $data=array(
		    			'ped_femision'=>$ped_femision, 
						'ped_num_registro'=>$num_doc, 
						'ped_local'=>$ped_local, 
						'ped_vendedor'=>$ped_vendedor, 
						'ped_ruc_cc_cliente'=>$ped_ruc_cc_cliente, 
						'ped_nom_cliente'=>$ped_nom_cliente, 
						'ped_dir_cliente'=>$ped_dir_cliente, 
						'ped_tel_cliente'=>$ped_tel_cliente, 
						'ped_email_cliente'=>$ped_email_cliente, 
						'ped_parroquia_cliente'=>$ped_parroquia_cliente, 
						'ped_ciu_cliente'=>$ped_ciu_cliente, 
						'ped_pais_cliente'=>$ped_pais_cliente,
						'ped_sbt12'=>$ped_sbt12, 
						'ped_sbt0'=>$ped_sbt0, 
						'ped_sbt_noiva'=>$ped_sbt_noiva, 
						'ped_sbt_excento'=>$ped_sbt_excento, 
						'ped_sbt'=>$ped_sbt,
						'ped_tdescuento'=>$ped_tdescuento, 
						'ped_ice'=>$ped_ice, 
						'ped_iva12'=>$ped_iva12, 
						'ped_propina'=>$ped_propina,
						'ped_total'=>$ped_total, 
						//'ped_desc_asolicitar'=>$, 
						'ped_observacion'=>$ped_observacion, 
						'cli_id'=>$cli_id, 
						'tipo_cliente'=>$tipo_cliente,
						'ped_fecha_hora'=>date('Y-m-d H:i'), 
						'ped_estado'=>7, 
						'emp_id'=>$rst_emp->emp_id

		    );

		    $ped_id=$this->pedido_model->insert($data);

		    if(!empty($ped_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("pro_aux$n")!=''){
		    			$pro_id = $this->input->post("pro_aux$n");
		    			$det_cod_producto = $this->input->post("det_cod_producto$n");
		    			$det_cod_auxiliar = $this->input->post("det_cod_producto$n");
		    			$det_cantidad = $this->input->post("det_cantidad$n");
		    			$det_descripcion = $this->input->post("det_descripcion$n");
		    			$det_vunit = $this->input->post("det_vunit$n");
		    			$det_descuento_porcentaje = $this->input->post("det_descuento_porcentaje$n");
		    			$det_descuento_moneda = $this->input->post("det_descuento_moneda$n");
		    			$det_total = $this->input->post("det_total$n");
		    			$det_impuesto = $this->input->post("det_impuesto$n");
		    			$det_val_ice = $this->input->post("det_val_ice$n");
		    			$det_p_ice = $this->input->post("det_p_ice$n");
		    			$det_cod_ice = $this->input->post("det_cod_ice$n");
		    			$det_unidad = $this->input->post("det_unidad$n");
		    			$dt_det=array(
		    							'ped_id'=>$ped_id,
	                                    'pro_id'=>$pro_id,
	                                    'det_cod_producto'=>$det_cod_producto,
	                                    'det_cod_auxiliar'=>$det_cod_producto,
	                                    'det_cantidad'=>$det_cantidad,
	                                    'det_descripcion'=>$det_descripcion,
	                                    'det_vunit'=>$det_vunit,
	                                    'det_descuento_porcentaje'=>$det_descuento_porcentaje,
	                                    'det_descuento_moneda'=>$det_descuento_moneda,
	                                    'det_total'=>$det_total,
	                                    'det_impuesto'=>$det_impuesto,
	                                    'det_val_ice'=>$det_val_ice, 
	                                    'det_p_ice'=>$det_p_ice, 
	                                    'det_cod_ice'=>$det_cod_ice,
	                                    'det_unidad'=>$det_unidad,
	                                    'det_estado'=>'7'
		    						);
		    			$this->pedido_model->insert_detalle($dt_det);
		    		}
		    	}

		    	///pagos
		    	$m=0;
		    	while($m<$count_pag){
		    		$m++;
		    		if($this->input->post("pag_descripcion$m")!=''){
                        $pag_tipo = $this->input->post("pag_tipo$m");
		    			$pag_forma = $this->input->post("pag_forma$m");
		    			$pag_cant = $this->input->post("pag_cantidad$m");
		    			$pag_plazo = $this->input->post("pag_plazo$m");
		    			$chq_numero = $this->input->post("pag_documento$m");
		    			$pag_id_chq = $this->input->post("id_nota_credito$m");
		    			if ($pag_tipo == '9') {
		    				$rst_plz=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($pag_plazo);
                            $nf = strtotime("+$rst_plz->btr_dias day", strtotime($ped_femision));
                            $pag_dias = $rst_plz->btr_dias;
                            $fec = date('Y-m-d', $nf);
                        } else {
                            $fec = $ped_femision;
                        }

                        if(empty($pag_plazo)){
                        	$pag_plazo='0';
                        }

		    			$dt_det=array(
		    							'ped_id'=>$ped_id,
                                        'pag_fecha_v'=>$fec,
                                        'pag_forma'=>$pag_forma,
                                        'pag_cant'=>$pag_cant,
                                        'pag_dias'=>$pag_dias,
                                        'pag_contado'=>$pag_plazo,
                                         'chq_numero'=>$chq_numero,
                                         'pag_id_chq'=>$pag_id_chq,
                                        'pag_estado'=>'1',
		    						);
		    				$this->pedido_model->insert_pagos($dt_det);

		    			
		    		}
		    	}	

		    	

		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PEDIDO DE VENTA',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$num_doc,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'pedido/show_frame/'. $ped_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'pedido/nuevo/'.$opc_id);
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
			$rst=$this->pedido_model->lista_un_pedido($id);
			$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			if($permisos->rop_actualizar){

			///pagos pedido
			$cns_pg=$this->pedido_model->lista_pagos_pedidos($id);
			$cns_pag=array();

			
			foreach ($cns_pg as $rst_pg) {
				$lista="<option value='0'>SELECCIONE</option>";
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
			// var_dump($cns_pag);


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
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'pedido'=> $this->pedido_model->lista_un_pedido($id),
						'action'=>base_url().'pedido/actualizar/'.$opc_id,
						'titulo'=>'Pedidos',
						'mod'=>'0',
						'aut'=>0
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

	public function actualizar($opc_id){
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

		$id = $this->input->post('ped_id');
		$ped_femision = $this->input->post('ped_femision');
		$ped_local= $this->input->post('ped_local');
		$ped_vendedor= $this->input->post('ped_vendedor');
		$ped_ruc_cc_cliente = $this->input->post('ped_ruc_cc_cliente');
		$ped_nom_cliente = $this->input->post('ped_nom_cliente');
		$cli_id = $this->input->post('cli_id');
		$ped_dir_cliente = $this->input->post('ped_dir_cliente');
		$ped_tel_cliente = $this->input->post('ped_tel_cliente');
		$ped_email_cliente = $this->input->post('ped_email_cliente');
		$ped_parroquia_cliente = $this->input->post('ped_parroquia_cliente');
		$ped_ciu_cliente = $this->input->post('ped_ciu_cliente');
		$ped_pais_cliente = $this->input->post('ped_pais_cliente');
		$ped_observacion = $this->input->post('ped_observacion');
		$ped_sbt12 = $this->input->post('ped_sbt12');
		$ped_sbt0 = $this->input->post('ped_sbt0');
		$ped_sbt_excento = $this->input->post('ped_sbt_excento');
		$ped_sbt_noiva = $this->input->post('ped_sbt_noiva');
		$ped_sbt = $this->input->post('ped_sbt');
		$ped_tdescuento = $this->input->post('ped_tdescuento');
		$ped_ice = $this->input->post('ped_ice');
		$ped_iva12 = $this->input->post('ped_iva12');
		$ped_propina = $this->input->post('ped_propina');
		$ped_total = $this->input->post('ped_total');
		$ped_id = $this->input->post('ped_id');
		$tipo_cliente = $this->input->post('tipo_cliente');
		$count_det=$this->input->post('count_detalle');
		$count_pag=$this->input->post('count_pagos');
		$rst=$this->pedido_model->lista_un_pedido($id);

		$this->form_validation->set_rules('ped_femision','Fecha de Emision','required');
		$this->form_validation->set_rules('ped_local','Local','required');
		$this->form_validation->set_rules('ped_vendedor','Vendedor','required');
		$this->form_validation->set_rules('ped_ruc_cc_cliente','Identificacion','required');
		$this->form_validation->set_rules('ped_nom_cliente','Nombre','required');
		$this->form_validation->set_rules('ped_dir_cliente','Direccion','required');
		$this->form_validation->set_rules('ped_tel_cliente','Telefono','required');
		$this->form_validation->set_rules('ped_email_cliente','Email','required');
		//$this->form_validation->set_rules('ped_parroquia_cliente','Parroquia','required');
		$this->form_validation->set_rules('ped_ciu_cliente','Ciudad','required');
		$this->form_validation->set_rules('ped_pais_cliente','Pais','required');
		$this->form_validation->set_rules('ped_total','Total Valor','required');
		if($this->form_validation->run()){
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
							  'cli_apellidos'=>$ped_nom_cliente,
							  'cli_raz_social'=>$ped_nom_cliente,
							  'cli_fecha'=>$ped_femision,
							  'cli_estado'=>'1',
							  'cli_tipo'=>'0',
							  'cli_categoria'=>'1',
							  'cli_ced_ruc'=>$ped_ruc_cc_cliente,
							  'cli_calle_prin'=>$ped_dir_cliente,
							  'cli_telefono'=>$ped_tel_cliente,
							  'cli_email'=>$ped_email_cliente,
							  'cli_canton'=>$ped_ciu_cliente,
							  'cli_pais'=>$ped_pais_cliente,
							  'cli_codigo'=>$retorno,
							  'cli_parroquia'=>$ped_parroquia_cliente
								);
				$cli_id=$this->pedido_model->insert_cliente($dat_cl);
			}else{
				$dat_cl=array(
								'cli_calle_prin'=>$ped_dir_cliente,
					            'cli_email'=>$ped_email_cliente,
					            'cli_telefono'=>$ped_tel_cliente,
					            'cli_canton'=>$ped_ciu_cliente,
					            'cli_pais'=>$ped_pais_cliente,
					            'cli_parroquia'=>$ped_parroquia_cliente,
					        ); 
				$this->cliente_model->update($cli_id,$dat_cl);	 
			}

			$rst_emp=$this->emisor_model->lista_un_emisor($ped_local);
			$data=array(
		    			'ped_femision'=>$ped_femision, 
						'ped_local'=>$ped_local, 
						'ped_vendedor'=>$ped_vendedor, 
						'ped_ruc_cc_cliente'=>$ped_ruc_cc_cliente, 
						'ped_nom_cliente'=>$ped_nom_cliente, 
						'ped_dir_cliente'=>$ped_dir_cliente, 
						'ped_tel_cliente'=>$ped_tel_cliente, 
						'ped_email_cliente'=>$ped_email_cliente, 
						'ped_parroquia_cliente'=>$ped_parroquia_cliente, 
						'ped_ciu_cliente'=>$ped_ciu_cliente, 
						'ped_pais_cliente'=>$ped_pais_cliente,
						'ped_sbt12'=>$ped_sbt12, 
						'ped_sbt0'=>$ped_sbt0, 
						'ped_sbt_noiva'=>$ped_sbt_noiva, 
						'ped_sbt_excento'=>$ped_sbt_excento, 
						'ped_sbt'=>$ped_sbt,
						'ped_tdescuento'=>$ped_tdescuento, 
						'ped_ice'=>$ped_ice, 
						'ped_iva12'=>$ped_iva12, 
						'ped_propina'=>$ped_propina,
						'ped_total'=>$ped_total, 
						//'ped_desc_asolicitar'=>$, 
						'ped_observacion'=>$ped_observacion, 
						'cli_id'=>$cli_id, 
						'tipo_cliente'=>$tipo_cliente,
						'ped_fecha_hora'=>date('Y-m-d H:i'), 
						'ped_estado'=>7, 
						'emp_id'=>$rst_emp->emp_id

		    );


		    if($this->pedido_model->update($id,$data)){
		    	if($this->pedido_model->delete_detalle($id)){
			    	$n=0;
			    	while($n<$count_det){
			    		$n++;
			    		if($this->input->post("pro_aux$n")!=''){
			    			$pro_id = $this->input->post("pro_aux$n");
			    			$det_cod_producto = $this->input->post("det_cod_producto$n");
			    			$det_cod_auxiliar = $this->input->post("det_cod_auxiliar$n");
			    			$det_cantidad = $this->input->post("det_cantidad$n");
			    			$det_descripcion = $this->input->post("det_descripcion$n");
			    			$det_vunit = $this->input->post("det_vunit$n");
			    			$det_descuento_porcentaje = $this->input->post("det_descuento_porcentaje$n");
			    			$det_descuento_moneda = $this->input->post("det_descuento_moneda$n");
			    			$det_total = $this->input->post("det_total$n");
			    			$det_impuesto = $this->input->post("det_impuesto$n");
			    			$det_val_ice = $this->input->post("det_val_ice$n");
			    			$det_p_ice = $this->input->post("det_p_ice$n");
			    			$det_cod_ice = $this->input->post("det_cod_ice$n");
			    			$det_unidad = $this->input->post("det_unidad$n");
			    			$dt_det=array(
			    							'ped_id'=>$id,
		                                    'pro_id'=>$pro_id,
		                                    'det_cod_producto'=>$det_cod_producto,
		                                    'det_cod_auxiliar'=>$det_cod_producto,
		                                    'det_cantidad'=>$det_cantidad,
		                                    'det_descripcion'=>$det_descripcion,
		                                    'det_vunit'=>$det_vunit,
		                                    'det_descuento_porcentaje'=>$det_descuento_porcentaje,
		                                    'det_descuento_moneda'=>$det_descuento_moneda,
		                                    'det_total'=>$det_total,
		                                    'det_impuesto'=>$det_impuesto,
		                                    'det_val_ice'=>$det_val_ice, 
		                                    'det_p_ice'=>$det_p_ice, 
		                                    'det_cod_ice'=>$det_cod_ice,
		                                    'det_unidad'=>$det_unidad,
		                                    'det_estado'=>'7'
			    						);
			    			$this->pedido_model->insert_detalle($dt_det);
			    		}
			    	}
			    }	

		    	///pagos
		    	if($this->pedido_model->delete_pagos($id)){
			    	$m=0;
		    	while($m<$count_pag){
		    		$m++;
		    		if($this->input->post("pag_descripcion$m")!=''){
                        $pag_tipo = $this->input->post("pag_tipo$m");
		    			$pag_forma = $this->input->post("pag_forma$m");
		    			$pag_cant = $this->input->post("pag_cantidad$m");
		    			$pag_plazo = $this->input->post("pag_plazo$m");
		    			$chq_numero = $this->input->post("pag_documento$m");
		    			$pag_id_chq = 0;
		    			if ($pag_tipo == '9') {
		    				$rst_plz=$this->bancos_tarjetas_model->lista_un_banco_tarjeta($pag_plazo);
                            $nf = strtotime("+$rst_plz->btr_dias day", strtotime($ped_femision));
                            $pag_dias = $rst_plz->btr_dias;
                            $fec = date('Y-m-d', $nf);
                        } else {
                            $fec = $ped_femision;
                            $pag_dias = 0;
                        }

                        if(empty($pag_plazo)){
                        	$pag_plazo='0';
                        }

		    			$dt_det=array(
		    							'ped_id'=>$ped_id,
                                        'pag_fecha_v'=>$fec,
                                        'pag_forma'=>$pag_forma,
                                        'pag_cant'=>$pag_cant,
                                        'pag_dias'=>$pag_dias,
                                        'pag_contado'=>$pag_plazo,
                                         'chq_numero'=>$chq_numero,
                                         'pag_id_chq'=>$pag_id_chq,
                                        'pag_estado'=>'1',
		    						);
		    				$this->pedido_model->insert_pagos($dt_det);

		    			
		    		}
		    	}
			    }	

			    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PEDIDO DE VENTA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst->ped_num_registro,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'pedido/show_frame/'. $id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'pedido/editar/'.$id.'/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
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
						'lista'=>$lista,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function buscar_cliente($txt){

		$lista="<tr>   
						<th></th>
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

	public function cambiar_estado($opc_id){
			$id=$this->input->post('ped_id');
			$est=$this->input->post('ped_estado');
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
								'adt_modulo'=>'PEDIDO DE VENTA',
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
				redirect(base_url().'pedido/'.$opc_id);
			}
		
	}

	public function visualizar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($permisos->rop_actualizar){
			$data=array(
						'action_est'=>base_url().'pedido/cambiar_estado/'.$rst_opc->opc_id,
						'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
						'pedido'=>$this->pedido_model->lista_un_pedido($id)
						);
			
			$this->load->view('pedido/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			// $id=$this->input->post('ped_id');
			$est='8';
			$data=array(
			'ped_estado'=>$est, 
			);
			if($this->pedido_model->update($id,$data)){

			// if($this->pedido_model->delete($id)){
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

    	$titulo='Pedidos';
    	$file="pedidos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    
    
}
