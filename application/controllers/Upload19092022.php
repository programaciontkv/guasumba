<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {


	function __construct(){
		parent:: __construct();
	}
	
	public function subir_imagen($img,$id){
		$id=  str_replace(".", "-", $id);
		$config['upload_path']='./imagenes/';
		$config['allowed_types']='gip|jpg|png|jpeg';
		$config['max_size']='4048';
		$config['max_width']='4024';
		$config['max_height']='2048';
		$config['file_name']='img_'.$id;
		$this->load->library('upload',$config);
		if($this->upload->do_upload($img)){
			$info=$this->upload->data();
			$file_info=$info['file_name'];
			$sms=0;
		}else{
			$file_info=$this->upload->display_errors();
			$sms=1;	
		}
		echo  $sms.'&&'.$file_info;
	}

	
}
