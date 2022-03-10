<?php
require_once './soapserver/conf.php';
require_once 'Conexion.php';
class ReservasSoapHandler{
    private $PDOconect;
    
    public function __construct() {
        $this->PDOconect=connect();
        //si hay conexión
        if($this->PDOconect){
            echo '<p>Conexión establecida con la base de datos</p>';
//            _log('Conexión establecida con la base de datos');
        }else{
            echo '<p>ERROR: No se ha podido establecer conexión a la base de datos</p>';
//            _log('ERROR: No se ha podido establecer conexión a la base de datos');
        }
    }
    
    public function crearReserva($reserva){
        $reserva= new class(){
            public $user;
            public $zona;
            public $fecha;
            public $tramo;
        };
        
        $reserva->tramo=new class(){
            public $horaInincio;
            public $horafin;
        };
        return $resultado;
    }
    
    public function eliminarReserva(){
    
    }
        
    public function listarreserva(){
        
    }
    
    
}