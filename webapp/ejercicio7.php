
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

//definicion de variables
$zona=$fecha=$horaInicio=$nuevoInicio=$nuevoFin="";
$errores=[];
$resultado=0;
$modificado=false;
//instaciamos la clase Peticion
$p=new Peticion();


if($p->has('enviar')){
    //verificamos que todos los csmpos tengan datos y sean correctos
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
         $errores[]="Fecha o el formato de fecha no es correcto";
     }else{
         //cambiamos el formato de fecha a MySQL
        $fecha=$p->fechaAMySQL($_fecha);
     }
     //**horaInicio**
     $_horaInicio=$p->getString('horaInicio', true);
     $horaInicio=$p->validaDate($_horaInicio, 'H:i');
     
     if(!$horaInicio){
         $errores[]="La hora de inicio no tiene el formato correcto";
     }
     
      //**nuevoInicio**
     $_nuevoInicio=$p->getString('nuevoInicio', true);
     $nuevoInicio=$p->validaDate($_nuevoInicio, 'H:i');
     if(!$nuevoInicio){
         $errores[]="La nueva hora de inicio no tiene el formato correcto";
     }
     
     //**horaFin**
     $_nuevoFin=$p->getString('nuevoFin', true);
     $nuevoFin=$p->validaDate($_nuevoFin, 'H:i');
     if(!$nuevoFin){
         $errores[]="La hora de fin no tiene el formato correcto";
     }
     //si no hay errores  enviamos lapeticion de modificacion al servidor
     if(empty($errores)){
        $idReserva=new class(){};
        $idReserva->zona=$zona;
        $idReserva->fecha=$fecha;
        $idReserva->horaInicio=$horaInicio;
        $tramo=new class(){};
        $tramo->horaInicio=$nuevoInicio;
        $tramo->horaFin=$nuevoFin;
        $tramo->user="";

        //creamos las instacia de SoapServer y le pasamos el descriptor WDSL
        $client=new SoapClient($wsdluri, array('trace' => 1));

        try{
            echo "modificar Reservas try";
            $resultado=$client->modificarReservas($idReserva, $tramo);
        }catch (SoapFault $ex) {
               echo $ex->getMessage();
               $errores[]="[ERROR] Error al conectar al servicio web."; 
               echo "<br>__getLastRequest(): ".$client->__getLastRequest();
               echo "<br>__getLastResponse(): ".$client->__getLastResponse();
        }
     }
}

 //procesamos el resultado obtenido
    switch ($resultado){
        case  1 : 
            $modificado="Se ha modificado el tramo  de la reserva con éxito.";
            break;
        case -1 : 
            $errores[]="Error en los datos de reserva o tramo.";
            break;
        case -2 : 
            $errores[]="Error: no existe la reserva a modificar.";
            break;
        case -3 : 
            $errores[]="Error: el nuevo tramo pisa sobre otro existente.";
            break;
    }
        //mostramos la plantilla smarty
        $smarty->assign('errores', $errores);
        $smarty->assign('modificado', $modificado);
        $smarty->assign('titulo','Modificar reservas');
        $smarty->display('../templates/ejercicio7.tpl');

