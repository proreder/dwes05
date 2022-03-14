<?php


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
     * @return type
     */
    public function crearReserva($reserva){
        //logs de los datos recibidos para crear una reserva
        _log ('Datos recibidos:'.print_r($reserva, true));
        $user=$reserva->user;
        $zona=$reserva->zona;
        $fecha=$reserva->fecha;
        $horaInicio=$reserva->tramo->horaInicio;
        $horaFin=$reserva->tramo->horaFin;
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