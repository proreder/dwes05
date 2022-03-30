
<?php
//Script para eliminar una reserva

//Ruta a la carpeta donde está instalado SMARTY
define('SMARTY_PATH', 'c:/xampp/smarty/libs');
//Directorio donde se almacenarán las plantillas de smarty
define('TEMPLATE_DIR', '/template');
//Directorio que usará Smarty para almacenar las plantillas compiladas
define('TEMPLATE_C_DIR', '/template_c');
//directorio que usará Smarty internamente
define('CACHE_DIR','/cache');

set_include_path(SMARTY_PATH);

$wsdluri=include 'wsdluri.php';
require_once 'Smarty.class.php';
require_once '../libs/peticion.php';
/* Carga de smarty */
$smarty = new Smarty();
//configuración del entorno de smarty
$smarty->template_dir = TEMPLATE_DIR;
$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->cache_dir = CACHE_DIR;

//instaciamos la clase Peticion
$p= new Peticion();

//variables
$errores="";
if($p->has('enviar')){
    
}else{
    $smarty->assign('titulo','Listar reservas');
    $smarty->display('../templates/ejercicio7.tpl');
}
if(!empty($errores)){
    $smarty->assign('errores', $errores);
    $smarty->assign('titulo','Listar reservas');
    $smarty->display('../templates/ejercicio7.tpl');
}else{

    $smarty->assign('reservas', $listaReservas);
//       $smarty->assign('tramos', $tramos);
    $smarty->assign('titulo','modificar las reservas');
    $smarty->display('../templates/ejercicio7_resultado.tpl');
}