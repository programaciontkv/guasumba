<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_declaracion extends CI_Controller {

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
		$this->load->model('rep_declaracion_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
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

		$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
		}

		//buscador ventas
		$documentos=$this->rep_declaracion_model->lista_facturas_buscador($text,$f1,$f2,$rst_cja->emp_id);
		$docnotas=$this->rep_declaracion_model->lista_notcre_periodo($text,$f1,$f2,$rst_cja->emp_id);
		$cns_ret= $this->rep_declaracion_model->lista_retencion_periodo($text,$f1,$f2,$rst_cja->emp_id);
		$cns_p=$this->rep_declaracion_model->lista_det_retenciones_agrup($text,$f1,$f2,$rst_cja->emp_id);

		//buscador compras
		$cns2 = $this->rep_declaracion_model->lista_registros_factura($text,$f1,$f2,$rst_cja->emp_id);
		
		$cns_ncr2=$this->rep_declaracion_model->lista_notcre_periodo2($text,$f1,$f2,$rst_cja->emp_id);
		$cns_ret2= $this->rep_declaracion_model->lista_retencion_periodo2($text,$f1,$f2,$rst_cja->emp_id);

		$cns_p2=$this->rep_declaracion_model->lista_det_retenciones_agrup2($text,$f1,$f2,$rst_cja->emp_id);
		//ventas
		$cns_ventas=array();
		foreach ($documentos as $doc) {
			$notas=$this->rep_declaracion_model->lista_notas_factura($doc->fac_id,$f1,$f2);
			$retenciones=$this->rep_declaracion_model->lista_retenciones_factura($doc->fac_id,$f1,$f2);
			//notas de credito ventas
			$ncr_numero="";
			$ncr_fecha_emision="";
			$ncr_total_descuento=0;
			$ncr_subtotal=0;
			$ncr_subtotal0=0;
			$ncr_subtotal12=0;
			$ncr_subtotal_ex_iva=0;
			$ncr_subtotal_no_iva=0;
			$ncr_total_iva=0;
			$nrc_total_valor=0;

			if(!empty($notas)){
				foreach($notas as $nt){
					$ncr_numero.=$nt->ncr_numero.' ';
					$ncr_fecha_emision.=$nt->ncr_fecha_emision.' ';
					$ncr_total_descuento+=round($nt->ncr_total_descuento,$dec);
					$ncr_subtotal+=round($nt->ncr_subtotal,$dec);
					$ncr_subtotal0+=round($nt->ncr_subtotal0,$dec);
					$ncr_subtotal12+=round($nt->ncr_subtotal12,$dec);
					$ncr_subtotal_ex_iva+=round($nt->ncr_subtotal_ex_iva,$dec);
					$ncr_subtotal_no_iva+=round($nt->ncr_subtotal_no_iva,$dec);
					$ncr_total_iva+=round($nt->ncr_total_iva,$dec);
					$nrc_total_valor+=round($nt->nrc_total_valor,$dec);
				}
			}

			//retenciones de ventas
			$rnumero='';
			$rfecha='';
			$rvalor='0';
			if(!empty($retenciones)){
				foreach($retenciones as $ret){
					$rnumero.=$ret->rgr_numero;
					$rfecha.=$ret->rgr_fecha_emision;
				}	
			}
			$det_retenciones=$this->rep_declaracion_model->lista_detalle_retenciones_factura($doc->fac_id,$f1,$f2);
            $p_iva='';
            $p_ren='';
            $v_iva=0;
            $v_ren=0;
            if(!empty($det_retenciones)){
                foreach ($det_retenciones as $dtr) {
                    if($dtr->drr_tipo_impuesto=='IV'){
                        $p_iva.=$dtr->drr_procentaje_retencion.' ';
                        $v_iva+=round($dtr->drr_valor,$dec);
                    }else if($dtr->drr_tipo_impuesto=='IR'){
                        $p_ren.=$dtr->drr_procentaje_retencion.' ';
                        $v_ren+=round($dtr->drr_valor,$dec);
                    }
                }    
            }
            $rvalor=round($v_iva,$dec)+round($v_ren,$dec);
			$venta=(object) array(
							'fac_id'=>$doc->fac_id,
							'fac_fecha_emision'=>$doc->fac_fecha_emision,
							'fac_numero'=>$doc->fac_numero,
							'fac_nombre'=>$doc->fac_nombre,
							'fac_identificacion'=>$doc->fac_identificacion,
							'fac_subtotal'=>$doc->fac_subtotal,
							'fac_total_descuento'=>$doc->fac_total_descuento,
							'fac_subtotal0'=>$doc->fac_subtotal0,
							'fac_subtotal12'=>$doc->fac_subtotal12,
							'fac_subtotal_ex_iva'=>$doc->fac_subtotal_ex_iva,
							'fac_subtotal_no_iva'=>$doc->fac_subtotal_no_iva,
							'fac_total_iva'=>$doc->fac_total_iva,
							'fac_total_valor'=>$doc->fac_total_valor,
							'ncr_fecha_emision'=>$ncr_fecha_emision,
							'ncr_numero'=>$ncr_numero,
							'ncr_subtotal'=>$ncr_subtotal,
							'ncr_total_descuento'=>$ncr_total_descuento,
							'ncr_subtotal0'=>$ncr_subtotal0,
							'ncr_subtotal12'=>$ncr_subtotal12,
							'ncr_subtotal_ex_iva'=>$ncr_subtotal_ex_iva,
							'ncr_subtotal_no_iva'=>$ncr_subtotal_no_iva,
							'ncr_total_iva'=>$ncr_total_iva,
							'nrc_total_valor'=>$nrc_total_valor,
							'rgr_fecha_emision'=>$rfecha,
							'rgr_numero'=>$rnumero,
							'por_iva'=>$p_iva,
							'valor_iva'=>$v_iva,
							'por_renta'=>$p_ren,
							'valor_renta'=>$v_ren,
							'rgr_total_valor'=>$rvalor,
							);
			array_push($cns_ventas, $venta);
		}

		//notas de credito con facturas de otro periodo
		$cns_notas=array();
		$rnumero='';
		$rfecha='';
		$rvalor='0';
		$p_iva='';
	    $p_ren='';
	    $v_iva=0;
	    $v_ren=0;
		foreach ($docnotas as $not) {

				//retenciones de ventas
				$rnumero='';
				$rfecha='';
				$rvalor='0';
				$p_iva='';
			    $p_ren='';
			    $v_iva=0;
			    $v_ren=0;
				$retenciones=$this->rep_declaracion_model->lista_retenciones_factura($not->fac_id,$f1,$f2);
				
				if(!empty($retenciones)){
					foreach($retenciones as $ret){
						$rnumero.=$ret->rgr_numero;
						$rfecha.=$ret->rgr_fecha_emision;
					}	
				}
				$det_retenciones=$this->rep_declaracion_model->lista_detalle_retenciones_factura($not->fac_id,$f1,$f2);
	            
	            if(!empty($det_retenciones)){
	                foreach ($det_retenciones as $dtr) {
	                    if($dtr->drr_tipo_impuesto=='IV'){
	                        $p_iva.=$dtr->drr_procentaje_retencion.' ';
	                        $v_iva+=round($dtr->drr_valor,$dec);
	                    }else if($dtr->drr_tipo_impuesto=='IR'){
	                        $p_ren.=$dtr->drr_procentaje_retencion.' ';
	                        $v_ren+=round($dtr->drr_valor,$dec);
	                    }
	                }    
	            }
	            $rvalor=round($v_iva,$dec)+round($v_ren,$dec);
        	
			$nota=(object) array(
							'fac_id'=>$not->fac_id,
							'fac_fecha_emision'=>$not->fac_fecha_emision,
							'fac_numero'=>$not->fac_numero,
							'fac_nombre'=>$not->fac_nombre,
							'fac_identificacion'=>$not->fac_identificacion,
							'fac_subtotal'=>$not->fac_subtotal,
							'fac_total_descuento'=>$not->fac_total_descuento,
							'fac_subtotal0'=>$not->fac_subtotal0,
							'fac_subtotal12'=>$not->fac_subtotal12,
							'fac_subtotal_ex_iva'=>$not->fac_subtotal_ex_iva,
							'fac_subtotal_no_iva'=>$not->fac_subtotal_no_iva,
							'fac_total_iva'=>$not->fac_total_iva,
							'fac_total_valor'=>$not->fac_total_valor,
							'ncr_fecha_emision'=>$not->ncr_fecha_emision,
							'ncr_numero'=>$not->ncr_numero,
							'ncr_subtotal'=>$not->ncr_subtotal,
							'ncr_total_descuento'=>$not->ncr_total_descuento,
							'ncr_subtotal0'=>$not->ncr_subtotal0,
							'ncr_subtotal12'=>$not->ncr_subtotal12,
							'ncr_subtotal_ex_iva'=>$not->ncr_subtotal_ex_iva,
							'ncr_subtotal_no_iva'=>$not->ncr_subtotal_no_iva,
							'ncr_total_iva'=>$not->ncr_total_iva,
							'nrc_total_valor'=>$not->nrc_total_valor,
							'rgr_fecha_emision'=>$rfecha,
							'rgr_numero'=>$rnumero,
							'por_iva'=>$p_iva,
							'valor_iva'=>$v_iva,
							'por_renta'=>$p_ren,
							'valor_renta'=>$v_ren,
							'rgr_total_valor'=>$rvalor,
							);
			array_push($cns_notas, $nota);
		}

		// registro retenciones con facturas de otros periodos

		$cns_retenciones=array();
        if(!empty($cns_ret)){
	        foreach ($cns_ret as $rst5) {
	         
	            $rst_ret = $this->rep_declaracion_model->lista_retencion_factura($rst5->fac_numero, 1,$f1,$f2); //Retenciones
	           
	            $cns_reten=$this->rep_declaracion_model->lista_det_retenciones($rst5->fac_id,$f1,$f2);
	            $p_iva='';
	            $p_ren='';
	            $rgr_fecha_emision='';
	            $rgr_numero='';
	            $gr='';
	            foreach($cns_reten as $rst_rt){
	                if($rst_rt->drr_tipo_impuesto=='IV'){
	                    $p_iva.=$rst_rt->drr_procentaje_retencion.' ';
	                    $v_iva+=round($rst_rt->drr_valor,$dec);
	                }else if($rst_rt->drr_tipo_impuesto=='IR'){
	                    $p_ren.=$rst_rt->drr_procentaje_retencion.' ';
	                    $v_ren+=round($rst_rt->drr_valor,$dec);
	                }
	                if($gr!=$rst_rt->rgr_numero){
	                    $rgr_fecha_emision.=$rst_rt->rgr_fecha_emision.' ';
	                    $rgr_numero.=$rst_rt->rgr_numero.' ';
	                }
	                $gr=$rst_rt->rgr_numero;
	            }

	            $rvalor=round($v_iva,$dec)+round($v_ren,$dec);
	            $retencion=(object) array(
							'fac_id'=>$rst5->fac_id,
							'fac_fecha_emision'=>$rst5->fac_fecha_emision,
							'fac_numero'=>$rst5->fac_numero,
							'fac_nombre'=>$rst5->fac_nombre,
							'fac_identificacion'=>$rst5->fac_identificacion,
							'fac_subtotal'=>$rst5->fac_subtotal,
							'fac_total_descuento'=>$rst5->fac_total_descuento,
							'fac_subtotal0'=>$rst5->fac_subtotal0,
							'fac_subtotal12'=>$rst5->fac_subtotal12,
							'fac_subtotal_ex_iva'=>$rst5->fac_subtotal_ex_iva,
							'fac_subtotal_no_iva'=>$rst5->fac_subtotal_no_iva,
							'fac_total_iva'=>$rst5->fac_total_iva,
							'fac_total_valor'=>$rst5->fac_total_valor,

							'rgr_fecha_emision'=>$rgr_fecha_emision,
							'rgr_numero'=>$rgr_numero,
							'por_iva'=>$p_iva,
							'valor_iva'=>$v_iva,
							'por_renta'=>$p_ren,
							'valor_renta'=>$v_ren,
							'rgr_total_valor'=>$rvalor,
							);
				array_push($cns_retenciones, $retencion);
	        }
	    } 

	    //compras
		$cns_compras=array();
		foreach ($cns2 as $rst) {
            $cns_ncc = $this->rep_declaracion_model->lista_notcre_factura2($rst->reg_id,$f1,$f2); //Notas de credito
            $rnc_fecha_emision='';
            $rnc_numero='';
            $rnc_subtotal=0;
            $rnc_total_descuento=0;   
            $rnc_subtotal0=0; 
            $rnc_subtotal_ex_iva=0; 
            $rnc_subtotal_no_iva=0;
            $rnc_subtotal12=0;
            $rnc_total_iva=0;    
            $rnc_total_valor=0;
            $b=0;    
            foreach($cns_ncc as $rst_ncc){
                if($b>0){
                	$rnc_fecha_emision.='  '. $rst_ncc->rnc_fecha_emision;
                   	$rnc_numero.='  '. $rst_ncc->rnc_numero;
                }else{
                    $rnc_numero=$rst_ncc->rnc_numero;
                    $rnc_fecha_emision.=$rst_ncc->rnc_fecha_emision;
                }

                $rnc_subtotal+=round($rst_ncc->rnc_subtotal,$dec);
                $rnc_total_descuento+=round($rst_ncc->rnc_total_descuento,$dec);   
                $rnc_subtotal0+=round($rst_ncc->rnc_subtotal0,$dec);
                $rnc_subtotal_ex_iva+=round($rst_ncc->rnc_subtotal_ex_iva,$dec); 
                $rnc_subtotal_no_iva+=round($rst_ncc->rnc_subtotal_no_iva,$dec);
                $rnc_subtotal12+=round($rst_ncc->rnc_subtotal12,$dec);
                $rnc_total_iva+=round($rst_ncc->rnc_total_iva,$dec);    
                $rnc_total_valor+=round($rst_ncc->rnc_total_valor,$dec);

                $b++;
            }
            
            //retenciones de compras
            $reten2 = $this->rep_declaracion_model->lista_retencion_reg_factura($rst->reg_id,$f1,$f2); 
                                
			$rnumero2='';
			$rfecha2='';
			$rvalor2=0;
			if(!empty($reten2)){
				foreach($reten2 as $ret2){
					$rnumero2.=$ret2->ret_numero.' ';
					$rfecha2.=$ret2->ret_fecha_emision.' ';
				}	
			}

            $nc_tot = ($rst->reg_total - $rnc_total_valor);

            $cns_reten2=$this->rep_declaracion_model->lista_det_retencion($rst->reg_id,$f1,$f2);
            $p_iva2='';
            $p_ren2='';
            $v_iva2=0;
            $v_ren2=0;
            if(!empty($cns_reten2 )){
	            foreach($cns_reten2 as $rst_rt2){
	                if($rst_rt2->dtr_tipo_impuesto=='IV'){
	                    $p_iva2.=$rst_rt2->dtr_procentaje_retencion.' ';
	                    $v_iva2+=round($rst_rt2->dtr_valor,$dec);
	                }else if($rst_rt2->dtr_tipo_impuesto=='IR'){
	                    $p_ren2.=$rst_rt2->dtr_procentaje_retencion.' ';
	                    $v_ren2+=round($rst_rt2->dtr_valor,$dec);
	                }

	            }
	        }    
            
            $rvalor2=$v_iva2+$v_ren2;

            $compra=(object) array(
							'reg_id'=>$rst->reg_id,
							'reg_femision'=>$rst->reg_femision,
							'reg_num_documento'=>$rst->reg_num_documento,
							'cli_raz_social'=>$rst->cli_raz_social,
							'cli_ced_ruc'=>$rst->cli_ced_ruc,
							'reg_sbt'=>$rst->reg_sbt,
							'reg_tdescuento'=>$rst->reg_tdescuento,
							'reg_sbt0'=>$rst->reg_sbt0,
							'reg_sbt12'=>$rst->reg_sbt12,
							'reg_sbt_excento'=>$rst->reg_sbt_excento,
							'reg_sbt_noiva'=>$rst->reg_sbt_noiva,
							'reg_iva12'=>$rst->reg_iva12 ,
							'reg_total'=>$rst->reg_total,
							'rnc_fecha_emision'=>$rnc_fecha_emision,
							'rnc_numero'=>$rnc_numero,
							'rnc_subtotal'=>$rnc_subtotal,
							'rnc_total_descuento'=>$rnc_total_descuento,
							'rnc_subtotal0'=>$rnc_subtotal0,
							'rnc_subtotal12'=>$rnc_subtotal12,
							'rnc_subtotal_ex_iva'=>$rnc_subtotal_ex_iva,
							'rnc_subtotal_no_iva'=>$rnc_subtotal_no_iva,
							'rnc_total_iva'=>$rnc_total_iva,
							'rnc_total_valor'=>$rnc_total_valor,
							'ret_fecha_emision'=>$rfecha2,
							'ret_numero'=>$rnumero2,
							'por_iva'=>$p_iva2,
							'valor_iva'=>$v_iva2,
							'por_renta'=>$p_ren2,
							'valor_renta'=>$v_ren2,
							'ret_total_valor'=>$rvalor2,
							);
				array_push($cns_compras, $compra);
        }

        //notas compras
        $cns_notas_compras=array();
		foreach ($cns_ncr2 as $rst4) {
            $rst_ret = $this->rep_declaracion_model->lista_retencion_factura2($rst4->reg_id,$f1,$f2); //Retenciones
	            $rnumero3='';
				$rfecha3='';
				$rvalor3=0;
				if(!empty($rst_ret)){
					foreach($rst_ret as $ret3){
						$rnumero3.=$ret3->ret_numero.' ';
						$rfecha3.=$ret3->ret_fecha_emision.' ';
					}	
				}

                    $cns_reten3=$this->rep_declaracion_model->lista_det_retenciones2($rst4->reg_id,$f1,$f2);
                    $p_iva3='';
		            $p_ren3='';
		            $v_iva3=0;
		            $v_ren3=0;
		            if(!empty($cns_reten3 )){
			            foreach($cns_reten3 as $rst_rt3){
			                if($rst_rt3->dtr_tipo_impuesto=='IV'){
			                    $p_iva3.=$rst_rt3->dtr_procentaje_retencion.' ';
			                    $v_iva3+=round($rst_rt3->dtr_valor,$dec);
			                }else if($rst_rt3->dtr_tipo_impuesto=='IR'){
			                    $p_ren3.=$rst_rt3->dtr_procentaje_retencion.' ';
			                    $v_ren3+=round($rst_rt3->dtr_valor,$dec);
			                }

			            }
			        }    

            $nota_compra=(object) array(
							'reg_id'=>$rst4->reg_id,
							'reg_femision'=>$rst4->reg_femision,
							'reg_num_documento'=>$rst4->reg_num_documento,
							'cli_raz_social'=>$rst4->cli_raz_social,
							'cli_ced_ruc'=>$rst4->cli_ced_ruc,
							'reg_sbt'=>$rst4->reg_sbt,
							'reg_tdescuento'=>$rst4->reg_tdescuento,
							'reg_sbt0'=>$rst4->reg_sbt0,
							'reg_sbt12'=>$rst4->reg_sbt12,
							'reg_sbt_excento'=>$rst4->reg_sbt_excento,
							'reg_sbt_noiva'=>$rst4->reg_sbt_noiva,
							'reg_iva12'=>$rst4->reg_iva12 ,
							'reg_total'=>$rst4->reg_total,
							'rnc_fecha_emision'=>$rst4->rnc_fecha_emision,
							'rnc_numero'=>$rst4->rnc_numero,
							'rnc_subtotal'=>$rst4->rnc_subtotal,
							'rnc_total_descuento'=>$rst4->rnc_total_descuento,
							'rnc_subtotal0'=>$rst4->rnc_subtotal0,
							'rnc_subtotal12'=>$rst4->rnc_subtotal12,
							'rnc_subtotal_ex_iva'=>$rst4->rnc_subtotal_ex_iva,
							'rnc_subtotal_no_iva'=>$rst4->rnc_subtotal_no_iva,
							'rnc_total_iva'=>$rst4->rnc_total_iva,
							'rnc_total_valor'=>$rst4->rnc_total_valor,
							'ret_fecha_emision'=>$rfecha3,
							'ret_numero'=>$rnumero3,
							'por_iva'=>$p_iva3,
							'valor_iva'=>$v_iva3,
							'por_renta'=>$p_ren3,
							'valor_renta'=>$v_ren3,
							'ret_total_valor'=>$rvalor3,
							);
				array_push($cns_notas_compras, $nota_compra);
        }  

        //retencion compras
        $cns_retenciones_compras=array();
		foreach ($cns_ret2 as $rst6) {
            $rst_ret6 = $this->rep_declaracion_model->lista_retencion_factura2($rst6->reg_id,$f1,$f2); //Retenciones
	            $rnumero6='';
				$rfecha6='';
				$rvalor6=0;
				if(!empty($rst_ret6)){
					foreach($rst_ret6 as $ret6){
						$rnumero6.=$ret6->ret_numero.' ';
						$rfecha6.=$ret6->ret_fecha_emision.' ';
					}	
				}

                    $cns_reten6=$this->rep_declaracion_model->lista_det_retenciones2($rst6->reg_id,$f1,$f2);
                    $p_iva6='';
		            $p_ren6='';
		            $v_iva6=0;
		            $v_ren6=0;
		            if(!empty($cns_reten6)){
			            foreach($cns_reten6 as $rst_rt6){
			                if($rst_rt6->dtr_tipo_impuesto=='IV'){
			                    $p_iva6.=$rst_rt6->dtr_procentaje_retencion.' ';
			                    $v_iva6+=round($rst_rt6->dtr_valor,$dec);
			                }else if($rst_rt6->dtr_tipo_impuesto=='IR'){
			                    $p_ren6.=$rst_rt6->dtr_procentaje_retencion.' ';
			                    $v_ren6+=round($rst_rt6->dtr_valor,$dec);
			                }

			            }
			        }    

            $retencion_compra=(object) array(
							'reg_id'=>$rst6->reg_id,
							'reg_femision'=>$rst6->reg_femision,
							'reg_num_documento'=>$rst6->reg_num_documento,
							'cli_raz_social'=>$rst6->cli_raz_social,
							'cli_ced_ruc'=>$rst6->cli_ced_ruc,
							'reg_sbt'=>$rst6->reg_sbt,
							'reg_tdescuento'=>$rst6->reg_tdescuento,
							'reg_sbt0'=>$rst6->reg_sbt0,
							'reg_sbt12'=>$rst6->reg_sbt12,
							'reg_sbt_excento'=>$rst6->reg_sbt_excento,
							'reg_sbt_noiva'=>$rst6->reg_sbt_noiva,
							'reg_iva12'=>$rst6->reg_iva12 ,
							'reg_total'=>$rst6->reg_total,
							'rnc_fecha_emision'=>'',
							'rnc_numero'=>'',
							'rnc_subtotal'=>0,
							'rnc_total_descuento'=>0,
							'rnc_subtotal0'=>0,
							'rnc_subtotal12'=>0,
							'rnc_subtotal_ex_iva'=>0,
							'rnc_subtotal_no_iva'=>0,
							'rnc_total_iva'=>0,
							'rnc_total_valor'=>0,
							'ret_fecha_emision'=>$rfecha6,
							'ret_numero'=>$rnumero6,
							'por_iva'=>$p_iva6,
							'valor_iva'=>$v_iva6,
							'por_renta'=>$p_ren6,
							'valor_renta'=>$v_ren6,
							'ret_total_valor'=>$rvalor6,
							);
				array_push($cns_retenciones_compras, $retencion_compra);
        }       

		$data=array(
					'permisos'=>$this->permisos,
					'ventas'=>$cns_ventas,
					'notas'=>$cns_notas,
					'retenciones'=>$cns_retenciones,
					'compras'=>$cns_compras,
					'notas_compras'=>$cns_notas_compras,
					'retenciones_compras'=>$cns_retenciones_compras,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'dec'=>$dec,
					'cns_p'=>$cns_p,
					'cns_p2'=>$cns_p2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('rep_contables/lista_declaracion',$data);
		$modulo=array('modulo'=>'reg_retencion');
		$this->load->view('layout/footer_bodega',$modulo);
	}


	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Declaracion '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="rep_declaracion".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }
		
}
