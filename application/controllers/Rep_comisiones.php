<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class rep_comisiones extends CI_Controller {

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
		$this->load->model('rep_comisiones_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('vendedor_model');
		$this->load->model('configuracion_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library('html5pdf');
		$this->load->library('html4pdf');

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
		
        $cns_empresas=$this->empresa_model->lista_empresas_estado('1');		
        
        $data=array(
                    'permisos'=>$this->permisos,
                    'empresas'=>$cns_empresas,
                    'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
                    'opc_id'=>$rst_opc->opc_id,
                    'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
                    'empresa'=>$empresa,
                    'detalle'=>'',
                    'fec1'=>$f1,
                    'fec2'=>$f2,
                    'ids'=>$ids,
                    'txt'=>$txt,
        );
        $this->load->view('layout/header',$this->menus());
        $this->load->view('layout/menu',$this->menus());
        $this->load->view('reportes/rep_comisiones',$data);
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


	

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

    	$titulo='Ventas por Producto ';
    	$file="rep_comisiones".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


  

	public  function show_frame($opc_id,$empresa,$f1,$f2,$ids,$txt=""){

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

		
		if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'rep_comisiones ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"rep_comisiones/buscar_2/$opc_id/$empresa/$f1/$f2/$ids/$txt",
					'fec1'=>$f1,
					'fec2'=>$f2,
					'txt'=>$txt,
					'estado'=>'',
					'tipo'=>$ids,
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>1,
					'tip'=>1,
					'detalle'=>'',
				);

			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'rep_comisiones');
			$this->load->view('layout/footer',$modulo);

		}
	}


	public function buscar_2($opc_id,$empresa,$f1,$f2,$ids,$txt=""){
		
		$cns_productos=$this->rep_comisiones_model->lista_productos_buscador($f1,$f2,$empresa,$ids,$txt);
		$locales=$this->emisor_model->lista_emisores_empresa_2($empresa);
		$locales2=$this->emisor_model->lista_emisores_empresa_2($empresa);
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emisor=$this->emisor_model->lista_un_emisor($rst_cja->emi_id);

		$detalle="<table id='tbl_list' width='100%' class='table table-bordered table-list table-hover'>
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
								<th>".utf8_encode('Código')."</th>
								<th>".utf8_encode('Descripción') ."</th>";
								
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
		$t_c = 0;
		$t_val = 0;
		$can = 0;
		$val = 0;
		
		if(!empty($cns_productos)){
			foreach ($cns_productos as $prod) {
				$n=0;;
				$detalle.="<tr>
								<td>$prod->mp_c</td>
								<td>$prod->mp_d</td>";
				$th_cnt=0;
				$th_val=0;

				$locales3=$this->emisor_model->lista_emisores_empresa_2($empresa);
				if(!empty($locales3)){
					foreach ($locales3 as $loc3) {
						$n++;
						$rst_cnt=$this->rep_comisiones_model->lista_productos_local($loc3->emi_id,$prod->pro_id,$f1,$f2);
						$detalle.="<td class='cnt$n number' >".number_format($rst_cnt->cantidad,$dec)."</td>
								<td class='val$n number'>".number_format($rst_cnt->valor,$dec)."</td>";
						$th_cnt+=round($rst_cnt->cantidad,$dec);
						$th_val+=round($rst_cnt->valor,$dec);
					}
				}
				$detalle.="<td class='th_cnt number'>".number_format($th_cnt,$dec)."</td>
							<td class='th_val number'>".number_format($th_val,$dec)."</td>
							</tr>";
				$can += $rst_cnt->cantidad;
				$val += $rst_cnt->valor;
				$t_c+= $th_cnt;
				$t_val+=$th_val;


				
			}						
		}		
		$detalle.="</tbody>
		<tfoot>
		<tr class='total'>
						<td colspan='2'>Total</td>";
					$n=0;	
					$locales4=$this->emisor_model->lista_emisores_empresa_2($empresa);
					if(!empty($locales4)){
						foreach ($locales4 as $loc3) {	
						$n++;		
						$detalle.="<td id='tv_cnt$n' class='number'>".number_format($can,$dec)."</td>
								<td id='tv_val$n' class='number'>".number_format($val,$dec)."</td>";
						}
					}			
		$detalle.="<td id='tv_cnt' class='number'>" .number_format($t_c,$dec)."</td>
					<td id='tv_val' class='number'>".number_format($t_val,$dec)."</td>
				</tr>
				</tfoot>
				</table>";

			
			$data=array(
						'detalle'=>$detalle,
						'empresa'=>$emisor,
			);
		

			$this->html4pdf->filename('rep_comisiones.pdf');
			$this->html4pdf->paper('a4', 'landscape');
    		$this->html4pdf->html(utf8_decode($this->load->view('pdf/rep_comisiones', $data, true)));
			$this->html4pdf->output(array("Attachment" => 0));	
	}
     

    public function com_tiempo($f1,$f2,$vnd)
    {
    
    $detalle = '';
        
        

    $detalle.='<thead id="encabezado"> <tr>
    <th colspan="2"></th>';

    $j=0;
    $cns_per=$this->rep_comisiones_model->lista_periodos(1);

    
        foreach ($cns_per as $rst_per) {
            
        
        if($rst_per->pcv_hasta !=1000){
        $prd=$rst_per->pcv_desde.' - '.$rst_per->pcv_hasta;
        }else{
        $prd='>= '.$rst_per->pcv_desde;
        }

        $detalle.='<th colspan="2" class="periodo">'. $prd .'  <br> Porcentaje '. $rst_per->pcv_porcentaje .'<br>'.$rst_per->pcv_categoria.'</th>';
        $j++;
    }
    $detalle.='<th colspan="3">TOTALES</th></tr></thead>';

        

              
            if ($vnd!=0) {
                $vnd = " and cta_fecha_pago between '$f1' and '$f2' and f.vnd_id=$vnd";
              }else{
                $vnd = "and cta_fecha_pago between '$f1' and '$f2'";
              }

             

        $cns = $this->rep_comisiones_model->lista_documentos_buscador($vnd);

        ////
        $n = 0;
        $x = 0;
        $grup_vnd = '';
        $tv_cob=0;
        $tv_cmp=0;
        $tv_csi=0;
        $tt_cob=0;
        $tt_cmp=0;
        $tt_csi=0;
        $v=0;
        //
        
        foreach ($cns as $rst ) {

            
            if($rst->vnd_id!=$grup_vnd){
                
    
                 $detalle.='<tr>
                            <td class="encabezado">Factura</td>
                            <td class="encabezado">Cliente</td>';
                          
                            $l=0; 
                            while($l<$j){
                            
                              $detalle.='<td class="encabezado">Cobranza</td>
                                <td class="encabezado">Comision Parcial</td>';
                                 
                                $l++;
                            }
                            
                           $detalle.='<td class="encabezado">Total Cobrado</td>
                            <td class="encabezado">Total Cobrado sin iva</td>
                            <td class="encabezado">Total Comision</td>
                        </tr>';
              
                           
                    }
    
                    
                    
                   $detalle.=' <tr> ';   
                       
                            if($rst->vnd_id!=$grup_vnd){
                                $x++;
                              
                    $detalle.='<tr>';
                            
                            $l=0; 
                            while($l<$j){
                                   
                                $l++;
                            }
                            $l=$l*2+5;
                            
                    $detalle.='<td class="item" colspan="'.$l.'" style="font-weight: bolder; font-size: 15px;text-align: center">'. $rst->vnd_nombre.'</td>
                        </tr>';    
                       
                            }
                        
                    $detalle.='<td>'. $rst->fac_numero.'</td> <td>' .$rst->fac_nombre.'</td>';
                            
                            $y=0;
                            $th_cob=0;
                            $th_cmp=0;
                            // $cns_per2=$Set->lista_periodos();
                            $cns_per2=$this->rep_comisiones_model->lista_periodos(1);
                            foreach ($cns_per2 as $rst_p2) {
                            // while ($rst_p2=pg_fetch_array($cns_p2)) {

                                    $y++;
                                    $fecha = $rst->fac_fecha_emision;
                

                                    if($rst_p2->pcv_desde==0){
                                        $rst_p2->pcv_desde=100;
                                        $desde = strtotime ( "-$rst_p2->pcv_desde day" , strtotime ( $fecha ) ) ;
                                    }else{
                                        $desde = strtotime ( "+$rst_p2->pcv_desde day" , strtotime ( $fecha ) ) ;
                                    }
                                    

                                    $desde = date ( 'Y-m-d' , $desde );
                
                                    $hasta = strtotime ( "+$rst_p2->pcv_hasta day" , strtotime ( $fecha ) ) ;
                                    $hasta = date ( 'Y-m-d' , $hasta );
                                    if($rst_p2->pcv_hasta !=1000){
                                        $txt_fec="and cta_fecha_pago between '$desde' and '$hasta' and cta_fecha_pago between '$f1' and '$f2'";
                                    }else{
                                        $txt_fec="and cta_fecha_pago>='$desde' and cta_fecha_pago between '$f1' and '$f2'";
                                    }
    
                                    $rst_cxc=$this->rep_comisiones_model->suma_ctasxcobrar_fechas($txt_fec,$rst->fac_id);

                

                                    if($rst->fac_total_iva>0){
                                        $cmp=round($rst_cxc->cta_monto,2)*($rst_p2->pcv_porcentaje/100)/1.12;
                                    }else{
                                        $cmp=round($rst_cxc->cta_monto,2)*($rst_p2->pcv_porcentaje/100);
                                    }
    
                                    $th_cob+=round($rst_cxc->cta_monto,2);      
                                    $th_cmp+=round($cmp,2);
    
                                    if(empty($rst_cxc->cta_monto)){
                                        $cob='';
                                        $cmp=''; 
                                    }else{
                                        $cob=number_format($rst_cxc->cta_monto,2);
                                        $cmp=number_format($cmp,2);
                                    }

                            $detalle.='<td align="right" class="cob'.$x.$y.'">'.$cob.'</td>
                                <td align="right" class="cmp'.$x.$y.'">'.$cmp.'</td>';
                            }
                            
                            if($rst->fac_total_iva>0){
                                $th_csi=$th_cob/1.12;
                            }else{
                                $th_csi=$th_cob;
                            }
                           
                            $detalle.='<td align="right">'. number_format($th_cob,2).'</td>
                            <td align="right">'.number_format($th_csi,2).'</td>
                            <td align="right">'.number_format($th_cmp,2).'</td>
                        </tr>';
 
                            $tv_cob+=round($th_cob,2);
                            $tv_cmp+=round($th_cmp,2);
                            $tv_csi+=round($th_csi,2);
                            $tt_cob+=round($th_cob,2);
                            $tt_cmp+=round($th_cmp,2);
                            $tt_csi+=round($th_csi,2);
                            $grup_vnd=$rst->vnd_id;
                            $fa = $rst->fac_id;
            
                           
                }    

                $detalle.=   '<tr>
                        <td class="totales" colspan="2">Total COBRANZA </td>';
                       
                        $l=0; 
                        while($l<$j){
                            $l++;
                        
                        $detalle.= '<td class="totales" align="right" id=cob" '.$x.$l.'"></td>
                            <td class="totales" align="right" id=cmp"'.$x.$l.'"></td>';
                             
                        }
                        
                        $detalle.= '<td class="totales" align="right">'.number_format($tv_cob,2).'</td>
                        <td class="totales" align="right">'.  number_format($tv_csi,2).'</td>
                        <td class="totales" align="right">'.  number_format($tv_cmp,2).'</td> </tr>';
                    
                
       
                
        $data=array(
            'detalle'=>$detalle,
              );
         echo json_encode($data);

    }


    function com_valor($f1,$f2,$vnd){


        $detalle = '';
        
        

        $detalle.='<thead id="encabezado"> <tr>
        <th colspan="2"></th>';
    
        $j=0;
        $cns_per=$this->rep_comisiones_model->lista_periodos(2);

        if (!empty($cns_per)) {
                
            foreach ($cns_per as $rst_per) {


                if ($rst_per->pcv_hasta != 1000) {
                    $prd = $rst_per->pcv_desde . ' - ' . $rst_per->pcv_hasta;
                } else {
                    $prd = '>= ' . $rst_per->pcv_desde;
                }

                $detalle .= '<th colspan="2" class="periodo">' . $prd . '  <br> Porcentaje ' . $rst_per->pcv_porcentaje . '<br>' . $rst_per->pcv_categoria . '</th>';
                $j++;
            }
            $detalle.='<th colspan="3">TOTALES</th></tr></thead>';
        
      
    
            
    
                  
                if ($vnd!=0) {
                    $vnd = " and cta_fecha_pago between '$f1' and '$f2' and f.vnd_id=$vnd";
                  }else{
                    $vnd = "and cta_fecha_pago between '$f1' and '$f2'";
                  }
    
                 
    
            $cns = $this->rep_comisiones_model->lista_documentos_buscador($vnd);
    
            ////
            $n = 0;
            $x = 0;
            $grup_vnd = '';
            $tv_cob=0;
            $tv_cmp=0;
            $tv_csi=0;
            $tt_cob=0;
            $tt_cmp=0;
            $tt_csi=0;
            $v=0;
            //
            
            foreach ($cns as $rst ) {
    
                
                if($rst->vnd_id!=$grup_vnd){
                    
        
                     $detalle.='<tr>
                                <td class="encabezado">Factura</td>
                                <td class="encabezado">Cliente</td>';
                              
                                $l=0; 
                                while($l<$j){
                                
                                  $detalle.='<td class="encabezado">Cobranza</td>
                                    <td class="encabezado">Comision Parcial</td>';
                                     
                                    $l++;
                                }
                                
                               $detalle.='<td class="encabezado">Total Cobrado</td>
                                <td class="encabezado">Total Cobrado sin iva</td>
                                <td class="encabezado">Total Comision</td>
                            </tr>';
                  
                               
                        }
        
                        
                        
                       $detalle.=' <tr> ';   
                           
                                if($rst->vnd_id!=$grup_vnd){
                                    $x++;
                                  
                        $detalle.='<tr>';
                                
                                $l=0; 
                                while($l<$j){
                                       
                                    $l++;
                                }
                                $l=$l*2+5;
                                
                        $detalle.='<td class="item" colspan="'.$l.'" style="font-weight: bolder; font-size: 15px;text-align: center">'. $rst->vnd_nombre.'</td>
                            </tr>';    
                           
                                }
                            
                        $detalle.='<td>'. $rst->fac_numero.'</td> <td>' .$rst->fac_nombre.'</td>';
                                
                                $y=0;
                                $th_cob=0;
                                $th_cmp=0;
                                // $cns_per2=$Set->lista_periodos();
                                $cns_per2=$this->rep_comisiones_model->lista_periodos(2);
                                foreach ($cns_per2 as $rst_p2) {


                                    $y++;
                                    $txt_fec='';
                                    $por=0;
                                    $cob='';
                                    $cmp='';
                                 
                                    $rst_cxc=$this->rep_comisiones_model->suma_ctasxcobrar_fechas($txt_fec,$rst->fac_id);

                                    if(round($rst_cxc->cta_monto,2)>=round($rst_p2->pcv_desde,2) && round($rst_cxc->cta_monto,2)<=round($rst_p2->pcv_hasta,2)){
                                        $por=$rst_p2->pcv_porcentaje;
                                        
                                        if(empty($rst_cxc->cta_monto)){
                                            $cob=0;
                                            $cmp=0; 
                                        }else{
                                            $cob=round($rst_cxc->cta_monto,2);
                                            $cmp=round($cmp,2);
                                        }
    
                                        if($rst->fac_total_iva>0){
                                            $cmp=round($rst_cxc->cta_monto,2)*($por/100)/1.12;
                                        }else{
                                            $cmp=round($rst_cxc->cta_monto,2)*($por/100);
                                        }
    
                                        
    
                                    }
                                    
                                    
    
                                    $th_cob+=round($cob,2);      
                                    $th_cmp+=round($cmp,2);
    
                                    if(empty($cob)){
                                            $cob='';
                                            $cmp=''; 
                                    }else{
                                            $cob=number_format($cob,2);
                                            $cmp=number_format($cmp,2);
                                    }
                                


    
                                        
    
                                $detalle.='<td align="right" class="cob'.$x.$y.'">'.$cob.'</td>
                                    <td align="right" class="cmp'.$x.$y.'">'.$cmp.'</td>';
                                }
                                
                                if($rst->fac_total_iva>0){
                                    $th_csi=$th_cob/1.12;
                                }else{
                                    $th_csi=$th_cob;
                                }
                               
                                $detalle.='<td align="right">'. number_format($th_cob,2).'</td>
                                <td align="right">'.number_format($th_csi,2).'</td>
                                <td align="right">'.number_format($th_cmp,2).'</td>
                            </tr>';
     
                                $tv_cob+=round($th_cob,2);
                                $tv_cmp+=round($th_cmp,2);
                                $tv_csi+=round($th_csi,2);
                                $tt_cob+=round($th_cob,2);
                                $tt_cmp+=round($th_cmp,2);
                                $tt_csi+=round($th_csi,2);
                                $grup_vnd=$rst->vnd_id;
                                $fa = $rst->fac_id;
                
                               
                    }
                    
                    $detalle.=   '<tr>
                        <td class="totales" colspan="2">Total COBRANZA </td>';
                       
                        $l=0; 
                        while($l<$j){
                            $l++;
                        
                        $detalle.= '<td class="totales" align="right" id=cob" '.$x.$l.'"></td>
                            <td class="totales" align="right" id=cmp"'.$x.$l.'"></td>';
                             
                        }
                        
                        $detalle.= '<td class="totales" align="right">'.number_format($tv_cob,2).'</td>
                        <td class="totales" align="right">'.  number_format($tv_csi,2).'</td>
                        <td class="totales" align="right">'.  number_format($tv_cmp,2).'</td> </tr>';
                    
           
                    
            $data=array(
                'detalle'=>$detalle,
                  );
             echo json_encode($data);
            }else{
                $detalle = '';
            }

    }

}
