<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cheque extends CI_Controller {

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
		$this->load->model('cheque_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('ctasxcobrar_model');
		$this->load->model('factura_model');
		$this->load->model('cliente_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('asiento_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");
		$this->load->model('bancos_cajas_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('configuracion_cuentas_model');

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
			$cns_cheques=$this->cheque_model->lista_cheque_buscador2($text,$f1,$f2,$rst_cja->emp_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_cheques=$this->cheque_model->lista_cheque_buscador2($text,$f1,$f2,$rst_cja->emp_id);
		}

			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;

			$data=array(
						'permisos'=>$this->permisos,
						'cheques'=>$cns_cheques,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'txt'=>$text,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'dec'=>$dec

			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cheque/lista',$data);
			$modulo=array('modulo'=>'cheque');
			$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			// $b= $this->bancos_cajas_model->lista_bancos_cajas_estado_2('1');
			// var_dump($b);
			$data=array(
						'dec'=>$dec,
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'bancos_cajas'=>$this->bancos_cajas_model->lista_bancos_cajas_estado_2('1'),
						'bancos'=>$this->bancos_tarjetas_model->lista_bancos_tarjetas_tipo('0','1'),
						'cheque'=> (object) array(
											'emp_id'=>$rst_cja->emp_id,
											'chq_recepcion'=>date('Y-m-d'),
											'chq_fecha'=>date('Y-m-d'),
											'chq_nombre'=>'',
					                        'chq_banco'=>'',
					                        'chq_numero'=>'',
					                        'chq_monto'=>'0',
					                        'cli_id'=>'',
					                        'cli_raz_social'=>'',
					                        'chq_id'=>'',
					                        'chq_tipo_doc'=>'',
					                        'chq_concepto'=>'',
					                        'chq_estado'=>'7',
					                        'chq_estado_cheque'=>'11',
					                        'chq_cuenta'=>'',
					                        'pln_descripcion'=>''
					                        
										),
						'action'=>base_url().'cheque/guardar/'.$rst_opc->opc_id,
						);
			$this->load->view('cheque/form',$data);
			$modulo=array('modulo'=>'cheque');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){

		$emp_id            = $this->input->post('emp_id');
		$chq_recepcion     = $this->input->post('chq_recepcion');
		$chq_fecha         = $this->input->post('chq_fecha');
		$chq_tipo_doc      = $this->input->post('chq_tipo_doc');
		$cli_id            = $this->input->post('cli_id');
		$chq_nombre        = $this->input->post('chq_nombre');
		$chq_concepto      = $this->input->post('chq_concepto');
		$chq_banco         = $this->input->post('chq_banco');
		$chq_numero        = $this->input->post('chq_numero');
		$chq_monto         = $this->input->post('chq_monto');
		$chq_estado        = $this->input->post('chq_estado');
		$chq_estado_cheque = $this->input->post('chq_estado_cheque');
		$chq_cuenta        = $this->input->post('chq_cuenta');

		
		$this->form_validation->set_rules('chq_recepcion','Fecha de Recepcion','required');
		$this->form_validation->set_rules('chq_fecha','Fecha de Cobro','required');
		$this->form_validation->set_rules('chq_tipo_doc','Tipo Documento','required');
		$this->form_validation->set_rules('cli_id','Nombre Cliente','required');
		$this->form_validation->set_rules('cli_raz_social','Nombre Cliente','required');
		$this->form_validation->set_rules('chq_tipo_doc','Tipo Documento','required');
		$this->form_validation->set_rules('chq_nombre','Descripcion del Documento','required');
		$this->form_validation->set_rules('chq_concepto','Concepto','required');
		$this->form_validation->set_rules('chq_numero','No.Documento','required');
		$this->form_validation->set_rules('chq_monto','Monto','required');
		$this->form_validation->set_rules('chq_cuenta','Cuenta contable','required');


		if($this->form_validation->run()){
			$rst_sec=$this->cheque_model->lista_secuencial();
			if (empty($rst_sec)) {
		        $sec = 1;
		    } else {
		        $sec = $rst_sec->chq_secuencial + 1;
		    }
		    if ($sec >= 0 && $sec < 10) {
		        $tx = '0000000';
		    } else if ($sec >= 10 && $sec < 100) {
		        $tx = '000000';
		    } else if ($sec >= 100 && $sec < 1000) {
		        $tx = '00000';
		    } else if ($sec >= 1000 && $sec < 10000) {
		        $tx = '0000';
		    } else if ($sec >= 10000 && $sec < 100000) {
		        $tx = '000';
		    } else if ($sec >= 100000 && $sec < 1000000) {
		        $tx = '00';
		    } else if ($sec >= 1000000 && $sec < 10000000) {
		        $tx = '0';
		    } else if ($sec >= 10000000 && $sec < 100000000) {
		        $tx = '';
		    }
		    $chq_secuencial = $tx . $sec;
			
		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'cli_id'=>$cli_id,
		    				'chq_secuencial'=>$chq_secuencial,
		    				'chq_recepcion'=>$chq_recepcion,
							'chq_fecha'=>$chq_fecha,
							'chq_tipo_doc'=>$chq_tipo_doc, 
							'chq_nombre'=>$chq_nombre, 
							'chq_concepto'=>$chq_concepto,
							'chq_banco'=>$chq_banco,
							'chq_numero'=>$chq_numero,
							'chq_monto'=>$chq_monto,
							'chq_estado'=>$chq_estado,
							'chq_estado_cheque'=>$chq_estado_cheque,
							'chq_cuenta'=>$chq_cuenta
		    );
			
		    if($this->cheque_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONTROL DE COBROS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$num_documento,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'cheque/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'dec'=>$dec,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cheque'=>$this->cheque_model->lista_un_cheque3($id),
						'action'=>base_url().'cheque/actualizar/'.$opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cheque/form',$data);
			$modulo=array('modulo'=>'cheque');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('chq_id');
		$emp_id = $this->input->post('emp_id');
		$chq_recepcion = $this->input->post('chq_recepcion');
		$chq_fecha = $this->input->post('chq_fecha');
		$chq_tipo_doc = $this->input->post('chq_tipo_doc');
		$cli_id = $this->input->post('cli_id');
		$chq_nombre = $this->input->post('chq_nombre');
		$chq_concepto = $this->input->post('chq_concepto');
		$chq_banco = $this->input->post('chq_banco');
		$chq_numero = $this->input->post('chq_numero');
		$chq_monto = $this->input->post('chq_monto');
		$chq_estado = $this->input->post('chq_estado');
		$chq_estado_cheque = $this->input->post('chq_estado_cheque');
		
		$this->form_validation->set_rules('chq_recepcion','Fecha de Recepcion','required');
		$this->form_validation->set_rules('chq_fecha','Fecha de Cobro','required');
		$this->form_validation->set_rules('cli_id','Nombre Cliente','required');
		$this->form_validation->set_rules('chq_tipo_doc','Tipo Documento','required');
		$this->form_validation->set_rules('chq_nombre','Descripcion del Documento','required');
		$this->form_validation->set_rules('chq_concepto','Concepto','required');
		$this->form_validation->set_rules('chq_monto','Monto $','required');
			

		if($this->form_validation->run()){
			
		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'cli_id'=>$cli_id,
		    				'chq_recepcion'=>$chq_recepcion,
							'chq_fecha'=>$chq_fecha,
							'chq_tipo_doc'=>$chq_tipo_doc, 
							'chq_nombre'=>$chq_nombre, 
							'chq_concepto'=>$chq_concepto,
							'chq_banco'=>$chq_banco,
							'chq_numero'=>$chq_numero,
							'chq_monto'=>$chq_monto,
							'chq_estado'=>$chq_estado,
							'chq_estado_cheque'=>$chq_estado_cheque
		    );
				
			if($this->cheque_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONTROL DE COBROS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'cheque/editar'.$id.'/'.$opc_id);
			}
		}else{
			redirect(base_url().'cheque/editar'.$id.'/'.$opc_id);
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




	public function cobrar($id,$opc_id,$cli){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		$cuenta_anterior=$this->plan_cuentas_model->consulta_cuenta($id);
		if (empty($cuenta_anterior)) {
			$cuenta_anterior=0;
		}else
		{
			$cuenta_anterior=$cuenta_anterior->pln_codigo;
		}
		if($this->permisos->rop_actualizar){
			$data=array(
						'dec'=>$dec,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'cuentas'=>$this->plan_cuentas_model->lista_plan_mov(),
						'cuenta_anterior'=>$cuenta_anterior,
						'cheque'=>$this->cheque_model->lista_un_cheque3($id),
						'facturas'=>$this->cheque_model->lista_facturas_cliente($cli),
						'action'=>base_url().'cheque/guardar_cobros/'.$opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cheque/form_cobrar',$data);
			$modulo=array('modulo'=>'cheque');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function guardar_cobros($opc_id){
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

		$id          = $this->input->post('chq_id');
		$count       = $this->input->post('count');
		$chq_fecha   = $this->input->post('chq_fecha');
		$chq_tipo_doc= $this->input->post('chq_tipo_doc');
		$chq_numero  = $this->input->post('chq_numero');
		$chq_concepto= $this->input->post('chq_concepto');
		$saldo       = $this->input->post("chq_cta_cantidad");
		$estado      = $this->input->post("chq_estado");
		$chq_cuenta  = $this->input->post("chq_cuenta");
		$ct_abono    = $this->input->post("chq_cta_abono");
		$chq_monto   = $this->input->post("chq_monto");
		$t_cobro     = $this->input->post("t_cobro");

		$n=0;
		$t_cobro=0;
		$total=0;
		$emp =0;
		$asi = $this->siguiente_asiento();
		$fecha_emision =  date('Y-m-d');

		
		

		while($n<$count){
			$n++;
			$fac_id=$this->input->post("fac_id$n");
			$cobro=$this->input->post("cobro$n");
			$pag_id        =  $this->input->post("pag_id$n");
			if($cobro>0){
			    $data=array(	
			    				'com_id'=>$fac_id,
			    				'cta_fecha_pago'=>$chq_fecha,
								'cta_forma_pago'=>$chq_tipo_doc,
								'num_documento'=>$chq_numero, 
								'cta_concepto'=>$chq_concepto, 
								'cta_monto'=>$cobro,
								'pag_id'=>$pag_id,
								'cta_fecha'=>date('Y-m-d'),
								'chq_id'=>$id,
								'cta_estado'=>'1'
			    );
			    $t_cobro+=$cobro;
			    $this->ctasxcobrar_model->insert($data);

			}
		    
		}

		$n=0;
		///asiento abono si es que lo hay 
		
		if($estado==7){
		
	  	while($n<$count){
			$n++;
			$fac_id        =  $this->input->post("fac_id$n");
			$cli_id        =  $this->input->post("cli_id");
			$pag_id        =  $this->input->post("pag_id$n");
			$rst           =  $this->factura_model->lista_una_factura($fac_id);
			$extra 		   =  $this->cliente_model->lista_un_cliente($cli_id);
			if($extra->cli_tipo_cliente==0){
				$cuenta = $this->configuracion_cuentas_model->lista_cta_config(1);
			}else{
				$cuenta = $this->configuracion_cuentas_model->lista_cta_config(2);
			}
			$fac_num       =  $this->input->post("fac_num$n");
			$cobro         =  $this->input->post("cobro$n");
			if($cobro>0){
			$total+=$cobro;
			}
		}
		///asiento banco si esta disponible


		$asiento = array(
			     'con_asiento'       =>  $asi,
			     'con_concepto'      =>  $chq_concepto,
			     'con_documento'     =>  $id, //doc
			     'con_fecha_emision' =>  $fecha_emision, //doc
			     'con_concepto_debe' =>  $chq_cuenta, //doc
			     'con_concepto_haber'=>  '', //doc
			     'con_valor_debe'    =>  $chq_monto, //doc
			     'con_valor_haber'   =>  0, //doc
			     'doc_id'            =>  $pag_id , //doc
			     'con_pago_nombre'   =>  '', //con_pago_nombre
			     'cli_id'            =>  0, //doc
			     'mod_id'            =>  10, //doc
			     'emp_id'=>$rst->emp_id,
			      );

	  	$this->asiento_model->insert($asiento);


	  	/////asiento de abono

			
		if ($saldo>0) {
			$asiento = array(
			     'con_asiento'       =>  $asi,
			     'con_concepto'      =>  $chq_concepto,
			     'con_documento'     =>  $id, //doc
			     'con_fecha_emision' =>  $fecha_emision, //doc
			     'con_concepto_debe' =>  '', //doc
			     'con_concepto_haber'=>  $ct_abono , //doc
			     'con_valor_debe'    =>  0, //doc
			     'con_valor_haber'   =>  $saldo, //doc
			     'doc_id'            =>  0, //doc
			     'cli_id'            =>  0, //doc
			     'con_pago_nombre'   =>  '', //con_pago_nombre
			     'mod_id'            =>  10, //doc
			     'emp_id'=>$rst->emp_id,
			      );

			$this->asiento_model->insert($asiento);
		}

			
		}elseif ($estado==8) {

			///asiento facturas

		while($n<$count){
			$n++;
			$fac_id        =  $this->input->post("fac_id$n");
			$cli_id        =  $this->input->post("cli_id");
			$pag_id        =  $this->input->post("pag_id$n");
			$rst           =  $this->factura_model->lista_una_factura($fac_id);
			$extra 		   =  $this->cliente_model->lista_un_cliente($cli_id);
			if($extra->cli_tipo_cliente==0){
				$cuenta = $this->configuracion_cuentas_model->lista_cta_config(1);	
			}else{
				$cuenta = $this->configuracion_cuentas_model->lista_cta_config(2);
			}
			$fac_num       =  $this->input->post("fac_num$n");
			$cobro         =  $this->input->post("cobro$n");
			if($cobro>0){
			$total+=$cobro;
			}
			if (!empty($rst)) {
				$emp = $rst->emp_id;
			}
			
		}

			////asiento si es que esta semi cobrado
		if ($t_cobro >0 ) {
			$asiento = array(
			     'con_asiento'       =>  $asi,
			     'con_concepto'      =>  $chq_concepto,
			     'con_documento'     =>  $id, //doc
			     'con_fecha_emision' =>  $fecha_emision, //doc
			     'con_concepto_debe' =>  $ct_abono , //doc
			     'con_concepto_haber'=>  '', //doc
			     'con_valor_debe'    =>  $t_cobro  , //doc
			     'con_valor_haber'   =>  0, //doc
			     'doc_id'            =>  $pag_id, //doc
			     'cli_id'            =>  0, //doc
			     'con_pago_nombre'   =>  '', //con_pago_nombre
			     'mod_id'            =>  10, //doc
			     'emp_id'=>$emp,
			      );

	  	$this->asiento_model->insert($asiento);
		}
		
		}

		$n=0;
		////asiento pago
		while($n<$count){
			$n++;
			$fac_id        =  $this->input->post("fac_id$n");
			$cli_id        =  $this->input->post("cli_id");
			$fac_num       =  $this->input->post("fac_num$n");
			$cobro         =  $this->input->post("cobro$n");
			$pag_id        =  $this->input->post("pag_id$n");
			$rst           =  $this->factura_model->lista_una_factura($fac_id);
			if($cobro>0){
			$asiento = array(
			     'con_asiento'       =>  $asi,
			     'con_concepto'      =>  $chq_concepto,
			     'con_documento'     =>  $id, //doc
			     'con_fecha_emision' =>  $fecha_emision, //doc
			     'con_concepto_debe' =>  '', //doc
			     'con_concepto_haber'=>  $cuenta->cod, //doc
			     'con_valor_debe'    =>  0, //doc
			     'con_valor_haber'   =>  $cobro, //doc
			     'doc_id'            =>  $pag_id, //doc
			     'con_pago_nombre'   =>  '', //con_pago_nombre
			     'cli_id'            =>  $cli_id, //doc
			     'mod_id'            =>  10, //doc
			     'emp_id'=>$rst->emp_id,
			      );

			$this->asiento_model->insert($asiento);
			}
		}
		
		///////////    
			$cheque=$this->cheque_model->lista_un_cheque($id);
			$chq_monto=$cheque->chq_monto;
			$chq_cobro=$cheque->chq_cobro+$t_cobro;
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
			if($this->cheque_model->update($id,$data_chq)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONTROL DE COBROS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$id,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				//redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);

				$this->show_frame($id,$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'cheque/editar'.$id.'/'.$opc_id);
			}
			
	}

	public function cambiar_estado($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		$cantidad=$this->cheque_model->listas_ctas_pagadas_con($id);
		if (!empty($cantidad)) {
			$cant=$cantidad->n;
		}else{
			$cant=0;
		}
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'dec'=>$dec,
						'facturas'=>$this->cheque_model->listas_ctas_pagadas($id),
						'cantidad'=>$cant,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'cheque'=>$this->cheque_model->lista_un_cheque3($id),
						'action'=>base_url().'cheque/actualizar_estado/'.$opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cheque/form_estado',$data);
			$modulo=array('modulo'=>'cheque');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}
	
	public function actualizar_estado($opc_id){
		
		$id = $this->input->post('chq_id');
		$chq_estado_cheque = $this->input->post('chq_estado_cheque');
		$chq_est_observacion = $this->input->post('chq_est_observacion');
		
		    $data=array(	
		    				'chq_estado_cheque'=>$chq_estado_cheque,
		    				'chq_est_observacion'=>$chq_est_observacion
		    );
				
			if($this->cheque_model->update($id,$data)){
				if($chq_estado_cheque==12 || $chq_estado_cheque==3){
					$data_cxc=array('cta_estado'=>'3');
					$data_est=array('chq_estado'=>'3');
					$this->cheque_model->update_ctaxcobrar($id,$data_cxc);		
					$this->cheque_model->update($id,$data_est);	
					///anular asientos de pago
					$asientos = $this->asiento_model->asientos_pago($id);
					$gr='';


					foreach ($asientos as $as) {

					if($gr!=$as->con_asiento){
						$asi = $this->siguiente_asiento();
					}
					$asiento = array(
					     'con_asiento'       =>  $asi,
					     'con_concepto'      =>  'ANULACION DE COBROS',
					     'con_documento'     =>  $as->con_documento, //doc
					     'con_fecha_emision' =>  $as->con_fecha_emision, //doc
					     'con_concepto_debe' =>  $as->con_concepto_haber, //doc
					     'con_concepto_haber'=>  $as->con_concepto_debe, //doc
					     'con_valor_debe'    =>  $as->con_valor_haber, //doc
					     'con_valor_haber'   =>  $as->con_valor_debe, //doc
					     'doc_id'            =>  $as->doc_id, //doc
					     'con_pago_nombre'   =>  '', //con_pago_nombre
					     'cli_id'            =>  $as->cli_id, //doc
					     'mod_id'            =>  10, //doc
					     'emp_id'            =>  $as->emp_id,
					 );
						$gr = $as->con_asiento;

						$this->asiento_model->insert($asiento);
					}

				}
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONTROL DE COBROS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'cheque/editar'.$id.'/'.$opc_id);
			}
			
	} 

	public function cambiar_est($opc_id){
		
		$id = $this->input->post('chq_id');
		$chq_estado_cheque = $this->input->post('chq_estado_cheque');
		$chq_est_observacion = $this->input->post('chq_est_observacion');
		
		    $data=array(	
		    				'chq_estado_cheque'=>$chq_estado_cheque,
		    				'chq_est_observacion'=>$chq_est_observacion
		    );
				
			if($this->cheque_model->update($id,$data)){
				if($chq_estado_cheque==12 || $chq_estado_cheque==3){
					$data_cxc=array('cta_estado'=>'3');
					$data_est=array('chq_estado'=>'3');
					$this->cheque_model->update_ctaxcobrar($id,$data_cxc);		
					$this->cheque_model->update($id,$data_est);	
					///anular asientos de pago
					$asientos = $this->asiento_model->asientos_pago($id);
					$gr='';


					foreach ($asientos as $as) {

					if($gr!=$as->con_asiento){
						$asi = $this->siguiente_asiento();
					}
					$asiento = array(
					     'con_asiento'       =>  $asi,
					     'con_concepto'      =>  'ANULACION DE COBROS',
					     'con_documento'     =>  $as->con_documento, //doc
					     'con_fecha_emision' =>  $as->con_fecha_emision, //doc
					     'con_concepto_debe' =>  $as->con_concepto_haber, //doc
					     'con_concepto_haber'=>  $as->con_concepto_debe, //doc
					     'con_valor_debe'    =>  $as->con_valor_haber, //doc
					     'con_valor_haber'   =>  $as->con_valor_debe, //doc
					     'doc_id'            =>  $as->doc_id, //doc
					     'con_pago_nombre'   =>  '', //con_pago_nombre
					     'cli_id'            =>  $as->cli_id, //doc
					     'mod_id'            =>  10, //doc
					     'emp_id'            =>  $as->emp_id,
					 );
						$gr = $as->con_asiento;

						$this->asiento_model->insert($asiento);
					}

				}
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CONTROL DE COBROS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$id,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				echo "1";
			}else{
				echo "0";
			}
			
	} 

	public function eliminar($id,$opc_id){

		if($this->permisos->rop_eliminar){
			if($this->cheque_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CHEQUES',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$id,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'cheque/'.$opc_id;
				redirect(base_url().'cheque/'.$opc_id);
			}
		}else{
			redirect(base_url().'inicio');
			
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
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Control de Cobros '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"cheque/show_pdf/$id/$opc_id",
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
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

    public function show_pdf($id,$opc_id){
			$cns_cxc=$this->ctasxcobrar_model->lista_ctasxcobrar_cheque2($id);
			$cxc=array();
			foreach ($cns_cxc as $rst_cxc) {
				$pago=(object) array('factura'=>$rst_cxc->fac_numero,
							'fecha_pago'=>$rst_cxc->cta_fecha_pago,
							'valor'=>$rst_cxc->cta_monto,
							'concepto'=>$rst_cxc->cta_concepto,
							'asiento'=>$rst_cxc->con_asiento,
								);
				array_push($cxc, $pago);
			}
			
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$emp = $this->empresa_model->lista_una_empresa(1);
			$dec=$conf->con_valor;
			$data=array(
						'dec'=>$dec,
						'pagos'=>$cxc,
						'cheque'=>$this->cheque_model->lista_un_cheque3($id),
						'logo' => $emp->emp_logo
						);
			
			$this->html2pdf->filename('cheque_factura.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_pagos_cheque', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));

	}

	public function excel($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Control de Cobros '.ucfirst(strtolower($rst_cja->emp_nombre));
    	$file="control_cobros".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

   


function siguiente_asiento() {
        
        $rst = $this->asiento_model->ultimo_asiento();

        if (!empty($rst)) {
            $sec = (substr($rst->con_asiento, -10) + 1);
            $n_sec = substr($rst->con_asiento, 0, (12 - strlen($sec))) . $sec;
        } else {
            $n_sec = 'AS0000000001';
        }
        return $n_sec;
        
    }
    public function load_codigo($id){

		$cta = $this->plan_cuentas_model->lista_un_plan_cuentas_codigo($id);
        echo $cta->pln_id . '&' . $cta->pln_codigo . '&' . $cta->pln_descripcion;
        
	}

	public function traer_banco($id){
		$rst=$this->forma_pago_model->lista_una_forma_pago_tipo($id);
		
		if(!empty($rst)){

			$lista="<option value='0'>SELECCIONE</option>";
			$cns=$this->bancos_tarjetas_model->lista_bancos_tarjetas_plazo('0',$rst->fpg_tipo,'1');
			if(!empty($cns)){
				foreach ($cns as $rst_plz) {
					$lista.="<option value='$rst_plz->btr_id'>$rst_plz->btr_descripcion</option>";
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

	public function buscar_cliente($txt){

	
	$lista=" <tr>   <th></th>
                    <th>Identificación</th>
                    <th>Nombres y Apellidos</th>
                  </tr>";
	$cns=$this->cliente_model->lista_un_cliente2($txt);
	$n=1;
	
	if(!empty($cns)){

		foreach ($cns as $rst) {
			$n++;
			$nm = $rst->cli_raz_social;
			$lista.="<tr ><td><input type='button' class='btn btn-success' value='&#8730;' onclick=" . "traer_cliente('$rst->cli_ced_ruc')" . " /></td><td>$rst->cli_ced_ruc</td><td>$nm</td></tr>";
		}
	$data=array(
				'lista'=>$lista,
				);
	echo json_encode($data);
	}else{
		echo "";
	}
	
}


}


