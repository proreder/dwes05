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
  
    //verificamos que todos los csmpos tengan datosy sean correctos
    //***zona**//
    try{
        $zona=$p->getInt('zona_id', true);
        
    } catch (Exception $ex){
        $errores[]="El campo ID de ".$ex->getMessage();
    }
   
    //**fecha**
     $date=$p->getString('fecha', true);
     $_fecha=$p->validaDate($date, 'd-m-Y');
     
     if(!$_fecha){
         $errores[]="Fecha o el formato no es correcto";
     }else{
         //cambiamos el formato de fecha a MySQL
        $fecha=$p->fechaAMySQL($_fecha);
     }
    
     //si no hay errores
     if(empty($errores)){
        //creamos las instacia de SoapServer y le pasamos el descriptor WDSL
        $client=new SoapClient($wsdluri, array('trace' => 1));
        //si no hay errores se crea la clase anónima $idreserva
       try{ 

             $listaReservas=$client->listarReservas($fecha, $zona);
             
            if(!isset($listaReservas->reservas->tramo)){
                     $errores[]="No hay tramos a mostrar";
           
            }
             
        } catch (SoapFault $ex) {
           $errores[]="[ERROR] Error al conectar al servicio web."; 
           echo "<br>__getLastRequest(): ".$client->__getLastRequest();
           echo "<br>__getLastResponse(): ".$client->__getLastResponse();
        }
     
     }
    
}
    

if(isset($listaReservas->reservas->tramo)){
    $smarty->assign('reservas', $listaReservas);
    $smarty->assign('titulo','Listado de las reservas');
    $smarty->display('../templates/ejercicio6_resultado.tpl');
}else{
    $smarty->assign('errores', $errores);
    $smarty->assign('titulo','Listar reservas');
    $smarty->display('../templates/ejercicio6.tpl');
}

