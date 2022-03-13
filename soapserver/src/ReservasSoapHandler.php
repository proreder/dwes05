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
     * @param type $reserva
     * @return type
     */
    public function crearReserva($reserva){
        //logs de los datos recibidos para crear una reserva
        _log ('Datos recibido 1:'.print_r($reserva, true));
        
        return $resultado;
    }
    
    public function eliminarReserva($datosIdReserva){
        return $resultado;
    
    }
        
    public function listarreserva($fecha, $zona){
        return $listaReservas;
    }
    
    
}