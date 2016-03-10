<?php
class Request {
 
    public function __construct() {
        $this->modulo = "";
        $this->metodo = "";
        $this->argumentos = array ();
 
        $path = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL); # filtra caracteres de la URI
        $url = str_replace(WEB_DIR, "", $path ); # quita el directorio web de la URI
        $url = strtolower($url); # convierte toda la cadena a minusculas
        $url = explode('/', $url); # crea un array dividido segun "/"
        $url = array_filter($url); #elimina elementos no validos / falses
        $cantidad = count($url); #cantidad de elementos en la URI
        //echo $cantidad; exit ();
        switch ($cantidad) {
            case 0:  # si la uri es vacia se toman los valores por defecto definidos mas abajo  (creados en settings)
                break;
 
            case 1: #Ej:  item/ o  item.html
                $modulo = limpiar_extension ($url[0]);
                $this->modulo = $modulo;
                $this->metodo = DEFAULT_METODO;
                break;
 
            case 2: #Ej:  item/listar/  o  item/listar.html
                $this->modulo = array_shift($url); # almacena y quita el primer valor del array
                $this->metodo = array_shift($url);
                $this->argumentos = array(); // argumentos array
                //$this->argumentos = $this->limpiar_extension ($url[0]); // toma el
                break;
 
            case 3: #Ej:  agenda/categoria0/categoria1/ o  agenda/categoria0/categoria1.html
            case 4: #Ej:  agenda/categoria0/categoria1/categoria2 o agenda/categoria0/categoria1/categoria2.html
            case 5: #Ej:  agenda/categoria0/categoria1/categoria2 o agenda/categoria0/categoria1/categoria2.html
            case 6: #Ej:  agenda/categoria0/categoria1/categoria2/1234/detalle_del_evento.html
                $this->modulo = array_shift($url); # almacena y quita el primer valor del array
                $indice = count($url);
                array_push($url,$this->limpiar_extension(array_pop($url)));  // quita el ultimo elemento - array_pop- , le aplica la funcion y lo vuelve a agregar el ultimo elemento - array_push-
 
                if ((int)($url[$indice-2]) != 0){ // a la cantidad total se le quito uno y teniendo en cuenta que los indices parten de cero, chequeamos si el penultimo  es un numero
                    $this->metodo = "ver";
                } else {
                    $this->metodo = "listar";
                }
                $this->argumentos = $url;
                break;
 
            default:
                $this->modulo = "error";
                $this->metodo = "ver";
                break;
        } #end switch
 
        # CARGA POR DEFECTO
        if (!$this->modulo) {
            $this->modulo = DEFAULT_CONTROLLER; # definido como constante en index
        }
 
        if (!$this->metodo) {
            $this->metodo = DEFAULT_METODO; #  por defecto, definido como metodo abstracto en la clase Controler
        }
 
        if(!isset($this->argumentos)) {
            $this->argumentos =  array ();
        }
    }
 
    private function limpiar_extension($url){
        $extension_html = strrchr($url, "."); # chequea si la url tiene extension ej: .html
        if ($extension_html) {
            return strrev(preg_replace(strrev("/$extension_html/"),"",strrev($url),1));
        } else {
            return $url;
        }
    }
 
    public function getModulo(){
        return $this->modulo;
    }
 
    public function getMetodo() {
        return $this->metodo;
    }
 
    public function getArgs(){
        return $this->argumentos;
    }
}
?>
