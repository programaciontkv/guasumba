<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_guia extends CI_Controller {

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
		$this->load->model('reg_factura_model');
		$this->load->model('reg_guia_model');
		$this->load->model('cliente_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
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
			$text= trim($this->input->post('txt'));
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_guias=$this->reg_guia_model->lista_buscador_guias($text,$f1,$f2,$rst_cja->emp_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_guias=$this->reg_guia_model->lista_buscador_guias($text,$f1,$f2,$rst_cja->emp_id);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'guias'=>$cns_guias,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('reg_guia/lista',$data);
		$modulo=array('modulo'=>'reg_guia');
		$this->load->view('layout/footer',$modulo);
	}

	public function unir($opc_id){
		///unificar
			$sms = 0;
	        $n = 0;
	        $v=0;
	        $txt = '000000000';
	        $rst_sec = $this->reg_guia_model->lista_ultimo_sec_reg_guia();
	        if(empty($rst_sec)){
	        	$num_doc =0;
	        }else{
	        	$num_doc = $rst_sec->rgu_secuencia_unif;
	        }
	        
	        $num_doc = intval($num_doc + 1);
	        $num_doc = substr($txt, 0, (10 - strlen($num_doc))) . $num_doc;
	        $data = $_REQUEST['data'];

	        while($n < count($data)){
	            $rst_gu=$this->reg_guia_model->lista_una_guia($data[$n]);
	            if(!empty($rst_gu->rgu_secuencia_unif) && $v==0){
	                $secu=$rst_gu->rgu_secuencia_unif;
	                $v=1;
	            }
	            $n++;
	        }
	        if($v==1){
	           $num_doc = $secu;
	        }

	        $n = 0;
	        while($n < count($data)){
	        	$dt_up=array('rgu_secuencia_unif'=>$num_doc);
	            $this->reg_guia_model->update($data[$n],$dt_up);
	            $n++;
	        } 

	        echo $num_doc;

	}

	public function nuevo($opc_id,$secuencial){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			

			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			$detalle=$this->reg_guia_model->lista_detalle_secuencial($secuencial);
			$cns_det=array();
			$texto="";
                
            $cns_fac=$this->reg_guia_model->lista_guias_facturas_secuencial($secuencial);
            if(!empty($cns_fac)){
                $l=0;
                $texto="(";
                foreach($cns_fac as $rst_fac){
                	if($l==0){
                    	$texto.="reg_id=$rst_fac->reg_id"; 
                    }else{
                        $texto.=" or reg_id=$rst_fac->reg_id"; 
                    }
                    $l++;
                }
                $texto.=") and ";
            }else{
            	$texto="reg_id=0 and ";
            }
            
			foreach ($detalle as $det) {
				
				$factura=$this->reg_guia_model->lista_facturado_secuencial($det->pro_id,$texto);
				if(!empty($factura)){
					$entregado=$factura->facturado;
				}else{
					$entregado=0;
				}
				$saldo=$det->drg_cantidad-$entregado;
				$dat=(object) array(
							'pro_id'=>$det->pro_id,
							'mp_c'=>$det->mp_c,
							'mp_d'=>$det->mp_d,
							'mp_q'=>$det->mp_q,
							'drg_cantidad'=>$det->drg_cantidad,
							'entregado'=>$entregado,
							'saldo'=>$saldo
						);
				array_push($cns_det, $dat);
			}
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'guia'=> $this->reg_guia_model->lista_una_guia_secuencial($secuencial),
						'cns_det'=>$cns_det,
						'cnt_detalle'=>0,
						'action'=>base_url().'reg_guia/guardar/'.$opc_id,
						);
			
			$this->load->view('reg_guia/form',$data);
			$modulo=array('modulo'=>'reg_guia');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$rgu_secuencia_unif = $this->input->post('rgu_secuencia_unif');
		$rgu_fregistro= $this->input->post('rgu_fregistro');
		$cli_id= $this->input->post('cli_id');
		$reg_num_documento= $this->input->post('reg_num_documento');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$count_det=$this->input->post('count_detalle');
		$t_solicitado=$this->input->post('t_solicitado');
		$t_saldo2=$this->input->post('t_saldo2');
		
		$this->form_validation->set_rules('rgu_fregistro','Fecha de Emision','required');
		$this->form_validation->set_rules('reg_num_documento','Factura No','required');
		
		if($this->form_validation->run()){
			$rst_cli=$this->cliente_model->lista_un_cliente($cli_id);
		    if($rst_cli->cli_tipo_cliente==0){
		    	$tpc='LOCAL';
		    }else{
		    	$tpc='EXTRANJERO';
		    }
		    	$encabezado=array(
                        'reg_fregistro'=>$rgu_fregistro,
                        'reg_tipo_documento'=>'1',
                        'reg_num_documento'=>$reg_num_documento,
                        'reg_fautorizacion'=>'1900-01-01',//fec_aut
                        'reg_fcaducidad'=>'1900-01-01',//fec_aut_hasta
                        'reg_sbt12'=>0,//sub12
                        'reg_sbt0'=>0,//sub0
                        'reg_sbt'=>0,//subtotal
                        'reg_tdescuento'=>0,
                        'reg_iva12'=>0,
                        'reg_total'=>0,//total
                        'cli_id'=>$cli_id,
                        'reg_tpcliente'=>$tpc,
                        'reg_ruc_cliente'=>$rst_cli->cli_ced_ruc,
                        'reg_estado'=>'7',//estado
                        'emp_id'=>$emp_id,
                        'emi_id'=>$emi_id,
                        'cja_id'=>$cja_id,
                        'reg_tipo_pago'=>'01',
                        'reg_forma_pago'=>'1',
                        'reg_pais_importe'=>'241',
                        'reg_relacionado'=>'NO',
                        'reg_num_ingreso'=>$rgu_secuencia_unif,

                    );
    
		    	$fac_id=$this->reg_factura_model->insert($encabezado);
		    	$n=0;
		    	while($n < $count_det) {
		    		$n++;
    				if($this->input->post("pro_id$n") !=''){
    					if($this->input->post("saldo$n")>0){
	    					$pro_id = $this->input->post("pro_id$n");
					    	$cantidad = $this->input->post("saldo$n");
					    	$rst_pro=$this->producto_comercial_model->lista_un_producto($pro_id);
	    	 				$detalle=array(     
		                             'det_cantidad'=>$cantidad,
		                             'det_vunit'=>0,
		                             'det_descuento_porcentaje'=>0,
		                             'det_descuento_moneda'=>0,
		                             'det_tipo'=>$rst_pro->ids,
		                             'det_impuesto'=>$rst_pro->mp_h,
		                             'pln_id'=>'0',
		                             'pro_id'=>$pro_id,
		                             'reg_id'=>$fac_id
		                         );
	    	 				$this->reg_factura_model->insert_detalle($detalle);
    	 				}
    	 			}
    			} 	
		    	
		    	///guias-facturas

		    	$cns_guias=$this->reg_guia_model->lista_guias_secuencial($rgu_secuencia_unif);
		    	foreach ($cns_guias as $guia) {
		    		$dat=array(
		    					'reg_id'=>$fac_id,
		    					'rgu_id'=>$guia->rgu_id
		    		);
		    		$this->reg_guia_model->insert_guias($dat);

		    		///cabiar a estado guia
		    		if($t_solicitado>$t_saldo2){
		    			$estado=15;
		    		}else{
		    			$estado=16;
		    		}
			    	$dt_up=array('rgu_estado'=>$estado);
			    	$this->reg_guia_model->update($guia->rgu_id,$dt_up);
		    	}


				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO GUIA REMISION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$ndb_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
			
		}else{
			$this->nuevo($opc_id);
		}	

	}

	
	
	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

			$rst_chq=$this->reg_guia_model->lista_cheque_nota($id);
				
			if(!empty($rst_chq)){
				//anulacion pagos
				$up_pag=array('pag_estado'=>3);
				$this->reg_guia_model->update_pagos($rst_chq->chq_id,$up_pag);

				$rst_ncr=$this->reg_guia_model->lista_una_nota($id);
			    $up_dtf=array('ndb_estado'=>3);
				if($this->reg_guia_model->update($id,$up_dtf)){
					$up_chq=array('chq_estado_cheque'=>3,'chq_estado'=>3);
					$this->cheque_model->update_chq_nota($id,$up_chq);
					//asiento anulacion nota
					if($cnf_as==0){
						$this->asiento_anulacion($id,'3');
					}

					$data_aud=array(
									'usu_id'=>$this->session->userdata('s_idusuario'),
									'adt_date'=>date('Y-m-d'),
									'adt_hour'=>date('H:i'),
									'adt_modulo'=>'REGISTRO GUIA REMISION',
									'adt_accion'=>'ANULAR',
									'adt_ip'=>$_SERVER['REMOTE_ADDR'],
									'adt_documento'=>$num,
									'usu_login'=>$this->session->userdata('s_usuario'),
									);
					$this->auditoria_model->insert($data_aud);
					$data=array(
									'estado'=>0,
									'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);

				}else{
					$data=array(
							'estado'=>1,
							'sms'=>'No se anulo la REGISTRO GUIA REMISION',
							'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
					);
				}
			}
			echo json_encode($data);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function traer_facturas($num,$emi){
		$rst=$this->factura_model->lista_factura_numero($num,$emi);
		echo json_encode($rst);
	}

	public function load_factura($id,$inven,$ctrl_inv,$dec,$dcc){
		$rst=$this->factura_model->lista_una_factura($id);
		$n=0;
		
			$data= array(
						'fac_id'=>$rst->fac_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_email'=>$rst->cli_email,
						'fac_fecha_emision'=>$rst->fac_fecha_emision,
						'fac_numero'=>$rst->fac_numero,
						);	

		echo json_encode($data);
	} 

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='REGISTRO GUIA DE REMISION '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="reg_guia".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function doc_duplicado($id,$num,$tip){
		$rst=$this->reg_guia_model->lista_doc_duplicado($id,$num,$tip);
		if(!empty($rst)){
			echo $rst->reg_id;
		}else{
			echo "";
		}
	}

   
    
   
	
}
