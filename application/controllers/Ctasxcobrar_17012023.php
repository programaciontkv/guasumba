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
			$credencial=$this->configuracion_model->lista_una_configuracion('26');
           	$cred=explode('&',$credencial->con_valor2);
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
						'doc_mail'=>$cred[3],
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
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$vencer=$this->input->post('vencer');
			$vencido=$this->input->post('vencido');
			$pagado=$this->input->post('pagado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$vencer='';
			$vencido='';
			$pagado='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Cuentas Por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ctasxcobrar/show_pdf/$id/$opc_id",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
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
	
	public  function show_rpt_pdf($opc_id){

		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$vencer=$this->input->post('vencer');
			$vencido=$this->input->post('vencido');
			$pagado=$this->input->post('pagado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$vencer='';
			$vencido='';
			$pagado='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

	    $text= $this->input->post('txt');
		$ids= $this->input->post('tipo');
		$f1= $this->input->post('fec1');
		$f2= $this->input->post('fec2');
		$vencer= $this->input->post('vencer');	
		$vencido= $this->input->post('vencido');
		$pagado= $this->input->post('pagado');		


		 
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Central de Cobranzas '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ctasxcobrar/show_pdf_2/$opc_id/$f1/$f2/$vencer/$vencido/$text/$pagado",
					'permisos'=>$this->permisos,
					'opc_id'=>$rst_opc->opc_id,
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',	
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'ctasxcobrar');
			$this->load->view('layout/footer',$modulo);
		}

			
	}

public function show_pdf_2($opc_id,$f1,$f2,$vencer,$vencido,$text='',$pagado=''){
	    $permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	    $conf=$this->configuracion_model->lista_una_configuracion('2');
	 	$dec=$conf->con_valor;

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

			
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$data=array(
						'permisos'=>$this->permisos,
						'facturas'=>$cns_facturas,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'dec'=>$dec,
						
						);
			$this->load->view('pdf/pdf_ctasxcobrar_rpt',$data);

			// $this->html2pdf->filename('ctasxcobrar_pdf.pdf');
			// $this->html2pdf->paper('a4', 'landscape');
    		// $this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_ctasxcobrar_rpt', $data, true)));
    		// $this->html2pdf->output(array("Attachment" => 0));

	}
	

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Cuentas por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="ctasxcobrar".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function buscar_reporte($id,$opc_id,$reporte){
    	if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$vencer=$this->input->post('vencer');
			$vencido=$this->input->post('vencido');
			$pagado=$this->input->post('pagado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$vencer='';
			$vencido='';
			$pagado='';
		}
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
            
            $fec=$this->input->post('fec2');
            $txt=$this->input->post('txt');
            $tipo ='I';

            switch ($reporte) {
                case '0':
                    $url="pdf_mora_cxc/index/$id/$opc_id/$tipo";
                    break;
                case '1':
                    $url="pdf_estado_cliente/index/$id/$opc_id/$fec/$tipo";
                    break;
                case '2':
                    $url="pdf_saldo_clientes/index/$opc_id/$fec/$txt";
                    break;   
                case '3':
                    $url="pdf_vencidos_cxc/index/$opc_id/$fec/$txt";
                    break;   
                case '4':
                    $url="pdf_mora_cxc/index/$id/$opc_id";
                    break;  
                case '5':
                    $url="pdf_estado_cta_cliente/index/$id/$opc_id/$fec/$tipo";
                    break;
                case '6':
                    $url="pdf_estado_cta_cobros/index/$id/$opc_id/$tipo";
                    break;              
            }

          $data=array(
					'titulo'=>'Reportes Cuentas por Cobrar ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>$url,
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'cuentasxcobrar');
			$this->load->view('layout/footer',$modulo);
                       
    }


    public function envio_doc(){

    	$tipo ='F';

		$id         = $this->input->post('id');
		$mora       = $this->input->post('mora');
		$std_cobros = $this->input->post('std_cobros');
		$sta_1      = $this->input->post('sta_1');
		$sta_2      = $this->input->post('sta_2');
		$fec2       = $this->input->post('fec2'); 
		$correo     = $this->input->post('correo'); 
		$asunto     = $this->input->post('asunto'); 
		$opc_id     = $this->input->post('opc_id'); 

		$credencial=$this->configuracion_model->lista_una_configuracion('26');
		$cliente = $this->cliente_model->lista_un_cliente_cedula($id);
        $cred=explode('&',$credencial->con_valor2);
        $config['smtp_port'] = $cred[1];//'587';
        $config['smtp_host'] = $cred[2];//'mail.tivkas.com';
        $config['smtp_user'] = $cred[3];//'info@tivkas.com';
        $config['smtp_pass'] = $cred[4];//'tvk*36146';
        $config['protocol'] = 'smtp';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['smtp_crypto'] = 'ssl';
        $mensaje = str_replace('-', '<br>', $cred[7]);

        $this->email->initialize($config);

        $this->email->from($cred[3], $cred[5]);
        $correos = str_replace(';',',', strtolower($correo));
        
        $this->email->to($correos);
        $this->email->cc($cred[3]);

         if($mora=='on'){

         	
         	$url= base_url()."pdf_mora_cxc/index/$id/$opc_id/$tipo";
         	$nombre= 'pdfs/estados/mora_cxc_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);
         }
         
         if($std_cobros=='on'){
         	$url=base_url()."pdf_estado_cta_cobros/index/$id/$opc_id/$tipo";
         	$nombre = 'pdfs/estados/estado_cta_cobros_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);
         }

         if($sta_1=='on'){
         	$url=base_url()."pdf_estado_cliente/index/$id/$opc_id/$fec2/$tipo";
         	$nombre = 'pdfs/estados/estado_cliente_cxc_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);	
         }

         if($sta_2=='on'){
         	$url=base_url()."pdf_estado_cta_cliente/index/$id/$opc_id/$fec2/$tipo";
         	$nombre='pdfs/estados/estado_cta_cliente_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);
         }


        
        $emp = $this->empresa_model->lista_una_empresa(1);
        $img_logo=base_url().'imagenes/'.$emp->emp_logo;
        $img_mail=base_url().'imagenes/mail2.png';
        $img_whatsapp=base_url().'imagenes/whatsapp2.png';
        $img_telefono=base_url().'imagenes/telefono2.png';


        $datos_sms = "<html>
              <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                 <style>
                      td {
                          color: #070707;
                          font-family: Arial, Helvetica, sans-serif;
                          font-size: 14px;
                          text-align: center;
                          font-weight: bolder;
                      }
                       .mensaje {
						color: #070707;
						font-family: Arial, Helvetica, sans-serif;
						font-size: 14px;
						justify-content: left;
						// font-weight: bolder;
          			 }


                      
                  </style>
             </head>
             <body>
               <table width='100%'>
                
                  <tr><td><img  height='150px' width='300px' src='$img_logo'/></td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr class='mensaje'><td class='mensaje' > <p class='mensaje'>$mensaje </p>  </td></tr>
                  <tr style='with:60px' ><td>$cliente->cli_raz_social, </td></tr>
                  
                  
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
                      
                        <img src='$img_mail' width='20px'><a href='https://www.tikvas.com/'>www.tikvas.com</a> 
                        <img src='$img_whatsapp' width='20px'> +593 999404989 / +593 991815559
                        
                      </td>
                  </tr>
                  <tr><td style='font-size:10px'>Copyright &copy; 2022 Todos los derechos reservados <a href='https://www.tikvas.com/'>TIKVASYST S.A.S</a></td></tr>
               </table>
             </body>
           </html>";

        $this->email->subject($asunto);
        $this->email->message(utf8_decode($datos_sms));

        if($this->email->send()){
            
           echo "Documentos enviados Correctamente";  
            
        }else{
            echo "no enviado";
        }

	
}
public function crear_Archivo($url,$nombre){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
		'Cookie: ci_session=3icgoits0kct5avtlimsto47vetdjo74'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$this->email->attach($nombre);	
		echo $response;
	}

	}


