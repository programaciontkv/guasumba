<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ventas_por_punto extends CI_Controller {

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
		$this->load->model('rep_ventas_por_punto_model');
		$this->load->model('vendedor_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('opcion_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
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
		$mensaje='';
		$we =  intval($this->session->userdata('s_we'));
		if($we>=760){
		$mensaje='';
		}else{
		$mensaje='Para una mejor experiencia gire la pantalla de su celular';
		}

		///buscador 
		if($_POST){
			$mensaje='';
			$empresa= $this->input->post('empresa');
			$vendedor= $this->input->post('vendedor');
			$ventas= $this->input->post('ventas');
			$devoluciones= $this->input->post('devoluciones');
			$cajab= $this->input->post('cajab');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_facturas=$this->rep_ventas_por_punto_model->lista_factura_buscador($f1,$f2,$empresa);
		}else{
			$empresa='1';
			$vendedor= 'on';
			$ventas= 'on';
			$devoluciones= 'on';
			$cajab= 'on';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_facturas=$this->rep_ventas_por_punto_model->lista_factura_buscador($f1,$f2,$empresa);
		}
		if($vendedor=='on'){
			$vendedor='checked';
		}else{
			$vendedor='';
		}
		if($ventas=='on'){
			$ventas='checked';
		}else{
			$ventas='';
		}
		if($devoluciones=='on'){
			$devoluciones='checked';
		}else{
			$devoluciones='';
		}
		if($cajab=='on'){
			$cajab='checked';
		}else{
			$cajab='';
		}
		$dec=2;
		$detalle="";
		$gr_nm="";
		$s_nf=0;
		$s_desc=0;
		$s_sub12=0;
		$s_sub0=0;
		$s_sub=0;
		$s_ice=0;
		$s_iva=0;
		$s_total=0;
		$s_nc=0;
		$s_nsub12=0;
		$s_nsub0=0;
		$s_nsub=0;
		$s_niva=0;
		$s_ntotal=0;
		$s_tvd=0;
		$s_tc=0;
		$s_td=0;
		$s_ch=0;
		$s_ef=0;
		$s_crt=0;
		$s_tran=0;
		$s_ret=0;
		$s_not=0;
		$s_cre=0;
		$s_tcaja=0;
		$t_nf=0;
		$t_desc=0;
		$t_sub12=0;
		$t_sub0=0;
		$t_sub=0;
		$t_ice=0;
		$t_iva=0;
		$t_total=0;
		$t_nc=0;
		$t_nsub12=0;
		$t_nsub0=0;
		$t_nsub=0;
		$t_niva=0;
		$t_ntotal=0;
		$t_tvd=0;
		$t_tc=0;
		$t_td=0;
		$t_ch=0;
		$t_ef=0;
		$t_crt=0;
		$t_tran=0;
		$t_ret=0;
		$t_not=0;
		$t_cre=0;
		$t_tcaja=0;
		if(!empty($cns_facturas)){
			$gr="";
			$n=0;
			
			foreach ($cns_facturas as $factura) {
				$n++;
				if($gr!=$factura->emi_id && $n!=1){
					$detalle.="<tr class='subtotal'>
								<td>TOTAL $gr_nm</td>";
					if($ventas=='checked'){			
						$detalle.="<td class='hidden-mobile' class='number'>".number_format($s_nf)."</td>
								<td class='number'>".number_format($s_desc,$dec)."</td>
								<td class='number'>".number_format($s_sub12,$dec)."</td>
								<td class='number'>".number_format($s_sub0,$dec)."</td>
								<td class='number'>".number_format($s_sub,$dec)."</td>
								<td class='number'>".number_format($s_ice,$dec)."</td>
								<td class='number'>".number_format($s_iva,$dec)."</td>
								<td class='number'>".number_format($s_total,$dec)."</td>";
					}			
					if($devoluciones=='checked'){			
						$detalle.="<td class='hidden-mobile' class='number'>".number_format($s_nc)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_nsub12,$dec)."</td>
								<td class='hidden-mobile'  class='number'>".number_format($s_nsub0,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_nsub,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_niva,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ntotal,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_tvd,$dec)."</td>";
					}
					if($cajab=='checked'){			
						$detalle.="<td class='number'>".number_format($s_tc,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_td,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ch,$dec)."</td>
								<td class='hidden-mobile'  class='number'>".number_format($s_ef,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_crt,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_tran,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ret,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_not,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_cre,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_tcaja,$dec)."</td>";
					}			
					$detalle.="</tr>";
					
							$s_nf=0;
							$s_desc=0;
							$s_sub12=0;
							$s_sub0=0;
							$s_sub=0;
							$s_ice=0;
							$s_iva=0;
							$s_total=0;
							$s_nc=0;
							$s_nsub12=0;
							$s_nsub0=0;
							$s_nsub=0;
							$s_niva=0;
							$s_ntotal=0;
							$s_tvd=0;
							$s_tc=0;
							$s_td=0;
							$s_ch=0;
							$s_ef=0;
							$s_crt=0;
							$s_tran=0;
							$s_ret=0;
							$s_not=0;
							$s_cre=0;
							$s_tcaja=0;
				}
				if($gr!=$factura->emi_id){
					$detalle.="<tr class='local'>
								<td colspan='25'>$factura->emi_nombre</td>
							</tr>";
				}	
				$nota=$this->rep_ventas_por_punto_model->lista_nota_credito($f1,$f2,$factura->emp_id,$factura->emi_id);
				if(empty($nota->nc)){
					$nota= (object) array(
								'nc'=>0,
								'subtotal12'=>0,
								'subtotal_sin_iva'=>0,
								'subtotal'=>0,
								'subtotal12'=>0,
								'iva'=>0,
								'total'=>0,
								);
				}
				$tvd=round($factura->total,$dec)-round($nota->total,$dec);
				$caja=$this->rep_ventas_por_punto_model->lista_cierre_caja($f1,$f2,$factura->emp_id,$factura->emi_id);
				$tcaja=round($caja->tc,$dec)+round($caja->td,$dec)+round($caja->cheque,$dec)+round($caja->efectivo,$dec)+round($caja->certificados,$dec)+round($caja->transferencia,$dec)+round($caja->retencion,$dec)+round($caja->nc,$dec)+round($caja->credito,$dec);
				if($vendedor=='checked'){
					$detalle.="<tr>
							<td>".$factura->vnd_nombre."</td>";
					if($ventas=='checked'){
						$detalle.= "<td class='hidden-mobile' class='number'>".number_format($factura->nf)."</td>
									<td class='number'>".number_format($factura->descuento,$dec)."</td>
									<td class='number'>".number_format($factura->subtotal12,$dec)."</td>
									<td class='number'>".number_format($factura->subtotal_sin_iva,$dec)."</td>
									<td class='number'>".number_format($factura->subtotal,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($factura->ice,$dec)."</td>
									<td class='number'>".number_format($factura->iva,$dec)."</td>
									<td class='number'>".number_format($factura->total,$dec)."</td>";
					}	
					if($devoluciones=='checked'){			
						$detalle.= "<td class='hidden-mobile' class='number'>".number_format($nota->nc)."</td>
									<td class='hidden-mobile'  class='number'>".number_format($nota->subtotal12,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($nota->subtotal_sin_iva,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($nota->subtotal,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($nota->iva,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($nota->total,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($tvd,$dec)."</td>";
					}				
					if($cajab=='checked'){			
						$detalle.= "<td class='hidden-mobile' class='number'>".number_format($caja->tc,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->td,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->cheque,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->efectivo,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->certificados,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->transferencia,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->retencion,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->nc,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($caja->credito,$dec)."</td>
									<td class='hidden-mobile' class='number'>".number_format($tcaja,$dec)."</td>";
					}				
				$detalle.= "</tr>";
				}
				$gr=$factura->emi_id;
				$gr_nm=$factura->emi_nombre;
				$s_nf+=	round($factura->nf);	
				$s_desc+= round($factura->descuento,$dec);	
				$s_sub12+= round($factura->subtotal12,$dec);	
				$s_sub0+= round($factura->subtotal_sin_iva,$dec);	
				$s_sub+= round($factura->subtotal,$dec);	
				$s_ice+= round($factura->ice,$dec);	
				$s_iva+= round($factura->iva,$dec);	
				$s_total+= round($factura->total,$dec);	
				$s_nc+=round($nota->nc);	
				$s_nsub12+=round($nota->subtotal12,$dec);	
				$s_nsub0+=round($nota->subtotal_sin_iva,$dec);	
				$s_nsub+=round($nota->subtotal,$dec);	
				$s_niva+=round($nota->iva,$dec);	
				$s_ntotal+=round($nota->total,$dec);	
				$s_tvd+=round($tvd,$dec);	
				$s_tc+=round($caja->tc,$dec);	
				$s_td+=round($caja->td,$dec);	
				$s_ch+=round($caja->cheque,$dec);	
				$s_ef+=round($caja->efectivo,$dec);	
				$s_crt+=round($caja->certificados,$dec);	
				$s_tran+=round($caja->transferencia,$dec);	
				$s_ret+=round($caja->retencion,$dec);	
				$s_not+=round($caja->nc,$dec);	
				$s_cre+=round($caja->credito,$dec);	
				$s_tcaja+=round($tcaja,$dec);	

				$t_nf+=	round($factura->nf);	
				$t_desc+= round($factura->descuento,$dec);	
				$t_sub12+= round($factura->subtotal12,$dec);	
				$t_sub0+= round($factura->subtotal_sin_iva,$dec);	
				$t_sub+= round($factura->subtotal,$dec);	
				$t_ice+= round($factura->ice,$dec);	
				$t_iva+= round($factura->iva,$dec);	
				$t_total+= round($factura->total,$dec);	
				$t_nc+=round($nota->nc);	
				$t_nsub12+=round($nota->subtotal12,$dec);	
				$t_nsub0+=round($nota->subtotal_sin_iva,$dec);	
				$t_nsub+=round($nota->subtotal,$dec);	
				$t_niva+=round($nota->iva,$dec);	
				$t_ntotal+=round($nota->total,$dec);	
				$t_tvd+=round($tvd,$dec);	
				$t_tc+=round($caja->tc,$dec);	
				$t_td+=round($caja->td,$dec);	
				$t_ch+=round($caja->cheque,$dec);	
				$t_ef+=round($caja->efectivo,$dec);	
				$t_crt+=round($caja->certificados,$dec);	
				$t_tran+=round($caja->transferencia,$dec);	
				$t_ret+=round($caja->retencion,$dec);	
				$t_not+=round($caja->nc,$dec);	
				$t_cre+=round($caja->credito,$dec);	
				$t_tcaja+=round($tcaja,$dec);	
			}
		}
		$detalle.="<tr class='subtotal'>
								<td>TOTAL $gr_nm</td>";
		if($ventas=='checked'){						
					$detalle.="<td class='hidden-mobile' class='number'>".number_format($s_nf)."</td>
								<td class='number'>".number_format($s_desc,$dec)."</td>
								<td class='number'>".number_format($s_sub12,$dec)."</td>
								<td class='number'>".number_format($s_sub0,$dec)."</td>
								<td class='number'>".number_format($s_sub,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ice,$dec)."</td>
								<td class='number'>".number_format($s_iva,$dec)."</td>
								<td class='number'>".number_format($s_total,$dec)."</td>";
		}						
		if($devoluciones=='checked'){						
					$detalle.="<td class='hidden-mobile' class='number'>".number_format($s_nc)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_nsub12,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_nsub0,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_nsub,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_niva,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ntotal,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_tvd,$dec)."</td>";
		}
		if($cajab=='checked'){						
					$detalle.="<td class='hidden-mobile' class='number'>".number_format($s_tc,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_td,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ch,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ef,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_crt,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_tran,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_ret,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_not,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_cre,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($s_tcaja,$dec)."</td>";
		}						
		$detalle.="</tr>";			
		$detalle.="<tr class='total'>
								<td>TOTAL</td>";
				if($ventas=='checked'){						
					$detalle.="	<td class='hidden-mobile' class='number'>".number_format($t_nf)."</td>
								<td class='number'>".number_format($t_desc,$dec)."</td>
								<td class='number'>".number_format($t_sub12,$dec)."</td>
								<td class='number'>".number_format($t_sub0,$dec)."</td>
								<td class='number'>".number_format($t_sub,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_ice,$dec)."</td>
								<td class='number'>".number_format($t_iva,$dec)."</td>
								<td class='number'>".number_format($t_total,$dec)."</td>";
				}				
				if($devoluciones=='checked'){						
					$detalle.="<td class='hidden-mobile' class='number'>".number_format($t_nc)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_nsub12,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_nsub0,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_nsub,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_niva,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_ntotal,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_tvd,$dec)."</td>";
				}				
				if($cajab=='checked'){						
					$detalle.="<td class='hidden-mobile' class='number'>".number_format($t_tc,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_td,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_ch,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_ef,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_crt,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_tran,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_ret,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_not,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_cre,$dec)."</td>
								<td class='hidden-mobile' class='number'>".number_format($t_tcaja,$dec)."</td>";
				}				
					$detalle.="</tr>";	

					
															
			$cns_empresas=$this->empresa_model->lista_empresas_estado('1');		
			$data=array(
						'permisos'=>$this->permisos,
						'empresas'=>$cns_empresas,
						'detalle'=>$detalle,
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'empresa'=>$empresa,
						'vendedor'=>$vendedor,
						'ventas'=>$ventas,
						'devoluciones'=>$devoluciones,
						'cajab'=>$cajab,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'mensaje'=> $mensaje
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reportes/rep_ventas_por_punto',$data);
			$modulo=array('modulo'=>'reportes');
			$this->load->view('layout/footer_bodega',$modulo);
	}


	
	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'producto'=>$this->factura_model->lista_un_producto($id)
						);
			$this->load->view('factura/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	
	
	
	public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Factura '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"factura/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }


    public function show_pdf($id,$opc_id){
    		$rst=$this->factura_model->lista_una_factura($id);
    		$imagen=$this->set_barcode($rst->fac_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
			$cns_pg=$this->factura_model->lista_pagos_factura($id);
			$cns_pag=array();
			foreach ($cns_pg as $rst_pg) {
				$dt_pg= (object) array(
							'pag_forma'=>$rst_pg->pag_forma,
							'fpg_descripcion'=>$rst_pg->fpg_descripcion,
							'pag_tipo'=>$rst_pg->fpg_tipo,
							'chq_numero'=>$rst_pg->chq_numero,
							'pag_id_chq'=>$rst_pg->pag_id_chq,
							'pag_contado'=>$rst_pg->pag_contado,
							'pag_cant'=>$rst_pg->pag_cant,
								);
				array_push($cns_pag, $dt_pg);
			}

			///recupera detalle
			$cns_dt=$this->factura_model->lista_detalle_factura($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
			$dt_det=(object) array(
						'pro_id'=>$rst_dt->pro_id,
						'ids'=>$rst_dt->ids,
						'pro_descripcion'=>$rst_dt->mp_d,
						'pro_codigo'=>$rst_dt->mp_c,
						'pro_precio'=>$rst_dt->dfc_precio_unit,
						'pro_iva'=>$rst_dt->dfc_iva,
						'pro_descuento'=>$rst_dt->dfc_porcentaje_descuento,
						'pro_descuent'=>$rst_dt->dfc_val_descuento,
						'pro_unidad'=>$rst_dt->mp_q,
						'cantidad'=>$rst_dt->dfc_cantidad,
						'ice'=>$rst_dt->dfc_ice,
						'ice_p'=>$rst_dt->dfc_p_ice,
						'ice_cod'=>$rst_dt->dfc_cod_ice,
						'precio_tot'=>$rst_dt->dfc_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'factura'=>$this->factura_model->lista_una_factura($id),
						'cns_det'=>$cns_det,
						'cns_pag'=>$cns_pag,
						);
			$this->html2pdf->filename('factura.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_factura', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    	
    }  

    
	
    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

    	$titulo='Reportes por Punto de Facturacion ';
    	$file="rep_ventas_por_punto".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

     

}
