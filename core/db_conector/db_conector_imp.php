<?php

/*
--------------------------------------------------------------------------------------------
CAPA DE ABSTRACCIÓN PARA REALIZAR LAS DIVERSAS CONSULTAS A LAS BASES DE DATOS CON MySQLi
--------------------------------------------------------------------------------------------

COMO SE UTILIZA:
Se invoca al metodo estatico a ejecutar, pasándole al menos dos parámetros: 
*la sentencia SQL  
*un array con los datos a enlazar a la sentencia SQL preparada.

POR EJ:

	$insert_id = DBObject::ejecutar($sql, $data);

DONDE 
	$sql = "INSERT INTO productos(categoria, nombre, descripcion, precio) VALUES (?, ?, ?, ?)";
	$data = array("isbd","{$categoria}", "{$nombre}", "{$descripcion}", "{$precio}");
	
CUANDO SE TRATE DE UNA CONSULTA DE SELECCIÓN:
se deberá adicionar además un tercer parámetro, el cuál será un array asociativo, cuyas claves, serán los campos de
la tabla cuyos valores vamos a recuperar:

Ej:

	$sql = "SELECT nombre, descripcion, precio FROM productos WHERE categoria = ?";
	$data = array("i", "{$categoria}");
	$fields = array("Producto" => "", "Descripción" => "", "Precio" => "");
	
	DBConnector::ejecutar($sql, $data, $fields);


CONSULTAS PREPARADAS:
"Las bases de datos MySQL soportan sentencias preparadas. Una sentencia preparada o una sentencia parametrizada se usa para ejecutar la misma sentencia repetidamente con gran eficiencia."

"las sentencias preparadas de SQL nos permiten comunicarnos con la base de datos de manera segura, ya que separa la lógica, de los datos que enviamos a la base de datos."

Se implementa en etapas:
	* Primera etapa (prepare): se envia a el servidor MySQL, mediante el comando "prepare", la plantilla de la consulta que se utilizará repetidamente, utilizando "?" para los parametros que se reemplazaran posteriormente
	*Segunda etapa (bind_param): VINCULACION se envian solamente los datos a reemplazar mediante bind_param("is...", $valor1, $valor2,...) donde el primer parametro indica el tipo de datos de los valores siguientes: s (string), i(entero), d (doble) y b (blob).
	*Tercera etapa (execute): Ejecución, mediante el comando execute()
---------
	
REFLEXION
PHP5 es compatible con la reflexión a través de la API de reflexión, que permite examinar las variables, las interfaces, funciones, métodos, parámetros, clases, etc.
ReflectionClass es la clase principal de la API de reflexión. Se utiliza para aplicar la reflexión sobre otras CLASES, permitiendo la extracción de información oportuna sobre todos los componentes de la clase. 	
*/

class DBObject {
	
	protected static $conn; # Objeto conector mysqli
	protected static $stmt; # preparación de la consulta SQL
	protected static $reflection; # Objeto Reflexivo de mysqli_stmt
	protected static $sql; # Sentencia SQL a ser preparada
	protected static $data; # Array conteniendo los tipo de datos más los datos a ser enlazados (será recibido como parámetro)
    public static $results = array(); # Colección de datos retornados por una consulta de selección

    // Conexion a la base de datos
	protected static function conectar() {
        self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // constantes definidas en settings.php
    }
	
	// Etapa uno de la consulta preparada: envio de plantilla de consulta mediante el comando prepare
	protected static function preparar() { 
        self::$stmt = self::$conn->prepare(self::$sql); 
		// Mediante ReflectionClass se recupera la informacion sobre una clase determinada, en este caso la consulta preparada
        self::$reflection = new ReflectionClass('mysqli_stmt'); // mysqli_stmt: clase que representa la consulta preparada.
    }
	
	// Etapa dos de la consulta preparada: vincula los parametros a reemplazar con los corresponedientes argumentos 
    protected static function set_params() { 
        $method = self::$reflection->getMethod('bind_param'); // Obtiene el listado de argumentos mediante bind_param 
		//var_dump ($method);
		// invokeArgs vincula los parametros a reemplazar con los argumentos que los reemplazan 
        $method->invokeArgs(self::$stmt, self::$data); 
    }
	
    // Vincular los datos de la tabla a recuperar para una consulta de selección
    protected static function get_data($fields) { 
        self::$results = array();
        $method = self::$reflection->getMethod('bind_result');  //Recupera los campos de la tabla solicitados 
        $method->invokeArgs(self::$stmt, $fields);// se pasan los argumentos: la consulta preparada y un array ($fields) que como resultado contendrá como indice el nombre de los campos de la consulta y como valores los correspondientes datos contenidos en el campo de la tabla a partir de la consulta
        while(self::$stmt->fetch()) {
            self::$results[] = unserialize(serialize($fields));
        }
    }

    protected static function finalizar() { // cerrar las conexiones abiertas
        self::$stmt->close();
        self::$conn->close();
    }
	
	public static function ejecutar($sql, $data=False, $fields=False) {
		self::$sql = $sql; # plantilla de consulta
		self::$data = $data; # datos a reemplazar
		self::conectar(); # conectar a la base de datos
		self::preparar(); # preparar la consulta SQL
		if($data) {
			self::set_params(); # vincula los parametros a reemplazar con los corresponedientes argumentos 
		}
		self::$stmt->execute(); # ejecutar la consulta
        if($fields) { # si hay consulta select
            self::get_data($fields);
            return self::$results;
        } else {
            if(strpos(strtoupper(self::$sql), 'INSERT') === 0) {
                return self::$stmt->insert_id; # devuelve el id del campo autoincrement de la ultima consulta 
            }
        }
        self::finalizar(); # cerrar conexiones abiertas
    }

}
?>
