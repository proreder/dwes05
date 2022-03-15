<?php

require_once 'utils.php';
require_once 'Peticion';

class ReservasSoapHandler{
    private $PDOconect;
   
    //patrones de fecha y hora
    
    public function __construct() {
        $this->PDOconect=connect();
        //si hay conexión
        if($this->PDOconect){
             _log('Conexión establecida con la base de datos');
        }else{
             _log('ERROR: No se ha podido establecer conexión a la base de datos');
        }
    }
    //crea una reserva con el objeto $reserva pasado como argumento
    /**
     * 
     * @param type $reserva objeto de clase anonima:
     *  
     *       $reserva= new class(){
     *       public $user;
     *       public $zona;
     *       public $fecha;
     *       public $tramo;
     *   };
     *    $tramo=new class(){
     *       public $horaInicio;
     *       public horaFin;
     * 
     * @return $resultado int con el número de error encontrado
     */
    public function crearReserva($reserva){
        $error=false;
        $p=new Peticion();
        //logs de los datos recibidos para crear una reserva
        _log ('Datos recibidos:'.print_r($reserva, true));
        $_user=$reserva->user;
        $_zona=$reserva->zona;
        $_date=$reserva->fecha;
        $_horaInicio=$reserva->tramo->horaInicio;
        $_horaFin=$reserva->tramo->horaFin;
        
        //verificamo que los datos son válidos
        //verificamos zona es numérico y positivo
    //***zona**//
    if(is_numeric($_zona) && $_zona=intval($zona)>0){
        $zona=$_zona;
    }else{
        $error=-6;
    }
    //***User**//
    if((is_numeric($_user) && $_user=intval($_user)>0) && !$error){
        $user=$_user;
    }else{
        $error=-5;
    }
    //**fecha**
     $date=$p->getString('fecha', true);
     $_fecha=validaDate($date, 'Y-m-d');
     
     if(!$_fecha){
         $error=-2;
     }else{
         $fecha=$_fecha;
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
        
        _log ('User: '.$user);
        _log ('Zona: '.$zona);
        _log ('Fecha: '.$fecha);
        _log ('Hora de Inicio:'.$horaInicio);
        _log ('Hora de Fin:'.$horaFin);
        
        return $resultado;
    }
    
    public function eliminarReserva($datosIdReserva){
        return $resultado;
    
    }
        
    public function listarreserva($fecha, $zona){
        return $listaReservas;
    }
    
    
}