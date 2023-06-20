<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Cierre_caja extends CI_Controller {

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
		$this->load->model('cierre_caja_model');
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
			$text= trim($this->input->post('txt'));
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$fecha= date('Y-m-d');
			$cns_cierres=$this->cierre_caja_model->lista_cierre_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$fecha= date('Y-m-d');
			$cns_cierres=$this->cierre_caja_model->lista_cierre_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}

			$data=array(
						'permisos'=>$this->permisos,
						'cierres'=>$cns_cierres,
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'txt'=>$text,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'fecha'=>$fecha,
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cierre_caja/lista',$data);
			$modulo=array('modulo'=>'cierre_caja');
			$this->load->view('layout/footer',$modulo);
	}


	
	public function guardar($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$fecha= $this->input->post('fecha');
		$usu_id=$this->session->userdata('s_idusuario');
		$rst_vnd=$this->vendedor_model->lista_un_vendedor_usuario($usu_id);
		if(!empty($rst_vnd->vnd_id)){
			$n_facturas=$this->cierre_caja_model->lista_num_facturas($fecha,$rst_cja->emp_id,$rst_cja->emi_id,$rst_vnd->vnd_id);
			echo $n_facturas->n_factura;
			if(!empty($n_facturas->n_factura)){
				//borra cierre
				$this->cierre_caja_model->delete_cierre($fecha,$rst_cja->emi_id,$rst_vnd->vnd_local);
				$rst_prod=$this->cierre_caja_model->lista_cantidad_productos($fecha,$rst_cja->emi_id,$rst_vnd->vnd_id);
				$rst_sbt=$this->cierre_caja_model->lista_total_subtotal($fecha,$rst_cja->emi_id,$rst_vnd->vnd_id);
				$rst_tnc=$this->cierre_caja_model->lista_total_notacredito($fecha,$rst_cja->emi_id,$rst_vnd->vnd_id);
				$rst_fp=$this->cierre_caja_model->lista_formas_pago($fecha,$rst_cja->emi_id,$rst_vnd->vnd_id);

				//secuencial
				$rst_sec = $this->cierre_caja_model->lista_ultimo_secuencial($rst_cja->emi_id);
				$emisor=$rst_cja->emi_cod_punto_emision;
				if ($emisor >= 10) {
                	$ems = $emisor;
	            } else {
	                $ems = '0' . $emisor;
	            }
	            if(!empty($rst_sec->cie_secuencial)){
	            	$sec = (substr($rst_sec->cie_secuencial, -4) + 1);
	            }else{
	            	$sec=1;
	        	}
	            if ($sec >= 0 && $sec < 10) {
	                $txt = '000';
	            } else if ($sec >= 10 && $sec < 100) {
	                $txt = '00';
	            } else if ($sec >= 100 && $sec < 1000) {
	                $txt = '0';
	            } else if ($sec >= 1000 && $sec < 10000) {
	                $txt = '';
	            }
	            
	            $secuencial = $ems . $txt . $sec;

	            $fac_emitidas = $n_facturas->n_factura;
	            
	            $suma_productos = $rst_prod->suma_cantidad;
	            $suma_total_notcre = $rst_tnc->suma_total_valor_nc;	
	            $suma_subt = $rst_sbt->suma_subtotal + $rst_sbt->suma_descuento;
	            $subtotal = $suma_subt;
	            $descuento = $rst_sbt->suma_descuento;
	            $iva = $rst_sbt->suma_iva;
	            $suma_total_valor = $rst_sbt->suma_total_valor;
	            $suma_tarjeta_credito = $rst_fp->tarjeta_credito;
	            $suma_tarjeta_debito = $rst_fp->tarjeta_debito;
	            $suma_cheque = $rst_fp->cheque;
	            $suma_efectivo = $rst_fp->efectivo;
	            $suma_certificados = $rst_fp->certificados;
	            $suma_bonos = $rst_fp->transferencia;
	            $suma_retencion = $rst_fp->retencion;
	            $suma_not_cre = $rst_fp->nota_credito;
	            $suma_cre = $rst_fp->credito;


	            $data = array(
	                'cie_secuencial'=>$secuencial,
	                'cie_fecha'=>$fecha,
	                'cie_hora'=>date('H:i:s'),
	                'cie_usuario'=>$usu_id,
	                'cie_punto_emision'=>$rst_cja->emi_id,
	                'cie_fac_emitidas'=>$fac_emitidas,
	                'cie_productos_facturados'=>$suma_productos,
	                'cie_subtotal'=>str_replace(',', '', number_format($subtotal, 4)),
	                'cie_descuento'=>str_replace(',', '', number_format($descuento, 4)),
	                'cie_iva'=>str_replace(',', '', number_format($iva, 4)),
	                'cie_total_facturas'=>str_replace(',', '', number_format($suma_total_valor, 4)),
	                'cie_total_notas_credito'=>str_replace(',', '', number_format($suma_total_notcre, 4)),
	                'cie_total_tarjeta_credito'=>str_replace(',', '', number_format($suma_tarjeta_credito, 4)),
	                'cie_total_tarjeta_debito'=>str_replace(',', '', number_format($suma_tarjeta_debito, 4)),
	                'cie_total_cheque'=>str_replace(',', '', number_format($suma_cheque, 4)),
	                'cie_total_efectivo'=>str_replace(',', '', number_format($suma_efectivo, 4)),
	                'cie_total_certificados'=>str_replace(',', '', number_format($suma_certificados, 4)),
	                'cie_total_bonos'=>str_replace(',', '', number_format($suma_bonos, 4)),
	                'cie_total_retencion'=>str_replace(',', '', number_format($suma_retencion, 4)),
	                'cie_total_not_credito'=>str_replace(',', '', number_format($suma_not_cre, 4)),
	                'cie_total_credito'=>str_replace(',', '', number_format($suma_cre, 4)),
	                'cie_estado'=>1,
	                'emp_id'=>$rst_cja->emp_id
	            );

	            if($this->cierre_caja_model->insert($data)){
					$data_aud=array(
									'usu_id'=>$this->session->userdata('s_idusuario'),
									'adt_date'=>date('Y-m-d'),
									'adt_hour'=>date('H:i'),
									'adt_modulo'=>'CIERRE DE CAJA',
									'adt_accion'=>'INSERTAR',
									'adt_campo'=>json_encode($this->input->post()),
									'adt_ip'=>$_SERVER['REMOTE_ADDR'],
									'adt_documento'=>$secuencial,
									'usu_login'=>$this->session->userdata('s_usuario'),
									);
					$this->auditoria_model->insert($data_aud);
					$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				 	$this->editar($secuencial,$opc_id);
				}else{
				 	$this->session->set_flashdata('error','No se pudo guardar');
					redirect(base_url().'cierre_caja/guardar/'.$opc_id);
				}
			}else{
				//no existen facturas
				$this->session->set_flashdata('error','No existen facturas');
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);	 
			}

		}else{
			//Usuario no es vendedor
			$this->session->set_flashdata('error','Usuario no es vendedor');
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
			$data=array(
						'cierre_caja'=>$this->cierre_caja_model->lista_un_cierre_secuencial($id),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'action'=>base_url()."cierre_caja/actualizar/$rst_opc->opc_id",
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cierre_caja/form',$data);
			$modulo=array('modulo'=>'cierre_caja');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('cie_id');
		$cie_camb_nc= $this->input->post('cie_camb_nc');
		$cie_camb_tc = $this->input->post('cie_camb_tc');
		$cie_camb_cheque = $this->input->post('cie_camb_cheque');
		$cie_camb_efectivo = $this->input->post('cie_camb_efectivo');
		$cie_camb_bonos = $this->input->post('cie_camb_bonos');
		$cie_camb_certif = $this->input->post('cie_camb_certif');
		$cie_camb_ret = $this->input->post('cie_camb_ret');
		$cie_camb_not_cre = $this->input->post('cie_camb_not_cre');
		$cie_camb_credito = $this->input->post('cie_camb_credito');
		$cie_secuencial = $this->input->post('cie_secuencial');

		$this->form_validation->set_rules('cie_camb_nc','Forma Pago Tarjeta Credito','required');
		$this->form_validation->set_rules('cie_camb_tc','Forma pago Tarjeta Debito','required');
		$this->form_validation->set_rules('cie_camb_cheque','Forma Pago Cheque','required');
		$this->form_validation->set_rules('cie_camb_efectivo','Forma Pago Efectivo','required');
		$this->form_validation->set_rules('cie_camb_certif','Forma Pago Certificados','required');
		$this->form_validation->set_rules('cie_camb_bonos','Forma Pago Transferencias','required');
		$this->form_validation->set_rules('cie_camb_ret','Forma Pago Retencion','required');
		$this->form_validation->set_rules('cie_camb_not_cre','Forma Pago Nota Credito','required');
		$this->form_validation->set_rules('cie_camb_credito','Forma Pago Credito','required');

		if($this->form_validation->run()){
			$data=array(
						'cie_secuencial'=>$cie_secuencial,
					    'cie_camb_nc'=>$cie_camb_nc,
					    'cie_camb_tc'=>$cie_camb_tc,
					    'cie_camb_cheque'=>$cie_camb_cheque,
					    'cie_camb_efectivo'=>$cie_camb_efectivo,
					    'cie_camb_certif'=>$cie_camb_certif,
					    'cie_camb_bonos'=>$cie_camb_bonos,
					    'cie_camb_ret'=>$cie_camb_ret,
					    'cie_camb_not_cre'=>$cie_camb_not_cre,
					    'cie_camb_credito'=>$cie_camb_credito,
			);	
			
			if($this->cierre_caja_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CIERRE DE CAJA',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$cie_secuencial,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'cierre_caja/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
		}

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$etiqueta='Cierre_caja.pdf';
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Cierre de Caja '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"cierre_caja/show_pdf/$id/$opc_id/$etiqueta",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
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
			$modulo=array('modulo'=>'cierre_caja');
			$this->load->view('layout/footer',$modulo);
		}
    }

    

    public function show_pdf($id,$opc_id,$etiqueta){
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'cierre'=>$this->cierre_caja_model->lista_un_cierre_secuencial($id),
						);
			$this->html2pdf->filename('cierre_caja.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_cierre_caja', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    }  

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Cierre de Caja '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="cierres_caja".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

   
    

   
}
