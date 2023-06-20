<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_aprobacion extends CI_Controller {

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
			$txt_est="";
		}else{
			$txt_est="and orc_estado='$est'";
		}
		$cns_ord=$this->orden_compra_model->lista_ordenes_aprobar_buscador($text,$f1,$f2,$txt_est);	

		$v=0;
		$ordenes=array();
		foreach ($cns_ord as $ord) {
			$detalle=$this->orden_compra_model->lista_detalle_aprob($ord->orc_id);
			$v=0;
			foreach($detalle as $det){
				if(round($det->orc_det_vu,$dec)<>round($det->val_aprob,$dec)){
                    $v=1;
                }
			}

			$orden=(object) array(
									'orc_codigo'=>$ord->orc_codigo,
									'orc_fecha'=>$ord->orc_fecha,
									'orc_fecha_entrega'=>$ord->orc_fecha_entrega,
									'cli_raz_social'=>$ord->cli_raz_social,
									'orc_concepto'=>$ord->orc_concepto,
									'orc_obs'=>$ord->orc_obs,
									'orc_sub12'=>$ord->orc_sub12,
									'orc_sub0'=>$ord->orc_sub0,
									'orc_iva'=>$ord->orc_iva,
									'orc_total'=>$ord->orc_total,
									'orc_estado'=>$ord->orc_estado,
									'orc_id'=>$ord->orc_id,
									'verifica'=>$v,
										);
			array_push($ordenes, $orden);
		}

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
		$this->load->view('orden_compra_aprobacion/lista',$data);
		$modulo=array('modulo'=>'orden_compra_aprobacion');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	
	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
						'mensaje'=> $mensaje,
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'orden'=> $this->orden_compra_model->lista_una_orden($id),
						'detalle'=> $this->orden_compra_model->lista_detalle_aprob($id),
						//action'=>base_url().'orden_compra_aprobacion/actualizar/'.$opc_id
						'opc_id'=>$opc_id,
						);
			//$this->load->view('orden_compra_aprobacion/form',$data);

			// $we =  intval($this->session->userdata('s_we'));
			// if($we>=760){
				$this->load->view('orden_compra_aprobacion/form',$data);
			// }else{
			// 	$this->load->view('orden_compra_aprobacion/form_movil',$data);
			// }

			$modulo=array('modulo'=>'orden_compra_aprobacion');
			$this->load->view('layout/footer_bodega',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function actualizar($id,$opc_id,$est){
		$rst=$this->orden_compra_model->lista_una_orden($id);
			$data=array(
		    			'orc_estado'=>$est, 
		    			'orc_fecha_aut'=>date('Y-m-d'),
		    );

			$data_audito=array(
		    			'orc_id'=>$id, 
		    			'orc_fecha_aut'=>date('Y-m-d'),
		    			'orc_codigo'=>$rst->orc_codigo,
		    			'orc_estado'=>$est, 

		    );


		    if($this->orden_compra_model->update($id,$data)){

		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'APROBACION ORDENES COMPRA',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst->orc_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'orden_compra_aprobacion/editar/'.$id.'/'.$opc_id);
			}
	}

	

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Aprobacion de Ordenes de Compras';
    	$file="orden_compra_aprobacions".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    
}
