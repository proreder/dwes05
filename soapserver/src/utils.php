<?php
/**
 * Esta función retornará un array asociativo con la configuración del archivo .ini 
 * combinada con la configuración por defecto, teniendo preferencia la configuración 
 * indicada en el archivo .ini.
 * @param string $file ruta del archivo de configuración
 * @param array $defaultConfig  Array asociativo con la configuracion por defecto
 * @return array $config contiene la configuración de la conexion en un array asociativo
 */
function readConfig(string $file, array $defaultConfig){
   
   $array1= parse_ini_file($file);
   $config=array_replace_recursive($defaultConfig, $array1);
   return $config;
}

/**
 * Conexión a la base de datos convecinos
 */
function connect($array) {
    $driver=$array['DB_DRIVER'];
    $host=$array['DB_HOST'];
    $port=$array['DB_PORT'];
    $dbname=$array['DB_SCHEMA'];
    $usuario=$array['DB_USER'];
    $password=$array['DB_PASSWORD'];
    $pdo;
    $dsn=$driver.":host=".$host.";dbname=".$dbname.";charset=utf8";
    
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    try{
        $pdo=new PDO($dsn, $usuario, $password, $options);
        
        return $pdo;
        
    } catch (PDOException $e) {
        
        return 0;
    }
      
}
/**
 * 
 * @param PDO $conn  Objeto que apunta a la conexión con la tabla MySQL
 * @param INT $id  Entero con el id del registro a buscar 
 * @param STRING $orden  cadena que define el ordén de la presentación
 * @return ARRAY arreglo con el resultado de la busqueda
 */
function consultarReservas(PDO $conn, $id, $orden){
    $datod="";
    //preparar la sentencia sql
    try{
        $ps=$conn->prepare('SELECT fecha,inicio,fin FROM reservas WHERE user_id=? ORDER BY timestamp(fecha,inicio) '.$orden);
        $ps->bindParam(1, $id);
        $ps->execute();
        $datos=$ps->fetchAll(PDO::FETCH_ASSOC);
                
    } catch (PDOException $p){
        echo 'Error en la cunsulta a la base de datos.';
    }
    
    $result=null;
    $conn=null;
    if(!$datos){
        //No hay resultados'//
        $datos=false;
    }
       
    return $datos; 
    
     
}

/**
 * busca una reserva 
 * @param PDO $conn objeto que apunta a la base de datos
 * @param array $datos contienes los
 * @return int  1 si se ha encontrado el registro o false si no hay resultado
 */
function buscarReserva(PDO $conn, $array){
    //pasamos los datos almacenados en el array  a variables
    list($_inicio, $_fecha, $_zonaid)=$array;
      
    $resultado="";
    $buscar_sql="SELECT * FROM reservas WHERE fecha = :fecha AND  inicio= :inicio AND zona_id = :zonaid;";
    
    try{
        $ps=$conn->prepare($buscar_sql);
        $ps->bindValue('fecha', $_fecha);
        $ps->bindValue('inicio', $_inicio);
        $ps->bindValue('zonaid', $_zonaid);
        $ps->execute();
        $resultado=$ps->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $ex) {
       //Error en la consulta.//
       echo $ex->getMessage(); 
       $resultado=false;
    }
    //cerramos conexione
    $ps=null;
    $conn=null;
    return $resultado;
}

function modificarReserva(PDO $conn, $array){
    $resultado=false;
    $array_reservas=[];
    //pasamos los datos almacenados en el array  a variables
    list($_inicio, $_fecha, $_zonaid, $_nuevo_inicio, $_nuevo_fin)=$array;
    
    $buscar_reserva="SELECT * FROM reservas WHERE fecha = :fecha AND  inicio= :inicio AND zona_id = :zonaid;";
    $obtener_reservas="SELECT inicio, fin FROM reservas WHERE fecha =:fecha AND  inicio<>:inicio AND zona_id =:zonaid; ORDER BY fecha DESC";
    $modifica_reserva="UPDATE reservas SET inicio=:nuevo_inicio, fin=:nuevo_fin WHERE fecha=:fecha AND inicio=:inicio AND zona_id=:zona_id;" ;

    //iniciamos la transacción
    $conn->beginTransaction();
    try{
        //buscamos si exite la reserva
        $ps=$conn->prepare($buscar_reserva);
        $ps->bindValue('fecha', $_fecha);
        $ps->bindValue('inicio', $_inicio);
        $ps->bindValue('zonaid', $_zonaid);
        $ps->execute();
        $encontrado=$ps->fetch(PDO::FETCH_ASSOC);
        //var_dump($encontrado);
        if($encontrado){
            
            //obtenemos las reservas de ese día, poro sólo los compos inicio y fin excluyendo el resultado anterior
            $ps=$conn->prepare($obtener_reservas);
            $ps->bindValue('fecha', $_fecha);
            $ps->bindValue('inicio', $_inicio);
            $ps->bindValue('zonaid', $_zonaid);
            $ps->execute();
            //se obtiene un array con las reservas del día
            $array_reservas=$ps->fetchAll(PDO::FETCH_ASSOC);
            //muestraTabla($array_reservas);
            $sePisa=sePisa($_nuevo_inicio, $_nuevo_fin, $array_reservas);
            
            //si sePisa=false el registro se puede modificar
            if($sePisa){
               $conn->rollBack();
               $resultado=false;
            }else{
                //los valores para la sentencia: $_inicio, $_fecha, $_zonaid, $_nuevo_inicio, $_nuevo_fin
                $ps=$conn->prepare($modifica_reserva);
                $ps->bindValue('nuevo_inicio', $_nuevo_inicio);
                $ps->bindValue('nuevo_fin', $_nuevo_fin);
                $ps->bindValue('fecha', $_fecha);
                $ps->bindValue('inicio', $_inicio);
                $ps->bindValue('zona_id', $_zonaid);
                if($ps->execute()){
                    $resultado=$ps->rowCount();
                    $conn->commit();
                }else{
                    $conn->rollBack();
                    $resultado=false;
                } 
            }
        }else{
            echo "<br>Registro no encontrado.";
            $conn->rollBack();
            $resultado=false;
        }
    } catch (PDOException $ex) {
         $resultado=false;
        $conn->rollBack();
    }
    $ps=null;
    $conn=null;
    return $resultado;
}
/**
 * 
 * @param PDO $conn objeto que apunta a la conexion con la base de datos
 * @return array con el resultado de la consltas
 */
function obtenerReservas(PDO $conn){
    $resultado=false;
    try{
        $consulta="SELECT * FROM reservas ORDER BY fecha DESC";
        $result=$conn->prepare($consulta);
        $result->execute();
        $resultado=$result->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $ex) {
         echo 'Error en la cunsulta a la base de datos.';

    }
    $result=null;
    $conn=null;
    return $resultado;
}

/**
 * 
 * @param PDO $conn objeto que apunta a la conexión con la MySql
 * @param string $fecha cadena que representa el día para registrar
 * @param string $inicio
 * @param string $fin
 * @param int $zonaID
 * @param int $userID
 * @return -1 si se pisa el horario
 *         ok si se ha insertado el registro con éxito 
 *         error si el registro no se ha podido insertar   
 */
function insertarReserva(PDO $conn, $fecha, $inicio, $fin, $zonaID, $userID){
   $resultado=false;
    
   
   $sql_select="SELECT count(*)  FROM reservas WHERE fecha = :fecha AND "
           . "((inicio>= :hora_inicio AND fin <= :hora_fin) OR"
           . "(inicio <= :hora_inicio AND fin > :hora_inicio) OR"
           . " fin >:hora_inicio AND inicio < :hora_fin);";
           
   $sql_insert="INSERT INTO RESERVAS (fecha, inicio, fin, zona_id, user_id) VALUES (:fecha,:hora_inicio,:hora_fin, :zona_id, :user_id)";
   try{
       //iniciamos transacción, desactivando 'autocommit'
       $conn->beginTransaction();
       //realizamos un select para buscar registros que puedan pisar el horario
       $stmt=$conn->prepare($sql_select);
       //enviamos los parámetros
       $stmt->bindValue('fecha', $fecha);
       $stmt->bindValue('hora_inicio', $inicio);
       $stmt->bindValue('hora_fin', $fin);
       $stmt->execute();
       //recuperamos un sólo registro
       $resultado=$stmt->fetch();
       $registros=$resultado[0];
       //si el número de rigistros devueltos es 0, no se pisan las horas y
       // se procede a insertar el registro
       if($registros == 0){
           $stmt=$conn->prepare($sql_insert);
           //enviamos los parámetros
           $stmt->bindValue('fecha', $fecha);
           $stmt->bindValue('hora_inicio', $inicio);
           $stmt->bindValue('hora_fin', $fin);
           $stmt->bindValue('zona_id', $zonaID);
           $stmt->bindValue('user_id', $userID);
           $stmt->execute();
           if($conn->commit()){
               $resultado=$stmt->rowCount();
           }else{
               $resultado='error';
           }
        }else{
           $resultado=-1;
       }
      
   } catch (PDOException $ex) {
       echo '<br>Error en la consulta';
       $ex->getMessage();
       $conn->rollBack();
       $stmt->debugDumpParams();
   }
   $stmt=null;
   $conn=null;
   return $resultado;
}

/**
 * Se borra la fila que corresponde con las columnas de zona, fecha e inicio que se han recibido por parámetros
 * @param PDO $conn objeto que apunta a la base de datos mysql
 * @param int $zona entero para especificar la zona a borrar
 * @param string $fecha cadena con la fecha en formato válido a borrar
 * @param string $inicio cadena con la hora en formato valido para borrar
 * @return int número de filas afectadas
 */
function eliminarReserva(PDO $conn, $zona, $fecha, $inicio){
    $resultado=false;
    $sql_borrado="DELETE FROM reservas WHERE zona_id = :zona AND  fecha = :fecha AND inicio= :inicio;";
    try{
        $stmt=$conn->prepare($sql_borrado);
        //enviamos los parámetros
        $stmt->bindValue('zona', $zona);
        $stmt->bindValue('fecha', $fecha);
        $stmt->bindValue('inicio', $inicio);
        $stmt->execute();
        $resultado=$stmt->rowCount();
        
    } catch (PDOException $ex) {
        echo '<br>Error en el borrado';
    }
    
    $stmt=null;
    $conn=null;
    return $resultado;
}

/**
 * 
 * @param string $nuevo_inicio hora de inicio nueva a la que se quiere fijar
 * @param string   $nuevo_fin hora de fin nueva a la que se quiere fijar
 * @param array $array contiene para ese dia las horas de las reservas
 * @return bool devuelve true si la hora de la reserva nueva pisan las que ya estan presentes
 *                     o false en caso contrario
 */
function sePisa($nuevo_inicio, $nuevo_fin, $array):bool{
    $resultado=false;
    $contador=count($array);
    //recorremos el array y verificamos las coincidencias en cada indice
    for($i=0;$i<$contador;$i++) {
        $inicio=$array[$i]['inicio'];
        $fin=$array[$i]['fin'];
        
        if((($inicio>= $nuevo_inicio) && ($fin <= $nuevo_fin)) || ($inicio <= $nuevo_inicio && $fin > $nuevo_inicio) || ($fin >$nuevo_inicio && $inicio < $nuevo_fin)){
           $resultado=true;
           }
           
    }
    return $resultado;
}

//muestra los datos encontrados en una tabla
    function muestraTabla($datos){
        echo "<b style='font-size:1.3em'><br>Reservas para este día</b>";
        echo '<br>';
        echo "<table border CELLSPACING=0>";
        echo "<tr style='background-color:#eae3e3'><th>Hora inicio</th><th>Hora final</th></tr>";
        
        $longitud=count($datos);
        for($i=0;$i<$longitud;$i++){
            echo "<tr><td>{$datos[$i]['inicio']}</td>";
            echo "<td>{$datos[$i]['fin']}</td></tr>";
        }
        echo "</table>";
    }