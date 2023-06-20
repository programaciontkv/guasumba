<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(30);

class Kardex extends CI_Controller {


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
		$this->load->model('kardex_model');
		$this->load->model('cliente_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('configuracion_model');
		$this->load->model('empresa_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->library('export_excel');
		$this->load->library('html2pdf');
		$this->load->library('html5pdf');
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
			$text= trim($this->input->post('txt'));
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}
			$cns_mov=$this->kardex_model->lista_kardex_buscador($text,$ids,$f1,$f2,$txt);						
		}else{
			$text= '';
			$ids= '26';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');		
			$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}
			$cns_mov=$this->kardex_model->lista_kardex_buscador($text,$ids,$f1,$f2,$txt);										
		}
		$dec=$this->configuracion_model->lista_una_configuracion('2');
		$dcc=$this->configuracion_model->lista_una_configuracion('1');
		$detalle='';
		$grup='';
		$dec=$dec->con_valor;
		$dcc=$dcc->con_valor;
		$n=0;
		$inicial=0;
		$ini_uni=0;
		$ini_tot=0;
		$t_cnt_ing=0;
		$t_ctu_ing=0;
		$t_ctt_ing=0;
		$t_cnt_egr=0;
		$t_ctu_egr=0;
		$t_ctt_egr=0;
		$t_saldo=0;
		$t_sal_uni=0;
		$t_sal_tot=0;	
		$a='"@"';

	$detalle="<table id='detalle' style='' border='1'  width='100%' style='' class='table table-bordered table-list table-hover table-striped'>

						<thead id='tbl_thead'> 
							<tr class='h-mobile' >
								<th   colspan='4' style='text-align: center;'>Documento</th>
								<th  colspan='3' style='text-align: center;'>Producto</th>
								<th    style='text-align: center;'>Transaccion</th>
								<th  colspan='3' style='text-align: center;'>Ingreso</th>
								<th colspan='3' style='text-align: center;'>Egreso</th>
								<th  colspan='3' style='text-align: center;'>Saldo</th>
							</tr>
							<tr class='show-mobile' >
								<th  colspan='2' style='text-align: center;width:30px'>Documento</th>
								<th  colspan='1' style='text-align: center;'>Producto</th>
								<th  colspan='2' style='text-align: center;'>Ingreso</th>
								<th colspan='2' style='text-align: center;'>Egreso</th>
								<th  colspan='3' style='text-align: center;'>Saldo</th>
							</tr>
							<tr>
								<th style='text-align: center;width:30px'>Fecha</th>
								<th >Documento No</th>
								<th class='h-mobile'  >Documento/ Informacion</th>
								<th class='h-mobile'>Proveedor</th>
								<th class='h-mobile' >Codigo</th>
								<th>Descripcion</th>
								<th class='h-mobile'>Unidad</th>
								<th class='h-mobile'>Tipo</th>
								<th>Cantidad</th>
								<th class='h-mobile' >Costo Unitario</th>
								<th >Costo Total</th>
								<th>Cantidad</th>
								<th class='h-mobile'>Costo Unitario</th>
								<th >Costo Total</th>
								<th>Cantidad</th>
								<th class='h-mobile' >Costo Unitario</th>
								<th>Costo Total</th>
							</tr>
						</thead>
						<tbody>";					
		if(!empty($cns_mov)){
			foreach ($cns_mov as $kardex) {
				$n++;
				//totales
					if($grup!=$kardex->pro_id && $n!=1){
						$t_saldo=$saldo;
						$t_sal_uni=$sal_uni;
						$t_sal_tot=$sal_tot;
						
						
						$detalle.="<tr class='success'>
								<td class='total' style='font-weight: bolder;> TOTAL</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total h-mobile' style='font-weight: bolder;'> </td>
								<td class='total h-mobile' style='font-weight: bolder;'> </td>
								<td class='total h-mobile' style='font-weight: bolder;'> </td>
								<td  class='total h-mobile ' style='font-weight: bolder; mso-number-format:$a'> $g_cod</td>
								<td class='total' style='font-weight: bolder;'> $g_desc</td>
								<td class='total h-mobile' style='font-weight: bolder;'> $g_uni</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='number total' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_cnt_ing,$dcc))."</td>
								<td class='number total h-mobile' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_ctu_ing,$dec))."</td>
								<td  class='number total' style='font-weight: bolder; text-align:center'>". str_replace(',','', number_format($t_ctt_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_egr,$dcc))."</td>
								<td class='number total h-mobile' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_ctu_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_ctt_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_saldo,$dcc))."</td>
								<td class='number total  h-mobile' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_sal_uni,$dec))."</td>
								<td class='number total' style='font-weight: bolder;text-align:center'>". str_replace(',','', number_format($t_sal_tot,$dec))."</td>
								</tr>";
						$inicial=0;
						$ini_uni=0;
						$ini_tot=0;		
						$t_cnt_ing=0;
						$t_ctu_ing=0;
						$t_ctt_ing=0;
						$t_cnt_egr=0;
						$t_ctu_egr=0;
						$t_ctt_egr=0;
						$t_saldo=0;
						$t_sal_uni=0;
						$t_sal_tot=0;		

					}

				//saldo inicial 
					if($grup!=$kardex->pro_id){
						$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
						if($ctr_inv->con_valor==0){
							$txt="and emp_id= $rst_cja->emp_id and mov_fecha_trans<'$f1'";
						}else{
							$txt="and bod_id=$rst_cja->emi_id and mov_fecha_trans<'$f1'";
						}
						$rst_sal=$this->kardex_model->lista_costos_mov($kardex->pro_id,$txt);
						$inicial=round($rst_sal->icnt,$dcc)-round($rst_sal->ecnt,$dcc);
						$ini_tot=round($rst_sal->ingreso,$dcc)-round($rst_sal->egreso,$dec);
						if($inicial>0 && $ini_tot>0){
							$ini_uni=$ini_tot/$inicial;
						}else{
							$ini_uni=0;
						}
						$detalle.="<tr>
								<td class='h-mobile inicial'  style='font-weight: bolder;'> </td>
								<td class='h-mobile inicial' style='font-weight: bolder;'> </td>
								<td class='h-mobile  inicial'  style='font-weight: bolder;'> </td>
								<td  class='h-mobile  inicial' style='font-weight: bolder;'> </td>
								<td class=' h-mobile  inicial' style='mso-number-format:$a; font-weight: bolder;'></td>
								<td class='h-mobile  inicial' style='font-weight: bolder;'> </td>
								<td  class='h-mobile inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='font-weight: bolder;'> SALDO INICIAL</td>
								<td  class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'>". str_replace(',','', number_format($inicial,$dcc))."</td>
								<td  class='h-mobile number inicial' style='font-weight: bolder;'>". str_replace(',','', number_format($ini_uni,$dec))."</td>
								<td class='number inicial' style='font-weight: bolder;'>". str_replace(',','', number_format($ini_tot,$dec))."</td>
								</tr>";
					}
					

							if($kardex->trs_operacion==0){
								$cnt_ing=str_replace(',','', number_format($kardex->mov_cantidad,$dcc));
								$ctu_ing=str_replace(',','', number_format($kardex->mov_val_unit,$dec));
								$ctt_ing=str_replace(',','', number_format($kardex->mov_val_tot,$dec));
								$cnt_egr='';
								$ctu_egr='';
								$ctt_egr='';
							}else{
								$cnt_ing='';
								$cnt_ing='';
								$ctu_ing='';
								$ctt_ing='';
								$cnt_egr=str_replace(',','', number_format($kardex->mov_cantidad,$dcc));
								$ctu_egr=str_replace(',','', number_format($kardex->mov_val_unit,$dec));
								$ctt_egr=str_replace(',','', number_format($kardex->mov_val_tot,$dec));
							}	

							$saldo=round($inicial,$dcc)+round($cnt_ing,$dcc)-round($cnt_egr,$dcc);
							$sal_tot=round($ini_tot,$dcc)+round($ctt_ing,$dec)-round($ctt_egr,$dec);
							if(round($sal_tot,$dec)==0 || round($saldo,$dec)==0){
								$sal_uni=0;
							}else{
								$sal_uni=round($sal_tot,$dec)/round($saldo,$dec);
							}
								    
							$detalle.="<tr>
								<td > $kardex->mov_fecha_trans</td>
								<td style='mso-number-format:$a'> $kardex->mov_documento</td>
								<td class='h-mobile' style='mso-number-format:$a'> $kardex->mov_guia_transporte</td>
								<td class='h-mobile'>  $kardex->cli_raz_social</td>
								<td class='h-mobile' style='mso-number-format:$a'> $kardex->mp_c</td>
								<td> $kardex->mp_d</td>
								<td class='h-mobile' > $kardex->mp_q</td>
								<td class='h-mobile'>  $kardex->trs_descripcion</td>
								<td style='text-align: center'  class='number'>".number_format(floatval($cnt_ing),2) ." </td>
								<td style='text-align: center 'class='h-mobile number' >".number_format(floatval($ctu_ing),2) ." </td>
								<td style='text-align: center' class='number'>" .number_format(floatval($ctt_ing),2) ." </td>
								<td style='text-align: center' class='number'>" .number_format(floatval($cnt_egr),2) ." </td>
								<td style='text-align: center' class='h-mobile number' >" .number_format(floatval($ctu_egr),2) ." </td>
								<td style='text-align: center' class='number'>" .number_format(floatval($ctt_egr),2) ." </td>
								<td style='text-align: center' class='number'>". str_replace(',','', number_format($saldo,$dcc))."</td>
								<td style='text-align: center' class='h-mobile number'>". str_replace(',','', number_format($sal_uni,$dec))."</td>
								<td style='text-align: center' class='number'>". str_replace(',','', number_format($sal_tot,$dec))."</td>
							</tr>";


							$grup=$kardex->pro_id;
							$g_cod=$kardex->mp_c;
							$g_desc=$kardex->mp_d;
							$g_uni=$kardex->mp_q;
							$inicial=$saldo;
							$ini_tot=$sal_tot;
							$t_cnt_ing+=round($cnt_ing,$dcc);
							$t_ctu_ing+=round($ctu_ing,$dec);
							$t_ctt_ing+=round($ctt_ing,$dec);
							$t_cnt_egr+=round($cnt_egr,$dcc);
							$t_ctu_egr+=round($ctu_egr,$dec);
							$t_ctt_egr+=round($ctt_egr,$dec);
							// $t_saldo+=round($saldo,$dcc);
							// $t_sal_uni+=round($sal_uni,$dec);
							// $t_sal_tot+=round($sal_tot,$dec);
							}
							$detalle.="<tr class='success'>
								<td class='total' style='font-weight: bolder;'> TOTAL</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total h-mobile' style='font-weight: bolder;'> </td>
								<td class='total h-mobile'    style='mso-number-format:$a; font-weight: bolder;'> $g_cod</td>
								<td class='total' style='font-weight: bolder;'> $g_desc</td>
								<td class='total h-mobile'  style='font-weight: bolder;'> $g_uni</td>
								<td class='total h-mobile' style='font-weight: bolder;'> </td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_ing,$dcc))."</td>
								<td  class=' h-mobile number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctu_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctt_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_egr,$dcc))."</td>
								<td  class='h-mobile number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctu_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctt_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($saldo,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($sal_uni,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($sal_tot,$dec))."</td>
								</tr>";
						}
						$detalle.="</tbody>
								</table>";
		$data=array(
					'permisos'=>$this->permisos,
					'kardexs'=>$cns_mov,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'ids'=>$ids,
					'fec1'=>$f1,
					'fec2'=>$f2,
					'detalle'=>$detalle,
					'mensaje'=> $mensaje,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());


		$this->load->view('kardex/lista',$data);
		$modulo=array('modulo'=>'kardex');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Kardex '.ucfirst(strtolower($rst_cja->emi_nombre));
    	$file="Kardex".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function show_frame($opc_id){
    	if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$ids= '26';
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$ids= '26';
		}
    	$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Kardex ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"kardex/show_pdf/$opc_id/$ids/$fec1/$fec2/$text",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>$ids,
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
			$modulo=array('modulo'=>'kardex');
			$this->load->view('layout/footer',$modulo);

		}
    	
    }

    
    public function show_pdf($opc_id,$ids,$f1,$f2,$text=''){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emisor=$this->emisor_model->lista_un_emisor($rst_cja->emi_id);
		
		$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
			if($ctr_inv->con_valor==0){
				$txt="and emp_id= $rst_cja->emp_id";
			}else{
				$txt="and bod_id=$rst_cja->emi_id";
			}

    	$cns_mov=$this->kardex_model->lista_kardex_buscador(urlencode($text),$ids,$f1,$f2,$txt);
    	$dec=$this->configuracion_model->lista_una_configuracion('2');
		$dcc=$this->configuracion_model->lista_una_configuracion('1');
		$detalle='';
		$grup='';
		$dec=$dec->con_valor;
		$dcc=$dcc->con_valor;
		$n=0;
		$inicial=0;
		$ini_uni=0;
		$ini_tot=0;
		$t_cnt_ing=0;
		$t_ctu_ing=0;
		$t_ctt_ing=0;
		$t_cnt_egr=0;
		$t_ctu_egr=0;
		$t_ctt_egr=0;
		$t_saldo=0;
		$t_sal_uni=0;
		$t_sal_tot=0;	
		$a='"@"';
		$detalle="<table id='tbl_list'  border='1'>
						<thead id='tbl_thead'> 
							<tr>
								<th colspan='4' style='text-align: center;'>Documento</th>
								<th colspan='3' style='text-align: center;'>Producto</th>
								<th style='text-align: center;'>Transaccion</th>
								<th colspan='3' style='text-align: center;'>Ingreso</th>
								<th colspan='3' style='text-align: center;'>Egreso</th>
								<th colspan='3' style='text-align: center;'>Saldo</th>
							</tr>
							<tr>
								<th>Fecha</th>
								<th>Documento No</th>
								<th>Documento/ Informacion</th>
								<th>Proveedor</th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Unidad</th>
								<th>Tipo</th>
								<th>Cantidad</th>
								<th>Costo Unitario</th>
								<th>Costo Total</th>
								<th>Cantidad</th>
								<th>Costo Unitario</th>
								<th>Costo Total</th>
								<th>Cantidad</th>
								<th>Costo Unitario</th>
								<th>Costo Total</th>
							</tr>
						</thead>
						<tbody>";					
		if(!empty($cns_mov)){
			foreach ($cns_mov as $kardex) {
				
				$n++;
				//totales
					if($grup!=$kardex->pro_id && $n!=1){
						$t_saldo=$saldo;
						$t_sal_uni=$sal_uni;
						$t_sal_tot=$sal_tot;
						
						
						$detalle.="<tr class='success'>
								<td class='total' style='font-weight: bolder;'> Total</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='font-weight: bolder; mso-number-format:$a'> $g_cod</td>
								<td class='total' style='font-weight: bolder;'>". ucwords(strtolower($g_desc))."</td>
								<td class='total' style='font-weight: bolder;'> ". strtolower($g_uni)."</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_ing,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctu_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctt_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_egr,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctu_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctt_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_saldo,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_sal_uni,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_sal_tot,$dec))."</td>
								</tr>";
						$inicial=0;
						$ini_uni=0;
						$ini_tot=0;		
						$t_cnt_ing=0;
						$t_ctu_ing=0;
						$t_ctt_ing=0;
						$t_cnt_egr=0;
						$t_ctu_egr=0;
						$t_ctt_egr=0;
						$t_saldo=0;
						$t_sal_uni=0;
						$t_sal_tot=0;		
						$n++;
					}

				//saldo inicial 
					if($grup!=$kardex->pro_id){
						$ctr_inv=$this->configuracion_model->lista_una_configuracion('6');
						if($ctr_inv->con_valor==0){
							$txt="and emp_id= $rst_cja->emp_id and mov_fecha_trans<'$f1'";
						}else{
							$txt="and bod_id=$rst_cja->emi_id and mov_fecha_trans<'$f1'";
						}
						$rst_sal=$this->kardex_model->lista_costos_mov($kardex->pro_id,$txt);
						$inicial=round($rst_sal->icnt,$dcc)-round($rst_sal->ecnt,$dcc);
						$ini_tot=round($rst_sal->ingreso,$dcc)-round($rst_sal->egreso,$dec);
						if($inicial>0 && $ini_tot>0){
							$ini_uni=$ini_tot/$inicial;
						}else{
							$ini_uni=0;
						}
						$detalle.="<tr>
								<td class='inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='mso-number-format:$a; font-weight: bolder;'></td>
								<td class='inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='font-weight: bolder;'> </td>
								<td class='inicial' style='font-weight: bolder;'> Saldo Inicial</td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'> </td>
								<td class='number inicial' style='font-weight: bolder;'>". str_replace(',','', number_format($inicial,$dcc))."</td>
								<td class='number inicial' style='font-weight: bolder;'>". str_replace(',','', number_format($ini_uni,$dec))."</td>
								<td class='number inicial' style='font-weight: bolder;'>". str_replace(',','', number_format($ini_tot,$dec))."</td>
								</tr>";
								$n++;
					}
					

							if($kardex->trs_operacion==0){
								$cnt_ing=str_replace(',','', number_format($kardex->mov_cantidad,$dcc));
								$ctu_ing=str_replace(',','', number_format($kardex->mov_val_unit,$dec));
								$ctt_ing=str_replace(',','', number_format($kardex->mov_val_tot,$dec));
								$cnt_egr='';
								$ctu_egr='';
								$ctt_egr='';
							}else{
								$cnt_ing='';
								$cnt_ing='';
								$ctu_ing='';
								$ctt_ing='';
								$cnt_egr=str_replace(',','', number_format($kardex->mov_cantidad,$dcc));
								$ctu_egr=str_replace(',','', number_format($kardex->mov_val_unit,$dec));
								$ctt_egr=str_replace(',','', number_format($kardex->mov_val_tot,$dec));
							}	

							$saldo=round($inicial,$dcc)+round($cnt_ing,$dcc)-round($cnt_egr,$dcc);
							$sal_tot=round($ini_tot,$dcc)+round($ctt_ing,$dec)-round($ctt_egr,$dec);
							if(round($sal_tot,$dec)==0 || round($saldo,$dec)==0){
								$sal_uni=0;
							}else{
								$sal_uni=round($sal_tot,$dec)/round($saldo,$dec);
							}
							
							$detalle.="<tr>
								<td> $kardex->mov_fecha_trans</td>
								<td style='mso-number-format:$a'> $kardex->mov_documento</td>
								<td style='mso-number-format:$a'> $kardex->mov_guia_transporte</td>
								<td> ". ucwords(strtolower($kardex->cli_raz_social))."</td>
								<td style='mso-number-format:$a'> $kardex->mp_c</td>
								<td> ". ucwords(strtolower($kardex->mp_d))."</td>
								<td> ". strtolower($kardex->mp_q)."</td>
								<td> ". ucwords(strtolower($kardex->trs_descripcion))."</td>
								<td class='number'> $cnt_ing</td>
								<td class='number'> $ctu_ing</td>
								<td class='number'> $ctt_ing</td>
								<td class='number'> $cnt_egr</td>
								<td class='number'> $ctu_egr</td>
								<td class='number'> $ctt_egr</td>
								<td class='number'>". str_replace(',','', number_format($saldo,$dcc))."</td>
								<td class='number'>". str_replace(',','', number_format($sal_uni,$dec))."</td>
								<td class='number'>". str_replace(',','', number_format($sal_tot,$dec))."</td>
							</tr>";
							$n++;
							$grup=$kardex->pro_id;
							$g_cod=$kardex->mp_c;
							$g_desc=$kardex->mp_d;
							$g_uni=$kardex->mp_q;
							$inicial=$saldo;
							$ini_tot=$sal_tot;
							$t_cnt_ing+=round($cnt_ing,$dcc);
							$t_ctu_ing+=round($ctu_ing,$dec);
							$t_ctt_ing+=round($ctt_ing,$dec);
							$t_cnt_egr+=round($cnt_egr,$dcc);
							$t_ctu_egr+=round($ctu_egr,$dec);
							$t_ctt_egr+=round($ctt_egr,$dec);
							// $t_saldo+=round($saldo,$dcc);
							// $t_sal_uni+=round($sal_uni,$dec);
							// $t_sal_tot+=round($sal_tot,$dec);
							}
							$detalle.="<tr class='success'>
								<td class='total' style='font-weight: bolder;'> Total</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='total' style='mso-number-format:$a; font-weight: bolder;'> $g_cod</td>
								<td class='total' style='font-weight: bolder;'>". ucwords(strtolower($g_desc))."</td>
								<td class='total' style='font-weight: bolder;'>". strtolower($g_uni)."</td>
								<td class='total' style='font-weight: bolder;'> </td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_ing,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctu_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctt_ing,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_cnt_egr,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctu_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($t_ctt_egr,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($saldo,$dcc))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($sal_uni,$dec))."</td>
								<td class='number total' style='font-weight: bolder;'>". str_replace(',','', number_format($sal_tot,$dec))."</td>
								</tr>";
								$n++;
						}
						$detalle.="</tbody>
								</table>";	
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'kardex'=>$detalle,
						'fecha1'=>$f1,
						'fecha2'=>$f2,
						'empresa'=>$emisor,
						);	
			$this->load->view('pdf/pdf_kardex', $data);		
			// $direccion= $emisor->emi_ciudad."-".$emisor->emi_pais;
			// $this->html5pdf->filename('kardex.pdf');
			// $this->html5pdf->paper('a4', 'landscape');
			// $this->html5pdf->empresa($emisor->emi_nombre,$emisor->emp_identificacion,$direccion,$emisor->emi_telefono,$emisor->emp_logo);
    		// $this->html5pdf->html(utf8_decode($this->load->view('pdf/pdf_kardex', $data, true)));
    		// $this->html5pdf->output(array("Attachment" => 0));
			
		
    }
	
}
