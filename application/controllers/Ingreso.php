<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingreso extends CI_Controller {

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
		$this->load->model('ingreso_model');
		$this->load->model('cliente_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('configuracion_model');
		$this->load->model('empresa_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->model('reg_factura_model');
		$this->load->model('reg_guia_model');
		$this->load->library('html2pdf');
		$this->load->library('html4pdf');
		$this->load->library('html5pdf');
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
			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}
			$cns_mov=$this->ingreso_model->lista_ingresos_buscador($text,$ids,$f1,$f2,$txt);						
		}else{
			$text= '';
			$ids= '26';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}
			$cns_mov=$this->ingreso_model->lista_ingresos_buscador($text,$ids,$f1,$f2,$txt);								
		}

		$data=array(
					'permisos'=>$this->permisos,
					'ingresos'=>$cns_mov,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'ids'=>$ids,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('ingreso/lista',$data);
		$modulo=array('modulo'=>'ingreso');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function nuevo($trs_id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$rst=$this->ingreso_model->lista_una_transaccion($trs_id);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		if($trs_id==28){
			$cli_nombre="";
			$cli_id="0";
		}else{
			$cli_nombre=$rst_cja->cli_raz_social;
			$cli_id=$rst_cja->emi_cod_cli;
		}
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cns_pro'=>$this->producto_comercial_model->lista_productos_bodega_estado('1'),
						'tipo_documentos'=>$this->ingreso_model->lista_tipo_documentos('1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'mensaje'=> $mensaje,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'ingreso'=> (object) array(
											'mov_documento'=>'',
					                        'mov_fecha_trans'=>date('Y-m-d'),
					                        'mov_guia_transporte'=>'',
					                        'trs_id'=>$trs_id,
					                        'trs_descripcion'=>$rst->trs_descripcion,
					                        'cli_nombre'=>$cli_nombre,
					                        'cli_id'=>$cli_id,
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'reg_tipo_documento'=>"",
										),
						'action'=>base_url().'ingreso/guardar/'.$opc_id
						);
			//$this->load->view('ingreso/form',$data);

			$we =  intval($this->session->userdata('s_we'));
			if($we>=760){
				$this->load->view('ingreso/form',$data);
			}else{
				$this->load->view('ingreso/form_movil',$data);
			}

			$modulo=array('modulo'=>'ingreso');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$emp_id= $this->input->post('emp_id');
		$emi_id= $this->input->post('emi_id');
		$cja_id= $this->input->post('cja_id');
		$mov_fecha_trans = $this->input->post('mov_fecha_trans');
		$mov_guia_transporte = $this->input->post('mov_guia_transporte');
		$trs_id = $this->input->post('trs_id');
		$cli_id = $this->input->post('cli_id');
		$reg_tipo_documento = $this->input->post('reg_tipo_documento');
		$reg_num_documento = $this->input->post('reg_num_documento');
		$count_det = $this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cli_id','Proveedor','required');
		
		if($this->form_validation->run()){
			$rst = $this->ingreso_model->lista_secuencial();
			if(empty($rst)){
				$sec=1;
			}else{
				$sc=explode('-',$rst->mov_documento);
	        	$sec = $sc[1] + 1;
	    	}
	        if ($sec >= 0 && $sec < 10) {
	            $txt = '000000000';
	        } else if ($sec >= 10 && $sec < 100) {
	            $txt = '00000000';
	        } else if ($sec >= 100 && $sec < 1000) {
	            $txt = '0000000';
	        } else if ($sec >= 1000 && $sec < 10000) {
	            $txt = '000000';
	        } else if ($sec >= 10000 && $sec < 100000) {
	            $txt = '00000';
	        } else if ($sec >= 100000 && $sec < 1000000) {
	            $txt = '0000';
	        } else if ($sec >= 1000000 && $sec < 10000000) {
	            $txt = '000';
	        } else if ($sec >= 10000000 && $sec < 100000000) {
	            $txt = '00';
	        } else if ($sec >= 100000000 && $sec < 1000000000) {
	            $txt = '0';
	        } else if ($sec >= 1000000000 && $sec < 10000000000) {
	            $txt = '';
	        }

	        $secuencial = '001-' . $txt . $sec;
			$n=0;
			$sms=0;
		    	while($n < $count_det){
		    		$n++;

		    		if($this->input->post("pro_id$n") !=''){
		    			$pro_id = $this->input->post("pro_id$n");
		    			$mov_cantidad = $this->input->post("mov_cantidad$n");
		    			$mov_val_unit = $this->input->post("mov_cost_unit$n");
		    			$mov_val_tot = $this->input->post("mov_cost_tot$n");
		    	
						$data=array(
								    'pro_id'=>$pro_id,
					                'trs_id'=>$trs_id,
					                'cli_id'=>$cli_id,
					                'emp_id'=>$emp_id,
					                'bod_id'=>$emi_id,
					                'mov_documento'=>$secuencial,
					                'mov_guia_transporte'=>$mov_guia_transporte,
					                'mov_fecha_trans'=>$mov_fecha_trans,
					                'mov_cantidad'=>$mov_cantidad,                
					                'mov_fecha_registro'=>date('Y-m-d'),
					                'mov_hora_registro'=>date('H:i'),
					                'mov_val_unit'=>$mov_val_unit,
					                'mov_val_tot'=>$mov_val_tot,
					                'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
					                'mov_doc_tipo'=>$reg_tipo_documento,
					                'mov_num_fac_entrega'=>$reg_num_documento,

					                
						);	
						

						if($this->ingreso_model->insert($data)==false){
							$sms+=1;
						}
					
					}
				}	
			if($sms==0){
				
				if($trs_id==28){
					if($reg_tipo_documento!=0){
						///registro de factura 
						$rst_cli=$this->cliente_model->lista_un_cliente($cli_id);
						if($rst_cli->cli_tipo_cliente==0){
				    		$tpc='LOCAL';
				    	}else{
				    		$tpc='EXTRANJERO';
				    	}
						$encabezado=array(
				                        'reg_fregistro'=>date('Y-m-d'),
				                        'reg_num_documento'=>$reg_num_documento,
				                        'reg_fautorizacion'=>'1900-01-01',//fec_aut
				                        'reg_fcaducidad'=>'1900-01-01',//fec_aut_hasta
				                        'cli_id'=>$cli_id,
				                        'reg_ruc_cliente'=>$rst_cli->cli_ced_ruc,
				                        'reg_estado'=>'7',//estado
				                        'reg_tipo_documento'=>$reg_tipo_documento,
				                        'reg_sustento'=>'0',
				                        'emp_id'=>$emp_id,
				                        'emi_id'=>$emi_id,
				                        'cja_id'=>$cja_id,
				                        'reg_tpcliente'=>$tpc,
				                        'reg_tipo_pago'=>'01',
				                        'reg_forma_pago'=>'1',
				                        'reg_pais_importe'=>'241',
				                        'reg_relacionado'=>'NO',
				                        'reg_num_ingreso'=>$secuencial,
				                    );
				    
				    	$fac_id=$this->reg_factura_model->insert($encabezado);
				    	$m=0;
				    	while($m < $count_det){
				    		$m++;

				    		if($this->input->post("pro_id$m") !=''){
						        $pro_id = $this->input->post("pro_id$m");
				    			$mov_cantidad = $this->input->post("mov_cantidad$m");
				    			$mov_val_unit = $this->input->post("mov_cost_unit$m");
				    			$mov_val_tot = $this->input->post("mov_cost_tot$m");
				    			$rst_pro=$this->producto_comercial_model->lista_un_producto($pro_id);
						        $detalle=array(     
						                             'det_cantidad'=>$mov_cantidad,
						                             'det_vunit'=>$mov_val_unit,
						                             'det_descuento_porcentaje'=>0,
						                             'det_descuento_moneda'=>0,
						                             'det_total'=>$mov_val_tot,
						                             'det_impuesto'=>$rst_pro->mp_h,
						                             'det_tipo'=>$rst_pro->ids,
						                             'pln_id'=>'0',
						                             'reg_codigo_cta'=>'',
						                             'pro_id'=>$pro_id,
						                             'reg_id'=>$fac_id
						                         );

					            $this->reg_factura_model->insert_detalle($detalle);
					        }

						}
					
			    	}else{
			    		///registro guias
						$rst_cli=$this->cliente_model->lista_un_cliente($cli_id);
						if($rst_cli->cli_tipo_cliente==0){
				    		$tpc='LOCAL';
				    	}else{
				    		$tpc='EXTRANJERO';
				    	}
						$enc_guia=array(
				                        'rgu_fregistro'=>date('Y-m-d'),
				                        'rgu_num_documento'=>$reg_num_documento,
				                        'cli_id'=>$cli_id,
				                        'rgu_estado'=>'7',//estado
				                        'rgu_num_ingreso'=>$secuencial,
				                        'emp_id'=>$emp_id,
				                        'emi_id'=>$emi_id,
				                        'cja_id'=>$cja_id,
				                    );
				    
				    	$rgu_id=$this->reg_guia_model->insert($enc_guia);
				    	$l=0;
				    	while($l < $count_det){
				    		$l++;

				    		if($this->input->post("pro_id$l") !=''){
						        $pro_id = $this->input->post("pro_id$l");
				    			$mov_cantidad = $this->input->post("mov_cantidad$l");
				    			$mov_val_unit = $this->input->post("mov_cost_unit$l");
				    			$mov_val_tot = $this->input->post("mov_cost_tot$l");
				    			$rst_pro=$this->producto_comercial_model->lista_un_producto($pro_id);
						        $det_guia=array(     
						                             'drg_cantidad'=>$mov_cantidad,
						                             'drg_vunit'=>$mov_val_unit,
						                             'drg_total'=>$mov_val_tot,
						                             'drg_tipo'=>$rst_pro->ids,
						                             'pro_id'=>$pro_id,
						                             'rgu_id'=>$rgu_id
						                         );

					            $this->reg_guia_model->insert_detalle($det_guia);
					        }

						}

			    	}
				}
			        
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'INGRESOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$secuencial,
								'usu_login'=>$this->session->userdata('s_usuario'),
							);
						
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'ingreso/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}


	public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente($id);
		if(!empty($rst)){
			$data=array(
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function load_producto($id,$emi){
		$rst=$this->producto_comercial_model->lista_un_producto_cod($id);
		if(empty($rst)){
			$rst=$this->producto_comercial_model->lista_un_producto($id);
		}
		if(!empty($rst)){
			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if ($ctr_inv->con_valor == 0) {
				$rst_emp=$this->emisor_model->lista_un_emisor($emi);
	            $fra = "and emp_id=$rst_emp->emp_id";
	        } else {
	            $fra = "and m.bod_id=$emi";
	        }
			
			$rst1 =$this->ingreso_model->total_ingreso_egreso_fact($rst->id,$fra); 
			if(!empty($rst1)){
	            $inv = $rst1->ingreso - $rst1->egreso;
	            $rst2 = $this->ingreso_model->lista_costos_mov($rst->id,$fra); 
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
	        
			$data=array(
						'pro_id'=>$rst->id,
						'pro_descripcion'=>$rst->mp_d,
						'pro_codigo'=>$rst->mp_c,
						'pro_unidad'=>$rst->mp_q,
						'inventario'=>$inv,
						'cost_unit'=>$cost_unit,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Ingresos a Bodega';
    	$file="ingresos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function show_frame($id,$opc_id){
    	if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$tipo= $this->input->post('tipo');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$tipo='26';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Ingresos a Bodega ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ingreso/show_pdf/$opc_id/$id",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>$tipo,
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
			$modulo=array('modulo'=>'ingreso');
			$this->load->view('layout/footer_bodega',$modulo);
		}
    }

    

    public function show_pdf($opc_id,$id){
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'ingreso'=>$this->ingreso_model->lista_un_ingreso_secuencial($id),
						'cns_det'=>$this->ingreso_model->lista_detalle_ingreso_secuencial($id),
						);

			$ingreso=$this->ingreso_model->lista_un_ingreso_secuencial($id);
			$direccion= $ingreso->emp_ciudad."-".$ingreso->emp_pais;
			// $this->load->view('pdf/pdf_ingreso', $data);

			$this->html4pdf->filename('ingreso.pdf');
			$this->html4pdf->paper('a4', 'portrait');
			///$this->html4pdf->empresa($ingreso->emp_nombre,$ingreso->emp_identificacion,$direccion,$ingreso->emp_telefono,$ingreso->emp_logo);
    		$this->html4pdf->html(utf8_decode($this->load->view('pdf/pdf_ingreso', $data, true)));
    		$this->html4pdf->output(array("Attachment" => 0));
		
    } 

    public function doc_duplicado($id,$num,$tip){
    	if($tip!=0){
			$rst=$this->ingreso_model->lista_doc_duplicado($id,$num,$tip);
			if(!empty($rst)){
				echo $rst->reg_id;
			}else{
				echo "";
			}
		}else{
			$rst=$this->ingreso_model->lista_guia_duplicado($id,$num);
			if(!empty($rst)){
				echo $rst->rgu_id;
			}else{
				echo "";
			}
		}
	} 
}
