<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctasxcobrar_pagos extends CI_Controller {

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
		$this->load->model('ctasxcobrar_pagos_model');
		$this->load->model('ctasxpagar_model');
		$this->load->model('factura_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
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
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');
			$vencer= $this->input->post('vencer');	
			$vencido= $this->input->post('vencido');
			$pagado= $this->input->post('pagado');		
			if($vencer=='on' && $vencido=='on' && $pagado=='on'){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $vencido=='on' && $pagado==''){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $pagado=='on' && $vencido==''){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencido=='on' && $pagado=='on' && $vencer==''){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencido_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='on' && $vencido=='' && $pagado==''){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer($text,$f1,$f2,$rst_cja->emp_id);
			}else if($vencer=='' && $vencido=='on' && $pagado==''){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencido($text,$f1,$f2,$rst_cja->emp_id);
			}else if($pagado=='on'){
				$cns_facturas=$this->ctasxcobrar_pagos_model->lista_pagado($text,$f1,$f2,$rst_cja->emp_id);
			}
		}else{
			$text= '';
			$f1= '1900-01-01';
			$f2= date('Y-m-d');
			// $cns_facturas=$this->ctasxcobrar_pagos_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
			$vencer= 'on';	
			$vencido= 'on';
			$pagado= '';		
		}
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$credencial=$this->configuracion_model->lista_una_configuracion('26');
           	$cred=explode('&',$credencial->con_valor2);
			$dec=$conf->con_valor;

			$we =  intval($this->session->userdata('s_we'));
			if($we>=760){
				$mensaje='';
			}else{
				$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			}

			$data=array(
						'permisos'=>$this->permisos,
						'facturas'=>$cns_facturas,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'txt'=>$text,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'dec'=>$dec,
						'doc_mail'=>$cred[3],
						'vencer'=>$vencer,	
						'vencido'=>$vencido,
						'pagado'=>$pagado,
						'mensaje'=>$mensaje	

			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('ctasxcobrar_pagos/lista',$data);
			$modulo=array('modulo'=>'ctasxcobrar_pagos');
			$this->load->view('layout/footer_bodega',$modulo);
	}


	

	public function buscar_cobros($id,$tip,$emp){
		$cobros=$this->cheque_model->lista_cheques_tip_cliente($tip,$id,$emp);
		$lista="";
		if(!empty($cobros)){
			foreach ($cobros as $cobro) {
				$monto=$cobro->chq_monto-$cobro->chq_cobro;
				$lista.="<tr onclick='traer_cobro($cobro->chq_id)'><td><input type='checkbox'/></td><td>$cobro->chq_fecha</td><td>$cobro->chq_numero</td><td>$monto</td></tr>";
			}

			$data=array(
						'lista'=>$lista,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function traer_cobro($id){
		$cobro=$this->cheque_model->lista_un_cheque($id);
		
		if(!empty($cobro)){
			$data=array(
						'chq_id'=>$cobro->chq_id,
						'chq_numero'=>$cobro->chq_numero,
						'chq_monto'=>$cobro->chq_monto,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	
	 

	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$vencer=$this->input->post('vencer');
			$vencido=$this->input->post('vencido');
			$pagado=$this->input->post('pagado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$vencer='';
			$vencido='';
			$pagado='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Cuentas Por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ctasxcobrar/show_pdf/$id/$opc_id/$fec2",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'factura');
			$this->load->view('layout/footer',$modulo);
		}
    }

    
    public function show_pdf($fac_id,$opc_id,$fec2){
			$rst_fac=$this->factura_model->lista_una_factura($fac_id);
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$data=array(
						'dec'=>$dec,
						'cliente'=>$this->cliente_model->lista_un_cliente($rst_fac->cli_id),
						'credito'=>0,
						'saldo_vencido'=>$this->ctasxcobrar_pagos_model->lista_saldo_factura($rst_fac->fac_id),
						'cns_pag'=>$this->ctasxcobrar_pagos_model->lista_pagos_factura($rst_fac->fac_id),
						'cns_det'=>$this->ctasxcobrar_pagos_model->lista_ctasxcobrar_fecha($rst_fac->fac_id,$fec2),
						);

			

			$this->html2pdf->filename('ctasxcobrar_factura.pdf');
			$this->html2pdf->paper('a4', 'landscape');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_ctasxcobrar_factura', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));

	}
	
	public  function show_rpt_pdf($opc_id){

		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$vencer=$this->input->post('vencer');
			$vencido=$this->input->post('vencido');
			$pagado=$this->input->post('pagado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$vencer='';
			$vencido='';
			$pagado='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

	    $text= $this->input->post('txt');
		$ids= $this->input->post('tipo');
		$f1= $this->input->post('fec1');
		$f2= $this->input->post('fec2');
		$vencer= $this->input->post('vencer');	
		$vencido= $this->input->post('vencido');
		$pagado= $this->input->post('pagado');		


		 
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Central de Cobranzas '.ucfirst(strtolower($rst_cja->emi_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"ctasxcobrar/show_pdf_2/$opc_id/$f1/$f2/$vencer/$vencido/$text/$pagado",
					'permisos'=>$this->permisos,
					'opc_id'=>$rst_opc->opc_id,
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',	
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'ctasxcobrar');
			$this->load->view('layout/footer',$modulo);
		}

			
	}

public function show_pdf_2($opc_id,$f1,$f2,$vencer,$vencido,$text='',$pagado=''){
	    $permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	    $conf=$this->configuracion_model->lista_una_configuracion('2');
	 	$dec=$conf->con_valor;

			if($vencer=='on' && $vencido=='on' && $pagado=='on'){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_factura_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}else if($vencer=='on' && $vencido=='on' && $pagado==''){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer_vencido($text,$f1,$f2,$rst_cja->emp_id);
		}else if($vencer=='on' && $pagado=='on' && $vencido==''){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer_pagado($text,$f1,$f2,$rst_cja->emp_id);
		}else if($vencido=='on' && $pagado=='on' && $vencer==''){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencido_pagado($text,$f1,$f2,$rst_cja->emp_id);
		}else if($vencer=='on' && $vencido=='' && $pagado==''){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencer($text,$f1,$f2,$rst_cja->emp_id);
		}else if($vencer=='' && $vencido=='on' && $pagado==''){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_vencido($text,$f1,$f2,$rst_cja->emp_id);
		}else if($pagado=='on'){
			$cns_facturas=$this->ctasxcobrar_pagos_model->lista_pagado($text,$f1,$f2,$rst_cja->emp_id);
		}

			
			$conf=$this->configuracion_model->lista_una_configuracion('2');
			$dec=$conf->con_valor;
			$data=array(
						'permisos'=>$this->permisos,
						'facturas'=>$cns_facturas,
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'opc_id'=>$rst_opc->opc_id,
						'dec'=>$dec,
						
						);
			$this->load->view('pdf/pdf_ctasxcobrar_rpt',$data);

			// $this->html2pdf->filename('ctasxcobrar_pdf.pdf');
			// $this->html2pdf->paper('a4', 'landscape');
    		// $this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_ctasxcobrar_rpt', $data, true)));
    		// $this->html2pdf->output(array("Attachment" => 0));

	}
	

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Cuentas por Cobrar '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="ctasxcobrar".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function buscar_reporte($id,$opc_id,$reporte){
    	if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
			$vencer=$this->input->post('vencer');
			$vencido=$this->input->post('vencido');
			$pagado=$this->input->post('pagado');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
			$vencer='';
			$vencido='';
			$pagado='';
		}
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
            
            $fec=$this->input->post('fec2');
            $txt=$this->input->post('txt');
            $tipo ='I';

            switch ($reporte) {
                case '0':
                    $url="pdf_mora_cxc/index/$id/$opc_id/$tipo/$fec";
                    break;
                case '1':
                    $url="pdf_estado_cliente/index/$id/$opc_id/$fec/$tipo";
                    break;
                case '2':
                    $url="pdf_saldo_clientes/index/$opc_id/$fec/$txt";
                    break;   
                case '3':
                    $url="pdf_vencidos_cxc/index/$opc_id/$fec/$txt";
                    break;   
                case '4':
                    $url="pdf_mora_cxc/index/$id/$opc_id/$tipo/$fec";
                    break;  
                case '5':
                    $url="pdf_estado_cta_cliente/index/$id/$opc_id/$fec/$tipo";
                    break;
                case '6':
                    $url="pdf_estado_cta_cobros/index/$id/$opc_id/$tipo/$fec";
                    break;              
            }

          $data=array(
					'titulo'=>'Reportes Cuentas por Cobrar ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>$url,
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>$vencer,
					'vencido'=>$vencido,
					'pagado'=>$pagado,
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'cuentasxcobrar');
			$this->load->view('layout/footer',$modulo);
                       
    }


    public function envio_doc(){

    	$tipo ='F';

		$id         = $this->input->post('id');
		$mora       = $this->input->post('mora');
		$std_cobros = $this->input->post('std_cobros');
		$sta_1      = $this->input->post('sta_1');
		$sta_2      = $this->input->post('sta_2');
		$fec2       = $this->input->post('fec2'); 
		$correo     = $this->input->post('correo'); 
		$asunto     = $this->input->post('asunto'); 
		$opc_id     = $this->input->post('opc_id'); 

		$credencial=$this->configuracion_model->lista_una_configuracion('26');
		$cliente = $this->cliente_model->lista_un_cliente_cedula($id);
        $cred=explode('&',$credencial->con_valor2);
        $config['smtp_port'] = $cred[1];//'587';
        $config['smtp_host'] = $cred[2];//'mail.tivkas.com';
        $config['smtp_user'] = $cred[3];//'info@tivkas.com';
        $config['smtp_pass'] = $cred[4];//'tvk*36146';
        $config['protocol'] = 'smtp';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['smtp_crypto'] = 'ssl';
        $mensaje = str_replace('-', '<br>', $cred[7]);

        $this->email->initialize($config);

        $this->email->from($cred[3], $cred[5]);
        $correos = str_replace(';',',', strtolower($correo));
        
        $this->email->to($correos);
        $this->email->cc($cred[3]);

         if($mora=='on'){

         	
         	$url= base_url()."pdf_mora_cxc/index/$id/$opc_id/$tipo/$fec2";
         	$nombre= 'pdfs/estados/mora_cxc_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);
         }
         
         if($std_cobros=='on'){
         	$url=base_url()."pdf_estado_cta_cobros/index/$id/$opc_id/$tipo/$fec2";
         	$nombre = 'pdfs/estados/estado_cta_cobros_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);
         }

         if($sta_1=='on'){
         	$url=base_url()."pdf_estado_cliente/index/$id/$opc_id/$fec2/$tipo";
         	$nombre = 'pdfs/estados/estado_cliente_cxc_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);	
         }

         if($sta_2=='on'){
         	$url=base_url()."pdf_estado_cta_cliente/index/$id/$opc_id/$fec2/$tipo";
         	$nombre='pdfs/estados/estado_cta_cliente_'.$cliente->cli_ced_ruc.'_'.date("Y-m-d H").'.pdf';
         	$this->crear_Archivo($url,$nombre);
         }


        
        $emp = $this->empresa_model->lista_una_empresa(1);
        $img_logo=base_url().'imagenes/'.$emp->emp_logo;
        $img_mail=base_url().'imagenes/mail2.png';
        $img_whatsapp=base_url().'imagenes/whatsapp2.png';
        $img_telefono=base_url().'imagenes/telefono2.png';


        $datos_sms = "<html>
              <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                 <style>
                      td {
                          color: #070707;
                          font-family: Arial, Helvetica, sans-serif;
                          font-size: 14px;
                          text-align: center;
                          font-weight: bolder;
                      }
                       .mensaje {
						color: #070707;
						font-family: Arial, Helvetica, sans-serif;
						font-size: 14px;
						justify-content: left;
						// font-weight: bolder;
          			 }


                      
                  </style>
             </head>
             <body>
               <table width='100%'>
                
                  <tr><td><img  height='150px' width='300px' src='$img_logo'/></td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr class='mensaje'><td class='mensaje' > <p class='mensaje'>$mensaje </p>  </td></tr>
                  <tr style='with:60px' ><td>$cliente->cli_raz_social, </td></tr>
                  
                  
                 <tr>
                      <td style='font-size:16px'>FACTURACION ELECTRONICA POR TIKVASYST S.A.S</td>
                  </tr>
                  <tr><td></br></br> </td></tr>
                   
                  <tr><td></br></br> </td></tr>
                  <tr>
                      <td style='font-size:12px'></td>
                  </tr>
                  <tr>   
                       <td style='font-size:12px'>
                      
                        <img src='$img_mail' width='20px'><a href='https://www.tikvas.com/'>www.tikvas.com</a> 
                        <img src='$img_whatsapp' width='20px'> +593 999404989 / +593 991815559
                        
                      </td>
                  </tr>
                  <tr><td style='font-size:10px'>Copyright &copy; 2022 Todos los derechos reservados <a href='https://www.tikvas.com/'>TIKVASYST S.A.S</a></td></tr>
               </table>
             </body>
           </html>";

        $this->email->subject($asunto);
        $this->email->message(utf8_decode($datos_sms));

        if($this->email->send()){
            
           echo "Documentos enviados Correctamente";  
            
        }else{
            echo "no enviado";
        }

	
}
public function crear_Archivo($url,$nombre){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
		'Cookie: ci_session=3icgoits0kct5avtlimsto47vetdjo74'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$this->email->attach($nombre);	
		echo $response;
	}

	 public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente_cedula($id);
		if(!empty($rst)){
			$data=array(
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_parroquia'=>$rst->cli_parroquia,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_canton'=>$rst->cli_canton,
						'cli_email'=>$rst->cli_email,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_pais'=>$rst->cli_pais,
						'cli_rep_email'=>$rst->cli_rep_email,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	}


