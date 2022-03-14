<?php

//script para poder ver más cómodamente el contenido del archivo ws-errors.log
include 'logger.php';

header("refresh:5;url=verlog.php");
if(isset($_GET['cleanlog'])){
    unlink(__DIR__."/logs/ws-errors.log");
    header("LOCATION: verlog.php");
}
echo '<h3> Última llamada '.date('d-m-Y H:i:s').'</h3>';

if(file_exists(ERROR_LOG_FILE)){
    echo '<p style="text-align:right"><a href="?cleanlog">Limpiar Log</a></p>';
    echo '<pre>';
    readfile(ERROR_LOG_FILE);
    echo'</pre>';
    echo '<p style="text-align:right"><a href="?cleanlog">Limpiar Log</a></p>';
}else{
    echo '<h1>¡No hay fichero log!</h1>';
}
