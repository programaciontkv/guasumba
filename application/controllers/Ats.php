<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ats extends CI_Controller {

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->model('menu_model');
		$this->load->model('configuracion_model'); 
		$this->load->model('ats_model');
		$this->load->model('caja_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('empresa_model');
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
	

	public function index(){
		$data=array(
					'permisos'=>$this->permisos,
					'anio'=>date('Y'),
					'mes'=>date('m'),
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('ats/lista',$data);
		$modulo=array('modulo'=>'ats');
		$this->load->view('layout/footer',$modulo);
	}

	
    public function generar_ats($opc_id){

    	$mes= $this->input->post('mes');
		$anio= $this->input->post('anio');
	 	
	 	$fec_ini = $anio . '-' . $mes . '-01';
		$fec_fin = date("Y-m-t", strtotime($fec_ini));

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $rst_emi=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

		
		$rst_nm_emi = $this->ats_model->lista_num_emisor($fec_ini, $fec_fin,$rst_emi->emp_id);
		$rst_ven0 = $this->ats_model->lista_ventas0($fec_ini, $fec_fin,$rst_emi->emp_id);
		
		$xml="";		
		///emisor
		$xml.="<iva>" . chr(13);
		$xml.="<TipoIDInformante>" . "R" . "</TipoIDInformante>" . chr(13);
		$xml.="<IdInformante>" . trim($rst_emi->emp_identificacion) . "</IdInformante>" . chr(13);
		$xml.="<razonSocial>" . $rst_emi->emp_nombre . "</razonSocial>" . chr(13);
		$xml.="<Anio>" . $anio . "</Anio>" . chr(13);
		$xml.="<Mes>" . $mes . "</Mes>" . chr(13);

		if (intval($rst_nm_emi) < 10) {
		    $numero_estab = '00' . $rst_nm_emi;
		} else if (intval($rst_nm_emi) < 100) {
		    $numero_estab = '0' . $rst_nm_emi;
		} else {
		    $numero_estab = $rst_nm_emi;
		}

		
		$xml.="<numEstabRuc>" . $numero_estab . "</numEstabRuc>" . chr(13);
		$xml.="<totalVentas>" . str_replace(',', '', number_format($rst_ven0->venta-$rst_ven0->devolucion, 2)) . "</totalVentas>" . chr(13);
		$xml.="<codigoOperativo>" . "IVA" . "</codigoOperativo>" . chr(13);

		// /// compras  
		$cns_cmp =$this->ats_model->lista_compras($fec_ini, $fec_fin,$rst_emi->emp_id);
		if (!empty($cns_cmp)) {
		    $xml.="<compras>" . chr(13);
		    foreach($cns_cmp as $rst_cmp) {
		        //    ///documneto de sustento
		        if ($rst_cmp->reg_sustento < 10) {
		            $doc_sust = "0" . $rst_cmp->reg_sustento;
		        } else {
		            $doc_sust = $rst_cmp->reg_sustento;
		        }

		        //tipo_comprobante 
		        if ($rst_cmp->tdc_codigo < 10) {
		            $tipo_comp = "0" . $rst_cmp->tdc_codigo;
		        } else {
		            $tipo_comp = $rst_cmp->tdc_codigo;
		        }

		        ///identificacion
		        if (strlen(trim($rst_cmp->reg_ruc_cliente)) == 13) {
		            $tpIdProv = '01'; //'ruc';
		        } else if (strlen(trim($rst_cmp->reg_ruc_cliente)) == 10) {
		            
		            if(substr(trim($rst_cmp->reg_ruc_cliente),2,1)<6){
		                            ///ruc natural o cedula
		                            $n = 0;
		                            $s = 0;
		                            while ($n < 9) {
		                                $r = $n % 2;
		                                if ($r == 0) {
		                                    $m = 2;
		                                } else {
		                                    $m = 1;
		                                }
		                                 $ml = (substr(trim($rst_cmp->reg_ruc_cliente),$n, 1) * 1) * $m;
		                                
		                                if ($ml > 9) {
		                                    $ml = (substr($ml,0, 1) * 1) + (substr($ml,1, 1) * 1);
		                                }
		                                
		                                $s += $ml;
		                                $n++;
		                            }
		                            $d = $s % 10;
		                            if ($d == 0) {
		                                $t = 0;
		                            } else {
		                                $t = 10 - $d;
		                            }
		                            
		                            if ($t == substr(trim($rst_cmp->reg_ruc_cliente),9, 1)) {
		                                $tpIdProv = '02'; //'cedula';
		                            } else {
		                                $tpIdProv = '03';
		                            }
		            }       
		        } else {
		            $tpIdProv = '03';
		        }

		        $num_doc = explode('-', $rst_cmp->reg_num_documento);
		        if($rst_cmp->tdc_codigo!=4){
		            $rst_rti = $this->ats_model->lista_retencion_iva($rst_cmp->reg_id);
		            $cns_rtr = $this->ats_model->lista_retencion_renta($rst_cmp->reg_id);
		            $rst_ret = $this->ats_model->lista_retencion($rst_cmp->reg_id);
		            if(empty($rst_ret)){
		            	$num_ret = '';
		            }else{
		            	$num_ret = explode('-', $rst_ret->ret_numero);
		        	}
		        	$iva10=0;
		        	$iva20=0;
		        	$iva30=0;
		        	$iva70=0;
		        	$iva50=0;
		        	$iva100=0;
		        }else{
		            if(empty($rst_rti->iva10)){
		            	$iva10=0;
		            }else{
		            	$iva10=$rst_rti->iva20;
		            }
		            if(empty($rst_rti->iva20)){
		            	$iva20=0;
		            }else{
		            	$iva20=$rst_rti->iva20;
		            }
		            if(empty($rst_rti->iva30)){
		            	$iva30=0;
		            }else{
		            	$iva30=$rst_rti->iva30;
		            }
		            if(empty($rst_rti->iva50)){
		            	$iva50=0;
		            }else{
		            	$iva50=$rst_rti->iva70;
		            }
		            if(empty($rst_rti->iva70)){
		            	$iva70=0;
		            }else{
		            	$iva70=$rst_rti->iva70;
		            }
		            if(empty($rst_rti->iva100)){
		            	$iva100=0;
		            }else{
		            	$iva100=$rst_rti->iva100;
		            }
		            
		        }
		        $xml.="<detalleCompras>" . chr(13);
		        $xml.="<codSustento>" . $doc_sust . "</codSustento>" . chr(13);
		        $xml.="<tpIdProv>" .$tpIdProv . "</tpIdProv>" . chr(13);
		        $xml.="<idProv>" . $rst_cmp->reg_ruc_cliente . "</idProv>" . chr(13);
		        $xml.="<tipoComprobante>" . $tipo_comp . "</tipoComprobante>" . chr(13);
		        $xml.="<parteRel>" . "NO" . "</parteRel>" . chr(13);
		        $xml.="<fechaRegistro>" . date_format(date_create($rst_cmp->reg_femision), 'd/m/Y') . "</fechaRegistro>" . chr(13);
		        $xml.="<establecimiento>" . $num_doc[0] . "</establecimiento>" . chr(13);
		        $xml.="<puntoEmision>" . $num_doc[1] . "</puntoEmision>" . chr(13);
		        $xml.="<secuencial>" . $num_doc[2] . "</secuencial>" . chr(13);
		        $xml.="<fechaEmision>" . date_format(date_create($rst_cmp->reg_femision), 'd/m/Y') . "</fechaEmision>" . chr(13);
		        $xml.="<autorizacion>" . $rst_cmp->reg_num_autorizacion . "</autorizacion>" . chr(13);
		        $xml.="<baseNoGraIva>" . str_replace(',', '', number_format($rst_cmp->reg_sbt_noiva, 2)) . "</baseNoGraIva>" . chr(13);
		        $xml.="<baseImponible>" . str_replace(',', '', number_format($rst_cmp->reg_sbt0, 2)) . "</baseImponible>" . chr(13);
		        $xml.="<baseImpGrav>" . str_replace(',', '', number_format($rst_cmp->reg_sbt12, 2)) . "</baseImpGrav>" . chr(13);
		        $xml.="<baseImpExe>" . str_replace(',', '', number_format($rst_cmp->reg_sbt_excento, 2)) . "</baseImpExe>" . chr(13);
		        $xml.="<montoIce>" . str_replace(',', '', number_format($rst_cmp->reg_ice, 2)) . "</montoIce>" . chr(13);
		        $xml.="<montoIva>" . str_replace(',', '', number_format($rst_cmp->reg_iva12, 2)) . "</montoIva>" . chr(13);
		        $xml.="<valRetBien10>" . str_replace(',', '', number_format($iva10, 2)) . "</valRetBien10>" . chr(13);
		        $xml.="<valRetServ20>" . str_replace(',', '', number_format($iva20, 2)) . "</valRetServ20>" . chr(13);
		        $xml.="<valorRetBienes>" . str_replace(',', '', number_format($iva30, 2)) . "</valorRetBienes>" . chr(13);
		        $xml.="<valRetServ50>" . str_replace(',', '', number_format($iva50, 2)) . "</valRetServ50>" . chr(13);
		        $xml.="<valorRetServicios>" . str_replace(',', '', number_format($iva70, 2)) . "</valorRetServicios>" . chr(13);
		        $xml.="<valRetServ100>" . str_replace(',', '', number_format($iva100, 2)) . "</valRetServ100>" . chr(13);
		        $xml.="<totbasesImpReemb>" . str_replace(',', '', number_format(0, 2)) . "</totbasesImpReemb>" . chr(13);
		        $xml.="<pagoExterior>" . chr(13);
		        $xml.="<pagoLocExt>" . "01" . "</pagoLocExt>" . chr(13);
		        $xml.="<paisEfecPago>" . "NA" . "</paisEfecPago>" . chr(13);
		        $xml.="<aplicConvDobTrib>" . "NA" . "</aplicConvDobTrib>" . chr(13);
		        $xml.="<pagExtSujRetNorLeg>" . "NA" . "</pagExtSujRetNorLeg>" . chr(13);
		        $xml.="<pagoRegFis>" . "NA" . "</pagoRegFis>" . chr(13);
		        $xml.= "</pagoExterior>" . chr(13);
		        if ($rst_cmp->reg_sbt > 1000) {
		            $tipoPago ='20';
		        } else {
		            $tipoPago = $rst_cmp->reg_tipo_pago;
		            if($tipoPago==0){
		                $tipoPago = '01';
		            }
		        }
		        if($rst_cmp->tdc_codigo==4){
		            $tipoPago = '01';
		        }
		        $xml.= "<formasDePago>" . chr(13);
		        $xml.= "<formaPago>" . $tipoPago . "</formaPago>" . chr(13);
		        $xml.= "</formasDePago>" . chr(13);
		        if($rst_cmp->tdc_codigo!=4){
		            if (!empty($rst_rtr)) {
		                $xml.= "<air>" . chr(13);
		                foreach ($cns_rtr as $rst_rtr) {
		                    
		                    $xml.= "<detalleAir>" . chr(13);
		                    $xml.= "<codRetAir>" . $rst_rtr->dtr_codigo_impuesto . "</codRetAir>" . chr(13);
		                    $xml.= "<baseImpAir>" . str_replace(',', '',number_format($rst_rtr->dtr_base_imponible,2)) . "</baseImpAir>" . chr(13);
		                    $xml.= "<porcentajeAir>" . str_replace(',', '',number_format($rst_rtr->dtr_procentaje_retencion,2)) . "</porcentajeAir>" . chr(13);
		                    $xml.= "<valRetAir>" . str_replace(',', '',number_format($rst_rtr->dtr_valor,2)) . "</valRetAir>" . chr(13);
		                    $xml.= "</detalleAir>" . chr(13);
		                }
		                    $xml.= "</air>" . chr(13);
		                    $xml.= "<estabRetencion1>" . $num_ret[0] . "</estabRetencion1>" . chr(13);
		                    $xml.= "<ptoEmiRetencion1>" . $num_ret[1] . "</ptoEmiRetencion1>" . chr(13);
		                    $xml.= "<secRetencion1>" . $num_ret[2] . "</secRetencion1>" . chr(13);
		                    $xml.= "<autRetencion1>" . $rst_ret->ret_autorizacion . "</autRetencion1>" . chr(13);
		                    $xml.= "<fechaEmiRet1>" . date_format(date_create($rst_ret->ret_fecha_emision), 'd/m/Y') . "</fechaEmiRet1>" . chr(13);
		                
		            }
		        }

		///nota de credito
		        if($rst_cmp->tdc_codigo==4){
		            $rst_nc = $this->ats_model->lista_reg_nota_credito($rst_cmp->reg_id);
		            if (!empty($rst_nc)) {
		                $num_nc = explode('-', $rst_nc->reg_num_documento);
		                $xml.= "<docModificado>" . '01' . "</docModificado>" . chr(13);
		                $xml.= "<estabModificado>" . $num_nc[0] . "</estabModificado>" . chr(13);
		                $xml.= "<ptoEmiModificado>" . $num_nc[1] . "</ptoEmiModificado>" . chr(13);
		                $xml.= "<secModificado>" . $num_nc[2] . "</secModificado>" . chr(13);
		                $xml.= "<autModificado>" . $rst_nc->reg_num_autorizacion . "</autModificado>" . chr(13);
		            }
		        }
		        $xml.="</detalleCompras>" . chr(13);
		    }
		    $xml.="</compras>" . chr(13);
		}
		///ventas
		$cns_vnt = $this->ats_model->lista_ventas_clientes($fec_ini, $fec_fin,$rst_emi->emp_id);
		if (!empty($cns_vnt)) {
		    $xml.="<ventas>" . chr(13);
		    foreach ($cns_vnt as $rst_vnt) {
		        // //         ///identificacion
		        if (trim($rst_vnt->cli_ced_ruc) == '9999999999' || trim($rst_vnt->cli_ced_ruc) == '9999999999999') {
		            $tpIdCliente = '07'; //'consumidor final';
		        } else if (strlen(trim($rst_vnt->cli_ced_ruc)) == 10) {
		            if(substr(trim($rst_vnt->cli_ced_ruc),2,1)<6){
		                            ///ruc natural o cedula
		                            $n = 0;
		                            $s = 0;
		                            while ($n < 9) {
		                                $r = $n % 2;
		                                if ($r == 0) {
		                                    $m = 2;
		                                } else {
		                                    $m = 1;
		                                }
		                                 $ml = (substr(trim($rst_vnt->cli_ced_ruc),$n, 1) * 1) * $m;
		                                
		                                if ($ml > 9) {
		                                    $ml = (substr($ml,0, 1) * 1) + (substr($ml,1, 1) * 1);
		                                }
		                                
		                                $s += $ml;
		                                $n++;
		                            }
		                            $d = $s % 10;
		                            if ($d == 0) {
		                                $t = 0;
		                            } else {
		                                $t = 10 - $d;
		                            }
		                            
		                            if ($t == substr(trim($rst_vnt->cli_ced_ruc),9, 1)) {
		                                $tpIdCliente = '05'; //'cedula';
		                            } else {
		                                $tpIdCliente = '06';
		                            }
		            }                

		        } else if (strlen(trim($rst_vnt->cli_ced_ruc)) == 13) {
		            $tpIdCliente = '04'; //'RUC';
		        } else {
		            $tpIdCliente = '06';
		        }
		        $xml.="<detalleVentas>" . chr(13);
		        $xml.= "<tpIdCliente>" . $tpIdCliente . "</tpIdCliente>" . chr(13);
		        $xml.= "<idCliente>" . $rst_vnt->cli_ced_ruc . "</idCliente>" . chr(13);
		        if($tpIdCliente !='07'){
		            $xml.= "<parteRelVtas>" . "NO" . "</parteRelVtas>" . chr(13);
		        }
		        if ($tpIdCliente == '06') {
		            $rst_cli = $this->ats_model->lista_cliente($rst_vnt->cli_ced_ruc);
		            if($rst_cli->cli_categoria==0){
		                $tp_cl='01';
		            }else{
		                $tp_cl='02';
		            }
		            $xml.= "<tipoCliente>" . $tp_cl . "</tipoCliente>" . chr(13);
		            $xml.= "<denoCli>" . $rst_cli->cli_raz_social . "</denoCli>" . chr(13);
		        }
		        $xml.= "<tipoComprobante>" . "18" . "</tipoComprobante>" . chr(13);
		        $xml.= "<tipoEmision>" . "F" . "</tipoEmision>" . chr(13);
		        $xml.= "<numeroComprobantes>" . $rst_vnt->facturas . "</numeroComprobantes>" . chr(13);
		        $xml.= "<baseNoGraIva>" . str_replace(',', '', number_format($rst_vnt->subno, 2)) . "</baseNoGraIva>" . chr(13);
		        $xml.= "<baseImponible>" . str_replace(',', '', number_format($rst_vnt->sub0+$rst_vnt->subex, 2)) . "</baseImponible>" . chr(13);
		        $xml.= "<baseImpGrav>" . str_replace(',', '', number_format($rst_vnt->sub12, 2)) . "</baseImpGrav>" . chr(13);
		        $xml.= "<montoIva>" . str_replace(',', '', number_format($rst_vnt->iva, 2)) . "</montoIva>" . chr(13);
		        $xml.= "<montoIce>" . str_replace(',', '', number_format($rst_vnt->ice, 2)) . "</montoIce>" . chr(13);
		        $xml.= "<valorRetIva>" . str_replace(',', '', number_format($rst_vnt->ret_iva, 2)) . "</valorRetIva>" . chr(13);
		        $xml.= "<valorRetRenta>" . str_replace(',', '', number_format($rst_vnt->ret_renta, 2)) . "</valorRetRenta>" . chr(13);
		        $xml.= "<formasDePago>" . chr(13);
		        $rst_pg = $this->ats_model->lista_pagos_cliente($rst_vnt->cli_ced_ruc, $fec_ini, $fec_fin, $rst_emi->emp_id);
		        $xml.="<formaPago>" . $rst_pg->fpg_codigo . "</formaPago>" . chr(13);
		        $xml.="</formasDePago>" . chr(13);
		        $xml.="</detalleVentas>" . chr(13);
		    }

		    ///detalles de Notas de Creditos
		    $cns_ncr=$this->ats_model->lista_notas_creditos_venta($fec_ini, $fec_fin, $rst_emi->emp_id);
		    foreach ($cns_ncr as $rst_ncr ) {
		        // //         ///identificacion
		        if ($rst_ncr->cli_ced_ruc == '9999999999' || $rst_ncr->cli_ced_ruc == '9999999999999') {
		            $tpIdCliente = '07'; //'consumidor final';
		        } else if (strlen(trim($rst_ncr->cli_ced_ruc)) == 10) {
		            
		            if(substr(trim($rst_ncr->cli_ced_ruc),2,1)<6){
		                            ///ruc natural o cedula
		                            $n = 0;
		                            $s = 0;
		                            while ($n < 9) {
		                                $r = $n % 2;
		                                if ($r == 0) {
		                                    $m = 2;
		                                } else {
		                                    $m = 1;
		                                }
		                                 $ml = (substr(trim($rst_ncr->cli_ced_ruc),$n, 1) * 1) * $m;
		                                
		                                if ($ml > 9) {
		                                    $ml = (substr($ml,0, 1) * 1) + (substr($ml,1, 1) * 1);
		                                }
		                                
		                                $s += $ml;
		                                $n++;
		                            }
		                            $d = $s % 10;
		                            if ($d == 0) {
		                                $t = 0;
		                            } else {
		                                $t = 10 - $d;
		                            }
		                            
		                            if ($t == substr(trim($rst_ncr->cli_ced_ruc),9, 1)) {
		                                $tpIdCliente = '05'; //'cedula';
		                            } else {
		                                $tpIdCliente = '06';
		                            }
		            }                

		                        
		        } else if (strlen(trim($rst_ncr->cli_ced_ruc)) == 13) {
		            $tpIdCliente = '04'; //'RUC';
		        } else {
		            $tpIdCliente = '06';
		        }
		        $xml.="<detalleVentas>" . chr(13);
		        $xml.= "<tpIdCliente>" . $tpIdCliente . "</tpIdCliente>" . chr(13);
		        $xml.= "<idCliente>" . $rst_ncr->cli_ced_ruc . "</idCliente>" . chr(13);
		        if($tpIdCliente != '07'){
		            $xml.= "<parteRelVtas>" . "NO" . "</parteRelVtas>" . chr(13);
		        }
		        if ($tpIdCliente == '06') {
		            $rst_cli = $this->ats_model->lista_cliente($rst_ncr->cli_ced_ruc);
		            if($rst_cli->cli_categoria==0){
		                $tp_cl='01';
		            }else{
		                $tp_cl='02';
		            }
		            $xml.= "<tipoCliente>" . $tp_cl . "</tipoCliente>" . chr(13);
		            $xml.= "<denoCli>" . $rst_cli->cli_raz_social . "</denoCli>" . chr(13);
		        }
		        $xml.= "<tipoComprobante>" . "04" . "</tipoComprobante>" . chr(13);
		        $xml.= "<tipoEmision>" . "F" . "</tipoEmision>" . chr(13);
		        $xml.= "<numeroComprobantes>" . $rst_ncr->notas . "</numeroComprobantes>" . chr(13);
		        $xml.= "<baseNoGraIva>" . str_replace(',', '', number_format($rst_ncr->subno, 2)) . "</baseNoGraIva>" . chr(13);
		        $xml.= "<baseImponible>" . str_replace(',', '', number_format($rst_ncr->sub0+$rst_ncr->subex, 2)) . "</baseImponible>" . chr(13);
		        $xml.= "<baseImpGrav>" . str_replace(',', '', number_format($rst_ncr->sub12, 2)) . "</baseImpGrav>" . chr(13);
		        $xml.= "<montoIva>" . str_replace(',', '', number_format($rst_ncr->iva, 2)) . "</montoIva>" . chr(13);
		        $xml.= "<montoIce>" . str_replace(',', '', number_format($rst_ncr->ice, 2)) . "</montoIce>" . chr(13);
		        $xml.= "<valorRetIva>" . str_replace(',', '', number_format($rst_ncr->ret_iva, 2)) . "</valorRetIva>" . chr(13);
		        $xml.= "<valorRetRenta>" . str_replace(',', '', number_format($rst_ncr->ret_renta, 2)) . "</valorRetRenta>" . chr(13);
		        $xml.="</detalleVentas>" . chr(13);
		    }
		    $xml.="</ventas>" . chr(13);
		}
		///ventasEstablecimiento
		$cns_vnt_emi = $this->ats_model->lista_ventas_emisor($fec_ini, $fec_fin,$rst_emi->emp_id);
		if (!empty($cns_vnt_emi)) {
		    $xml.="<ventasEstablecimiento>" . chr(13);

		    foreach ($cns_vnt_emi as $rst_vem) {
		        $emisor = $rst_vem->emi_id;
		        $rst_dv=$this->ats_model->lista_devoluciones_emisor($fec_ini, $fec_fin,$rst_vem->emi_id);
		        $xml.="<ventaEst>" . chr(13);
		        $xml.="<codEstab>" . $emisor . "</codEstab>" . chr(13);
		        $xml.="<ventasEstab>" . str_replace(',', '', number_format($rst_vem->sum-$rst_dv->sum, 2)) . "</ventasEstab>" . chr(13);
		        $xml.="</ventaEst>" . chr(13);
		    }
		    $xml.="</ventasEstablecimiento>" . chr(13);
		}
		///Anulados
		$cns_anulado = $this->ats_model->lista_anulados($fec_ini, $fec_fin,$rst_emi->emp_id);
		if (!empty($cns_anulado) != 0) {
		    $xml.="<anulados>" . chr(13);
		    foreach ($cns_anulado as $rst_anu) {
		        $num_dc = explode('-', $rst_anu->fac_numero);
		        $xml.="<detalleAnulados>" . chr(13);
		        $xml.="<tipoComprobante>" . $rst_anu->tipo . "</tipoComprobante>" . chr(13);
		        $xml.="<establecimiento>" . $num_dc[0] . "</establecimiento>" . chr(13);
		        $xml.="<puntoEmision>" . $num_dc[1] . "</puntoEmision>" . chr(13);
		        $xml.="<secuencialInicio>" . $num_dc[2] . "</secuencialInicio>" . chr(13);
		        $xml.="<secuencialFin>" . $num_dc[2] . "</secuencialFin>" . chr(13);
		        $xml.="<autorizacion>" . $rst_anu->fac_autorizacion . "</autorizacion>" . chr(13);
		        $xml.="</detalleAnulados>" . chr(13);
		    }
		    $xml.="</anulados>" . chr(13);
		}
		$xml.="</iva>" . chr(13);
		$fch = fopen("./xml_docs/ats" . $anio . '_' . $mes . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);
		$file = './xml_docs/ats' . $anio . '_' . $mes . '.xml';
		header("Content-type:xml");
		header("Content-length:" . filesize($file));
		header("Content-Disposition: attachment; filename=ats" . $anio . "_" . $mes . ".xml");
		readfile($file);
		unlink($file);       
        
    } 

}
