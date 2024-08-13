<?php
class Persona{

    private $nombre;
    private $apellido;
    private $mensajeoperacion;

    //Metodo constructor de la clase
    public function __construct(){
        $this->nombre = "";
        $this->apellido = "";
    }
    //Metodos de acceso a los datos de la clase
    public function getnombre(){
        return $this->nombre;
    }
    public function getapellido(){
        return $this->apellido;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    //Metodos de escritura de los atributos de la clase
    public function setnombre($nom){
        $this->nombre = $nom;
    }
    public function setapellido($ape){
        $this->apellido = $ape;
    }
    public function setmensajeoperacion($var){
        $this->mensajeoperacion = $var;
    }
    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = $this->getnombre()." ".$this->getapellido()." ";
        return $cadena;
    }    

}

?>