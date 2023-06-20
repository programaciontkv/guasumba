<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egreso extends CI_Controller {

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
		$this->load->model('egreso_model');
		$this->load->model('cliente_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('configuracion_model');
		$this->load->model('empresa_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
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
			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}
			$cns_mov=$this->egreso_model->lista_egresos_buscador($text,$ids,$f1,$f2,$txt);						
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
			$cns_mov=$this->egreso_model->lista_egresos_buscador($text,$ids,$f1,$f2,$txt);								
		}

		$data=array(
					'permisos'=>$this->permisos,
					'egresos'=>$cns_mov,
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
		$this->load->view('egreso/lista',$data);
		$modulo=array('modulo'=>'egreso');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function nuevo($trs_id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$rst=$this->egreso_model->lista_una_transaccion($trs_id);
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
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'egreso'=> (object) array(
											'mov_documento'=>'',
					                        'mov_fecha_trans'=>date('Y-m-d'),
					                        'mov_guia_transporte'=>'',
					                        'trs_id'=>$trs_id,
					                        'trs_descripcion'=>$rst->trs_descripcion,
					                        'cli_nombre'=>$cli_nombre,
					                        'cli_id'=>$cli_id,
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
										),
						'action'=>base_url().'egreso/guardar/'.$opc_id
						);
			$this->load->view('egreso/form',$data);
			$modulo=array('modulo'=>'egreso');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$emp_id= $this->input->post('emp_id');
		$emi_id= $this->input->post('emi_id');
		$mov_fecha_trans = $this->input->post('mov_fecha_trans');
		$mov_guia_transporte = $this->input->post('mov_guia_transporte');
		$trs_id = $this->input->post('trs_id');
		$cli_id = $this->input->post('cli_id');
		$count_det = $this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cli_id','Proveedor','required');
		
		if($this->form_validation->run()){
			$rst = $this->egreso_model->lista_secuencial();
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
					                'mov_doc_tipo'=>0,
					                
						);	
						

						if($this->egreso_model->insert($data)==false){
							$sms+=1;
						}
					
					}
				}	
			if($sms==0){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'EGRESOS',
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
				redirect(base_url().'egreso/nuevo/'.$opc_id);
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
			
			$rst1 =$this->egreso_model->total_egreso_egreso_fact($rst->id,$fra); 
			if(!empty($rst1)){
	            $inv = $rst1->egreso - $rst1->egreso;
	            $rst2 = $this->egreso_model->lista_costos_mov($rst->id,$fra); 
	            if(!empty($rst2)){
	             	$cnt_inv=$rst2->egreso - $rst2->egreso;
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

    	$titulo='Egresos de Bodega';
    	$file="egresos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Egresos de Bodega ',
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"egreso/show_pdf/$opc_id/$id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'egreso');
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
						'egreso'=>$this->egreso_model->lista_un_egreso_secuencial($id),
						'cns_det'=>$this->egreso_model->lista_detalle_egreso_secuencial($id),
						);
			$this->html2pdf->filename('egreso.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_egreso', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    }  

}
