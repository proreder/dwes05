<?php

include_once 'logger.php';
include_once 'ReservasSoapHandler.php';

$url=strtolower(dirname($_SERVER['REQUEST_URI']));
$uri="http://localhost/$url";

//creamos las instacia de SoapServer y le pasamos el descriptor WDSL
$server=new SoapServer("$uri/tarea5.wsdl");

//Establecemos la clase descriptora de las peticiones SOAP a la clase 
//"ReservasSoapHandle" 
$server->setClass('ReservasSoapHandler');
$server->handle();



        