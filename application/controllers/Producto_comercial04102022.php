<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producto_comercial extends CI_Controller {

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
		$this->load->model('producto_comercial_model');
		$this->load->model('tipo_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		// $this->load->model('imagen_model');
		$this->load->library('html2pdf');
		$this->load->library('export_excel');
		$this->load->model('opcion_model');
		$this->load->model('configuracion_model');
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

		if($_POST){
			$text= $this->input->post('txt');
		
			$est= $this->input->post('estado');	
			if($est!=""){
				$txt_est="$est";
			}else{
				$txt_est="";
			}
			$cns_productos=$this->producto_comercial_model->lista_productos_buscador($text,$txt_est);
		}else{
			$text= '';
			$txt_est="1";
			$est="1";
			$cns_productos=$this->producto_comercial_model->lista_productos_buscador($text,$txt_est);
		}


		$data=array(
					'permisos'=>$this->permisos,
					// 'productos'=>$this->producto_comercial_model->lista_productos(),
					'productos'=>$cns_productos,
					'cns_estados'=>$this->estado_model->lista_estados_modulo($rst_opc->opc_id),
					'txt'=>$text,
					'estado'=>$est,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,

				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('producto_comercial/lista',$data);
		$modulo=array('modulo'=>'producto_comercial');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'familias'=>$this->tipo_model->lista_familias('2'),
						'tipos'=>'',
						'opc_id'=>$rst_opc->opc_id,
						'producto'=> (object) array(
											'mp_a'=>'176',//familia
					                        'mp_b'=>'177',///tipo
					                        'mp_c'=>'',
					                        'mp_d'=>'',
					                        'mp_q'=>'UNIDAD',///unidad
					                        'mp_i'=>'1',///estado
					                        'mp_aa'=>'', ///imagen
					                        'mp_e'=>'0', ///precio1
					                        'mp_f'=>'0', //precio2
					                        'mp_g'=>'0', //descuento
					                        'mp_h'=>'12',//iva 
					                        'mp_n'=>'',//cod_aux 
					                        'mp_o'=>'',//propiedad1 
					                        'id'=>'0',
					                        'ids'=>'26'
										),
						
						'action'=>base_url().'producto_comercial/guardar/'.$opc_id
						);
			$this->load->view('producto_comercial/form',$data);
			$modulo=array('modulo'=>'producto_comercial');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		
		$ids = $this->input->post('ids');
		$pro_familia= $this->input->post('mp_a');
		$pro_tipo = $this->input->post('mp_b');
		$pro_codigo = $this->input->post('mp_c');
		$pro_codigo_aux = $this->input->post('mp_n');
		$pro_descripcion = $this->input->post('mp_d');
		//$pro_propiedad1 = $this->input->post('mp_o');
		$pro_uni = $this->input->post('mp_q');
		$pro_precio1 = $this->input->post('mp_e');
		//$pro_precio2 = $this->input->post('mp_f');
		$pro_descuento = $this->input->post('mp_g');
		$pro_iva = $this->input->post('mp_h');
		$pro_imagen = $this->input->post('mp_aa');
		$pro_estado = $this->input->post('mp_i');
		
		$this->form_validation->set_rules('mp_a','Familia','required');
		$this->form_validation->set_rules('mp_b','Tipo','required');
		$this->form_validation->set_rules('mp_c','Codigo','required|is_unique[erp_mp.mp_c]');
		$this->form_validation->set_rules('mp_d','Descripcion','required');
		$this->form_validation->set_rules('mp_e','Precio1','required');
		//$this->form_validation->set_rules('mp_f','Precio2','required');
		$this->form_validation->set_rules('mp_g','Descuento','required');
		$this->form_validation->set_rules('mp_h','Iva','required');
		if($this->form_validation->run()){
			$data=array(
											'ids'=>$ids,
											'mp_a'=>$pro_familia,
					                        'mp_b'=>$pro_tipo,
					                        'mp_c'=>$pro_codigo,
					                         'mp_n'=>$pro_codigo_aux,
					                        // 'mp_o'=>$pro_propiedad1,
					                        'mp_d'=>$pro_descripcion,
					                        'mp_q'=>$pro_uni,
					                        'mp_e'=>$pro_precio1,
					                        //'mp_f'=>$pro_precio2,
					                        'mp_g'=>$pro_descuento,
					                        'mp_h'=>$pro_iva,
					                        'mp_aa'=>$pro_imagen,
					                        'mp_i'=>$pro_estado
			);	

			if($this->producto_comercial_model->insert($data)){
				
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PRODUCTOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'producto_comercial/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'producto_comercial/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	
	}

	public function editar($id,$opc_id){
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		if($this->permisos->rop_actualizar){
			$rst=$this->producto_comercial_model->lista_un_producto($id);
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'familias'=>$this->tipo_model->lista_familias('2'),
						'tipos'=>$this->tipo_model->lista_tipos_familia($rst->mp_a),
						'producto'=>$this->producto_comercial_model->lista_un_producto($id),
						'action'=>base_url().'producto_comercial/actualizar/'.$opc_id,
						'opc_id'=>$rst_opc->opc_id
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('producto_comercial/form',$data);
			$modulo=array('modulo'=>'producto_comercial');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
		
		$id = $this->input->post('id');
		$ids = $this->input->post('ids');
		$pro_familia= $this->input->post('mp_a');
		$pro_tipo = $this->input->post('mp_b');
		$pro_codigo = $this->input->post('mp_c');
		$pro_codigo_aux = $this->input->post('mp_n');
		$pro_descripcion = $this->input->post('mp_d');
		//$pro_propiedad1 = $this->input->post('mp_o');
		$pro_uni = $this->input->post('mp_q');
		$pro_precio1 = $this->input->post('mp_e');
		//$pro_precio2 = $this->input->post('mp_f');
		$pro_descuento = $this->input->post('mp_g');
		$pro_iva = $this->input->post('mp_h');
		$pro_imagen = $this->input->post('mp_aa');
		$pro_estado = $this->input->post('mp_i');

		$producto_act=$this->producto_comercial_model->lista_un_producto($id);

		if($pro_codigo==$producto_act->mp_c){
			$unique='';
		}else{
			$unique='|is_unique[erp_mp.mp_c]';
		}
		$this->form_validation->set_rules('mp_a','Familia','required');
		$this->form_validation->set_rules('mp_b','Tipo','required');
		$this->form_validation->set_rules('mp_c','Codigo','required'.$unique);
		$this->form_validation->set_rules('mp_d','Descripcion','required');
		$this->form_validation->set_rules('mp_e','Precio1','required');
		//$this->form_validation->set_rules('mp_f','Precio2','required');
		$this->form_validation->set_rules('mp_g','Descuento','required');
		$this->form_validation->set_rules('mp_h','Iva','required');

		if($this->form_validation->run()){
			$data=array(
											'ids'=>$ids,
											'mp_a'=>$pro_familia,
					                        'mp_b'=>$pro_tipo,
					                        'mp_c'=>$pro_codigo,
					                         'mp_n'=>$pro_codigo_aux,
					                        // 'mp_o'=>$pro_propiedad1,
					                        'mp_d'=>$pro_descripcion,
					                        'mp_q'=>$pro_uni,
					                        'mp_e'=>$pro_precio1,
					                        // 'mp_f'=>$pro_precio2,
					                        'mp_g'=>$pro_descuento,
					                        'mp_h'=>$pro_iva,
					                        'mp_aa'=>$pro_imagen,
					                        'mp_i'=>$pro_estado,
			);

			if($this->producto_comercial_model->update($id,$data)){
				
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PRODUCTOS',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				redirect(base_url().'producto_comercial/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'producto_comercial/editar'.$id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'producto'=>$this->producto_comercial_model->lista_un_producto($id)
						);
			$this->load->view('producto_comercial/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function ver_img($id){
		if($this->permisos->rop_reporte){

			$cns=$this->producto_comercial_model->lista_un_producto($id);
		
				if(!empty($cns->mp_aa)){

						$img ='<p  > <center> <img  src="'.base_url().'imagenes/'.$cns->mp_aa .'" width="450px" height="300px">  </center></p>';
						
						$data=array('img'=> $img );

						echo json_encode($data);

						}else{
							echo "";
						}

			
		}else{
			redirect(base_url().'inicio');
		}	


	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->producto_comercial_model->delete($id)){
				
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PRODUCTOS',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				echo 'producto_comercial';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}
	public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>strtolower('Codigo de barras de producto terminado '),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"producto_comercial/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'producto_comercial');
			$this->load->view('layout/footer',$modulo);
		}
	}
     public function show_pdf($id,$opc_id){
    		$pro=$this->producto_comercial_model->lista_un_producto($id);
    		$imagen=$this->set_barcode($pro->mp_c); 
			$data=array(
						'producto'=>$this->producto_comercial_model->lista_un_producto($id)
						);
			$this->html2pdf->filename('cod_producto.pdf');
			$this->html2pdf->paper('c8', 'landscape');
			//$this->html2pdf->paper('b9', 'landscape');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_cod_producto', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    	
    } 
    public function set_barcode($code)
	{
		// //load library
		// $this->load->library('zend');
		// //load in folder Zend
		// $this->zend->load('Zend/Barcode');
		// //generate barcode
		// Zend_Barcode::factory('code39', 'image', array('text'=>$code), array());

		// $this->load->library('zend');
  //       $this->zend->load('Zend/Barcode');
  //       $barcodeOptions = array('text' => $code);
  //       $rendererOptions = array('imageType'=>'png');
  //       $imageResource=Zend_Barcode::factory('code39', 'image', $barcodeOptions, $rendererOptions)->render();
  //       // $this->subir_codigo($imageResource,$code);  
  //       // imagepng($imageResource,"./barcodes/$code.png"); 
        $this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$imageResource = Zend_Barcode::factory('code39', 'image', array('text' => "$code", 'barHeight'=> 50,'factor'=> 1, 'drawText'=>false), array())->draw();
		$path="./barcodes/$code.png";
		imagepng($imageResource, $path);
	}

	public function traer_tipos($f){
		//$familias=$this->tipo_model->lista_tipos_familia($f);
		$familias=$this->tipo_model->lista_tipos_familia_2();
		$lista="<option value='0'>SELECCIONE</option>";
		foreach ($familias as $rst) {
			$lista.="<option value='$rst->tps_id'>$rst->tps_nombre</option>";
		}
		 echo $lista;
	}

	public function load_imagen($id){
    	$rst = $this->imagen_model->lista_una_imagen($id);
        echo $rst->img_direccion.'&&'.$rst->img_orientacion;
    }    

    public function excel($opc_id){

    	$titulo='Productos';
    	$file="productos".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

    public function traer_codigo($id1,$id2){

    	$conf=$this->configuracion_model->lista_una_configuracion('24');
    	if($conf->con_valor==0){
    		//$pro    = $this->producto_comercial_model->lista_by_tipo($id1,$id2);
    		$pro    = $this->producto_comercial_model->lista_by_tipo_count($id1,$id2);
	        $rst_s1 = $this->tipo_model->lista_un_tipo($id1);
            $rst_s2 = $this->tipo_model->lista_un_tipo($id2);
	        
           if(!empty($pro->n)){
           	 $sec = $pro->n + 1;
           }else{
           	$sec=1;
           }
            if ($sec > 0 && $sec < 10) {
                $txt = '00';
            } else if ($sec >= 10 && $sec < 100) {
                $txt = '0';
            } else if ($sec >=100) {
                $txt = '';
            } else if ($sec == '') {
                $txt = '001';
            }
         echo $rst_s1->tps_siglas . '.' . $rst_s2->tps_siglas . '.' . $txt . $sec;
     }else{
     	echo "1";
     }
	        

    }

     public function cambiar_estado($estado,$id,$opc_id){
			
			$data=array(
		    			'mp_i'=>$estado, 
		    );

			$data_audito=array(
		    			'producto_comercial'=>$id, 
		    			'Estado'=>$estado, 

		    );

		    if($this->servicio_model->update($id,$data)){
		    	
		    	$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'PRODUCTO',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($data_audito),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$id." ".$estado,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo "1";
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				echo "0";
			}
		
	}
    

    
}
