<?php

//devisamos los mensajes de error a un archivo de texto para seber lo que está pasando
//Este código hará que los errores se vuelquen a un archivo 
//(el archivo dwes05/soapserver/logs/ws-errors.log) y que no se envíen al navegador.

define('ERROR_LOG_FILE', __DIR__."/logs/ws-errors.log");

ini_set("log_errors", 1);
ini_set("error_log", ERROR_LOG_FILE);
ini_set("display_errors", 0);
error_reporting(E_ALL);

function _log($stmt,$level='INFO'){
    $fecha=(new DateTime())->format("[d-M-Y H:i:s]");
    $level="(".$level.")";
    file_put_contents(ERROR_LOG_FILE, $fecha.$level.$stmt.PHP_EOL,FILE_APPEND);
}
