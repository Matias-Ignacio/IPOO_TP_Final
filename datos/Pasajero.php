<?php

class Pasajero{

    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $idviaje;
    private $mensajeoperacion;

    //Metodo constructor de la clase
    public function __construct(){
        $this->pnombre = "";
        $this->papellido = "";
        $this->pdocumento = "";
        $this->ptelefono = 0;
        $this->idviaje = 0;
    }
    //Metodos de acceso a los datos de la clase
    public function getpnombre(){
        return $this->pnombre;
    }
    public function getpapellido(){
        return $this->papellido;
    }
    public function getpdocumento(){
        return $this->pdocumento;
    }
    public function getptelefono(){
        return $this->ptelefono;
    }
    public function getidviaje(){
        return $this->idviaje;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    //Metodos de escritura de los atributos de la clase
    public function setpnombre($nom){
        $this->pnombre = $nom;
    }
    public function setpapellido($ape){
        $this->papellido = $ape;
    }
    public function setpdocumento($pdocumento){
        $this->pdocumento = $pdocumento;
    }
    public function setptelefono($tel){
        $this->ptelefono = $tel;
    }
    public function setidviaje($var){
        $this->idviaje = $var;
    }
    public function setmensajeoperacion($var){
        $this->mensajeoperacion = $var;
    }
    
    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = "Pasajero: ". $this->getpnombre()." ".$this->getpapellido()."\tDNI: ".$this->getpdocumento().
        "\t Tel: ".$this->getptelefono() . 
        "\n------------------------------------------\n";
        return $cadena;
    }
    /**
     * Caragr los datos del pasajero
     * @param $doc
     * @param $nom
     * @param $ape
     * @param $tel
     * @param $idviaje
     */
    public function cargar($doc, $nom, $ape, $tel, $idviaje){
        $this->setpdocumento($doc);
        $this->setpnombre($nom);
        $this->setpapellido($ape);
        $this->setptelefono($tel);
        $this->setidviaje($idviaje);
    }

    /**
     * REaliza la consulta en la base de datos
     * @param string $consultaSQL
     * @return boolean
     */
    public function realizarconsulta($consultaSQL){
        $base = new BaseDatos();
        $resp = false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
			    $resp = true;
			}else{
				$this->setmensajeoperacion($base->getError());					
			}
		}else{
			$this->setmensajeoperacion($base->getError());			
		}
		return $resp; 
    }

    /**
     * Metodo que compara dos pasajeros a traves del numero de pdocumento
     * Devuelve True si son iguales
     * @param $pasNuevo objeto
     * @return boolean
     */
    public function compararPasajero($pasNuevo){
        $igual = false;
        if ($pasNuevo->getpdocumento() == $this->getpdocumento()){
            $igual = true;
        }
        return $igual;
    }



	/**
	 * Recupera los datos de un pasajero por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base = new BaseDatos();
		$consultaSQL="SELECT * FROM pasajero WHERE pdocumento = ".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
				if($fila=$base->Registro()){					
				    $this->setpdocumento($dni);
					$this->setpnombre($fila['pnombre']);
					$this->setpapellido($fila['papellido']);
					$this->setptelefono($fila['ptelefono']);
                    $this->setidviaje($fila['idviaje']);
					$resp= true;
				}						
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());	 	
		 }		
		 return $resp;
	}	

    /**
     * Insertar el pasajero en la BD
     * @return boolean
     */
    public function insertar(){
		$consultaSQL="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje) 
				VALUES (".$this->getpdocumento().",
                '".$this->getpnombre()."',
                '".$this->getpapellido()."',
                ".$this->getptelefono().",
                ".$this->getidviaje().")";	
		return $this->realizarconsulta($consultaSQL);
	}


    /**
     * Modificar un pasajero en la BD
     * @return boolean
     */
    public function modificar(){
		$consultaSQL="UPDATE pasajero SET 
                papellido = '".$this->getpapellido()."',
                pnombre = '".$this->getpnombre()."',
                ptelefono = ".$this->getptelefono()." WHERE pdocumento = ". $this->getpdocumento();
		return $this->realizarconsulta($consultaSQL);
	}
	
    /**
     * Eliminar un pasajero de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM pasajero WHERE pdocumento=".$this->getpdocumento();
		return $this->realizarconsulta($consultaSQL);
	}

    /**
     * Devuelve un array con la coleccion de Pasajeros que cumplen la condicion
     * de pertenecer al mismo viaje
     * @param string $idviaje
     * @return array
     */
	public function listar($idviaje){
	    $arregloPasajeros = null;
		$base = new BaseDatos();
		$consultaSQL = "SELECT * FROM pasajero WHERE idviaje = " . $idviaje." ORDER BY papellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloPasajeros = array();
				while($registro = $base->Registro()){
					
					$NroDoc = $registro['pdocumento'];
					$Nombre = $registro['pnombre'];
					$Apellido = $registro['papellido'];
					$Telefono = $registro['ptelefono'];
				
					$pasajero=new Pasajero();
					$pasajero->cargar($NroDoc,$Nombre,$Apellido,$Telefono, $idviaje);
					array_push($arregloPasajeros,$pasajero);	
				}			
		 	}else{
		 		$this->setmensajeoperacion($base->getError());
			}
		 }else{
		 	$this->setmensajeoperacion($base->getError());
		 }	
		 return $arregloPasajeros;
	}


}