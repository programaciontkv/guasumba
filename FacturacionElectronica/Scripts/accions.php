<?php

include_once '../../Clases/Conn.php';

class Credenciales {

    var $con;

    function Credenciales() {

        $this->con = new Conn();
    }

    function update($dat, $emp) {
        if ($this->con->Conectar() == true) {
            return pg_query("update erp_configuraciones set con_valor2='$dat[0]&$dat[1]&$dat[2]&$dat[3]'  where con_nombre='$emp'   ");
        }
    }

    function lista_empresa() {
        if ($this->con->Conectar() == true) {
            return pg_query("select con_valor2 from  erp_configuraciones where con_id=15   ");
        }
    }

    function registro_certificado($alias, $cer) {
        $emp = pg_fetch_array($this->lista_empresa());
        $alias .= "_SRI";
        ///********SOLO LINUX CENTOS**********//////
        // $fnd = shell_exec("keytool -list -keystore /usr/lib/jvm/jre/lib/security/cacerts -storepass changeit -alias $alias");
        $fnd = shell_exec("keytool -list -keystore C:\Program Files\Java\jre1.8.0_111\lib\security\cacerts -storepass changeit -alias $alias");
        $existe = strpos($fnd, 'Certificate fingerprint');
        $noexiste = strpos($fnd, 'does not exist');

        if ($existe == true) {
            $val = 0;
            // shell_exec("keytool -delete -keystore /usr/lib/jvm/jre/lib/security/cacerts -storepass changeit -alias $alias"
        shell_exec("keytool -delete -keystore C:\Program Files\Java\jre1.8.0_111\lib\security\cacerts -storepass changeit -alias $alias");
        } elseif ($noexiste == true) {
            $val = 1;
        } else {
            $val = $fnd;
        }

        if ($val == 0 || $val == 1) {
            // $sms = shell_exec("keytool -import -keystore '/usr/lib/jvm/jre/lib/security/cacerts' -file /var/www/FacturacionElectronica/Scripts/archivos/$cer  -alias  $alias  -storepass changeit -noprompt");
            $sms = shell_exec("keytool -import -keystore 'C:\Program Files\Java\jre1.8.0_111\lib\security\cacerts' -file C:/xampp/htdocs/central_xml_local/FacturacionElectronica/Scripts/archivos/$cer  -alias  $alias  -storepass changeit -noprompt");
        } else {
            $sms = $val;
        }

        return $sms;
    }

    function genera_certificado($dat) {
        $certs = array();
        // $pkcs12 = file_get_contents('/var/www/FacturacionElectronica/Scripts/archivos/' . $dat[2]);
        $pkcs12 = file_get_contents('C:/xampp/htdocs/central_xml_local/FacturacionElectronica/Scripts/archivos/' . $dat[2]);
        if (openssl_pkcs12_read($pkcs12, $certs, $dat[1])) {
            $fch = fopen("C:/xampp/htdocs/central_xml_local/FacturacionElectronica/Scripts/archivos/" . $dat[0] . ".cer", "w+o");
            if (fwrite($fch, $certs[extracerts][0])) {
                fclose($fch);
                $sms = $this->registro_certificado($dat[0], $dat[0] . '.cer');
            } else {
                $sms = "Error al crear el archivo";
            }
        } else {
            $sms = " Clave Incorrecta";
        }

        return $sms;
    }

}

$Obj = new Credenciales();
$op = $_REQUEST[op];
$dat = $_REQUEST[data];

$sms = 0;
if ($op == 13) {
    $e = 1;
} elseif ($op == 14) {
    $e = 2;
}
if ($Obj->update($dat, 'CREDENCIALES' . $e)) {
    $cert = $Obj->genera_certificado($dat);
    if (!empty($cert)) {
        $sms = $cert;
    }
} else {
    $sms = pg_last_error();
}
echo $sms;

