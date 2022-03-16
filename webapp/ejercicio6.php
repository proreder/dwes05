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
$errores=[];
$resultado=0;
//verficamos que se han recibido datos del formulario
if($p->has('enviar')){
    echo "formulario recibido";
    var_dump($_POST);
    
    //verificamos que todos los csmpos tengan datosy sean correctos
    //***zona**//
    try{
        $zona=$p->getInt('zona_id', true);
        
    } catch (Exception $ex){
        $errores[]="El campo ID de zona ".$ex->getMessage();
    }
   
    //**fecha**
     $date=$p->getString('fecha', true);
     $_fecha=$p->validaDate($date, 'd-m-Y');
     
     if(!$_fecha){
         $errores[]="fecha o el formato no es correcto";
     }else{
         //cambiamos el formato de fecha a MySQL
        $fecha=$p->fechaAMySQL($_fecha);
     }
    
     //si no hay errores
     if(empty($errores)){
        //creamos las instacia de SoapServer y le pasamos el descriptor WDSL
        $client=new SoapClient($wsdluri, array('trace' => 1));
        //si no hay errores se crea la clase anónima $idreserva
        $idreserva=new class{};
        $idreserva->zona=$zona;
        $fecha;
        
        $resultado=$client->listarReserva($fecha, $zona);
        echo "<br>Resultado: ".print_r($resultado);
     }
    
}else{
    $errores[]='Formulario no recibido';
    
}
    //procesamos los resultados devueltos por ReservasSoapHandler
    switch($resultado){
        case  1:
          $result="Tramos encontrados.";
          break;
        case -1:
          $errores[]="No hay reservas en los tramos indicados";
          break;
        case -2:
          $errores[]="ERROR: No se puede acceder a la base de datos";
          break;
       
        
    }
    if(!empty($errores)){
        $smarty->assign('errores', $errores);
        $smarty->assign('titulo','Listar reservas');
        $smarty->display('../templates/ejercicio6.tpl');
    }else{
        $smarty->assign('resultado', $result);
        $smarty->assign('titulo','Listado de las reservas');
        $smarty->display('../templates/ejercicio6_resultado.tpl');
    }

