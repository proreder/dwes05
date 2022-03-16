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
    //crea una reserva con el objeto datosReserva pasado como argumento
    /**
     * 
     * @param type datosReserva objeto de clase anonima:
     *  
     *       datosReserva= new class(){
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
    public function crearReserva($datosReserva){
        $this->resultado=false;
        
        //logs de los datos recibidos para crear una reserva
        _log ('Datos recibidos:'.print_r($datosReserva, true));
        $_user=$datosReserva->user;
        $_zona=$datosReserva->zona;
        $_date=$datosReserva->fecha;
        $_horaInicio=$datosReserva->tramo->horaInicio;
        $_horaFin=$datosReserva->tramo->horaFin;
        
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
        _log ('Hora de Inicio:'.$horainicio);
        _log ('Hora de Fin:'.$horaFin);
    if(!$this->resultado){
        $this->resultado=insertarReserva($this->PDOconect, $fecha, $horainicio, $horaFin, $zona, $user);
    }  
        return $this->resultado;
    }
    
    public function eliminarReserva($datosBorrarReserva){
        
        $this->resultado=false;
        
        //logs de los datos recibidos para crear una reserva
        _log ('Datos recibidos:'.print_r($datosBorrarReserva, true));
        $_zona=$datosBorrarReserva->zona;
        $_date=$datosBorrarReserva->fecha;
        $_horaInicio=$datosBorrarReserva->horaInicio;
        
        
        //verificamo que los datos son válidos
        //verificamos zona es numérico y positivo
        //***zona**//
        if(is_numeric($_zona) && intval($_zona)>0){
            $zona=$_zona;
        }else{
            $this->resultado=-4;
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
             $this->resultado=-3;
         }else{
             $horainicio=date("H:i:s",strtotime($horaInicio));
         log ("Hora_inicio: ".$horainicio,true);
//            $horaInicio=$horaInicio.":00";
//             $horainicio=date("H:i",strtotime($horaInicio));
//              echo "Hora: ".$horainicio;
         }

             _log ('Zona: '.$zona);
            _log ('Fecha: '.$fecha);
            _log ('Hora de Inicio:'.$horaInicio);

        if(!$this->resultado){
            $this->resultado=eliminarReserva($this->PDOconect, $zona, $fecha, $horaInicio);
        }  
        return $this->resultado;
    
    
    }
    /**
     * 
     * @param type $fecha
     * @param type $zona
     * @return type
     */    
    public function listarReserva($_fecha, $_zona){
        $this->resultado=false;
        
        //logs de los datos recibidos para crear una reserva
        _log ('Datos fecha:'.$_fecha, true);
        _log('Datos zona: ', $_zona, true);
        
        //verificamo que los datos son válidos
        //verificamos zona es numérico y positivo
        //***zona**//
        if(is_numeric($_zona) && intval($_zona)>0){
            $zona=$_zona;
        }else{
            $this->resultado=-4;
        }

        //**fecha**
         $fecha=validaDate($_fecha, 'Y-m-d');

         if(!$fecha){
             $error=-4;
         }else{
             $fecha=$_fecha;
         }
         _log ('Zona: '.$zona);
         _log ('Fecha: '.$fecha);
           

        if(!$this->resultado){
            $this->resultado=listarReserva($this->PDOconect, $zona, $fecha);
        }  
        return $this->resultado;
    
        return $listaReservas;
    }
    
    
}