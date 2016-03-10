<?php
if($_SERVER["HTTP_HOST"] != 'localhost' && $_SERVER["HTTP_HOST"] != 'desktop'){
    define("DB_HOST", "localhost");
    define("DB_USER", "");
    define("DB_PASS", "");
    define("DB_NAME", "");
    define("BASE_URL", "http://www.algo.com/");
    define("WEB_DIR", "algo/"); // utilizada en request para eliminar el directorio de la url
    define("ADMIN_DIR", "admindir/");
    define("URL_FILES", BASE_URL."demo/files/"); // ubicacion de la carpeta de archivos creados dinamicamente  mediante el panel
    //define("APP_DIR", "");
}else{
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_NAME", "db");
    define("BASE_URL", "http://localhost/");  // URL raiz del sitio
    define("WEB_DIR", "web_dir/"); // utilizada en request para eliminar el directorio de la url
    define("ADMIN_DIR", "admin_dir/");
    define("URL_FILES", BASE_URL.WEB_DIR."archivos/"); // ubicacion de la carpeta de archivos creados dinamicamente  mediante el panel
    //define("APP_DIR", "");
}
 
// URL - UBICACIONES COMUNES
define("URL_PAGE", BASE_URL.WEB_DIR);
define("URL_ADMIN", BASE_URL.WEB_DIR.ADMIN_DIR);
define("ACTUAL_LAYOUT", ""); // nombre de la carpeta donde se ubica la  plantilla HTML usada actualmente
define("URL_LAYOUT", URL_PAGE."site_media/".ACTUAL_LAYOUT); // url que define la ubicacion de la plantilla actual
 
 
//RUTAS FISICAS (PATH)
define("DS", DIRECTORY_SEPARATOR); // constante predefinida - barra separadora de directorios según el sist. operativo / se usa en rutas e includes de php
define("ROOT", realpath(dirname(__FILE__)) . DS); //Ubicación fisica del directorio raiz del sitio ej: C:\xampp\htdocs\loquesea\ - SE USA EN LAS RUTAS E INCLUDES DEL PHP
define("CORE_PATH", ROOT . 'core' . DS); // Directorio o ubicación física de las aplicaciones (core)
define("MODULO_PATH", ROOT."modulos".DS);
 
//define("FILE_PATH", ROOT."..".DS."cmblue".DS."sistema".DS."CmsV3".DS."files".DS); // UBICACIÓN DE LOS ARCHIVOS DEL SITIO
 
//CONTROLADOR Y MÉTODO POR DEFECTO
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_METODO', 'ver');
 
// ini_set: Establece el valor de una directiva de configuración (php.ini)
// include_path: Especifica la lista de directorios donde las funciones require, include, fopen(), file(), readfile() y file_get_contents() buscarán ficheros.
//ini_set("include_path", APP_DIR);
 
const PRODUCCION = TRUE; //False no muestra errores
 
if(PRODUCCION) {
    ini_set('error_reporting', E_ALL | E_NOTICE | E_STRICT);
    ini_set('display_errors', '1');
    ini_set('track_errors', 'On');
} else {
    ini_set('display_errors', '0');
}
?>
