<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends CI_Controller {

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
		$this->load->model('servicio_model');
		$this->load->model('tipo_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		// $this->load->model('imagen_model');
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
	

	public function index(){
		$data=array(
					'permisos'=>$this->permisos,
					'productos'=>$this->servicio_model->lista_productos()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('servicio/lista',$data);
		$modulo=array('modulo'=>'servicio');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo(){
		if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'familias'=>$this->tipo_model->lista_familias('9'),
						'tipos'=>'',
						'producto'=> (object) array(
											'mp_a'=>'172',//familia
					                        'mp_b'=>'173',///tipo
					                        'mp_c'=>'',
					                        'mp_d'=>'',
					                        'mp_q'=>'UNIDAD',///unidad
					                        'mp_i'=>'1',///estado
					                        'mp_e'=>'0', ///precio1
					                        'mp_f'=>'0', //precio2
					                        'mp_g'=>'0', //descuento
					                        'mp_h'=>'12',//iva 
					                        // 'mp_n'=>'',//cod_aux 
					                        'mp_o'=>'',//propiedad1 
					                        'id'=>'',
					                        'ids'=>'79'
										),
						
						'action'=>base_url().'servicio/guardar'
						);
			$this->load->view('servicio/form',$data);
			$modulo=array('modulo'=>'servicio');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		
		$ids = $this->input->post('ids');
		$pro_familia= $this->input->post('mp_a');
		$pro_tipo = $this->input->post('mp_b');
		$pro_codigo = $this->input->post('mp_c');
		//$pro_codigo_aux = $this->input->post('mp_n');
		$pro_descripcion = $this->input->post('mp_d');
		//$pro_propiedad1 = $this->input->post('mp_o');
		$pro_uni = $this->input->post('mp_q');
		$pro_precio1 = $this->input->post('mp_e');
		//$pro_precio2 = $this->input->post('mp_f');
		$pro_descuento = $this->input->post('mp_g');
		$pro_iva = $this->input->post('mp_h');
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
					                        //'mp_n'=>$pro_codigo_aux,
					                        //'mp_o'=>$pro_propiedad1,
					                        'mp_d'=>$pro_descripcion,
					                        'mp_q'=>$pro_uni,
					                        'mp_e'=>$pro_precio1,
					                        //'mp_f'=>$pro_precio2,
					                        'mp_g'=>$pro_descuento,
					                        'mp_h'=>$pro_iva,
					                        'mp_i'=>$pro_estado,
			);	

			if($this->servicio_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'SERVICIOS',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				redirect(base_url().'servicio');
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'servicio/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$rst=$this->servicio_model->lista_un_producto($id);
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'familias'=>$this->tipo_model->lista_familias('9'),
						'tipos'=>$this->tipo_model->lista_tipos_familia($rst->mp_a),
						'producto'=>$this->servicio_model->lista_un_producto($id),
						'action'=>base_url().'servicio/actualizar'
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('servicio/form',$data);
			$modulo=array('modulo'=>'servicio');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id = $this->input->post('id');
		$ids = $this->input->post('ids');
		$pro_familia= $this->input->post('mp_a');
		$pro_tipo = $this->input->post('mp_b');
		$pro_codigo = $this->input->post('mp_c');
		//$pro_codigo_aux = $this->input->post('mp_n');
		$pro_descripcion = $this->input->post('mp_d');
		//$pro_propiedad1 = $this->input->post('mp_o');
		$pro_uni = $this->input->post('mp_q');
		$pro_precio1 = $this->input->post('mp_e');
		//$pro_precio2 = $this->input->post('mp_f');
		$pro_descuento = $this->input->post('mp_g');
		$pro_iva = $this->input->post('mp_h');
		$pro_estado = $this->input->post('mp_i');

		$producto_act=$this->servicio_model->lista_un_producto($id);

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
					                        //'mp_n'=>$pro_codigo_aux,
					                        //'mp_o'=>$pro_propiedad1,
					                        'mp_d'=>$pro_descripcion,
					                        'mp_q'=>$pro_uni,
					                        'mp_e'=>$pro_precio1,
					                        //'mp_f'=>$pro_precio2,
					                        'mp_g'=>$pro_descuento,
					                        'mp_h'=>$pro_iva,
					                        'mp_i'=>$pro_estado,
			);

			if($this->servicio_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'MATERIA PRIMA',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'servicio');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'servicio/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'producto'=>$this->servicio_model->lista_un_producto($id)
						);
			$this->load->view('servicio/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->servicio_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'MATERIA PRIMA',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'servicio';
			}
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function traer_tipos($f){
		$familias=$this->tipo_model->lista_tipos_familia($f);
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

    	$titulo='Servicios';
    	$file="sevcios".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }
    
}
