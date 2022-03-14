<?php

require_once 'logger.php';
require_once 'conf/conf.php';
require_once './src/utils.php';
include_once './src/ReservasSoapHandler.php';

$baseURL=$_SERVER['REQUEST_URI'];

if(!preg_match("/\/webapp\/?$/", $baseURL)){
    $baseURL= dirname($baseURL);

}elseif(substr($baseURL, -1)=='/'){
    $baseURL= substr($baseURL,0, -1);
}
$baseURL=str_replace('webapp', 'soapserver', $baseURL);
$wsdluri="http://localhost{$baseURL}/tarea05.wsdl";

_log('Se ha iniciado el servicio SoapServer en la url: '.$wsdluri);
//creamos las instacia de SoapServer y le pasamos el descriptor WDSL
$server=new SoapServer($wsdluri);

//Establecemos la clase descriptora de las peticiones SOAP a la clase 
//"ReservasSoapHandle" 
$server->setClass('ReservasSoapHandler');
$server->handle();



        