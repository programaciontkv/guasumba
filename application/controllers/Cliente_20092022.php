<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller {

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
		$this->load->model('menu_model');
		$this->load->model('vendedor_model');
		$this->load->model('auditoria_model');
		$this->load->model('cliente_model');
		$this->load->model('estado_model');
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
					'clientes'=>$this->cliente_model->lista_clientes()
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('cliente/lista',$data);
		$modulo=array('modulo'=>'cliente');
		$this->load->view('layout/footer',$modulo);
	}

	public function crear_codigo($t,$c){

	
		$rst=$this->cliente_model->lista_secuencial_cliente("$t$c");
		if (!empty($rst)){
			if ($t == 'CP') {
	            $sec = (substr($rst->cli_codigo, 3, 8) + 1);
	        } else {
	        	$sec = (substr($rst->cli_codigo, 2, 8) + 1);
	        }
    	}else{
        	$sec = 1;
        }


        if ($sec >= 0 && $sec < 10) {
            $txt = '0000';
        } else if ($sec >= 10 && $sec < 100) {
            $txt = '000';
        } else if ($sec >= 100 && $sec < 1000) {
            $txt = '00';
        } else if ($sec >= 1000 && $sec < 10000) {
            $txt = '0';
        } else if ($sec >= 10000 && $sec < 100000) {
            $txt = '';
        }
        if ($t == '0') {
            $retorno = '';
        } else {
            $retorno = $t . $c . $txt . $sec;
        }

        echo $retorno;
	}

	public function nuevo(){
	if($this->permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'vendedores'=>$this->vendedor_model->lista_vendedores(),
						'cliente'=> (object) array(
											'cli_fecha'=>date('Y-m-d'),
											'cli_tipo'=>'2',
											'cli_categoria'=>'0',
											'cli_codigo'=>'',
											'cli_estado'=>'1',
											'cli_tipo_cliente'=>'0',
											'cli_apellidos'=>'',
											'cli_nombres'=>'',
											'cli_raz_social'=>'',
											'cli_ced_ruc'=>'',
											'cli_nom_comercial'=>'',
											'cli_nacionalidad'=>'',//vendedor
											'cli_refb_tip_cuenta2'=>'',
											'cli_pais'=>'Ecuador',
											'cli_provincia'=>'',
											'cli_canton'=>'',
											'cli_parroquia'=>'',
											'cli_calle_prin'=>'',
											'cli_numeracion'=>'',
											'cli_calle_sec'=>'',
											'cli_telefono'=>'',
											'cli_email'=>'',
											'cli_referencia'=>'',
											'cli_id'=>''
										),
						'action'=>base_url().'cliente/guardar'
						);
			$this->load->view('cliente/form',$data);
			$modulo=array('modulo'=>'cliente');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar(){
		$cli_fecha = $this->input->post('cli_fecha');
		$cli_tipo = $this->input->post('cli_tipo');
		$cli_categoria = $this->input->post('cli_categoria');
		$cli_codigo = $this->input->post('cli_codigo');
		$cli_estado = $this->input->post('cli_estado');
		$cli_tipo_cliente = $this->input->post('cli_tipo_cliente');
		$cli_apellidos = $this->input->post('cli_apellidos');
		$cli_nombres = $this->input->post('cli_nombres');
		$cli_raz_social = $this->input->post('cli_raz_social');
		$cli_ced_ruc = $this->input->post('cli_ced_ruc');
		$cli_nom_comercial = $this->input->post('cli_nom_comercial');
		$cli_nacionalidad = $this->input->post('cli_nacionalidad');
		$cli_refb_tip_cuenta2 = $this->input->post('cli_refb_tip_cuenta2');
		$cli_pais = $this->input->post('cli_pais');
		//$cli_provincia = $this->input->post('cli_provincia');
		$cli_canton = $this->input->post('cli_canton');
		$cli_parroquia = $this->input->post('cli_parroquia');
		$cli_calle_prin = $this->input->post('cli_calle_prin');
		$cli_numeracion = $this->input->post('cli_numeracion');
		$cli_calle_sec = $this->input->post('cli_calle_sec');
		$cli_telefono = $this->input->post('cli_telefono');
		$cli_email = $this->input->post('cli_email');
		$cli_referencia = $this->input->post('cli_referencia');

		$this->form_validation->set_rules('cli_fecha','Fecha Ingreso','required');
		$this->form_validation->set_rules('cli_tipo','Tipo','required');
		$this->form_validation->set_rules('cli_codigo','Codigo','required');
		if($cli_categoria==0){
			//$this->form_validation->set_rules('cli_apellidos','Apellidos','required');
			//$this->form_validation->set_rules('cli_nombres','Nombres','required');
		}else{
			//$this->form_validation->set_rules('cli_nom_comercial','Nombre Comercial','required');
		}
		$this->form_validation->set_rules('cli_raz_social','Razon Social','required');
		$this->form_validation->set_rules('cli_ced_ruc','Cedula/RUC','required|is_unique[erp_i_cliente.cli_ced_ruc]');
		$this->form_validation->set_rules('cli_pais','Pais','required');
		$this->form_validation->set_rules('cli_canton','Ciudad','required');
		$this->form_validation->set_rules('cli_parroquia','Parroquia','required');
	    $this->form_validation->set_rules('cli_calle_prin','Calle Principal','required');
	    $this->form_validation->set_rules('cli_telefono','Telefono','required');
		$this->form_validation->set_rules('cli_email','Email','required|valid_email');

		if($this->form_validation->run()){
			$data=array(
						'cli_fecha'=>$cli_fecha,
						'cli_tipo'=>$cli_tipo,
						'cli_categoria'=>$cli_categoria,
						'cli_codigo'=>$cli_codigo,
						'cli_estado'=>$cli_estado,
						'cli_tipo_cliente'=>$cli_tipo_cliente,
						'cli_apellidos'=>$cli_apellidos,
						'cli_nombres'=>$cli_nombres,
						'cli_raz_social'=>$cli_raz_social,
						'cli_ced_ruc'=>$cli_ced_ruc,
						'cli_nom_comercial'=>$cli_nom_comercial,
						'cli_nacionalidad'=>$cli_nacionalidad,//vendedor
						'cli_refb_tip_cuenta2'=>$cli_refb_tip_cuenta2,
						'cli_pais'=>$cli_pais,
						//'cli_provincia'=>$cli_provincia,
						'cli_canton'=>$cli_canton,
						'cli_parroquia'=>$cli_parroquia,
						'cli_calle_prin'=>$cli_calle_prin,
						'cli_numeracion'=>$cli_numeracion,
						'cli_calle_sec'=>$cli_calle_sec,
						'cli_telefono'=>$cli_telefono,
						'cli_email'=>$cli_email,
						'cli_referencia'=>$cli_referencia
			);	

			if($this->cliente_model->insert($data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CLIENTES',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'cliente');
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'cliente/nuevo');
			}
		}else{
			$this->nuevo();
		}	
	}

	public function editar($id){
		if($this->permisos->rop_actualizar){
			$data=array(
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'vendedores'=>$this->vendedor_model->lista_vendedores(),
						'cliente'=>$this->cliente_model->lista_un_cliente($id),
						'action'=>base_url().'cliente/actualizar'
						);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('cliente/form',$data);
			$modulo=array('modulo'=>'cliente');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar(){
		
		$id=$this->input->post('cli_id');
		$cli_fecha = $this->input->post('cli_fecha');
		$cli_tipo = $this->input->post('cli_tipo');
		$cli_categoria = $this->input->post('cli_categoria');
		$cli_codigo = $this->input->post('cli_codigo');
		$cli_estado = $this->input->post('cli_estado');
		$cli_tipo_cliente = $this->input->post('cli_tipo_cliente');
		$cli_apellidos = $this->input->post('cli_apellidos');
		$cli_nombres = $this->input->post('cli_nombres');
		$cli_raz_social = $this->input->post('cli_raz_social');
		$cli_ced_ruc = $this->input->post('cli_ced_ruc');
		$cli_nom_comercial = $this->input->post('cli_nom_comercial');
		$cli_nacionalidad = $this->input->post('cli_nacionalidad');
		$cli_refb_tip_cuenta2 = $this->input->post('cli_refb_tip_cuenta2');
		$cli_pais = $this->input->post('cli_pais');
		$cli_provincia = $this->input->post('cli_provincia');
		$cli_canton = $this->input->post('cli_canton');
		$cli_parroquia = $this->input->post('cli_parroquia');
		$cli_calle_prin = $this->input->post('cli_calle_prin');
		$cli_numeracion = $this->input->post('cli_numeracion');
		$cli_calle_sec = $this->input->post('cli_calle_sec');
		$cli_telefono = $this->input->post('cli_telefono');
		$cli_email = $this->input->post('cli_email');
		$cli_referencia = $this->input->post('cli_referencia');

		$cliente_act=$this->cliente_model->lista_un_cliente($id);

		if($cli_ced_ruc==$cliente_act->cli_ced_ruc){
			$unique='';
		}else{
			$unique='|is_unique[erp_i_cliente.cli_ced_ruc]';
		}

		$this->form_validation->set_rules('cli_fecha','Fecha Ingreso','required');
		$this->form_validation->set_rules('cli_tipo','Tipo','required');
		$this->form_validation->set_rules('cli_codigo','Codigo','required');
		if($cli_categoria==0){
			//$this->form_validation->set_rules('cli_apellidos','Apellidos','required');
			//$this->form_validation->set_rules('cli_nombres','Nombres','required');
		}else{
			//$this->form_validation->set_rules('cli_nom_comercial','Nombre Comercial','required');
		}
		$this->form_validation->set_rules('cli_raz_social','Razon Social','required');
		$this->form_validation->set_rules('cli_ced_ruc','Cedula/RUC','required'.$unique);
		$this->form_validation->set_rules('cli_pais','Pais','required');
		//$this->form_validation->set_rules('cli_provincia','Provincia','required');
		$this->form_validation->set_rules('cli_canton','Ciudad','required');
		//$this->form_validation->set_rules('cli_parroquia','Parroquia','required');
		$this->form_validation->set_rules('cli_calle_prin','Calle Principal','required');
		$this->form_validation->set_rules('cli_telefono','Telefono','required');
		$this->form_validation->set_rules('cli_email','Email','required|valid_email');

		if($this->form_validation->run()){
			$data=array(
						'cli_fecha'=>$cli_fecha,
						'cli_tipo'=>$cli_tipo,
						'cli_categoria'=>$cli_categoria,
						'cli_codigo'=>$cli_codigo,
						'cli_estado'=>$cli_estado,
						'cli_tipo_cliente'=>$cli_tipo_cliente,
						'cli_apellidos'=>$cli_apellidos,
						'cli_nombres'=>$cli_nombres,
						'cli_raz_social'=>$cli_raz_social,
						'cli_ced_ruc'=>$cli_ced_ruc,
						'cli_nom_comercial'=>$cli_nom_comercial,
						'cli_nacionalidad'=>$cli_nacionalidad,//vendedor
						'cli_refb_tip_cuenta2'=>$cli_refb_tip_cuenta2,
						'cli_pais'=>$cli_pais,
						'cli_provincia'=>$cli_provincia,
						'cli_canton'=>$cli_canton,
						'cli_parroquia'=>$cli_parroquia,
						'cli_calle_prin'=>$cli_calle_prin,
						'cli_numeracion'=>$cli_numeracion,
						'cli_calle_sec'=>$cli_calle_sec,
						'cli_telefono'=>$cli_telefono,
						'cli_email'=>$cli_email,
						'cli_referencia'=>$cli_referencia
			);	

			if($this->cliente_model->update($id,$data)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CLIENTES',
								'adt_accion'=>'ACTUALIZAR',
								'adt_campo'=>json_encode($data),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				redirect(base_url().'cliente');
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'cliente/editar'.$id);
			}
		}else{
			$this->editar($id);
		}	
	}

	public function visualizar($id){
		$data=array(
					'cliente'=>$this->cliente_model->lista_un_cliente($id)
					);
		$this->load->view('cliente/visualizar',$data);
	}


	public function eliminar($id,$nombre){
		if($this->permisos->rop_eliminar){
			if($this->cliente_model->delete($id)){
				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'CLIENTES',
								'adt_accion'=>'ELIMINAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$nombre,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				echo 'cliente';
			}
		}else{
			redirect(base_url().'inicio');
			
		}	
	}

	public function excel($opc_id){

    	$titulo='Clientes';
    	$file="clientes".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel2($data,$file,$titulo);
    }

}
