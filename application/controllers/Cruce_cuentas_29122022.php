<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cruce_cuentas extends CI_Controller {

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
		$this->load->model('configuracion_model'); 
		$this->load->model('configuracion_cuentas_model'); 
		$this->load->model('asiento_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
		$this->load->model('cruce_cuentas_model');
		$this->load->model('reg_factura_model');
		$this->load->model('ctasxpagar_model');
		$this->load->model('ctasxcobrar_model');
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
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_facturas=$this->cruce_cuentas_model->lista_facturas_pendientes($text,$f1,$f2,$rst_cja->emp_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_facturas=$this->cruce_cuentas_model->lista_facturas_pendientes($text,$f1,$f2,$rst_cja->emp_id);
		}

		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

		$data=array(
					'permisos'=>$this->permisos,
					'facturas'=>$cns_facturas,
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'dec'=>$dec,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('cruce_cuentas/lista',$data);
		$modulo=array('modulo'=>'cruce_cuentas');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id,$id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		
		$reg_factura=$this->reg_factura_model->lista_una_factura($id);
		$rst_pag= $this->cruce_cuentas_model->lista_pago_reg_factura($id);
		$saldo=round($reg_factura->reg_total,$dec)-round($rst_pag->saldo,$dec);
		if($this->permisos->rop_actualizar){
			$data=array(
						'dec'=>$dec,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'reg_factura'=>$reg_factura,
						'saldo'=>$saldo,
						'facturas'=>$this->cruce_cuentas_model->lista_facturas_cliente($reg_factura->cli_id,$rst_cja->emp_id),
						'action'=>base_url().'cruce_cuentas/guardar/'.$opc_id
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cruce_cuentas/form',$data);
			$modulo=array('modulo'=>'cruce_cuentas');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function guardar($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');


		$reg_id= $this->input->post('reg_id');
		$reg_numero= $this->input->post('reg_numero');
		$count= $this->input->post('count');
		$fecha= $this->input->post('fecha');
		$cli_id= $this->input->post('cli_id');

		if($conf_as->con_valor==0){
			$cta1=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('26',$rst_cja->emi_id);
        	$cta2=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('1',$rst_cja->emi_id);

        	$pln_id=$cta1->pln_id;
			$banco=$cta2->pln_codigo;
			$pln_id2=$cta2->pln_id;
			$banco2=$cta1->pln_codigo;
		}else{
			$pln_id=0;
			$banco='';
			$pln_id2=0;
			$banco2='';
		}

		$n=0;
		
		while($n<$count){
			$n++;
			$fac_id=$this->input->post("fac_id$n");
			$abono=$this->input->post("abono$n");
			$fac_num=$this->input->post("fac_num$n");
			if($abono>0){
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
		    				'ctp_fecha_pago'=>$fecha,
							'ctp_forma_pago'=>'10',
							'num_documento'=>$fac_num, 
							'ctp_concepto'=>'CRUCE DE CUENTAS', 
							'ctp_monto'=>$abono,
							'pln_id'=>$pln_id,
							'ctp_banco'=>$banco,
							'ctp_fecha'=>date('Y-m-d'),
							'ctp_estado'=>'1',
							'doc_id'=>$fac_id,
							'ctp_secuencial'=>$secuencial_cxp,
							'emp_id'=>$rst_cja->emp_id,
			    );
			    $ctp_id=$this->ctasxpagar_model->insert($data);

			    $data2=array(	
			    				'com_id'=>$fac_id,
			    				'cta_fecha_pago'=>$fecha,
								'cta_forma_pago'=>'10',
								'num_documento'=>$reg_numero, 
								'cta_concepto'=>'CRUCE DE CUENTAS', 
								'cta_monto'=>$abono,
								'cta_fecha'=>date('Y-m-d'),
								'chq_id'=>0,
								'cta_estado'=>'1',
								'pln_id'=>$pln_id2,
								'cta_banco'=>$banco2,
								'emp_id'=>$rst_cja->emp_id,
			    );
			    $this->ctasxcobrar_model->insert($data2);
			    
			    if($conf_as->con_valor==0){
			    	$this->asientos($ctp_id);
			    }
			}
		}

		
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CRUCE DE CUENTAS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$reg_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$this->nuevo($opc_id,$reg_id);
			
			
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

}
