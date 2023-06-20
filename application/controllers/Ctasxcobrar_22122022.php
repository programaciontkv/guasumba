<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctasxcobrar extends CI_Controller {

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
		$this->load->model('ctasxcobrar_model');
		$this->load->model('ctasxpagar_model');
		$this->load->model('factura_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
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
				$cns_facturas=$this->ctasxcobrar_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $vencido=='on' && $pagado==''){
				$cns_facturas=$this->ctasxcobrar_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $pagado=='on' && $vencido==''){
				$cns_facturas=$this->ctasxcobrar_model->lista_vencer_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencido=='on' && $pagado=='on' && $vencer==''){
				$cns_facturas=$this->ctasxcobrar_model->lista_vencido_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $vencido=='' && $pagado==''){
				$cns_facturas=$this->ctasxcobrar_model->lista_vencer($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='' && $vencido=='on' && $pagado==''){
				$cns_facturas=$this->ctasxcobrar_model->lista_vencido($text,$f1,$f2,$rst_cja->emp_id);
			}else if($pagado=='on'){
				$cns_facturas=$this->ctasxcobrar_model->lista_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}
		}else{
			$text= '';
			$f1= '1900-01-01';
			$f2= date('Y-m-d');
			// $cns_facturas=$this->ctasxcobrar_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
			$cns_facturas=$this->ctasxcobrar_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
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
			$this->load->view('ctasxcobrar/lista',$data);
			$modulo=array('modulo'=>'ctasxcobrar');
			$this->load->view('layout/footer_bodega',$modulo);
	}


	public function nuevo($opc_id,$fac_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$rst_fac=$this->factura_model->lista_una_factura($fac_id);

		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$cheque=$this->cheque_model->lista_credito_cliente($rst_fac->cli_id,$rst_fac->emp_id);
			$cdt=round($cheque->monto,$dec)-round($cheque->cobro,$dec);
			$data=array(
						'dec'=>$dec,
						'formas_pago'=>$this->forma_pago_model->lista_formas_pago_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'cliente'=>$this->cliente_model->lista_un_cliente($rst_fac->cli_id),
						'cheque'=>$cdt,
						'saldo_vencido'=>$this->ctasxcobrar_model->lista_saldo_factura($rst_fac->fac_id),
						'ctasxcob'=> (object) array(
											'cta_fecha_pago'=>date('Y-m-d'),
											'cta_forma_pago'=>'0',
					                        'num_documento'=>'',
					                        'cta_concepto'=>'',
					                        'cta_monto'=>'',
					                        'fac_id'=>$fac_id,
					                        'chq_id'=>'',
					                        'fac_numero'=>'',
					                        'fac_total_valor'=>'',
					                        'valor_cruce'=>'',
					                        'emp_id'=>$rst_fac->emp_id,
					                        'numero'=>$rst_fac->fac_numero,
					                        
										),
						'cns_pag'=>$this->ctasxcobrar_model->lista_pagos_factura($rst_fac->fac_id),
						'cns_det'=>$this->ctasxcobrar_model->lista_ctasxcobrar($rst_fac->fac_id),
						'action'=>base_url().'ctasxcobrar/guardar/'.$opc_id.'/1',
						'action2'=>base_url().'ctasxcobrar/guardar/'.$opc_id.'/2',
						'cancelar2'=>base_url().'ctasxcobrar/nuevo/'.$rst_opc->opc_id.'/'.$fac_id,
						);
			$this->load->view('ctasxcobrar/form',$data);
			$modulo=array('modulo'=>'ctasxcobrar');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id,$cr){
		
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		$fac_id = $this->input->post('fac_id');
		$cta_fecha_pago = $this->input->post('cta_fecha_pago');
		$reg_id = $this->input->post('reg_id');
		$numero = $this->input->post('numero');
		$chq_id =  $this->input->post('chq_id');
		if($cr==1){
			$cta_forma_pago = $this->input->post('cta_forma_pago');
			$num_documento = $this->input->post('num_documento');
			$cta_concepto = $this->input->post('cta_concepto');
			$cta_monto = $this->input->post('cta_monto');
		}else{
			$cta_forma_pago = 10;//cruce de cuentas
			$num_documento = $this->input->post('fac_numero');
			$cta_concepto = 'CRUCE DE CUENTAS';
			$cta_monto = $this->input->post('valor_cruce');
			$chq_id=0;
		}
		
		
		$this->form_validation->set_rules('cli_codigo','Codigo Cliente','required');
		$this->form_validation->set_rules('cli_raz_social','Nombre Cliente','required');
		$this->form_validation->set_rules('cta_fecha_pago','Fecha','required');
		if($cr==1){
			$this->form_validation->set_rules('cta_forma_pago','Forma de Pago','required');
			$this->form_validation->set_rules('cta_concepto','Concepto','required');
			$this->form_validation->set_rules('cta_monto','Valor','required');
		}else{
			$this->form_validation->set_rules('valor_cruce','Valor','required');
		}	

		if($this->form_validation->run()){
			
		    $data=array(	
		    				'com_id'=>$fac_id,
		    				'cta_fecha_pago'=>$cta_fecha_pago,
							'cta_forma_pago'=>$cta_forma_pago,
							'num_documento'=>$num_documento, 
							'cta_concepto'=>$cta_concepto, 
							'cta_monto'=>$cta_monto,
							'cta_fecha'=>date('Y-m-d'),
							'cta_estado'=>'1',
							'chq_id'=>$chq_id
		    );
			
		    $cta_id=$this->ctasxcobrar_model->insert($data);

		    ///cruce de cuentas
		    if($cr!=1){
		    	$data_cruce=array(	
		    				'reg_id'=>$reg_id,
		    				'ctp_fecha_pago'=>$cta_fecha_pago,
							'ctp_forma_pago'=>$cta_forma_pago,
							'num_documento'=>$numero, 
							'ctp_concepto'=>$cta_concepto, 
							'ctp_monto'=>$cta_monto,
							'ctp_fecha'=>date('Y-m-d'),
							'ctp_estado'=>'1'
			    );
				
			    $cta_id=$this->ctasxpagar_model->insert($data_cruce);
		    	
		    }

		    ///cobrar cheque
		    if($chq_id!=0){
		    	$cheque=$this->cheque_model->lista_un_cheque($chq_id);
				$chq_monto=$cheque->chq_monto;
				$chq_cobro=$cheque->chq_cobro+$cta_monto;
				$chq_saldo=round($chq_monto,$dec)-round($chq_cobro,$dec);
				if($chq_saldo>0){
					$chq_estado=8;
				}else{
					$chq_estado=9;
				}
				$data_chq=array(
								'chq_cobro'=>$chq_cobro,
								'chq_estado'=>$chq_estado,
								);	
				$chq=$this->cheque_model->update($chq_id,$data_chq);
		    }

		    if(!empty($cta_id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CUENTASXCOBRAR',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$num_documento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'ctasxcobrar/nuevo/'.$opc_id.'/'.$fac_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'ctasxcobrar/nuevo/'.$opc_id.'/'.$fac_id);
			}
		}else{
			$this->nuevo($opc_id,$fac_id);
		}	

	}

	public function traer_factura($num,$id,$emp){
		$rst=$this->ctasxcobrar_model->lista_una_factura_cliente($num,$id,$emp);
		if(!empty($rst)){
			$data=array(
						'reg_id'=>$rst->reg_id,
						'reg_num_documento'=>$rst->reg_num_documento,
						'reg_total'=>$rst->reg_total,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function buscar_cobros($id,$tip,$emp){
		$cobros=$this->cheque_model->lista_cheques_tip_cliente($tip,$id,$emp);
		$lista="";
		if(!empty($cobros)){
			foreach ($cobros as $cobro) {
				$monto=$cobro->chq_monto-$cobro->chq_cobro;
				$lista.="<tr onclick='traer_cobro($cobro->chq_id)'><td><input type='checkbox'/></td><td>$cobro->chq_fecha</td><td>$cobro->chq_numero</td><td>$monto</td></tr>";
			}

			$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function traer_cobro($id){
		$cobro=$this->cheque_model->lista_un_cheque($id);
		
		if(!empty($cobro)){
			$data=array(
						'chq_id'=>$cobro->chq_id,
						'chq_numero'=>$cobro->chq_numero,
						'chq_monto'=>$cobro->chq_monto,
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
					'titulo'=>'Cuentas Por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ctasxcobrar/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

    public function show_pdf($fac_id,$opc_id){
			$rst_fac=$this->factura_model->lista_una_factura($fac_id);
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$data=array(
						'dec'=>$dec,
						'cliente'=>$this->cliente_model->lista_un_cliente($rst_fac->cli_id),
						'credito'=>0,
						'saldo_vencido'=>$this->ctasxcobrar_model->lista_saldo_factura($rst_fac->fac_id),
						'cns_pag'=>$this->ctasxcobrar_model->lista_pagos_factura($rst_fac->fac_id),
						'cns_det'=>$this->ctasxcobrar_model->lista_ctasxcobrar($rst_fac->fac_id),
						);

			$this->html2pdf->filename('ctasxcobrar_factura.pdf');
			$this->html2pdf->paper('a4', 'landscape');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_ctasxcobrar_factura', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));

	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Cuentas por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="ctasxcobrar".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }
}
