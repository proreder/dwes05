<?php

$url=strtolower(dirname($_SERVER['REQUEST_URI']));
$wsdluri=include 'wsdluri.php';
echo $wsdluri;
//creamos las instacia de SoapServer y le pasamos el descriptor WDSL
$client=new SoapClient($wsdluri, array('trace' => 1));
var_dump($client->__getFunctions());

