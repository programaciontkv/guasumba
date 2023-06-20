<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guia_remision extends CI_Controller {

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
		$this->load->model('transportista_model');
		$this->load->model('guia_remision_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");
		$this->load->model('estado_model');
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
			$cns_guias=$this->guia_remision_model->lista_guia_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_guias=$this->guia_remision_model->lista_guia_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'guias'=>$cns_guias,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('guia_remision/lista',$data);
		$modulo=array('modulo'=>'guia_remision');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			$usu_id=$this->session->userdata('s_idusuario');
			$rst_vnd=$this->vendedor_model->lista_un_vendedor($usu_id);
			
			if(empty($rst_vnd)){
				$vnd='';
			}else{
				$vnd=$rst_vnd->vnd_id;
			}
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
				        
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_transportistas'=>$this->transportista_model->lista_transportistas_estado('1'),
						'cns_productos'=>$this->guia_remision_model->lista_productos('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'op_id'=>$opc_id,
						'transportista'=> (object) array(
											'tra_razon_social'=>'',
											'tra_email'=>'',
											'tra_placa'=>'',
											'tra_estado'=>'1',
											'tra_identificacion'=>'',
											'tra_direccion'=>'',
											'tra_telefono'=>'1',
											'tra_id'=>''
										),

						'guia'=> (object) array(
											'gui_fecha_emision'=>date('Y-m-d'),
											'gui_numero'=>'',
											'gui_num_comprobante'=>'',
											'gui_denominacion_comp'=>'1',
											'gui_fecha_comp'=>date('Y-m-d'),
											'fac_id'=>'0',
					                        'cli_id'=>'',
					                        'vnd_id'=>$vnd,
					                        'gui_identificacion'=>'',
					                        'gui_nombre'=>'',
					                        'cli_telefono'=>'',
					                        'cli_email'=>'',
					                        'gui_motivo_traslado'=>'VENTA',
					                        'gui_fecha_inicio'=>date('Y-m-d'),
					                        'gui_fecha_fin'=>date('Y-m-d'),
					                        'gui_punto_partida'=>$rst_cja->emp_direccion,
					                        'gui_destino'=>'',
					                        'gui_doc_aduanero'=>'1',
					                        'gui_cod_establecimiento'=>'001',
					                        'gui_aut_comp'=>'',
					                        'gui_observacion'=>'',
					                        'tra_id'=>'0',
					                        'tra_razon_social'=>'',
					                        'tra_placa'=>'',
					                        'gui_identificacion_transp'=>'',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'gui_id'=>'',
										),
						'cns_det'=>'',
						'action'=>base_url().'guia_remision/guardar/'.$opc_id,
						//'action2'=>base_url().'guia_remision/guardar_trans/'.$opc_id,
						);
			$this->load->view('guia_remision/form',$data);
			$modulo=array('modulo'=>'guia_remision');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
			
		}
	}

	public function guardar_trans($datos){
		var_dump($datos);

		$data=array(
						 					'tra_identificacion'=>$datos[0],
											'tra_placa'=>$datos[1],
											'tra_razon_social'=>$datos[2],
											'tra_estado'=>'1'
			);	

			if($this->transportista_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'TRANSPORTISTAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$datos[0],
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				

			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				$this->nuevo($opc_id);
			}


	}

	// public function n_trans($opc_id){
	// $data=array(	
	// 				'action2'=>base_url().'guia_remision/guardar_trans/'.$opc_id,
	// 				'transportista'=> (object) array(
	// 										'tra_razon_social'=>'',
	// 										'tra_email'=>'',
	// 										'tra_placa'=>'',
	// 										'tra_estado'=>'1',
	// 										'tra_identificacion'=>'',
	// 										'tra_direccion'=>'',
	// 										'tra_telefono'=>'1',
	// 										'tra_id'=>''
	// 									),
	// 				);
	// 	$this->load->view('guia_remision/modal',$data);


	// }

	public function guardar($opc_id){
		
		$gui_fecha_emision = $this->input->post('gui_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$fac_id= $this->input->post('fac_id');
		$tra_id= $this->input->post('tra_id');
		$tra_placa= $this->input->post('tra_placa');
		$tra_razon_social= $this->input->post('tra_razon_social');
		$gui_denominacion_comp= $this->input->post('gui_denominacion_comp');
		$gui_num_comprobante= $this->input->post('gui_num_comprobante');
		$gui_fecha_comp= $this->input->post('gui_fecha_comp');
		$gui_aut_comp = $this->input->post('gui_aut_comp');
		$gui_fecha_inicio= $this->input->post('gui_fecha_inicio');
		$gui_fecha_fin= $this->input->post('gui_fecha_fin');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$gui_motivo_traslado = $this->input->post('gui_motivo_traslado');
		$gui_punto_partida = $this->input->post('gui_punto_partida');
		$gui_destino = $this->input->post('gui_destino');
		$gui_identificacion_transp = $this->input->post('gui_identificacion_transp');
		$gui_doc_aduanero = $this->input->post('gui_doc_aduanero');
		$gui_cod_establecimiento = $this->input->post('gui_cod_establecimiento');
		$gui_observacion = $this->input->post('gui_observacion');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('gui_fecha_emision','Fecha de Emision','required');
		if($gui_denominacion_comp==1){
			$this->form_validation->set_rules('gui_num_comprobante','Factura','required');
			$this->form_validation->set_rules('gui_aut_comp','Autorizacion Factura','required');
		}
		$this->form_validation->set_rules('gui_fecha_comp','Fecha Factura','required');
		$this->form_validation->set_rules('gui_fecha_inicio','Fecha Inicio Traslado','required');
		$this->form_validation->set_rules('gui_fecha_fin','Fecha Fin Traslado','required');
		$this->form_validation->set_rules('gui_motivo_traslado','Motivo Traslado','required');
		$this->form_validation->set_rules('gui_punto_partida','Punto de Partida','required');
		$this->form_validation->set_rules('gui_destino','Destino','required');
		$this->form_validation->set_rules('gui_cod_establecimiento','Cod. Establecimiento Destino','required');
		$this->form_validation->set_rules('gui_identificacion_transp','CI/RUC Transportista','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');

		if($this->form_validation->run()){

			if($tra_id==0){
			$datos=array(
							$gui_identificacion_transp,
							$tra_placa,
							$tra_razon_social
						);
			$this->guardar_trans($datos);
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

			
			$rst_sec = $this->guia_remision_model->lista_secuencial_documento($emi_id,$cja_id);
		    if (empty($rst_sec)) {
		        $sec = $rst_cja->cja_sec_guia;
		    } else {
		    	$sc=explode('-',$rst_sec->gui_numero);
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
		    $gui_numero = $ems . '-'.$caja.'-' . $tx . $sec;

		    $clave_acceso=$this->clave_acceso($cja_id,$gui_numero,$gui_fecha_emision);

		    if($tra_id==0){
		    	$rst_trans=$this->transportista_model->lista_un_transportista_identificacion($gui_identificacion_transp);
		    	$tra_id=$rst_trans->tra_id;
			}

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
		    				'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'fac_id'=>$fac_id,
							'tra_id'=>$tra_id,
							'gui_denominacion_comp'=>$gui_denominacion_comp,
							'gui_doc_aduanero'=>$gui_doc_aduanero,
							'gui_fecha_emision'=>$gui_fecha_emision,
							'gui_numero'=>$gui_numero, 
							'gui_nombre'=>$nombre, 
							'gui_identificacion'=>$identificacion, 
							'gui_fecha_inicio'=>$gui_fecha_inicio, 
							'gui_fecha_fin'=>$gui_fecha_fin, 
							'gui_motivo_traslado'=>$gui_motivo_traslado, 
							'gui_punto_partida'=>$gui_punto_partida, 
							'gui_destino'=>$gui_destino, 
							'gui_num_comprobante'=>$gui_num_comprobante, 
							'gui_fecha_comp'=>$gui_fecha_comp, 
							'gui_aut_comp'=>$gui_aut_comp, 
							'gui_cod_establecimiento'=>$gui_cod_establecimiento, 
							'gui_identificacion_transp'=>$gui_identificacion_transp,
							'gui_observacion'=>$gui_observacion, 
							'gui_clave_acceso'=>$clave_acceso,
							'gui_estado'=>'4'
		    );


			// if($this->factura_model->insert($data)){
		    $gui_id=$this->guia_remision_model->insert($data);
		    if(!empty($gui_id)){
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
		    			$dt_det=array(
		    							'gui_id'=>$gui_id,
	                                    'pro_id'=>$pro_id,
	                                    'dtg_codigo'=>$dfc_codigo,
	                                    'dtg_cod_aux'=>$dfc_cod_aux,
	                                    'dtg_cantidad'=>$dfc_cantidad,
	                                    'dtg_descripcion'=>$dfc_descripcion,
		    						);
		    			$this->guia_remision_model->insert_detalle($dt_det);
		    		}
		    	}
		    	$this->generar_xml($gui_id);
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'GUIA DE REMISION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'guia_remision/show_frame/'. $gui_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'guia_remision/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$rst=$this->guia_remision_model->lista_una_guia($id);
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){

			///recupera detalle
			$cns_dt=$this->guia_remision_model->lista_detalle_guia($id);
			$cns_det=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			foreach ($cns_dt as $rst_dt) {
	        $rst_dg=$this->guia_remision_model->lista_suma_detalle_edit($rst->fac_id,$rst_dt->pro_id,$rst->gui_id); 
		    if(empty($rst_dg)){
		       	$entregado=0;
		    }else{
		        $entregado=$rst_dg->dtg_cantidad;
		    }

		    $rst_df= $this->guia_remision_model->lista_un_detalle_factura($rst->fac_id,$rst_dt->pro_id);    
	        if(empty($rst_df)){
	        	$cantidadf=0;
	        	$saldo=0;
	        }else{
				$cantidadf=$rst_df->dfc_cantidad;
				$saldo=$cantidadf-$entregado;
	        }

			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_unidad'=>$rst_dt->mp_q,
						'cantidadf'=>$cantidadf,
						'entregado'=>$entregado,
						'saldo'=>$saldo,
						'cantidad'=>$rst_dt->dtg_cantidad,
						);	
				
				array_push($cns_det, $dt_det);
			}
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_productos'=>$this->factura_model->lista_productos('1'),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cns_transportistas'=>$this->transportista_model->lista_transportistas_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'guia'=>$this->guia_remision_model->lista_una_guia($id),
						'cns_det'=>$cns_det,
						'action'=>base_url().'guia_remision/actualizar/'.$opc_id
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('guia_remision/form',$data);
			$modulo=array('modulo'=>'guia_remision');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		$id=$this->input->post('gui_id');
		$gui_fecha_emision = $this->input->post('gui_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$fac_id= $this->input->post('fac_id');
		$tra_id= $this->input->post('tra_id');
		$tra_placa= $this->input->post('tra_placa');
		$tra_razon_social= $this->input->post('tra_razon_social');
		$gui_num_comprobante= $this->input->post('gui_num_comprobante');
		$gui_denominacion_comp= $this->input->post('gui_denominacion_comp');
		$gui_fecha_comp= $this->input->post('gui_fecha_comp');
		$gui_aut_comp = $this->input->post('gui_aut_comp');
		$gui_fecha_inicio= $this->input->post('gui_fecha_inicio');
		$gui_fecha_fin= $this->input->post('gui_fecha_fin');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$gui_motivo_traslado = $this->input->post('gui_motivo_traslado');
		$gui_punto_partida = $this->input->post('gui_punto_partida');
		$gui_destino = $this->input->post('gui_destino');
		$gui_identificacion_transp = $this->input->post('gui_identificacion_transp');
		$gui_doc_aduanero = $this->input->post('gui_doc_aduanero');
		$gui_cod_establecimiento = $this->input->post('gui_cod_establecimiento');
		$gui_observacion = $this->input->post('gui_observacion');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('gui_fecha_emision','Fecha de Emision','required');
		if($gui_denominacion_comp==1){
			$this->form_validation->set_rules('gui_num_comprobante','Factura','required');
			$this->form_validation->set_rules('gui_aut_comp','Autorizacion Factura','required');
		}
		$this->form_validation->set_rules('gui_fecha_comp','Fecha Factura','required');
		$this->form_validation->set_rules('gui_fecha_inicio','Fecha Inicio Traslado','required');
		$this->form_validation->set_rules('gui_fecha_fin','Fecha Fin Traslado','required');
		$this->form_validation->set_rules('gui_motivo_traslado','Motivo Traslado','required');
		$this->form_validation->set_rules('gui_punto_partida','Punto de Partida','required');
		$this->form_validation->set_rules('gui_destino','Destino','required');
		$this->form_validation->set_rules('gui_cod_establecimiento','Cod. Establecimiento Destino','required');
		$this->form_validation->set_rules('gui_identificacion_transp','CI/RUC Transportista','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		
		if($this->form_validation->run()){
			if($tra_id==0){
				$datos=array(
								$gui_identificacion_transp,
								$tra_placa,
								$tra_razon_social
							);
				$this->guardar_trans($datos);
			}
			$rst_gui=$this->guia_remision_model->lista_una_guia($id);
		    $clave_acceso=$this->clave_acceso($cja_id,$rst_gui->gui_numero,$gui_fecha_emision);
		    if($tra_id==0){
		    	$rst_trans=$this->transportista_model->lista_un_transportista_identificacion($gui_identificacion_transp);
		    	$tra_id=$rst_trans->tra_id;
			}
		    $data=array(	
		    				// 'emp_id'=>$emp_id,
		    				// 'emi_id'=>$emi_id,
		    				// 'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'fac_id'=>$fac_id,
							'tra_id'=>$tra_id,
							'gui_denominacion_comp'=>$gui_denominacion_comp,
							'gui_doc_aduanero'=>$gui_doc_aduanero,
							'gui_fecha_emision'=>$gui_fecha_emision,
							'gui_nombre'=>$nombre, 
							'gui_identificacion'=>$identificacion, 
							'gui_fecha_inicio'=>$gui_fecha_inicio, 
							'gui_fecha_fin'=>$gui_fecha_fin, 
							'gui_motivo_traslado'=>$gui_motivo_traslado, 
							'gui_punto_partida'=>$gui_punto_partida, 
							'gui_destino'=>$gui_destino, 
							'gui_num_comprobante'=>$gui_num_comprobante, 
							'gui_fecha_comp'=>$gui_fecha_comp, 
							'gui_aut_comp'=>$gui_aut_comp, 
							'gui_cod_establecimiento'=>$gui_cod_establecimiento, 
							'gui_identificacion_transp'=>$gui_identificacion_transp,
							'gui_observacion'=>$gui_observacion, 
							'gui_clave_acceso'=>$clave_acceso,
							'gui_estado'=>'4'
		    );


			if($this->guia_remision_model->update($id,$data)){
				if($this->guia_remision_model->delete_detalle($id)){
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
				    		$dt_det=array(
				    							'gui_id'=>$id,
			                                    'pro_id'=>$pro_id,
			                                    'dtg_codigo'=>$dfc_codigo,
			                                    'dtg_cod_aux'=>$dfc_cod_aux,
			                                    'dtg_cantidad'=>$dfc_cantidad,
			                                    'dtg_descripcion'=>$dfc_descripcion,
				    						);
				    		$this->guia_remision_model->insert_detalle($dt_det);
				    	}
			    			
			    	}
			    }
		    	$this->generar_xml($id);
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'GUIA DE REMISION',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'guia_remision/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'guia_remision/editar'.$id.'/'.$opc_id);
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
		    $up_dtf=array('gui_estado'=>3);
			if($this->guia_remision_model->update($id,$up_dtf)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'GUIA DE REMISION',
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

	public function traer_transportista($id){
		$rst=$this->transportista_model->lista_un_transportista($id);
		if(!empty($rst)){
			$data=array(
						'tra_id'=>$rst->tra_id,
						'tra_razon_social'=>$rst->tra_razon_social,
						'tra_identificacion'=>$rst->tra_identificacion,
						'tra_placa'=>$rst->tra_placa,
						);
			echo json_encode($data);
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
						'ids'=>$rst->ids,
						'pro_descripcion'=>$rst->mp_d,
						'pro_codigo'=>$rst->mp_c,
						'pro_unidad'=>$rst->mp_q,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	
	function clave_acceso($cja,$doc_numero,$doc_fecha){
		$cod_doc='06';
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
					'titulo'=>'GUIA DE REMISION ',
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"guia_remision/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'guia_remision');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id){
    		$rst=$this->guia_remision_model->lista_una_guia($id);
    		$imagen=$this->set_barcode($rst->gui_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			///recupera detalle
			$cns_dt=$this->guia_remision_model->lista_detalle_guia($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
	        
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_codigo_aux'=>$rst_dt->mp_c,
						'cantidad'=>$rst_dt->dtg_cantidad,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'guia'=>$this->guia_remision_model->lista_una_guia($id),
						'cns_det'=>$cns_det,
						);
			$this->html2pdf->filename('guia_remision.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_guia_remision', $data, true)));
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

	public function traer_facturas($num,$emi){
		$rst=$this->factura_model->lista_factura_numero($num,$emi);
		echo json_encode($rst);
	}

	public function load_factura($id,$dec,$dcc){
		$rst=$this->factura_model->lista_una_factura_num($id);
		$cns=$this->factura_model->lista_detalle_factura($rst->fac_id);
		$n=0;
		$detalle='';
		foreach ($cns as $rst_det) {
			$n++;
		        
		        $rst_dg=$this->guia_remision_model->lista_suma_detalle($rst->fac_id,$rst_det->pro_id); 
		        if(empty($rst_dg)){
		        	$entregado=0;
		        }else{
		        	$entregado=$rst_dg->dtg_cantidad;
		        }
		        $cantidad=$rst_det->dfc_cantidad-$entregado;
		        $a='"';
		        $detalle.="<tr>
                                            <td id='item$n' name='item$n' lang='$n' align='center'>$n</td>
                                            <td id='pro_descripcion$n' name='pro_descripcion$n' lang='$n'>$rst_det->mp_c</td>
                                            <td id='pro_referencia$n' name='pro_referencia$n' lang='$n'>$rst_det->mp_d
                                                <input type='hidden' size='7' id='pro_aux$n' name='pro_aux$n' value='$rst_det->pro_id' lang='$n'/>
                                            </td>
                                            <td id='unidad$n' name='unidad$n' lang='$n'>$rst_det->mp_q</td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidadf$n' name='cantidadf$n' value='".str_replace(',', '', number_format($rst_det->dfc_cantidad, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='entregado$n' name='entregado$n' value='".str_replace(',', '', number_format($entregado, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='saldo$n' name='saldo$n' value='".str_replace(',', '', number_format($cantidad, $dec))."' lang='$n' readonly/></td>
                                            <td ><input type ='text' size='7'  style='text-align:right' class='form-control decimal' id='cantidad$n' name='cantidad$n' value='".str_replace(',', '', number_format($cantidad, $dec))."' lang='$n' onchange='validar_cantfactura(this)' onkeyup='this.value = this.value.replace(/[^0-9.]/, $a$a)'  /></td>
                                            <td></td>
                                        </tr>";
				
			}

			$data= array(
						'fac_id'=>$rst->fac_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_email'=>$rst->cli_email,
						'fac_fecha_emision'=>$rst->fac_fecha_emision,
						'fac_numero'=>$rst->fac_numero,
						'fac_autorizacion'=>$rst->fac_clave_acceso,
						'detalle'=>$detalle,
						'cnt_detalle'=>$n
						);	

		echo json_encode($data);
	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Guias de Remision '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="guias".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }
 
	

	public function consulta_sri(){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
	    $ambiente=$amb->con_valor;

    	if($ambiente!=0){
	    	$guia=$this->guia_remision_model->lista_guia_sin_autorizar();
	        set_time_limit(0);
	         if ($ambiente == 2) { //Produccion
	            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $guia->gui_clave_acceso]);
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($guia->gui_id); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
	        	if($res['estado']!='AUTORIZADO'){
	        		$this->generar_xml($guia->gui_id); 
	        	}else{
	            	$data = array(
	            					'gui_autorizacion'=>$res['numeroAutorizacion'], 
	            					'gui_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'gui_xml_doc'=>$res['comprobante'], 
	            					'gui_estado'=>'6'
	            				);
	            	$this->guia_remision_model->update($guia->gui_id,$data);
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
    	$guia=$this->guia_remision_model->lista_una_guia($id);
    	$detalle=$this->guia_remision_model->lista_detalle_guia($guia->gui_id);
        $dec = $this->configuracion_model->lista_una_configuracion('2');
        $round=$dec->con_valor;
        $codigo='12345678';  
        $tp_emison='1';  
        $empresa=$this->empresa_model->lista_una_empresa($guia->emp_id);    
        $emisor=$this->emisor_model->lista_un_emisor($guia->emi_id);    
        $ndoc = explode('-', $guia->gui_numero);
        $nfact = str_replace('-', '', $guia->gui_numero);
        $ems = $ndoc[0];
        $emi = intval($ndoc[0]);
        $pt_ems = $ndoc[1];
        $secuencial = $ndoc[2];
        $cod_doc = '06'; //01= factura, 02=nota de credito tabla 4
        $fecha = date_format(date_create($guia->gui_fecha_emision), 'd/m/Y');
        $f2 = date_format(date_create($guia->gui_fecha_emision), 'dmY');
        $dir_cliente = $guia->cli_calle_prin;
        $telf_cliente = $guia->cli_telefono;
        $email_cliente = $guia->cli_email;
        $contabilidad = $empresa->emp_obligado_llevar_contabilidad;
        $razon_soc_comprador = $guia->gui_nombre;
        $id_comprador = $guia->gui_identificacion;;
        if (strlen($id_comprador) == 13 && $id_comprador != '9999999999999' && substr($id_comprador, -3) == '001') {
            $tipo_id_comprador = "04"; //RUC 04 
        } else if (strlen($id_comprador) == 10) {
            $tipo_id_comprador = "05"; //CEDULA 05 
        } else if ($id_comprador == '9999999999999') {
            $tipo_id_comprador = "07"; //VENTA A CONSUMIDOR FINAL
        } else {
            $tipo_id_comprador = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
        }
        
        $id_trans = $guia->gui_identificacion_transp;
	    if (strlen($id_trans) == 13 && $id_trans != '9999999999999') {
	        $tipo_id_trans = "04"; //RUC 04 
	    } else if (strlen($id_trans) == 10) {
	        $tipo_id_trans = "05"; //CEDULA 05 
	    } else if ($id_trans == '9999999999999') {
	        $tipo_id_trans = "07"; //VENTA A CONSUMIDOR FINAL
	    } else {
	        $tipo_id_trans = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
	    }
        $clave = $guia->gui_clave_acceso;

        $xml.="<?xml version='1.0' encoding='UTF-8'?>" . chr(13);
	    $xml.="<guiaRemision version='1.1.0' id='comprobante'>" . chr(13);
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
	    $xml.="<infoGuiaRemision>" . chr(13);
	    $xml.="<dirEstablecimiento>" . $emisor->emi_dir_establecimiento_emisor . "</dirEstablecimiento>" . chr(13);
	    $xml.="<dirPartida>" . $guia->gui_punto_partida . "</dirPartida>" . chr(13);
	    $xml.="<razonSocialTransportista>" . $guia->tra_razon_social . "</razonSocialTransportista>" . chr(13);
	    $xml.="<tipoIdentificacionTransportista>" . $tipo_id_trans . "</tipoIdentificacionTransportista>" . chr(13);
	    $xml.="<rucTransportista>" . $guia->tra_identificacion . "</rucTransportista>" . chr(13);
	    $xml.="<obligadoContabilidad>" . $contabilidad . "</obligadoContabilidad>" . chr(13);
	    if(!empty($empresa->emp_contribuyente_especial)){
        	$xml.="<contribuyenteEspecial>$empresa->emp_contribuyente_especial</contribuyenteEspecial>" . chr(13);
    	}
	    $f_ini = date_format(date_create($guia->gui_fecha_inicio), 'd/m/Y');
	    $f_fin = date_format(date_create($guia->gui_fecha_fin), 'd/m/Y');
	    $xml.="<fechaIniTransporte>$f_ini</fechaIniTransporte>" . chr(13);
	    $xml.="<fechaFinTransporte>$f_fin</fechaFinTransporte>" . chr(13);
	    $xml.="<placa>$guia->tra_placa</placa>" . chr(13);
	    $xml.="</infoGuiaRemision>" . chr(13);

	    $xml.="<destinatarios>" . chr(13);
	    $xml.="<destinatario>" . chr(13);
	    $xml.="<identificacionDestinatario>" . $id_comprador . "</identificacionDestinatario>" . chr(13);
	    $xml.="<razonSocialDestinatario>" . $razon_soc_comprador . "</razonSocialDestinatario>" . chr(13);
	    $xml.="<dirDestinatario>" . $guia->gui_destino . "</dirDestinatario>" . chr(13);
	    $xml.="<motivoTraslado>" . $guia->gui_motivo_traslado . "</motivoTraslado>" . chr(13);
	    if ($guia->gui_doc_aduanero != '') {
	        $xml.="<docAduaneroUnico>" . $guia->gui_doc_aduanero . "</docAduaneroUnico>" . chr(13);
	    }
	    $xml.="<codEstabDestino>" . $guia->gui_cod_establecimiento . "</codEstabDestino>" . chr(13);
	    if(!empty($guia->fac_id)){
		    $xml.="<codDocSustento>0" . $guia->gui_denominacion_comp . "</codDocSustento>" . chr(13);
		    $xml.="<numDocSustento>" . $guia->gui_num_comprobante . "</numDocSustento>" . chr(13);
		    if ($guia->gui_aut_comp != '') {
		        $xml.="<numAutDocSustento>" . $guia->gui_aut_comp . "</numAutDocSustento>" . chr(13);
		    }
		    $fec_comp = date_format(date_create($guia->gui_fecha_comp), 'd/m/Y');
		    $xml.="<fechaEmisionDocSustento>" . $fec_comp . "</fechaEmisionDocSustento>" . chr(13);
		}
	    $xml.="<detalles>" . chr(13);
	    foreach ($detalle as $det) {
	        $xml.="<detalle>" . chr(13);
	        $xml.="<codigoInterno>" . $det->mp_c . "</codigoInterno>" . chr(13);
	        if ($det->mp_n != '') {
	            $xml.="<codigoAdicional>" . $det->mp_n . "</codigoAdicional>" . chr(13);
	        }
	        $xml.="<descripcion>" . $det->mp_d . "</descripcion>" . chr(13);
	        $xml.="<cantidad>" . round($det->dtg_cantidad, $round) . "</cantidad>" . chr(13);
	        $xml.="</detalle>" . chr(13);
	    }
	    $xml.="</detalles>" . chr(13);
	    $xml.="</destinatario>" . chr(13);
	    $xml.="</destinatarios>" . chr(13);
	    $xml.="<infoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Direccion'>" . $dir_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Telefono'>" . $telf_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Email'>" . strtolower(utf8_decode($email_cliente)) . "</campoAdicional>" . chr(13);
	    $xml.="</infoAdicional>" . chr(13);
	    $xml.="</guiaRemision>" . chr(13);
    
        $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);

		header("Location: http://localhost:8080/central_xml_local/envio_sri/firmar.php?clave=$clave&programa=$programa&firma=$firma&password=$pass&ambiente=$ambiente");
		}
    } 
	
}
