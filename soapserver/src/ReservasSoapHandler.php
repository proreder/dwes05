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
       
        }
    }
    //crea una reserva con el objeto datosReserva pasado como argumento
    /**
     * 
     * @param type datosReserva objeto de clase anonima:
     * @return int devuelve un entero que corresponte a la acción realizada
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
        
        $error=false;
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

     
        if(!$this->errorConnect && !$error){
            $result=insertarReserva($this->PDOconect, $fecha, $horaInicio, $horaFin, $zona, $user);
//            _log("result ".$result);
        }  
          
        return $result;
    }
    /**
     * Función pública de eliminación de una reserva
     * @param type $datosBorrarReserva objeto que contiene zona, fecha y horaInicio
     * @return int devuelve un entero que corresponde con una acción realizada
     */
    public function eliminarReserva($datosBorrarReserva){
        
        $this->resultado=false;
        $hayReserva=false;
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
//       
         }

        //verificamo si la reserva existe antes de eliminar
        //creamos un array[$horaInicio, $fecha, $zona] y lo pasamos como parametro a buscarReserva
        if(!$this->resultado){
            $array=[$horaInicio, $fecha, $zona];
            //$hayReserva=true si la reserva a eliminar existe
            $hayReserva=buscarReserva($this->PDOconect, $array);
            _log("hayReserva: ".$hayReserva);
        }  
        if($hayReserva){
            $this->resultado=eliminarReserva($this->PDOconect, $zona, $fecha, $horaInicio);
        }else{
            $this->resultado=-5;
        }  
        return $this->resultado;
    
    
    }
    /**
     * 
     * @param date $fecha string de fecha vaildo a buscar
     * @param int $zona  entero que corresponde a la zona a mostrar
     * @return objeto que contiene los tramos encontrados
     * * @return Los escenarios posibles en esta operación son:
	1->error. Que haya problemas en la base de datos o que los parámetros de entrada de la operación no sean correctos: retornará el objeto con la fecha y la zona vacíos, y sin ningún tramo.
	2->errorTramos. Que no haya ninguna reserva en los tramos indicados: se retornará un array con cero tramos y el resto de datos rellenos.
	3->errorPisa Que haya reservas en la fecha y la zona indicadas: retornará el objeto completamente relleno.
     */    
    public function listarReservas($_fecha, $_zona){
        $error=false;
        
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
       return $listaReservas;
    }
    
    
    /**
     * En esta operación deben contemplarse 6 escenarios posibles:
     *	Que el servicio web no pueda ser accesible por error interno (por ejemplo: base de datos caída).
     *	Que el servicio web responda que no se ha podido modificar la reserva porque la fecha no es posible (no puede existir).
     *	Que el servicio web responda que no se ha podido modificar la reserva porque la reserva a modificar no existe.
     * 	Que el servicio web responda que no se ha podido modificar la reserva porque el horario no es posible o la hora de inicio/fin son incorrectas.
     * 	Que el servicio web responda que no se ha podido modificar la reserva porque hay solapamiento con otra reserva.
     *	Que el servicio web responda que se ha modificado la reserva sin problemas.
     * 
     *  @param clase anónima $idReservas Objeto que contiene la fecha, horaInicio y zona
     *  @param clase anónima $tramo Objeto que contien un tramo con horaInicio y horaFin
     *  @return int entero que corresponde con el resultado de la operación
     *
     */
    public function modificarReservas($idReservas, $tramo){
        //variables
        $error=false;
        $ret=0;
        _log("idReservas: ".print_r($idReservas, true));
        _log("tramo: ".print_r($tramo, true));
        _log("zona: ".$idReservas->zona, true);
        _log("Fecha: ".$idReservas->fecha, true);
        _log("HoraInicio: ".$idReservas->horaInicio, true);
        _log("Tramo horaInicio: ".$tramo->horaInicio, true);
        _log("Tramo horaFin: ".$tramo->horaFin, true);
        
        //filtramos los datos de idReservas de la fecha, zona y horaInicio
        $zona=is_int($idReservas->zona)>0 ? $idReservas->zona : $error=true;
        
        $fecha_temp=filter_var($idReservas->fecha, FILTER_SANITIZE_STRING);
        //fecha = false si es incorrecta
        $fecha=validaDate($fecha_temp, 'Y-m-d');
        $horaInicio_temp=filter_var($idReservas->horaInicio, FILTER_SANITIZE_STRING);
        $horaInicio=validaDate($horaInicio_temp, 'H:i');
        
        //validamos los datos del tramo
        $nuevoInicio_temp=filter_var($tramo->horaInicio, FILTER_SANITIZE_STRING);
        $nuevoInicio=validaDate($nuevoInicio_temp, 'H:i');
        
        $nuevoFin_temp=filter_var($tramo->horaFin, FILTER_SANITIZE_STRING);
        $nuevoFin= validaDate($nuevoFin_temp, 'H:i');
        
        _log("Error: ".$error, true);
        //si hay errores en los datos de entrada terminamos con return -1
        if(!$error || !$fecha || !$horaInicio || !$nuevoInicio || !$nuevoFin){
            $ret -1;
        }else{
            _log("Variables:", true);
            _log("zona: ".$zona, true);
            _log("Fecha: ".$fecha, true);
            _log("HoraInicio: ".$horaInicio, true);
            _log("Tramo horaInicio: ".$nuevoInicio, true);
            _log("Tramo horaFin: ".$nuevoFin, true);
        }
        //verificamos si la reserva a modificar existe, si no existe ret=-2
        //almacenamos los datos en un array_reserva, que si hay resserva se devuelven los datos si 
        //no existe la reserva se devuelve false
        if(!$ret){
            $array_reserva=[$horaInicio, $fecha,$zona];
            $existe_reserva=buscarReserva($this->PDOconect, $array_reserva);
            if($existe_reserva){
                $disponible=false;
                //si existe verificamos si el nuevo tramo no pisa a otro tramo
                $tramos=obtenerTramos($this->PDOconect, $fecha, $zona);
                //si se han obtenido resultados los procesamos, si no hay datos
                //el día está libre
                if($tramos){
                    _log("tramos: ".print_r($tramos), true);
                  //true si el nuevo tramo pisa otro tramo ya existente
                  $sePisa=sePisa($nuevoInicio, $nuevoFin, $tramos);
                  //si no se pisa procedemos a la modificación del tramo
                  if(!$sePisa){
                      //pasamos los datos almacenados en el array  a variables
                        $array=[$horaInicio, $fecha, $zona, $nuevoInicio, $nuevoFin];
                        list($_inicio, $_fecha, $_zonaid, $_nuevo_inicio, $_nuevo_fin)=$array;
                        $ret=modificarReserva($this->PDOconect, $array);
                        _log("Reserva ret=modificarReserva: ".$ret);
                  }else{
                      $ret=-3;
                  }
                }
            }else{
                $ret=-2;
            }
        }
        
//        _log("Existe la reserva: ".$existe_reserva, true);
//        _log("array_tramos: ".print_r($arrayTramos, true));
        
        //resultado de retorno
        return $ret;
    }
}