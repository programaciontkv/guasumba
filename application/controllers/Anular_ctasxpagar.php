<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anular_ctasxpagar extends CI_Controller {

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
		$this->load->model('asiento_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
		$this->load->model('ctasxpagar_model');
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
			$cns_pagos=$this->ctasxpagar_model->lista_pagos_buscador($rst_cja->emp_id,$f1,$f2,$text);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_pagos=$this->ctasxpagar_model->lista_pagos_buscador($rst_cja->emp_id,$f1,$f2,$text);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'pagos'=>$cns_pagos,
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('anular_ctasxpagar/lista',$data);
		$modulo=array('modulo'=>'asiento');
		$this->load->view('layout/footer',$modulo);
	}


	public function anular($id,$doc,$factura,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;

			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			
			///ctasxpagar
			$up_dt=array('ctp_estado'=>3);
			if($this->ctasxpagar_model->update_cta($id,$up_dt)){
				//asiento
				if($cnf_as==0){
					$this->asiento_anulacion($id,'11');
				}
				
				$dt=array(
							'ctp_id'=>$id,
							'documento'=>$doc,
							'factura'=>$factura,
							'ctp_estado'=>3
						);
				
				$data_aud=array(
					'usu_id'=>$this->session->userdata('s_idusuario'),
					'adt_date'=>date('Y-m-d'),
					'adt_hour'=>date('H:i'),
					'adt_modulo'=>'PAGOS CTASXPAGAR',
					'adt_accion'=>'ANULAR',
					'adt_ip'=>$_SERVER['REMOTE_ADDR'],
					'adt_campo'=>json_encode($dt),
					'adt_documento'=>$doc,
					'usu_login'=>$this->session->userdata('s_usuario'),
				);

				$this->auditoria_model->insert($data_aud);
			}
			$data=array(
						'estado'=>0,
						'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
						);
			
			echo json_encode($data);	
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function asiento_anulacion($id,$mod){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $cns=$this->asiento_model->lista_asientos_modulo($id,$mod);
        $asiento = $asiento =$this->asiento_model->siguiente_asiento();

        foreach ($cns as $rst) {
            
            $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'ANULACION '.$rst->con_concepto,
                        'con_documento'=>$rst->con_documento,
                        'con_fecha_emision'=>date('Y-m-d'),
                        'con_concepto_debe'=>$rst->con_concepto_haber,
                        'con_concepto_haber'=>$rst->con_concepto_debe,
                        'con_valor_debe'=>round($rst->con_valor_haber, $dec),
                        'con_valor_haber'=>round($rst->con_valor_debe, $dec),
                        'mod_id'=>$rst->mod_id,
                        'doc_id'=>$rst->doc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
                    );

            $this->asiento_model->insert($data);
                   
        }

    }    

	

}
