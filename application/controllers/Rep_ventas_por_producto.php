<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ventas_por_producto extends CI_Controller {

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
		$this->load->model('rep_ventas_por_producto_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
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
		
		$empresa='1';
		$f1= date('Y-m-d');
		$f2= date('Y-m-d');
		$ids='26';
		$txt="";
		$cns_productos=$this->rep_ventas_por_producto_model->lista_productos_buscador($f1,$f2,$empresa,$ids,$txt);
		
		
		$locales=$this->emisor_model->lista_emisores_empresa($empresa);
		$locales2=$this->emisor_model->lista_emisores_empresa($empresa);
		$detalle="<table id='tbl_list' class='table table-bordered table-list table-hover'>
						<thead>
							<tr>
								<th colspan='2'>Producto</th>";
								
								if(!empty($locales)){
									foreach($locales as $local){
									$detalle.="<th colspan='2' class='enc'>$local->emi_nombre</th>";
								
									}
								}
								
								$detalle.="<th colspan='2'>Total</th>
							</tr>	
							<tr>	
								<th>Codigo</th>
								<th>Descripcion</th>";
								
								if(!empty($locales2)){
									foreach($locales2 as $local2){
								
								$detalle.="<th>Cant.</th>
									<th>Valor</th>";
								 
									}
								}
								
							$detalle.="<th>Cant.</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>";
						
		
		$dec=2;
		
		$gr_nm="";
		$th_cnt=0;
		$th_val=0;
		$s_sub12=0;
		$s_sub0=0;
		$s_sub=0;
		$s_ice=0;
		$s_iva=0;
		$s_total=0;
		$s_nc=0;
		
		if(!empty($cns_productos)){
			foreach ($cns_productos as $prod) {
				$n=0;;
				$detalle.="<tr>
								<td>$prod->mp_c</td>
								<td>$prod->mp_d</td>";
				$th_cnt=0;
				$th_val=0;				
				$locales3=$this->emisor_model->lista_emisores_empresa($empresa);
				if(!empty($locales3)){
					foreach ($locales3 as $loc3) {
						$n++;
						$rst_cnt=$this->rep_ventas_por_producto_model->lista_productos_local($loc3->emi_id,$prod->pro_id,$f1,$f2);
						$detalle.="<td class='cnt$n' >".number_format($rst_cnt->cantidad,$dec)."</td>
								<td class='val$n'>".number_format($rst_cnt->valor,$dec)."</td>";
						$th_cnt+=round($rst_cnt->cantidad,$dec);
						$th_val+=round($rst_cnt->valor,$dec);
					}
				}
				$detalle.="<td class='th_cnt'>".number_format($th_cnt,$dec)."</td>
							<td class='th_val'>".number_format($th_val,$dec)."</td>
							</tr>";

				
			}						
		}		
		$detalle.="<tr class='total'>
						<td colspan='2'>Total</td>";
					$n=0;	
					$locales4=$this->emisor_model->lista_emisores_empresa($empresa);
					if(!empty($locales4)){
						foreach ($locales4 as $loc3) {	
						$n++;		
						$detalle.="<td id='tv_cnt$n'></td>
								<td id='tv_val$n'></td>";
						}
					}			
		$detalle.="<td class='tv_cnt'></td>
					<td class='tv_val'></td>
				</tr>
				</tbody>
				</table>";
		


			$cns_empresas=$this->empresa_model->lista_empresas_estado('1');		
			
			$data=array(
						'permisos'=>$this->permisos,
						'empresas'=>$cns_empresas,
						'locales'=>$locales,
						'locales2'=>$locales2,
						'detalle'=>$detalle,
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'empresa'=>$empresa,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'ids'=>$ids,
						'txt'=>$txt,
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reportes/rep_ventas_por_producto',$data);
			$modulo=array('modulo'=>'reportes');
			$this->load->view('layout/footer',$modulo);
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

    	$titulo='Ventas por Producto ';
    	$file="rep_ventas_por_producto".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function buscar($empresa,$f1,$f2,$ids,$txt=""){
		
		$cns_productos=$this->rep_ventas_por_producto_model->lista_productos_buscador($f1,$f2,$empresa,$ids,$txt);
		$locales=$this->emisor_model->lista_emisores_empresa($empresa);
		$locales2=$this->emisor_model->lista_emisores_empresa($empresa);
		$detalle="<table id='tbl_list' class='table table-bordered table-list table-hover'>
						<thead>
							<tr>
								<th colspan='2'>Producto</th>";
								
								if(!empty($locales)){
									foreach($locales as $local){
									$detalle.="<th colspan='2' class='enc'>$local->emi_nombre</th>";
								
									}
								}
								
								$detalle.="<th colspan='2'>Total</th>
							</tr>	
							<tr>	
								<th>Codigo</th>
								<th>Descripcion</th>";
								
								if(!empty($locales2)){
									foreach($locales2 as $local2){
								
								$detalle.="<th>Cant.</th>
									<th>Valor</th>";
								 
									}
								}
								
							$detalle.="<th>Cant.</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>";
						
		
		$dec=2;
		
		$gr_nm="";
		$th_cnt=0;
		$th_val=0;
		$s_sub12=0;
		$s_sub0=0;
		$s_sub=0;
		$s_ice=0;
		$s_iva=0;
		$s_total=0;
		$s_nc=0;
		
		if(!empty($cns_productos)){
			foreach ($cns_productos as $prod) {
				$n=0;;
				$detalle.="<tr>
								<td>$prod->mp_c</td>
								<td>$prod->mp_d</td>";
				$th_cnt=0;
				$th_val=0;				
				$locales3=$this->emisor_model->lista_emisores_empresa($empresa);
				if(!empty($locales3)){
					foreach ($locales3 as $loc3) {
						$n++;
						$rst_cnt=$this->rep_ventas_por_producto_model->lista_productos_local($loc3->emi_id,$prod->pro_id,$f1,$f2);
						$detalle.="<td class='cnt$n number' >".number_format($rst_cnt->cantidad,$dec)."</td>
								<td class='val$n number'>".number_format($rst_cnt->valor,$dec)."</td>";
						$th_cnt+=round($rst_cnt->cantidad,$dec);
						$th_val+=round($rst_cnt->valor,$dec);
					}
				}
				$detalle.="<td class='th_cnt number'>".number_format($th_cnt,$dec)."</td>
							<td class='th_val number'>".number_format($th_val,$dec)."</td>
							</tr>";

				
			}						
		}		
		$detalle.="</tbody>
		<tfoot>
		<tr class='total'>
						<td colspan='2'>Total</td>";
					$n=0;	
					$locales4=$this->emisor_model->lista_emisores_empresa($empresa);
					if(!empty($locales4)){
						foreach ($locales4 as $loc3) {	
						$n++;		
						$detalle.="<td id='tv_cnt$n' class='number'>></td>
								<td id='tv_val$n' class='number'>></td>";
						}
					}			
		$detalle.="<td id='tv_cnt' class='number'>></td>
					<td id='tv_val' class='number'>></td>
				</tr>
				</tfoot>
				</table>";

			
			$data=array(
						'detalle'=>$detalle,
			);
			
			echo json_encode($data);
	}

     

}
