<?php

require_once 'logger.php';
require_once 'conf/conf.php';
include_once 'ReservasSoapHandler.php';

$url=strtolower(dirname($_SERVER['REQUEST_URI']));
$wdsluri=include 'wsdluri.php';

_log('Se ha iniciado el servicio SoapServer en la url: '.$wdsluri);
//creamos las instacia de SoapServer y le pasamos el descriptor WDSL
$server=new SoapServer("$uri/tarea05.wsdl");

//Establecemos la clase descriptora de las peticiones SOAP a la clase 
//"ReservasSoapHandle" 
$server->setClass('ReservasSoapHandler');
$server->handle();



        