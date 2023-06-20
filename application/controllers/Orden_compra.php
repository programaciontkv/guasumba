<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra extends CI_Controller {

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
		$this->load->model('orden_compra_model');
		$this->load->model('orden_compra_tiempo_model');
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


		///actualiza ordenes aprobadas a estado a culminado
		$tiempo=$this->orden_compra_tiempo_model->lista_tiempo_vigente();	
		
		$cns_ordenes=$this->orden_compra_model->lista_ordenes_estado('13');
		$fec_actual=date('Y-m-d');
		foreach ($cns_ordenes as $rst_ord) {
			if($tiempo->tie_tipo==1){
	            $fecha_limite=strtotime ( "+ $tiempo->tie_cantidad days" , strtotime ($rst_ord->orc_fecha_aut) ) ;
	            $fecha_limite=date ( 'Y-m-d' , $fecha_limite );
	        }else{
	            $fecha_limite=strtotime ( "+ $tiempo->tie_cantidad month" , strtotime ( $rst_ord->orc_fecha_aut) ) ;
	            $fecha_limite=date ( 'Y-m-d' , $fecha_limite );
	        }
	        if($fecha_limite<=$fec_actual){
	           	$this->orden_compra_model->update($rst_ord->orc_id,["orc_estado"=>23]);
	        }
		}
		
		
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		///buscador 
		if($_POST){
			$text= trim($this->input->post('txt'));
			$est= $this->input->post('estado');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			
		}else{
			$text= '';
			$est= '0';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			
										
		}
		if($est==0){
			$txt_est="";
		}else{
			$txt_est="and orc_estado='$est'";
		}
		$cns_mov=$this->orden_compra_model->lista_ordenes_buscador($text,$f1,$f2,$txt_est);	
		
		

		$data=array(
					'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
					'permisos'=>$this->permisos,
					'ordenes'=>$cns_mov,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
					'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'est'=>$est,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('orden_compra/lista',$data);
		$modulo=array('modulo'=>'orden_compra');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		
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
						'mensaje'=> $mensaje,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'orden'=> (object) array(
											'orc_id'=>'0',
											'orc_codigo'=>'',
											'orc_fecha'=>date('Y-m-d'),
					                        'orc_fecha_entrega'=>date('Y-m-d'),
					                        'orc_concepto'=>'',
					                        'orc_condicion_pago'=>'CONTADO',
					                        'orc_direccion_entrega'=>$rst_cja->emp_direccion,
					                        'cli_raz_social'=>'',
					                        'cli_id'=>0,
					                        'emp_id'=>$rst_cja->emp_id,
					                        'orc_obs'=>'',
					                        'orc_sub12'=>0,
					                        'orc_sub0'=>0,
					                        'orc_descuento'=>0,
					                        'orc_descv'=>0,
					                        'orc_iva'=>0,
					                        'orc_flete'=>0,
					                        'orc_total'=>0,
										),
						'detalle'=>'',
						'action'=>base_url().'orden_compra/guardar/'.$opc_id
						);
			//$this->load->view('orden_compra/form',$data);

			// $we =  intval($this->session->userdata('s_we'));
			// if($we>=760){
				$this->load->view('orden_compra/form',$data);
			// }else{
			// 	$this->load->view('orden_compra/form_movil',$data);
			// }

			$modulo=array('modulo'=>'orden_compra');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$emp_id= $this->input->post('emp_id');
		$orc_fecha = $this->input->post('orc_fecha');
		$orc_fecha_entrega = $this->input->post('orc_fecha_entrega');
		$orc_concepto = $this->input->post('orc_concepto');
		$orc_condicion_pago= $this->input->post('orc_condicion_pago');
		$orc_direccion_entrega = $this->input->post('orc_direccion_entrega');
		$cli_id = $this->input->post('cli_id');
		$orc_obs = $this->input->post('orc_obs');
		$orc_sub12 = $this->input->post('orc_sub12');
		$orc_sub0 = $this->input->post('orc_sub0');
		$orc_descuento = $this->input->post('orc_descuento');
		$orc_descv = $this->input->post('orc_descv');
		$orc_iva = $this->input->post('orc_iva');
		$orc_flete = $this->input->post('orc_flete');
		$orc_total = $this->input->post('orc_total');

		$count_det = $this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cli_id','Proveedor','required');
		
		if($this->form_validation->run()){
			


			$rst = $this->orden_compra_model->lista_secuencial();
			if(empty($rst)){
				$sec=1;
			}else{
				$sec=intval(str_replace('OC', '', $rst->orc_codigo) + 1);
	    	}
	        if ($sec >= 0 && $sec < 10) {
	            $txt = '00000';
	        } else if ($sec >= 10 && $sec < 100) {
	            $txt = '0000';
	        } else if ($sec >= 100 && $sec < 1000) {
	            $txt = '000';
	        } else if ($sec >= 1000 && $sec < 10000) {
	            $txt = '00';
	        } else if ($sec >= 10000 && $sec < 100000) {
	            $txt = '0';
	        } else if ($sec >= 100000 && $sec < 1000000) {
	            $txt = '';
	        } 

	        $secuencial = 'OC' . $txt . $sec;

	        //auditoria
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ORDENES COMPRA',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$secuencial,
								'usu_login'=>$this->session->userdata('s_usuario'),
							);
						
			$this->auditoria_model->insert($data_aud);

	        $data=array(
						'emp_id'=>$emp_id,
						'cli_id'=>$cli_id,
					    'orc_codigo'=>$secuencial,
					    'orc_fecha'=>$orc_fecha,
					    'orc_fecha_entrega'=>$orc_fecha_entrega,
					    'orc_concepto'=>$orc_concepto,
					    'orc_condicion_pago'=>$orc_condicion_pago,
					    'orc_direccion_entrega'=>$orc_direccion_entrega,
					    'orc_obs'=>$orc_obs,
					    'orc_sub12'=>$orc_sub12,
					    'orc_sub0'=>$orc_sub0,                
					    'orc_descuento'=>$orc_descuento,
					    'orc_descv'=>$orc_descv,
					    'orc_iva'=>$orc_iva,
					    'orc_flete'=>$orc_flete,
					    'orc_total'=>$orc_total,
					    'usu_id'=>$this->session->userdata('s_idusuario'),
					    'orc_estado'=>7,
					                
						);	
						
	        $sms=0;
	        $orc_id=$this->orden_compra_model->insert($data);
			if(!empty($orc_id)){
				
				$n=0;
			
		    	while($n < $count_det){
		    		$n++;

		    		if($this->input->post("pro_id$n") !=''){
		    			$pro_id = $this->input->post("pro_id$n");
		    			$orc_det_cant = $this->input->post("orc_det_cant$n");
		    			$orc_det_vu = $this->input->post("orc_det_vu$n");
		    			$orc_det_vt = $this->input->post("orc_det_vt$n");
		    			$orc_det_iva = $this->input->post("orc_det_iva$n");
		    	
						$data2=array(
								    'orc_id'=>$orc_id,
								    'mp_id'=>$pro_id,
					                'orc_det_cant'=>$orc_det_cant,
					                'orc_det_vu'=>$orc_det_vu,
					                'orc_det_vt'=>$orc_det_vt,
					                'orc_det_iva'=>$orc_det_iva,                
					                
						);	
						

						if($this->orden_compra_model->insert_det($data2)==false){
							$sms+=1;
						}
					
					}
				}
			}else{
				$sms=1;
			}
			
				
			if($sms==0){
				
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'orden_compra/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		
		if($permisos->rop_actualizar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cns_pro'=>$this->producto_comercial_model->lista_productos_bodega_estado('1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'mensaje'=> $mensaje,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'orden'=> $this->orden_compra_model->lista_una_orden($id),
						'detalle'=> $this->orden_compra_model->lista_detalle($id),
						'action'=>base_url().'orden_compra/actualizar/'.$opc_id
						);
			//$this->load->view('orden_compra/form',$data);

			// $we =  intval($this->session->userdata('s_we'));
			// if($we>=760){
				$this->load->view('orden_compra/form',$data);
			// }else{
			// 	$this->load->view('orden_compra/form_movil',$data);
			// }

			$modulo=array('modulo'=>'orden_compra');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function actualizar($opc_id){
		$id=$this->input->post('orc_id');
		$secuencial=$this->input->post('orc_codigo');
		$emp_id= $this->input->post('emp_id');
		$orc_fecha = $this->input->post('orc_fecha');
		$orc_fecha_entrega = $this->input->post('orc_fecha_entrega');
		$orc_concepto = $this->input->post('orc_concepto');
		$orc_condicion_pago= $this->input->post('orc_condicion_pago');
		$orc_direccion_entrega = $this->input->post('orc_direccion_entrega');
		$cli_id = $this->input->post('cli_id');
		$orc_obs = $this->input->post('orc_obs');
		$orc_sub12 = $this->input->post('orc_sub12');
		$orc_sub0 = $this->input->post('orc_sub0');
		$orc_descuento = $this->input->post('orc_descuento');
		$orc_descv = $this->input->post('orc_descv');
		$orc_iva = $this->input->post('orc_iva');
		$orc_flete = $this->input->post('orc_flete');
		$orc_total = $this->input->post('orc_total');

		$count_det = $this->input->post('count_detalle');
		
		$this->form_validation->set_rules('cli_id','Proveedor','required');
		
		if($this->form_validation->run()){
			
	        //auditoria
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'ORDENES COMPRA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$secuencial,
								'usu_login'=>$this->session->userdata('s_usuario'),
							);
						
			$this->auditoria_model->insert($data_aud);

	        $data=array(
						'emp_id'=>$emp_id,
						'cli_id'=>$cli_id,
					    'orc_fecha'=>$orc_fecha,
					    'orc_fecha_entrega'=>$orc_fecha_entrega,
					    'orc_concepto'=>$orc_concepto,
					    'orc_condicion_pago'=>$orc_condicion_pago,
					    'orc_direccion_entrega'=>$orc_direccion_entrega,
					    'orc_obs'=>$orc_obs,
					    'orc_sub12'=>$orc_sub12,
					    'orc_sub0'=>$orc_sub0,                
					    'orc_descuento'=>$orc_descuento,
					    'orc_descv'=>$orc_descv,
					    'orc_iva'=>$orc_iva,
					    'orc_flete'=>$orc_flete,
					    'orc_total'=>$orc_total,
					    'usu_id'=>$this->session->userdata('s_idusuario'),
					    'orc_estado'=>7,
					                
						);	
						
	        $sms=0;
	        $orc_id=$id;
			if($this->orden_compra_model->update($id,$data)){
				//borrar detalle 
				$this->orden_compra_model->delete_det($orc_id);

				$n=0;
		    	while($n < $count_det){
		    		$n++;

		    		if($this->input->post("pro_id$n") !=''){
		    			$pro_id = $this->input->post("pro_id$n");
		    			$orc_det_cant = $this->input->post("orc_det_cant$n");
		    			$orc_det_vu = $this->input->post("orc_det_vu$n");
		    			$orc_det_vt = $this->input->post("orc_det_vt$n");
		    			$orc_det_iva = $this->input->post("orc_det_iva$n");
		    	
						$data2=array(
								    'orc_id'=>$orc_id,
								    'mp_id'=>$pro_id,
					                'orc_det_cant'=>$orc_det_cant,
					                'orc_det_vu'=>$orc_det_vu,
					                'orc_det_vt'=>$orc_det_vt,
					                'orc_det_iva'=>$orc_det_iva,                
					                
						);	
						
						if($this->orden_compra_model->insert_det($data2)==false){
							$sms+=1;
						}
					
					}
				}
			}else{
				$sms=1;
			}
			
				
			if($sms==0){
				
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'orden_compra/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
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

	public function load_producto($id,$cli){
		$rst=$this->producto_comercial_model->lista_un_producto_cod($id);
		if(empty($rst)){
			$rst=$this->producto_comercial_model->lista_un_producto($id);
		}
		if(!empty($rst)){
			$rst_pre=$this->orden_compra_model->lista_ultima_compra_aprob($cli,$id);	
	        $val_unit=0;
	        if(!empty($rst_pre)){
	        	$val_unit=$rst_pre->orc_det_vu;
	        }

			$data=array(
						'pro_id'=>$rst->id,
						'pro_descripcion'=>$rst->mp_d,
						'pro_codigo'=>$rst->mp_c,
						'pro_unidad'=>$rst->mp_q,
						'pro_iva'=>$rst->mp_h,
						'val_unit'=>$val_unit,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Ordenes de Compra';
    	$file="orden_compras".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function show_frame($id,$opc_id){
    	if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$estado= $this->input->post('estado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$estado='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Ordenes de Compra ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"orden_compra/show_pdf/$opc_id/$id",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>$estado,
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
			$modulo=array('modulo'=>'orden_compra');
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
						'orden'=>$this->orden_compra_model->lista_una_orden($id),
						'cns_det'=>$this->orden_compra_model->lista_detalle($id),
						);
			$this->html2pdf->filename('orden_compra.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_orden_compra', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    }  
}
