<?php
define ('SMARTY_PATH',APP_ROOT_DIR.'\..\smarty\libs');

define ('TEMPLATE_DIR',APP_ROOT_DIR.'\templates');
define ('TEMPLATE_C_DIR',APP_ROOT_DIR.'\templates_c');
define ('CONFIG_DIR',APP_ROOT_DIR.'\config');
define ('CACHE_DIR',APP_ROOT_DIR.'\cache');

set_include_path(SMARTY_PATH);

////Carpeta raiz
//define('ROOTPATH', '/DWES05');
////Ruta a la carpeta donde está instalado SMARTY
//define('SMARTY_PATH', 'c:/xampp/smarty/libs');
////Directorio donde se almacenarán las plantillas de smarty
//define('TEMPLATE_DIR', '/template');
////Directorio que usará Smarty para almacenar las plantillas compiladas
//define('TEMPLATE_C_DIR', '/template_c');
////directorio que usará Smarty internamente
//define('CACHE_DIR','/cache');

//parámetros de conexión a la base de datos
define('DB_DSN', 'mysql:host=localhost;dbname=convecinos');
define('DB_USER', 'root');
define('DB_PASSWD', '');

//Incluir en la ruta por defectos los archivos de Smarty
//set_include_path(SMARTY_PATH);

