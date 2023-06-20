<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Nusoap_lib{

    function Nusoap_lib(){
        // require_once(base_url().'libraries/nusoap/lib/nusoap'); //Por si estamos ejecutando este script en un servidor Windows
        require_once(str_replace("\\","/",APPPATH).'libraries/nusoap/nusoap.php');
    }
}
?>