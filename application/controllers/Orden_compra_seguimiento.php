<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_seguimiento extends CI_Controller {

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
		$this->load->model('orden_compra_seguimiento_model');
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
		$this->load->model('ingreso_model');
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
		$dec=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$dec->con_valor;
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
			$txt_est="and (orc_estado='13' or orc_estado='22' or orc_estado='23')";
		}else{
			$txt_est="and orc_estado='$est'";
		}
		$ordenes=$this->orden_compra_seguimiento_model->lista_ordenes_buscador($text,$f1,$f2,$txt_est);	

		$data=array(
					'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
					'permisos'=>$this->permisos,
					'ordenes'=>$ordenes,
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
		$this->load->view('orden_compra_seguimiento/lista',$data);
		$modulo=array('modulo'=>'orden_compra_seguimiento');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	
	public function editar($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$est= $this->input->post('estado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$est='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			
			$data=array(
						'tipo_documentos'=>$this->orden_compra_seguimiento_model->lista_tipo_documentos('1'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'mensaje'=> $mensaje,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'orden'=> $this->orden_compra_seguimiento_model->lista_una_orden($id),
						'detalle'=> $this->orden_compra_seguimiento_model->lista_detalle($id),
						'action'=>base_url().'orden_compra_seguimiento/actualizar/'.$opc_id,
						'opc_id'=>$opc_id,
						'fec1'=>$fec1,
						'fec2'=>$fec2,
						'txt'=>$text,
						'est'=>$est,
						);
			//$this->load->view('orden_compra_seguimiento/form',$data);

			// $we =  intval($this->session->userdata('s_we'));
			// if($we>=760){
				$this->load->view('orden_compra_seguimiento/form',$data);
			// }else{
			// 	$this->load->view('orden_compra_seguimiento/form_movil',$data);
			// }

			$modulo=array('modulo'=>'orden_compra_seguimiento');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function actualizar($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		$id=$this->input->post('orc_id');
		$num0=$this->input->post('orc_factura0');
		$num1=$this->input->post('orc_factura1');
		$num2=$this->input->post('orc_factura2');
		$orc_tip_doc=$this->input->post('orc_tip_doc');
		$orc_factura=$num0.'-'.$num1.'-'.$num2;

		$rst=$this->orden_compra_seguimiento_model->lista_una_orden($id);
		$cns=$this->orden_compra_seguimiento_model->lista_detalle($id);
			$data=array(
		    			'orc_tip_doc'=>$orc_tip_doc, 
		    			'orc_factura'=>$orc_factura,
		    );

			$data_audito=array(
		    			'orc_id'=>$id, 
		    			'orc_codigo'=>$rst->orc_codigo,
		    			'orc_tip_doc'=>$orc_tip_doc,
		    			'orc_factura'=>$orc_factura, 

		    );


		    if($this->orden_compra_seguimiento_model->update($id,$data)){
		    	///guardar pre-registro factura
		    	if($rst->cli_tipo_cliente==0){
		    		$tpc='LOCAL';
		    	}else{
		    		$tpc='EXTRANJERO';
		    	}
		    	$encabezado=array(
                        'reg_fregistro'=>date('Y-m-d'),
                        'reg_tipo_documento'=>$orc_tip_doc,
                        'reg_num_documento'=>$orc_factura,
                        'reg_fautorizacion'=>'1900-01-01',//fec_aut
                        'reg_fcaducidad'=>'1900-01-01',//fec_aut_hasta
                        'reg_sbt12'=>$rst->orc_sub12,//sub12
                        'reg_sbt0'=>$rst->orc_sub0,//sub0
                        'reg_sbt'=>$rst->orc_sub12+$rst->orc_sub0,//subtotal
                        'reg_tdescuento'=>$rst->orc_descv,
                        'reg_iva12'=>$rst->orc_iva,
                        'reg_total'=>$rst->orc_total,//total
                        'cli_id'=>$rst->cli_id,
                        'reg_tpcliente'=>$tpc,
                        'reg_ruc_cliente'=>$rst->cli_ced_ruc,
                        'reg_estado'=>'7',//estado
                        'emp_id'=>$rst_cja->emp_id,
                        'emi_id'=>$rst_cja->emi_id,
                        'cja_id'=>$rst_cja->cja_id,
                        'reg_tipo_pago'=>'01',
                        'reg_forma_pago'=>'1',
                        'reg_pais_importe'=>'241',
                        'reg_relacionado'=>'NO',
                        'reg_num_ingreso'=>$rst->orc_codigo,

                    );
    
		    	$fac_id=$this->reg_factura_model->insert($encabezado);
		    	foreach($cns as $det) {
    		
    	 			$detalle=array(     
	                             'det_cantidad'=>$det->orc_det_cant,
	                             'det_vunit'=>$det->orc_det_vu,
	                             'det_descuento_porcentaje'=>0,
	                             'det_descuento_moneda'=>0,
	                             'det_tipo'=>$det->ids,
	                             'det_impuesto'=>$det->mp_h,
	                             'pln_id'=>'0',
	                             'pro_id'=>$det->id,
	                             'reg_id'=>$fac_id
	                         );
    	 			$this->reg_factura_model->insert_detalle($detalle);
    			} 	


		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'SEGUIMIENTO ORDENES COMPRA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst->orc_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				$this->editar($id,$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'orden_compra_seguimiento/editar/'.$id.'/'.$opc_id);
			}
	}

	public function cambiar_estado($id,$opc_id){
		$rst=$this->orden_compra_seguimiento_model->lista_una_orden($id);
		$data_audito=array(
						'orc_id'=>$id,
						'orc_codigo'=>$rst->orc_codigo,
						'orc_estado'=>22,
						);
		if($this->orden_compra_seguimiento_model->update($id,['orc_estado'=>22])){
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'SEGUIMIENTO ORDENES COMPRA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst->orc_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
		}
	}

	public function etiquetas($id,$opc_id){
		if($_POST){
			$text= $this->input->post('txt');
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$est= $this->input->post('estado');
		}else{
			$text='';
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$est='';
		}

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$detalle=$this->orden_compra_seguimiento_model->lista_una_detalle_orden($id);
			$peso_max=0;
			if(!empty($detalle)){
				$peso_max=$detalle->orc_det_cant*1.10;
			}
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'mensaje'=> $mensaje,
						'cancelar'=>base_url().'orden_compra_seguimiento/editar/'.$detalle->orc_id.'/'.$opc_id,
						'etiquetas'=>$this->orden_compra_seguimiento_model->lista_etiquetas_det($id),
						'registro'=> (object) array(
													'orc_id'=>$detalle->orc_id,
													'orc_det_id'=>$detalle->orc_det_id,
													'peso_max'=>$peso_max,
													'mp_q'=>$detalle->mp_q),
						'detalle'=> $this->orden_compra_seguimiento_model->lista_detalle($id),
						'action'=>base_url().'orden_compra_seguimiento/guardar_pesos/'.$opc_id.'/0',
						'opc_id'=>$opc_id,
						'txt'=>$text,
						'fec1'=>$fec1,
						'fec2'=>$fec2,
						'est'=>$est,
						);
			//$this->load->view('orden_compra_seguimiento/form',$data);

			// $we =  intval($this->session->userdata('s_we'));
			// if($we>=760){
				$this->load->view('orden_compra_seguimiento/form_pesos',$data);
			// }else{
			// 	$this->load->view('orden_compra_seguimiento/form_movil',$data);
			// }

			$modulo=array('modulo'=>'orden_compra_seguimiento');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}
	

	public function guardar_pesos($opc_id,$imp){
		$text= $this->input->post('txt');
		$fec1= $this->input->post('fec1');
		$fec2= $this->input->post('fec2');
		$est= $this->input->post('estado');

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$dec=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$dec->con_valor;

		$id=$this->input->post('orc_det_id');
		$rst=$this->orden_compra_seguimiento_model->lista_una_detalle_orden($id);
		$sms=0;
		$n=0;
		while ($n < 100) {
			$n++;
			if($this->input->post("etq_peso$n")!='' && $this->input->post("etq_peso$n")!='0' ){
				
				if($n<=10){
					$txt2='00';
				}else if($n>10 && $n<=100){
					$txt2='0';
				}else{
					$txt2='';
				}

				$etiqueta=$rst->mp_c.$rst->orc_codigo.'-'.$txt2.$n;
				$peso=$this->input->post("etq_peso$n");
				$data=array(
							'orc_det_id'=>$id,
	                        'etq_cant'=>1,
	                        'etq_peso'=>$peso,
	                        'etq_fecha'=>date('Y-m-d'),
	                        'etq_bar_code'=>$etiqueta,
			    );

			    if( $this->orden_compra_seguimiento_model->insert_etiqueta($data)){
			    	//inserta movimientos
			    	$rst_mov = $this->ingreso_model->lista_secuencial();
					if(empty($rst_mov)){
						$sec=1;
					}else{
						$sc=explode('-',$rst_mov->mov_documento);
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
					$dat_mov=array( 
									'mov_fecha_registro'=>date('Y-m-d'),
					                'mov_hora_registro'=>date('H:i'),
									'trs_id'=>'3',
                                	'pro_id'=>$rst->mp_id,
                                	'mov_guia_transporte'=>$rst->orc_codigo,
                                	'mov_documento'=>$secuencial,                     
                                	'mov_fecha_trans'=>date('Y-m-d'),
                                	'mov_cantidad'=>$peso, 
                                	'mov_val_tot'=>round($peso*$rst->orc_det_vu,$dec),
                                	'cli_id'=>$rst->cli_id,
                                	'mov_val_unit'=>$rst->orc_det_vu,
                                	'mov_pago'=>$etiqueta,
                                	'bod_id'=>$rst_cja->emi_id,
                                	'emp_id'=>$rst_cja->emp_id,
                                	'mov_usuario'=>strtoupper($this->session->userdata('s_usuario')),
					                'mov_doc_tipo'=>0
					            );
					$this->ingreso_model->insert($dat_mov);
			    }else{
			   		$sms+=1;
			    }

			}
		}	

		///cambiar estado orden a terminado
			$det=$this->orden_compra_seguimiento_model->lista_suma_detalle($rst->orc_id);
			$sum_sol=0;
			$sum_entr=0;
			if(!empty($det->cantidad)) {
				$sum_sol=$det->cantidad;
			}

			if(!empty($det->unidad)) {
				$sum_entr=$det->unidad;
			}

			if($sum_entr>=$sum_sol){
				$this->orden_compra_seguimiento_model->update($rst->orc_id,['orc_estado'=>22]);
			}

		    if($sms==0){

		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CANTIDADES ORDENES COMPRA',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst->orc_codigo.' - '.$rst->mp_c,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				
				if($imp==1){
					$this->show_frame($id,$opc_id,$fec1,$fec2,$est,$text);
				}else{
					$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
					$this->etiquetas($id,$opc_id);
				}	
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'orden_compra_seguimiento/etiquetas/'.$id.'/'.$opc_id);
			}
	}

	public function show_frame($id,$opc_id,$fec1,$fec2,$est,$text){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$rst=$this->orden_compra_seguimiento_model->lista_una_detalle_orden($id);
          $data=array(
					'titulo'=>'Ingreso de Cantidades',
					'regresar'=>base_url().'orden_compra_seguimiento/editar/'.$rst->orc_id.'/'.$opc_id,
					'direccion'=>'orden_compra_seguimiento/reporte/'.$id.'/'.$opc_id,
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>$est,
					
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'orden_compra_seguimiento');
			$this->load->view('layout/footer',$modulo);
	}
	
	public function reporte($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 	require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','etq_nop',0);
	    
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        

        set_time_limit(0);
        
      
        $cns = $this->orden_compra_seguimiento_model->lista_etq_orden($id);
		$rst_total = $this->orden_compra_seguimiento_model->lista_etq_orden_total($id);
	
		if (empty($rst_total->peso)) {
	    	$cns = $this->orden_compra_seguimiento_model->lista_etq_orden_mov($id);
	    	$rst_total = $this->orden_compra_seguimiento_model->lista_etq_orden_total_mov($id);
		}

		//modifica estado de etiquetas a impreso 
		$this->orden_compra_seguimiento_model->update_etiqueta($id,['etq_estado_imp'=>'1']);

		$rst_fac=$this->orden_compra_seguimiento_model->lista_una_detalle_orden($id);
		///etiqueta total
		$x = 0;
        $y = 0;
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Text($x + 30, $y + 5, "$emisor->emp_nombre");
        $pdf->Line($x + 1, $y + 6, $x + 110, $y + 6);
        $cx = strlen($rst_total->etq_bar_code);
        if ($cx <= 15) {
            $x1 = 20;
        } else {
            $x1 = 10;
        }
        $this->Code39($pdf,$x1, 7, $rst_total->etq_bar_code);
        $pdf->Line($x + 1, $y + 40, $x + 110, $y + 40);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text($x + 3, $y + 45, "REFERENCIA:  " . $rst_total->mp_d);
        $pdf->Text($x + 3, $y + 50, "FACTURA #:  " . $rst_fac->orc_factura);
        $pdf->Text($x + 3, $y + 55, "CANTIDAD TOTAL: " . $rst_total->peso . " $rst_total->mp_q");
        $pdf->Text($x + 70, $y + 55, "FECHA: " . date("Y-m-d"));

        //etiquetas por pesos
		foreach($cns as $rst) {
    		$pdf->AddPage('P','etq_nop',0);
     		$x = 0;
	        $y = 0;
	        $pdf->SetFont('helvetica', 'B', 16);
	        $pdf->Text($x + 30, $y + 5, "$emisor->emp_nombre");
	        $pdf->Line($x + 1, $y + 6, $x + 110, $y + 6);
	        
	        $cx = strlen($rst_total->etq_bar_code);
	        if ($cx <= 15) {
	            $x1 = 20;
	        } else {
	            $x1 = 10;
	        }

	        $this->Code39($pdf,$x1, 7, $rst->etq_bar_code);
	        $pdf->Line($x + 1, $y + 40, $x + 110, $y + 40);
	        $pdf->SetFont('helvetica', 'B', 10);
	        $pdf->Text($x + 3, $y + 45, "REFERENCIA:  " . $rst->mp_d);
	        $pdf->Text($x + 3, $y + 55, "CANTIDAD: " . $rst->etq_peso . " $rst->mp_q");
	        $pdf->Text($x + 70, $y + 55, "FECHA: " . date("Y-m-d"));
		}

        $pdf->Output('etiqueta_orden_compra.pdf' , 'I' ); 
	}	

	function Code39($pdf,$x, $y, $code, $w = 0.29, $h = 28, $ext = false, $cks = false, $wide = false) {
		

        $pdf->SetFont('Arial', '', 10);
        $pdf->Text($x + 30, $y + $h + 4, $code);
        if ($ext) {
            $code = $pdf->encode_code39_ext($code);
        } else {
            $code = strtoupper($code);
            if (!preg_match('|^[0-9A-Z. $/+%-]*$|', $code))
                $pdf->Error('Invalid barcode value: ' . $code);
        }
        if ($cks)
            $code .= $pdf->checksum_code39($code);
        $code = '*' . $code . '*';
        $narrow_encoding = array(
            '0' => '101001101101', '1' => '110100101011', '2' => '101100101011',
            '3' => '110110010101', '4' => '101001101011', '5' => '110100110101',
            '6' => '101100110101', '7' => '101001011011', '8' => '110100101101',
            '9' => '101100101101', 'A' => '110101001011', 'B' => '101101001011',
            'C' => '110110100101', 'D' => '101011001011', 'E' => '110101100101',
            'F' => '101101100101', 'G' => '101010011011', 'H' => '110101001101',
            'I' => '101101001101', 'J' => '101011001101', 'K' => '110101010011',
            'L' => '101101010011', 'M' => '110110101001', 'N' => '101011010011',
            'O' => '110101101001', 'P' => '101101101001', 'Q' => '101010110011',
            'R' => '110101011001', 'S' => '101101011001', 'T' => '101011011001',
            'U' => '110010101011', 'V' => '100110101011', 'W' => '110011010101',
            'X' => '100101101011', 'Y' => '110010110101', 'Z' => '100110110101',
            '-' => '100101011011', '.' => '110010101101', ' ' => '100110101101',
            '*' => '100101101101', '$' => '100100100101', '/' => '100100101001',
            '+' => '100101001001', '%' => '101001001001');

        $wide_encoding = array(
            '0' => '101000111011101', '1' => '111010001010111', '2' => '101110001010111',
            '3' => '111011100010101', '4' => '101000111010111', '5' => '111010001110101',
            '6' => '101110001110101', '7' => '101000101110111', '8' => '111010001011101',
            '9' => '101110001011101', 'A' => '111010100010111', 'B' => '101110100010111',
            'C' => '111011101000101', 'D' => '101011100010111', 'E' => '111010111000101',
            'F' => '101110111000101', 'G' => '101010001110111', 'H' => '111010100011101',
            'I' => '101110100011101', 'J' => '101011100011101', 'K' => '111010101000111',
            'L' => '101110101000111', 'M' => '111011101010001', 'N' => '101011101000111',
            'O' => '111010111010001', 'P' => '101110111010001', 'Q' => '101010111000111',
            'R' => '111010101110001', 'S' => '101110101110001', 'T' => '101011101110001',
            'U' => '111000101010111', 'V' => '100011101010111', 'W' => '111000111010101',
            'X' => '100010111010111', 'Y' => '111000101110101', 'Z' => '100011101110101',
            '-' => '100010101110111', '.' => '111000101011101', ' ' => '100011101011101',
            '*' => '100010111011101', '$' => '100010001000101', '/' => '100010001010001',
            '+' => '100010100010001', '%' => '101000100010001');

        $encoding = $wide ? $wide_encoding : $narrow_encoding;
        $gap = ($w > 0.29) ? '00' : '0';
        $encode = '';
        for ($i = 0; $i < strlen($code); $i++)
            $encode .= $encoding[$code[$i]] . $gap;
        $pdf->draw_code39($encode, $x, $y, $w, $h);
    }

   
    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Seguimiento de Ordenes de Compras';
    	$file="orden_compra_seguimientos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function doc_duplicado($id,$num,$tip){
		$rst=$this->orden_compra_seguimiento_model->lista_doc_duplicado($id,$num,$tip);
		if(!empty($rst)){
			echo $rst->reg_id;
		}else{
			echo "";
		}
	}


}
