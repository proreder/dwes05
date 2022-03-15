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
    //***User**//
    try{
        $user=$p->getInt('user_id', true);
        
    } catch (Exception $ex){
        $errores[]="El campo ID de usuario ".$ex->getMessage();
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
     //**horaInicio**
     $_horaInicio=$p->getString('horaInicio', true);
     $horaInicio=$p->validaDate($_horaInicio, 'H:i');
     
     if(!$horaInicio){
         $errores[]="La hora de inicio no tiene el formato correcto";
     }
     //**horaFin**
     $_horaFin=$p->getString('horaFin', true);
     $horaFin=$p->validaDate($_horaFin, 'H:i');
     if(!$horaFin){
         $errores[]="La hora de inicio no tiene el formato correcto";
     }
     
    //Comparamos que las horas sean consecutivas, que la horaFin no sea anterior que la horaFinal
     if(!$p->compararTiempos($horaInicio, $horaFin)){
        $errores[]="La hora inicio no puede ser superior a la hora final";
     }
     
     if(empty($errores)){
        //creamos las instacia de SoapServer y le pasamos el descriptor WDSL
        $client=new SoapClient($wsdluri, array('trace' => 1));
        //si no hay errores se crea la clase anónima $reserva
        $reserva=new class{};
        $reserva->user=$user;
        $reserva->zona=$zona;
        $reserva->fecha=$fecha;
        $reserva->tramo= new class(){};
        $reserva->tramo->horaInicio=$horaInicio;
        $reserva->tramo->horaFin=$horaFin;
        $resultado=$client->crearReserva($reserva);
        echo "<br>Resultado: ".$resultado;
     }
    
}else{
    $errores[]='Formulario no recibido';
    
}
    //procesamos los resultados devueltos por ReservasSoapHandler
    switch($resultado){
        case  1:
          $result="El registro se ha guardado correctamente";
          break;
        case -1:
          $errores[]="Existe un solapamiento en fecha y horas en un registro guardado";
          break;
        case -2:
          $errores[]="ERROR: No se ha podido guardar el registro";
          break; 
    }
    if(!empty($errores)){
        $smarty->assign('errores', $errores);
        $smarty->assign('titulo','Realizar una reserva');
        $smarty->display('../templates/ejercicio4.tpl');
    }else{
        $smarty->assign('resultado', $result);
        $smarty->assign('titulo','Realizar una reserva');
        $smarty->display('../templates/ejercicio4.tpl');
    }
    

//funciones


