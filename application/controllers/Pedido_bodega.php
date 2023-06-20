<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_bodega extends CI_Controller {

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
		$this->load->model('pedido_model');
		$this->load->model('movimiento_model');
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
		$txt_est="and (ped_estado=7 or ped_estado=19 or ped_estado=20) and tipo_cliente=1";
		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
				
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$txt_est);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_pedidos=$this->pedido_model->lista_pedidos_buscador($text,$f1,$f2,$rst_cja->emp_id,$txt_est);
		}

			$data=array(
						'permisos'=>$this->permisos,
						'pedidos'=>$cns_pedidos,
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'txt'=>$text,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pedido_bodega/lista',$data);
			$modulo=array('modulo'=>'pedido');
			$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){

			$pedidos=$this->input->post('txt_data');

			
			///recupera detalle
			$ex_ped=explode(',', $pedidos);
			$rst_ped=$this->pedido_model->lista_un_pedido($ex_ped[0]);
			$n=0;
			$cns_det=array();
			$cns_prod=array();
			$ctrl_inv=$this->configuracion_model->lista_una_configuracion('6');
			$inven=$this->configuracion_model->lista_una_configuracion('3');
			$rst_emi=$this->emisor_model->lista_emisor_cliente($rst_ped->cli_id);
			while ( $n< count($ex_ped)) {

					$cns_dt=$this->pedido_model->lista_pedidos_detalles($ex_ped[$n]);
					
					foreach ($cns_dt as $rst_dt) {
						if ($inven->con_valor == 0) {
							 if ($ctrl_inv->con_valor == 0) {
				                   	$rst_emp=$this->emisor_model->lista_un_emisor($rst_cja->emi_id);
			                    	$fra = "and emp_id=$rst_emp->emp_id";
			                    	$fra2 = "";
				                } else {
				                	
				                    $fra = "and m.bod_id=$rst_cja->emi_id";
				                    $fra2 = "and m.bod_id=$rst_emi->emi_id";
				                }
							$rst1 =$this->factura_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra); 
							if(!empty($rst1)){
				                $inv = $rst1->ingreso - $rst1->egreso;
				            }else{
				            	$inv=0;
				            }

				            $rst2 =$this->factura_model->total_ingreso_egreso_fact($rst_dt->pro_id,$fra2); 
							if(!empty($rst2)){
				                $inv2 = $rst2->ingreso - $rst2->egreso;
				            }else{
				            	$inv2=0;
				            }
				        }else{
				        	$inv=0;
				        	$inv2=0;
				        }  
			        $rst_mov=$this->pedido_model->lista_entregado_producto($rst_dt->ped_num_registro,$rst_dt->pro_id);
			        if(!empty($rst_mov->entregado)){
			        	$entr=$rst_mov->entregado;
			        }else{
			        	$entr=0;
			        }

			        $cnt=$rst_dt->det_cantidad-$entr;
					$dt_det=(object) array(
								'pro_id'=>$rst_dt->pro_id,
								'ids'=>$rst_dt->ids,
								'pro_descripcion'=>$rst_dt->det_descripcion,
								'pro_codigo'=>$rst_dt->det_cod_producto,
								'inventario'=>$inv,
								'inv_dest'=>$inv2,
								'solicitado'=>$rst_dt->det_cantidad,
								'entregado'=>$entr,
								'cantidad'=>$cnt,
								'ped_id'=>$rst_dt->ped_id,
								'ped_num_registro'=>$rst_dt->ped_num_registro,
								);	
						
						array_push($cns_det, $dt_det);
					}
					
					$n++;
				}
								
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'transacciones'=>$this->movimiento_model->lista_transacciones(),
						'movimiento'=> (object) array(
											'mov_documento'=>'',
					                        'mov_fecha_trans'=>date('Y-m-d'),
					                        'ped_fecha'=>date('Y-m-d'),
					                        'mov_guia_transporte'=>'',
					                        'trs_id'=>20,
					                        'cli_id'=>$rst_ped->cli_id,
					                        'cli_nombre'=>$rst_ped->ped_nom_cliente,
					                        'emi_destino'=>$rst_emi->emi_id,
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'emi_nombre'=>$rst_cja->emi_nombre,
					                        'cli_origen'=>$rst_cja->emi_cod_cli,
										),
						'cns_det'=>$cns_det,
						'action'=>base_url().'pedido_bodega/guardar/'.$opc_id
						);
			$this->load->view('pedido_bodega/form',$data);
			$modulo=array('modulo'=>'pedido_bodega');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$emp_id= $this->input->post('emp_id');
		$emi_id= $this->input->post('emi_id');
		$mov_fecha_trans = $this->input->post('mov_fecha_trans');
		$trs_id = $this->input->post('trs_id');
		$cli_id = $this->input->post('cli_id');
		$cli_origen = $this->input->post('cli_origen');
		$emi_destino = $this->input->post('emi_destino');
		$count_det = $this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cli_id','Destino','required');
		$this->form_validation->set_rules('emi_id','Origen','required');
		
		if($this->form_validation->run()){
			$rst = $this->movimiento_model->lista_secuencial();
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

		    		if($this->input->post("mov_cantidad$n") !='' && round($this->input->post("mov_cantidad$n"),2) !=0){
		    			$pro_id = $this->input->post("pro_id$n");
		    			$mov_cantidad = $this->input->post("mov_cantidad$n");
		    			$pedido=$this->input->post("ped_num_registro$n");
		    			$ped_id=$this->input->post("ped_id$n");
		    			//costos
		    			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
						if ($ctr_inv->con_valor == 0) {
							$rst_emp=$this->emisor_model->lista_un_emisor($emi_id);
				            $fra = "and emp_id=$rst_emp->emp_id";
				        } else {
				            $fra = "and m.bod_id=$emi_id";
				        }
						
						$rst1 =$this->movimiento_model->total_ingreso_egreso_fact($pro_id,$fra); 
						if(!empty($rst1)){
				            $inv = $rst1->ingreso - $rst1->egreso;
				            $rst2 = $this->movimiento_model->lista_costos_mov($pro_id,$fra); 
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
		    			$mov_val_unit = $cost_unit;
		    			$mov_val_tot = $cost_unit*$mov_cantidad;

		    			//egreso
						$data=array(
								    'pro_id'=>$pro_id,
					                'trs_id'=>20,
					                'cli_id'=>$cli_id,
					                'emp_id'=>$emp_id,
					                'bod_id'=>$emi_id,
					                'mov_documento'=>$secuencial,
					                'mov_fecha_trans'=>$mov_fecha_trans,
					                'mov_num_fac_entrega'=>$pedido,
					                'mov_cantidad'=>$mov_cantidad,                
					                'mov_fecha_registro'=>date('Y-m-d'),
					                'mov_hora_registro'=>date('H:i'),
					                'mov_val_unit'=>$mov_val_unit,
					                'mov_val_tot'=>$mov_val_tot,
					                'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
					                'mov_doc_tipo'=>0,
					                
						);

						$this->movimiento_model->insert($data);	

						///ingreso
						$data2=array(
								    'pro_id'=>$pro_id,
					                'trs_id'=>4,
					                'cli_id'=>$cli_origen,
					                'emp_id'=>$emp_id,
					                'bod_id'=>$emi_destino,
					                'mov_documento'=>$secuencial,
					                'mov_fecha_trans'=>$mov_fecha_trans,
					                'mov_num_fac_entrega'=>$pedido,
					                'mov_cantidad'=>$mov_cantidad,                
					                'mov_fecha_registro'=>date('Y-m-d'),
					                'mov_hora_registro'=>date('H:i'),
					                'mov_val_unit'=>$mov_val_unit,
					                'mov_val_tot'=>$mov_val_tot,
					                'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
					                'mov_doc_tipo'=>0,
					                
						);	

						if($this->movimiento_model->insert($data2)){

							//estado pedido
							$rst_ent=$this->pedido_model->lista_entregado_bodega($pedido);
					    	$rst_sol=$this->pedido_model->lista_solicitado($ped_id);
					    	$est="19";
					    	if(!empty($rst_ent->entregado) && !empty($rst_sol->solicitado)){
					    		if($rst_ent->entregado>=$rst_sol->solicitado){
					    			$est="20";
					    		}	
					    	}
					    	$dt_ped=array('ped_estado'=>$est);
					    	$this->pedido_model->update($ped_id,$dt_ped);

							$data_aud=array(
											'usu_id'=>$this->session->userdata('s_idusuario'),
											'adt_date'=>date('Y-m-d'),
											'adt_hour'=>date('H:i'),
											'adt_modulo'=>'PEDIDOS BODEGA',
											'adt_accion'=>'INSERTAR',
											'adt_campo'=>json_encode($data),
											'adt_ip'=>$_SERVER['REMOTE_ADDR'],
											'adt_documento'=>$secuencial,
											'usu_login'=>$this->session->userdata('s_usuario'),
											);
						
							$this->auditoria_model->insert($data_aud);
						}else{
							$sms=1;
						}
					
					}
				}	
			if($sms==0){
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'pedido_bodega/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	
    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Pedidos de Bodega '.ucfirst(strtolower($rst_cja->emi_nombre));
    	$file="pedidos_despachar".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

   

    

}
