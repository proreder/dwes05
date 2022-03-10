<?php
require_once './soapserver/src/ReservasSoapHandler.php';
$reservasSH=new ReservasSoapHandler();

//Verificamos si exiten las clases SoapClient y SoapServer
if(class_exists(SoapClient)){
    echo "<p>Existe la clase SoapClient</p>";
}else{
    echo "<p>Error: No existe la clase SoapClient</p>";
}    

if(class_exists(SoapServer)){
    echo "<p>Existe la clase SoapServer</p>";
}else{
    echo "<p>Error: No existe la clase SoapServer</p>";
} 