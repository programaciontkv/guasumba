<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden_compra_reimpresion extends CI_Controller {

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
		$this->load->model('orden_compra_seguimiento_model');
		$this->load->model('cliente_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('configuracion_model');
		$this->load->model('empresa_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->model('reg_factura_model');
		$this->load->model('ingreso_model');
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
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
		}
		
		$txt_est="and (orc_estado='13' or orc_estado='22' or orc_estado='23')";
		
		$ordenes=$this->orden_compra_seguimiento_model->lista_ordenes_reimpresion_buscador($text,$f1,$f2,$txt_est);	

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
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('orden_compra_reimpresion/lista',$data);
		$modulo=array('modulo'=>'orden_compra_reimpresion');
		$this->load->view('layout/footer_bodega',$modulo);
	}

	

	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');	
			
		}else{
			$text= '';
			$fec1= date('Y-m-d');
			$fec2= date('Y-m-d');
		}
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$rst=$this->orden_compra_seguimiento_model->lista_una_detalle_orden($id);
          $data=array(
					'titulo'=>'Reimpresion de Etiquetas de Ordenes de Compra',
					'regresar'=>base_url().'orden_compra_reimpresion/'.$opc_id,
					'direccion'=>'orden_compra_reimpresion/reporte/'.$id.'/'.$opc_id,
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'orden_compra_reimpresion');
			$this->load->view('layout/footer',$modulo);
	}
	
	public function reporte($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
	 	require_once APPPATH.'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
	    $pdf->AddPage('P','etq_nop',0);
	    
	    $dc=$this->configuracion_model->lista_una_configuracion('2');
	    $dec=$dc->con_valor;
	    $emisor=$this->empresa_model->lista_una_empresa($rst_cja->emp_id);

        set_time_limit(0);
        
	   	$cns = $this->orden_compra_seguimiento_model->lista_etq_orden_mov($id);
	    $rst_total = $this->orden_compra_seguimiento_model->lista_etq_orden_total_mov($id);

		//modifica estado de etiquetas a impreso 
		$this->orden_compra_seguimiento_model->update_etiqueta($id,['etq_estado_imp'=>'1']);

		$rst_fac=$this->orden_compra_seguimiento_model->lista_una_detalle_orden($id);
		///etiqueta total
		$x = 0;
        $y = 0;
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Text($x + 30, $y + 5, "$emisor->emp_nombre");
        $pdf->Line($x + 1, $y + 6, $x + 110, $y + 6);
        $cx = strlen($rst_total->etq_bar_code);
        if ($cx <= 15) {
            $x1 = 20;
        } else {
            $x1 = 10;
        }
        $this->Code39($pdf,$x1, 7, $rst_total->etq_bar_code);
        $pdf->Line($x + 1, $y + 40, $x + 110, $y + 40);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text($x + 3, $y + 45, "REFERENCIA:  " . $rst_total->mp_d);
        $pdf->Text($x + 3, $y + 50, "FACTURA #:  " . $rst_fac->orc_factura);
        $pdf->Text($x + 3, $y + 55, "CANTIDAD TOTAL: " . $rst_total->peso . " $rst_total->mp_q");
        $pdf->Text($x + 70, $y + 55, "FECHA: " . date("Y-m-d"));

        //etiquetas por pesos
		foreach($cns as $rst) {
    		$pdf->AddPage('P','etq_nop',0);
     		$x = 0;
	        $y = 0;
	        $pdf->SetFont('helvetica', 'B', 16);
	        $pdf->Text($x + 30, $y + 5, "$emisor->emp_nombre");
	        $pdf->Line($x + 1, $y + 6, $x + 110, $y + 6);
	        
	        $cx = strlen($rst_total->etq_bar_code);
	        if ($cx <= 15) {
	            $x1 = 10;
	        } else {
	            $x1 = 5;
	        }

	        $this->Code39($pdf,$x1, 7, $rst->etq_bar_code);
	        $pdf->Line($x + 1, $y + 40, $x + 110, $y + 40);
	        $pdf->SetFont('helvetica', 'B', 10);
	        $pdf->Text($x + 3, $y + 45, "REFERENCIA:  " . substr($rst->mp_d,0,25));
	        $pdf->Text($x + 3, $y + 55, "CANTIDAD: " . $rst->etq_peso . " $rst->mp_q");
	        $pdf->Text($x + 70, $y + 55, "FECHA: " . date("Y-m-d"));
		}

        $pdf->Output('reimpresion_etiqueta_orden_compra.pdf' , 'I' ); 
	}	

	function Code39($pdf,$x, $y, $code, $w = 0.29, $h = 28, $ext = false, $cks = false, $wide = false) {
		

        $pdf->SetFont('Arial', '', 10);
        $pdf->Text($x + 30, $y + $h + 4, $code);
        if ($ext) {
            $code = $pdf->encode_code39_ext($code);
        } else {
            $code = strtoupper($code);
            if (!preg_match('|^[0-9A-Z. $/+%-]*$|', $code))
                $pdf->Error('Invalid barcode value: ' . $code);
        }
        if ($cks)
            $code .= $pdf->checksum_code39($code);
        $code = '*' . $code . '*';
        $narrow_encoding = array(
            '0' => '101001101101', '1' => '110100101011', '2' => '101100101011',
            '3' => '110110010101', '4' => '101001101011', '5' => '110100110101',
            '6' => '101100110101', '7' => '101001011011', '8' => '110100101101',
            '9' => '101100101101', 'A' => '110101001011', 'B' => '101101001011',
            'C' => '110110100101', 'D' => '101011001011', 'E' => '110101100101',
            'F' => '101101100101', 'G' => '101010011011', 'H' => '110101001101',
            'I' => '101101001101', 'J' => '101011001101', 'K' => '110101010011',
            'L' => '101101010011', 'M' => '110110101001', 'N' => '101011010011',
            'O' => '110101101001', 'P' => '101101101001', 'Q' => '101010110011',
            'R' => '110101011001', 'S' => '101101011001', 'T' => '101011011001',
            'U' => '110010101011', 'V' => '100110101011', 'W' => '110011010101',
            'X' => '100101101011', 'Y' => '110010110101', 'Z' => '100110110101',
            '-' => '100101011011', '.' => '110010101101', ' ' => '100110101101',
            '*' => '100101101101', '$' => '100100100101', '/' => '100100101001',
            '+' => '100101001001', '%' => '101001001001');

        $wide_encoding = array(
            '0' => '101000111011101', '1' => '111010001010111', '2' => '101110001010111',
            '3' => '111011100010101', '4' => '101000111010111', '5' => '111010001110101',
            '6' => '101110001110101', '7' => '101000101110111', '8' => '111010001011101',
            '9' => '101110001011101', 'A' => '111010100010111', 'B' => '101110100010111',
            'C' => '111011101000101', 'D' => '101011100010111', 'E' => '111010111000101',
            'F' => '101110111000101', 'G' => '101010001110111', 'H' => '111010100011101',
            'I' => '101110100011101', 'J' => '101011100011101', 'K' => '111010101000111',
            'L' => '101110101000111', 'M' => '111011101010001', 'N' => '101011101000111',
            'O' => '111010111010001', 'P' => '101110111010001', 'Q' => '101010111000111',
            'R' => '111010101110001', 'S' => '101110101110001', 'T' => '101011101110001',
            'U' => '111000101010111', 'V' => '100011101010111', 'W' => '111000111010101',
            'X' => '100010111010111', 'Y' => '111000101110101', 'Z' => '100011101110101',
            '-' => '100010101110111', '.' => '111000101011101', ' ' => '100011101011101',
            '*' => '100010111011101', '$' => '100010001000101', '/' => '100010001010001',
            '+' => '100010100010001', '%' => '101000100010001');

        $encoding = $wide ? $wide_encoding : $narrow_encoding;
        $gap = ($w > 0.29) ? '00' : '0';
        $encode = '';
        for ($i = 0; $i < strlen($code); $i++)
            $encode .= $encoding[$code[$i]] . $gap;
        $pdf->draw_code39($encode, $x, $y, $w, $h);
    }

   
    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Reimpresion de Etiquetas de Ordenes de Compras';
    	$file="orden_compra_reimpresion".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

   
}
