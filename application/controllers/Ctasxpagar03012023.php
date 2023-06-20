<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctasxpagar extends CI_Controller {

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
		$this->load->model('ctasxpagar_model');
		$this->load->model('ctasxcobrar_model');
		$this->load->model('reg_factura_model');
		$this->load->model('reg_nota_credito_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('asiento_model');
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
			$vencer= $this->input->post('vencer');	
			$vencido= $this->input->post('vencido');
			$pagado= $this->input->post('pagado');		
			if($vencer=='on' && $vencido=='on' && $pagado=='on'){
				$cns_facturas=$this->ctasxpagar_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $vencido=='on' && $pagado==''){
				$cns_facturas=$this->ctasxpagar_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $pagado=='on' && $vencido==''){
				$cns_facturas=$this->ctasxpagar_model->lista_vencer_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencido=='on' && $pagado=='on' && $vencer==''){
				$cns_facturas=$this->ctasxpagar_model->lista_vencido_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $vencido=='' && $pagado==''){
				$cns_facturas=$this->ctasxpagar_model->lista_vencer($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='' && $vencido=='on' && $pagado==''){
				$cns_facturas=$this->ctasxpagar_model->lista_vencido($text,$f1,$f2,$rst_cja->emp_id);
			}else if($pagado=='on'){
				$cns_facturas=$this->ctasxpagar_model->lista_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}
		}else{
			$text= '';
			$f1= '1900-01-01';
			$f2= date('Y-m-d');
			// $cns_facturas=$this->ctasxpagar_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
			$cns_facturas=$this->ctasxpagar_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
			$vencer= 'on';	
			$vencido= 'on';
			$pagado= '';		
		}
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;

			$data=array(
						'permisos'=>$this->permisos,
						'facturas'=>$cns_facturas,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'txt'=>$text,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'dec'=>$dec,
						'vencer'=>$vencer,	
						'vencido'=>$vencido,
						'pagado'=>$pagado		

			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('ctasxpagar/lista',$data);
			$modulo=array('modulo'=>'ctasxpagar');
			$this->load->view('layout/footer_bodega',$modulo);
	}


	public function nuevo($opc_id,$fac_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$rst_fac=$this->reg_factura_model->lista_una_factura($fac_id);

		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$cuentas="";
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$pln_descripcion="";
			$pln_id=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				$cuentas=$this->plan_cuentas_model->lista_plan_cuentas_estado_tipo('1','1');
				$rst_cxp=$this->ctasxpagar_model->lista_ctasxpagar($rst_fac->reg_id);
				if(!empty($rst_cxp[0]->pln_id)){
					$rst_pln=$this->plan_cuentas_model->lista_un_plan_cuentas($rst_cxp[0]->pln_id);
					$pln_descripcion=$rst_pln->pln_descripcion;
					$pln_id=$rst_pln->pln_id;
				}
			}
			$data=array(
						'dec'=>$dec,
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'cliente'=>$this->cliente_model->lista_un_cliente($rst_fac->cli_id),
						'credito'=>0,
						'saldo_vencido'=>$this->ctasxpagar_model->lista_saldo_factura($rst_fac->reg_id),
						'ctasxpag'=> (object) array(
											'ctp_fecha_pago'=>date('Y-m-d'),
											'ctp_forma_pago'=>'0',
											'pln_id'=>$pln_id,
											'pln_descripcion'=>$pln_descripcion,
					                        'num_documento'=>'',
					                        'ctp_concepto'=>'',
					                        'ctp_banco'=>'',
					                        'ctp_monto'=>'',
					                        'reg_id'=>$fac_id,
					                        'fac_numero'=>'',
					                        'fac_total_valor'=>'',
					                        'valor_cruce'=>'',
					                        'emp_id'=>$rst_fac->emp_id,
					                        'numero'=>$rst_fac->reg_num_documento,
					                        
										),
						'cns_pag'=>$this->ctasxpagar_model->lista_pagos_factura($rst_fac->reg_id),
						'cns_det'=>$this->ctasxpagar_model->lista_ctasxpagar($rst_fac->reg_id),
						'action'=>base_url().'ctasxpagar/guardar/'.$opc_id.'/1',
						'action2'=>base_url().'ctasxpagar/guardar/'.$opc_id.'/2',
						'cancelar2'=>base_url().'ctasxpagar/nuevo/'.$rst_opc->opc_id.'/'.$fac_id,
						'conf_as'=>$conf_as->con_valor,
						'cuentas'=>$cuentas,
						);
			$this->load->view('ctasxpagar/form',$data);
			$modulo=array('modulo'=>'ctasxpagar');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id,$cr){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$reg_id = $this->input->post('reg_id');
		$ctp_fecha_pago = $this->input->post('ctp_fecha_pago');
		$fac_id = $this->input->post('fac_id');
		$numero = $this->input->post('numero');

		// if($cr==1){
			$ctp_forma_pago = $this->input->post('ctp_forma_pago');
			$num_documento = $this->input->post('num_documento');
			$ctp_concepto = $this->input->post('ctp_concepto');
			$ctp_monto = $this->input->post('ctp_monto');
			$pln_id=$this->input->post('pln_id');
			$ctp_banco = $this->input->post('ctp_banco');
			$doc_id = $this->input->post('doc_id');
		// }else{
		// 	$ctp_forma_pago = 10;//cruce de cuentas
		// 	$num_documento = $this->input->post('fac_numero');
		// 	$ctp_concepto = 'CRUCE DE CUENTAS';
		// 	$ctp_monto = $this->input->post('valor_cruce');
		// }
		
		
		$this->form_validation->set_rules('cli_codigo','Codigo Cliente','required');
		$this->form_validation->set_rules('cli_raz_social','Nombre Cliente','required');
		$this->form_validation->set_rules('ctp_fecha_pago','Fecha','required');
		
		// if($cr==1){
			$this->form_validation->set_rules('ctp_forma_pago','Forma de Pago','required');
			$this->form_validation->set_rules('ctp_concepto','Concepto','required');
			$this->form_validation->set_rules('ctp_monto','Valor','required');
			if($conf_as->con_valor==0){
				$this->form_validation->set_rules('pln_descripcion','Cuenta de Proveedor','required');
				$this->form_validation->set_rules('ctp_banco','Cuenta de Pago','required');
			}
		// }else{
		// 	$this->form_validation->set_rules('valor_cruce','Valor','required');
		// }	

		if($this->form_validation->run()){
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
		    $data=array(	
		    				'reg_id'=>$reg_id,
		    				'ctp_fecha_pago'=>$ctp_fecha_pago,
							'ctp_forma_pago'=>$ctp_forma_pago,
							'num_documento'=>$num_documento, 
							'ctp_concepto'=>$ctp_concepto, 
							'ctp_monto'=>$ctp_monto,
							'pln_id'=>$pln_id,
							'ctp_banco'=>$ctp_banco,
							'ctp_fecha'=>date('Y-m-d'),
							'doc_id'=>$doc_id,
							'ctp_estado'=>'1',
							'ctp_secuencial'=>$secuencial_cxp,
							'emp_id'=>$rst_cja->emp_id,
		    );
			
		    $cta_id=$this->ctasxpagar_model->insert($data);

		    //modifica estado reg_nota_credito
		    if($ctp_forma_pago==8){
		    	$nota=$this->reg_nota_credito_model->lista_una_nota($doc_id);
				$rst_pg=$this->ctasxpagar_model->lista_pagos_nota_credito($nota->rnc_id);
				$monto=0;
				if(!empty($rst_pg)){
					$monto = $nota->rnc_total_valor - $rst_pg->ctp_monto;
				}
				if($monto==0){
					$dt_up=array('rnc_estado'=>'4');
		    		$this->reg_nota_credito_model->update($doc_id,$dt_up);
				}
		    	
		    }

		    ///cruce de cuentas
		    // if($cr!=1){
		    // 	$data_cruce=array(	
		    // 				'com_id'=>$fac_id,
		    // 				'cta_fecha_pago'=>$ctp_fecha_pago,
						// 	'cta_forma_pago'=>$ctp_forma_pago,
						// 	'num_documento'=>$numero, 
						// 	'cta_concepto'=>$ctp_concepto, 
						// 	'cta_monto'=>$ctp_monto,
						// 	'cta_fecha'=>date('Y-m-d'),
						// 	'cta_estado'=>'1'
			   //  );
				
			   //  $cta_id=$this->ctasxcobrar_model->insert($data_cruce);
		    	
		    // }
		    if(!empty($cta_id)){
		    	if($conf_as->con_valor==0){
		        	$this->asientos($cta_id);
		        }
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CUENTASXPAGAR',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$num_documento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'ctasxpagar/nuevo/'.$opc_id.'/'.$reg_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'ctasxpagar/nuevo/'.$opc_id.'/'.$reg_id);
			}
		}else{
			$this->nuevo($opc_id,$reg_id);
		}	

	}

	public function traer_factura($num,$id,$emp){
		$rst=$this->ctasxpagar_model->lista_una_factura_cliente($num,$id,$emp);
		if(!empty($rst)){
			$data=array(
						'fac_id'=>$rst->fac_id,
						'fac_numero'=>$rst->fac_numero,
						'fac_total_valor'=>$rst->fac_total_valor,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Cuentas Por Pagar '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ctasxpagar/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

    public function show_pdf($fac_id,$opc_id){
			$rst_fac=$this->reg_factura_model->lista_una_factura($fac_id);
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$data=array(
						'dec'=>$dec,
						'cliente'=>$this->cliente_model->lista_un_cliente($rst_fac->cli_id),
						'credito'=>0,
						'saldo_vencido'=>$this->ctasxpagar_model->lista_saldo_factura($rst_fac->reg_id),
						'cns_pag'=>$this->ctasxpagar_model->lista_pagos_factura($rst_fac->reg_id),
						'cns_det'=>$this->ctasxpagar_model->lista_ctasxpagar($rst_fac->reg_id),
						);

			$this->html2pdf->filename('ctasxpagar_factura.pdf');
			$this->html2pdf->paper('a4', 'landscape');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_ctasxpagar_factura', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));

	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Cuentas por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="ctasxpagar".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function traer_cuenta($id){
		$rst=$this->plan_cuentas_model->lista_un_plan_cuentas_codigo(trim($id));
		if(!empty($rst)){
			$data=array(
						'pln_id'=>$rst->pln_id,
						'pln_descripcion'=>$rst->pln_descripcion,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function asientos($id){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $rst=$this->ctasxpagar_model->lista_una_ctaxpagar($id);
        $rst_cta=$this->plan_cuentas_model->lista_un_plan_cuentas($rst->pln_id);

        $asiento = $asiento =$this->asiento_model->siguiente_asiento();
            
        $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>$rst->ctp_concepto,
                        'con_documento'=>$rst->reg_num_documento,
                        'con_fecha_emision'=>$rst->ctp_fecha_pago,
                        'con_concepto_debe'=>$rst_cta->pln_codigo,
                        'con_concepto_haber'=>$rst->ctp_banco,
                        'con_valor_debe'=>round($rst->ctp_monto, $dec),
                        'con_valor_haber'=>round($rst->ctp_monto, $dec),
                        'mod_id'=>'11',
                        'doc_id'=>$rst->ctp_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
                    );

        $this->asiento_model->insert($data);

    }

    public function buscar_pagos($id,$emp){
		$notas=$this->ctasxpagar_model->lista_notas_credito_cliente($id,$emp);
		$lista="";
		if(!empty($notas)){
			foreach($notas as $nota) {
				$rst_pg=$this->ctasxpagar_model->lista_pagos_nota_credito($nota->rnc_id);
				if(!empty($rst_pg)){
					$monto = $nota->rnc_total_valor - $rst_pg->ctp_monto;
				}else{
					$monto=$nota->rnc_total_valor; 
				}
                
				if($monto!=0){
					$lista.="<tr onclick='traer_pago($nota->rnc_id)'><td><input 	type='checkbox'/></td><td>$nota->rnc_fecha_emision</td><td>$nota->rnc_numero</td><td>$monto</td></tr>";
				}
			}

			$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function traer_pago($id){
		$nota=$this->reg_nota_credito_model->lista_una_nota($id);
		$rst_pg=$this->ctasxpagar_model->lista_pagos_nota_credito($nota->rnc_id);
		if(!empty($rst_pg)){
			$monto = $nota->rnc_total_valor - $rst_pg->ctp_monto;
		}else{
			$monto=$nota->rnc_total_valor; 
		}
		if(!empty($nota)){
			$data=array(
						'rnc_id'=>$nota->rnc_id,
						'rnc_numero'=>$nota->rnc_numero,
						'rnc_total_valor'=>$monto,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}
}
