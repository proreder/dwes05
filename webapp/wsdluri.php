<?php

//script para obtener la ruta del archivo WDSL

$baseURL=$_SERVER['REQUEST_URI'];

if(!preg_match("/\/webapp\/?$/", $baseURL)){
    $baseURL= dirname($baseURL);

}elseif(substr($baseURL, -1)=='/'){
    $baseURL= substr($baseURL,0, -1);
}
$baseURL=str_replace('webapp', 'soapserver', $baseURL);
$wsdluri="http://localhost{$baseURL}/tarea05.wsdl";

return $wsdluri;