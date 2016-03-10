<?php
class Bootstrap {
	
	/*require_once("modulos/$modulo/$modulo-model.php");  
	require_once("modulos/$modulo/$modulo-view.php"); 
	require_once("modulos/$modulo/$modulo-controller.php"); 
	*/
	# encargada de llamar / ubicar / cargar los controladores - modelos y vistas - y creae el objeto controler correspondiente
	# recibe un objeto request llamado desde el index con las propiedades api (si no), modulo, metodo, argumentos
    public static function run(Request $request) { 
	
		$api = $request->getApi(); # Toma el valor true o false  y chequea si viene por api
		
		$ajax = $request->getAjax(); # Toma el valor true o false  y chequea si viene por ajax
		
		if ($api == false){
			
			/*if ($ajax == false){*/
				
				$modulo = $request->getModulo(); # Nombre del MODULO
				$metodo = $request->getMetodo(); # Nombre del METODO
				$argumentos = $request->getArgs();
				
				$rutaController = ROOT.'modulos'.DS.$modulo.DS.$modulo.'-controller.php'; # ruta donde se encontraria el controlador
				
				if(is_readable($rutaController)){ # si existe el archivo controller y es accesible
					
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
				
			/*} else { // si viene por api
			
				$modulo = $request->getModulo();
				$metodo = $request->getMetodo();
				$argumentos = $request->getArgs();
				echo ("esto va por AJAX");
				echo ("</br>");
				echo ($modulo);
				echo ("</br>");
				echo ($metodo);
				echo ("</br>");
				var_dump($argumentos);
			
			}; // end if ajax */
			
		} else { // si viene por api
		
			$modulo = $request->getModulo();
			$metodo = $request->getMetodo();
			$argumentos = $request->getArgs();
			echo ("esto va por API ");
			echo ("</br>");
			echo ($modulo);
			echo ("</br>");
			echo ($metodo);
			echo ("</br>");
			var_dump($argumentos);
		}
    }
}
?>
