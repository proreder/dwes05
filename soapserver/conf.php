<?php

//Carpeta raiz
define('ROOTPATH', '/DWES04');
//Ruta a la carpeta donde está instalado SMARTY
define('SMARTY_PATH', 'c:/xampp/smarty/libs');
//Directorio donde se almacenarán las plantillas de smarty
define('TEMPLATE_DIR', '/template');
//Directorio que usará Smarty para almacenar las plantillas compiladas
define('TEMPLATE_C_DIR', '/template_c');
//directorio que usará Smarty internamente
define('CACHE_DIR','/cache');

//parámetros de conexión a la base de datos
define('DB_DSN', 'mysql:host=localhost;dbname=convecinos');
define('DB_USER', 'root');
define('DB_PASSWD', '');

//Incluir en la ruta por defectos los archivos de Smarty
set_include_path(SMARTY_PATH);

