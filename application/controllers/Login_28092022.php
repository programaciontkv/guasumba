<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('login_model');
		$this->load->model('auditoria_model');
		$this->load->model('empresa_model');
		$this->load->model('factura_model');
	}

	public function index(){
		if($this->session->userdata('s_login')){
			redirect(base_url().'inicio');
		}else{
			$emp = $this->empresa_model->lista_una_empresa(1);
			$data = array(
				'logo' => $emp->emp_logo
			);
			
			$this->load->view('login/index',$data);

		}
	}

	public function ingresar(){
		$usuario = $this->input->post('usuario');
		$clave = $this->input->post('clave');

		$res = $this->login_model->ingresar($usuario, $clave);

		if(!$res){
			$this->session->set_flashdata('error','El usuario o contraseña son incorrectos');
			redirect(base_url());
		}else{
			$session_usuario=array(
									's_idusuario' =>$res->usu_id,
									's_usuario' =>$res->usu_login,
									's_rol' =>$res->rol_id,
									's_imagen' =>$res->usu_imagen,
									's_login'=>TRUE 
								);

			$this->session->set_userdata($session_usuario);
			$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'LOGIN',
								'adt_accion'=>'INGRESAR',
								'adt_campo'=>'',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>'',
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			redirect(base_url().'inicio');
		}

	}

	public function salir(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function documentos($clave,$cliente){

		$cns = $this->factura_model->lista_clave_acceso($clave, $cliente);
		if(!empty($cns)){
				$lista="<tr><th>Tipo Documento</th><th>Fecha de Emisión</th><th>Número Documento</th><th>Estado</th><th>PDF</th><th>XML</th></tr>";
			
			foreach($cns as $rst){

					switch ($rst->tipo) {
		        case '01':
		        $tp_documento = 'FACTURA';
		           
		            break;
		        case '04':$tp_documento = 'NOTA DE CREDITO';
		           
		            break;
		        case '05':$tp_documento = 'NOTA DE CREDITO';
		           
		            break;
		        case '06':$tp_documento = 'GUIA DE REMISION';
		            
		            break;
		        case '07':$tp_documento = 'RETENCION';
		           
		            break;
		   			 }

		   		$lista.="<tr><td> $tp_documento </td><td> $rst->fecha </td> <td> $rst->numero </td> <td> $rst->estado </td><td>
		   		<a href='./pdfs/$rst->clave.pdf' download='./pdfs/$rst->clave.pdf'  >
		   		<img width='24px' src='imagenes/orden.png'  title='Descargar PDF Documento'/>
		   		</a>
		   		</td>
		   		
		   		<td>
		   		<a href='./xml_docs/$rst->clave.xml' download='./xml_docs/$rst->clave.xml' > <img width='24px' src='imagenes/xml.png' ' title='Descargar XML Documento'/> </a>
		   		 </td></tr>";

			}
		$data=array(
				'lista'=>$lista,
				);
		echo json_encode($data);

		}else{
			echo "";
		}
		
		

		





	}

}
