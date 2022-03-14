<?php
//Ruta a la carpeta donde está instalado SMARTY
define('SMARTY_PATH', 'c:/xampp/smarty/libs');
//Directorio donde se almacenarán las plantillas de smarty
define('TEMPLATE_DIR', '/template');
//Directorio que usará Smarty para almacenar las plantillas compiladas
define('TEMPLATE_C_DIR', '/template_c');
//directorio que usará Smarty internamente
define('CACHE_DIR','/cache');

set_include_path(SMARTY_PATH);

require_once 'Smarty.class.php';
//require_once 'libs.php.php';
/* Carga de smarty */
$smarty = new Smarty();
//configuración del entorno de smarty
$smarty->template_dir = TEMPLATE_DIR;
$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->cache_dir = CACHE_DIR;

//variables
$errores=[];
//verficamos que se han recibido datos del formulario
if(isset($_POST['enviar'])){
    echo "formulario recibido";
    
}else{
    $errores[]='Formulario no recibido';
    $smarty->assign('errores', $errores);
    $smarty->assign('titulo','Realizar una reserva');
    $smarty->display('../templates/ejercicio4.tpl');
}

//funciones


