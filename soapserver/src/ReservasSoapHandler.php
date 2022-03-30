<?php
//Clase manejadora de peticiones http
require_once 'utils.php';
require_once 'Conexion.php';

class ReservasSoapHandler{
    private $PDOconect;
    private $resultado;
    private $errorConnect=false;
    //patrones de fecha y hora
    
    public function __construct() {
        $this->PDOconect=connect();
        //si hay conexión
        if($this->PDOconect){
             _log('Conexión establecida con la base de datos');
        }else{
            $this->errorConnect=true;
             _log('ERROR: No se ha podido establecer conexión a la base de datos');
//             return $array=['error' => -1];
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
    */
    public function crearReserva($datosReserva){
        $result=false;
        //error para return 1
        $error=false;
        $errorTramos=false;
        $errorPisa=false;
        
        //logs de los datos recibidos para crear una reserva
//        _log('Datos recibidos:'.print_r($datosReserva, true));
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
            $error=true;
        }
        //***User**//
        if((is_numeric($_user) && intval($_user)>0)){
            $user=$_user;
        }else{
            $error=true;
        }
        //**fecha**
         $_fecha=validaDate($_date, 'Y-m-d');

         if($_fecha){
             $fecha=$_fecha;
         }else{
             $error=true;
         }
         //**horaInicio**
         $horaInicio=validaDate($_horaInicio, 'H:i');
         if(!$horaInicio){
             $error=true;
         }
         //**horaFin**

         $horaFin=validaDate($_horaFin, 'H:i');
         if(!$horaFin){
             $error=true;
         }

         //Se compara los tiempos
         if(!compararTiempos($horaInicio, $horaFin) && !$error){
             $error=true;
         }

////        
//         _log ('User: '.$user);
//            _log ('Zona: '.$zona);
//            _log ('Fecha: '.$fecha);
//            _log ('Hora de Inicio:'.$horaInicio);
//            _log ('errorConnect:'.$this->errorConnect);
//            _log ('error:'.$error);
        if(!$this->errorConnect && !$error){
            $result=insertarReserva($this->PDOconect, $fecha, $horaInicio, $horaFin, $zona, $user);
//            _log("result ".$result);
        }  
          
        return $result;
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
         _log ("Hora_inicio: ".$horainicio,true);
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
     * @param date $fecha
     * @param int $zona
     * @return objeto o array
     * * @return Los escenarios posibles en esta operación son:
	1->error. Que haya problemas en la base de datos o que los parámetros de entrada de la operación no sean correctos: retornará el objeto con la fecha y la zona vacíos, y sin ningún tramo.
	2->errorTramos. Que no haya ninguna reserva en los tramos indicados: se retornará un array con cero tramos y el resto de datos rellenos.
	3->errorPisa Que haya reservas en la fecha y la zona indicadas: retornará el objeto completamente relleno.
     */    
    public function listarReservas($_fecha, $_zona){
        $error=false;
        
        
//        $tramo=new class(){};
        
        //logs de los datos recibidos para crear una reserva
        _log ('Datos fecha:'.$_fecha, true);
        _log('Datos zona: ', $_zona, true);
        
        //verificamo que los datos son válidos
        //verificamos zona es numérico y positivo
        //***zona**//
        if(is_numeric($_zona) && intval($_zona)>0){
            $zona=$_zona;
        }else{
            $error=true;
        }

        //**fecha** se valida la fecha
         $fecha=validaDate($_fecha, 'Y-m-d');

         if($fecha){
            $fecha=$_fecha; 
         }else{
           $error=true;  
         }
         
         _log('Error: '.$error);
           
        //si hay error en la conexión a la base de datos y error en los datos fecha y zona
        // se devuelve el objeto con fecha y zona vacios
        if(!$this->errorConnect && !$error){
            $tramos=listarReserva($this->PDOconect, $fecha, $zona);
        }else{
            //se crea un objeto con la fecha y zona y sin tramos
            $listaReservas=new class(){};
            $listaReservas->fecha="";
            $listaReservas->zona="";
            $listaReservas->reservas=[];
            return $listaReservas;
        } 
        _log('Tramos encontrados:'.print_r($tramos, true));
        //si hay error en la consulta o hay error en los paramentros de la consulta
//        if($error==-1 || $tramos==-1){
//            $listaReservas->fecha="";
//            $listaReservas->zona="";
//            $tramo->horaInicio="";
//            $tramo->horaFin="";
//            $listaReservas->reservas[]=$tramo;
//            _log('$error==-1 || $tramos==-1))'.print_r($listaReservas, true));
//        }
//        
//        //si no hay error en los paramentros de la consulta y tramos está vacio 
//        // un array con la fecha y zona y cero tramos
        if(!$error && !isset($tramos->tramo)){
            $listaReservas=new class(){};
            $listaReservas->fecha=$fecha;
            $listaReservas->zona=$zona;
            $listaReservas->reservas=[];
              
             _log('no hay error en los paramentros de la consulta y tramos está vacio'.print_r($listaReservas, true));
             return $listaReservas;
        }
        
        //si no hay errores y $tramos contiene propiedades
        if (!$error && isset($tramos->tramo)){
            $listaReservas=new class(){};
            $listaReservas->fecha=$fecha;
            $listaReservas->zona=$zona;
//            $listaReservas->reservas=[];
        
            $listaReservas->reservas=$tramos;
            
            
        }
       _log('listaReservas SoapHandler:'.print_r($listaReservas, true));
        
    
        return $listaReservas;
    }
    
    
}