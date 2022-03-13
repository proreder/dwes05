<?php

$url=strtolower(dirname($_SERVER['REQUEST_URI']));
$wsdluri=include 'wsdluri.php';
echo $wsdluri;
//creamos las instacia de SoapServer y le pasamos el descriptor WDSL
$client=new SoapClient($wsdluri, array('trace' => 1));
var_dump($client->__getFunctions());

$reserva=new class(){};
$reserva->user=4;
$reserva->zona=100;
$reserva->horaInicio="10:00";
$reserva->fecha='2022-03-01';
$reserva->tramo= new class(){};
$reserva->tramo->horaInicio="10:00";
$reserva->tramo->horaFin="11:00";
//$client->listarReservas('2022-03-01',1);

var_dump($reserva);

