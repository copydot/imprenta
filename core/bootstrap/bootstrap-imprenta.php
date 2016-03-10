<?php
class Bootstrap {
	# encargada de llamar / ubicar / cargar los controladores - modelos y vistas - y creae el objeto controler correspondiente
	# recibe un objeto request llamado desde el index con las propiedades api (si no), modulo, metodo, argumentos
    public static function run(Request $request) { 
			
		$modulo = $request->getModulo(); # Nombre del MODULO
		$metodo = $request->getMetodo(); # Nombre del METODO
		$argumentos = $request->getArgs();
		/*
		echo ("Modulo: </br>");
		var_dump($modulo); echo ("</br>");
		echo ("Metodo: </br>");
		var_dump($metodo); echo ("</br>");
		echo ("Argumentos: </br>");
		var_dump ($argumentos); echo ("</br>");
		*/
		//exit();
		
		$rutaController = ROOT.'modulos'.DS.$modulo.DS.$modulo.'-controller.php'; # ruta donde se encontraria el controlador
		
		if( is_readable($rutaController) ){ # si existe el archivo controller y es accesible
			
			$rutaView = ROOT.'modulos'.DS.$modulo.DS.$modulo.'-view.php'; # ruta donde se encontraria el controlador
			$rutaModel = ROOT.'modulos'.DS.$modulo.DS.$modulo.'-model.php'; # ruta donde se encontraria el controlador
			require_once $rutaModel;
			require_once $rutaView;
			require_once $rutaController;
			//ucwords tranforma a mayuscula la primer letra. Determina el nombre de la clase del objeto a crear
			$nombre_controlador = ucwords($modulo)."Controller"; 
			// crea un objeto del tipo elegido*/
			$controlador = new $nombre_controlador($metodo, $argumentos);
			
		} else { // si existe el archivo controller
			throw new Exception('Controlador no encontrado'); // lanza una excepcion
		} // fin if else si existe el archivo y es accesible
    }
}
?>
