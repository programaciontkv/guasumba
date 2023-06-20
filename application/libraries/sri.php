<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class sri {
	
	
	public function consulta_sri(){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
    	$ambiente=$amb->con_valor;
    	$factura=$this->factura_model->lista_factura_sin_autorizar();
        set_time_limit(0);
         if ($ambiente == 2) { //Produccion
            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
        } else {      //Pruebas
            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl', 'wsdl');
        }
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = FALSE;

        // Calls
        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $factura->fac_clave_acceso]);
        $res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
        
        if (empty($res)) {
           $this->generar_xml($ambiente,$factura->fac_id); 
        } else {
            $data = array(
            					'fac_autorizacion'=>$res['numeroAutorizacion'], 
            					'fac_fec_hora_aut'=>$res['fechaAutorizacion'], 
            					'fac_xml_doc'=>$res['comprobante'], 
            					'fac_estado'=>'6'
            				);
            $this->factura_model->update($factura->fac_id,$data);
        }

    }

    public function generar_xml($dt,$clave){
        if ($dt[0] == 'AUTORIZADO') {
            $xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
              <ns2:RespuestaAutorizacion xsi:type='ns2:autorizacion' xmlns:ns2='http://ec.gob.sri.ws.autorizacion' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
              <estado>" . $dt[0] . "</estado>
              <numeroAutorizacion>" . $dt[1] . "</numeroAutorizacion>
              <fechaAutorizacion>" . $dt[2] . "</fechaAutorizacion>
              <ambiente>" . $dt[3] . "</ambiente>
              <comprobante><![CDATA[" . $dt[4] . "]]></comprobante>
              <mensajes/>
              </ns2:RespuestaAutorizacion>";
                
                Storage::disk('xml')->put("$clave.xml", $xml);
            $opcion='0';     
        }else{
            $opcion='1';
        }

        $dat=array('opcion'=>$opcion);
        print_r(json_encode($dat)); 
    }


    public function desc_xml($clave){
        $pathToFile= public_path()."/xml/$clave.xml";
        $headers = array(
                            'Content-Type: ' . mime_content_type($pathToFile),
                        );
        $nombre=$clave.'.xml';
        return Response::download($pathToFile,$nombre,$headers);
    }

	function to_excel($data,$file,$titulo,$fec1,$fec2){
		header("Content-Disposition: atachment;filename=$file.xls");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Cache-Control: ");
		echo "<table>
			<thead>
                <tr><th colspan='9'><font size='-5' style='float:left'>Tivka Systems ---Derechos Reservados</font></th></tr>
                <tr><th colspan='9' align='center'>". utf8_decode($titulo)."</th></tr>
                <tr>
                    <td colspan='9'>Desde: $fec1 Hasta: $fec2 </td>
                </tr>
            </thead>
		</table>";
		echo $data;
	}

	function to_excel2($data,$file,$titulo){
		header("Content-Disposition: atachment;filename=$file.xls");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Cache-Control: ");
		echo "<table>
			<thead>
                <tr><th colspan='6'><font size='-5' style='float:left'>Tivka Systems ---Derechos Reservados</font></th></tr>
                <tr><th colspan='6' align='center'>". utf8_decode($titulo)."</th></tr>
            </thead>
		</table>";
		echo $data;
	}

	function to_excel3($data,$file,$titulo,$fec1,$fec2){
		header("Content-Disposition: atachment;filename=$file.xls");
		header("Content-type: application/force-download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Cache-Control: ");
		echo "<table>
			<thead>
                <tr><th colspan='6'><font size='-5' style='float:left'>Tivka Systems ---Derechos Reservados</font></th></tr>
                <tr><th colspan='6' align='center'>". utf8_decode($titulo)."</th></tr>
                <tr>
                    <td colspan='9'>Al: $fec2 </td>
                </tr>
            </thead>
		</table>";
		echo $data;
	}

	
}
?>