<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Sri extends CI_Controller {

    function __construct(){
        parent:: __construct();
        if(!$this->session->userdata('s_login')){
            redirect(base_url());
        }
        $this->load->model('configuracion_model');
        $this->load->library("nusoap_lib");
    }
	
	
	public function consulta_sri($clave){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
    	$ambiente=$amb->con_valor;
    	
        set_time_limit(0);
         if ($ambiente == 2) { //Produccion
            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
        } else {      //Pruebas
            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
        }
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = FALSE;

        // Calls
        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $clave]);
        $res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
        if (empty($res)) {
           $this->generar_xml($clave,''); 
        } else {
            if($res['estado']!='AUTORIZADO'){
                $this->generar_xml($clave,''); 
            }else{
                $data = array(
                					'estado'=>$res['estado'], 
                                    'autorizacion'=>$res['numeroAutorizacion'], 
                					'fecha'=>$res['fechaAutorizacion'], 
                					'comprobante'=>$res['comprobante'], 
                                    'ambiente'=>$res['ambiente'], 
                				);
                $this->generar_xml($clave,$data); 
            }
        }

    }

    public function generar_xml($clave,$dt){
        if (!empty($dt)) {
            $xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
              <ns2:RespuestaAutorizacion xsi:type='ns2:autorizacion' xmlns:ns2='http://ec.gob.sri.ws.autorizacion' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
              <estado>" . $dt['estado'] . "</estado>
              <numeroAutorizacion>" . $dt['autorizacion'] . "</numeroAutorizacion>
              <fechaAutorizacion>" . $dt['fecha'] . "</fechaAutorizacion>
              <ambiente>" . $dt['ambiente'] . "</ambiente>
              <comprobante><![CDATA[" . $dt['comprobante'] . "]]></comprobante>
              <mensajes/>
              </ns2:RespuestaAutorizacion>";
                $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
                fwrite($fch, $xml);
                fclose($fch);
                
        }

        $file = './xml_docs/' . $clave . '.xml';
        header("Content-type:xml");
        header("Content-length:" . filesize($file));
        header("Content-Disposition: attachment; filename=$clave.xml");
        readfile($file);
        unlink($file);

        
    }
	
}
?>