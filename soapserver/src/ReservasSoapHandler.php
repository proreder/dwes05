<?php

require_once 'utils.php';
require_once 'Conexion.php';

class ReservasSoapHandler{
    private $PDOconect;
    private $resultado;
    //patrones de fecha y hora
    
    public function __construct() {
        $this->PDOconect=connect();
        //si hay conexión
        if($this->PDOconect){
             _log('Conexión establecida con la base de datos');
        }else{
            $this->resultado=-7;
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
        $this->resultado=false;
        
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
    if(is_numeric($_zona) && intval($_zona)>0){
        $zona=$_zona;
    }else{
        $this->resultado=-6;
    }
    //***User**//
    if((is_numeric($_user) && intval($_user)>0)){
        $user=$_user;
    }else{
        $this->resultado=-5;
    }
    //**fecha**
     $_fecha=validaDate($_date, 'Y-m-d');
     
     if(!$_fecha){
         $error=-3;
     }else{
         $fecha=$_fecha;
     }
     //**horaInicio**
     $horaInicio=validaDate($_horaInicio, 'H:i');
     if(!$horaInicio){
         $this->resultado=-4;
     }
     //**horaFin**
     
     $horaFin=validaDate($_horaFin, 'H:i');
     if(!$horaFin){
         $this->resultado=-4;
     }
     
     //Se compara los tiempos
     if(!compararTiempos($horaInicio, $horaFin)){
         $this->resultado=-3;
     }
    
        _log ('User: '.$user);
        _log ('Zona: '.$zona);
        _log ('Fecha: '.$fecha);
        _log ('Hora de Inicio:'.$horaInicio);
        _log ('Hora de Fin:'.$horaFin);
    if(!$this->resultado){
        $this->resultado=insertarReserva($this->PDOconect, $fecha, $horaInicio, $horaFin, $zona, $user);
    }  
        return $this->resultado;
    }
    
    public function eliminarReserva($datosIdReserva){
        return $resultado;
    
    }
        
    public function listarreserva($fecha, $zona){
        return $listaReservas;
    }
    
    
}