<?php
include_once 'Persona.php';
class Pasajero extends Persona{

    private $pdocumento;
    private $ptelefono;
    private $idviaje;
    private $mensajeoperacion;

    //Metodo constructor de la clase
    public function __construct(){
        parent::__construct();
        $this->pdocumento = "";
        $this->ptelefono = 0;
        $this->idviaje = 0;
    }
    //Metodos de acceso a los datos de la clase
    public function getpdocumento(){
        return $this->pdocumento;
    }
    public function getptelefono(){
        return $this->ptelefono;
    }
    public function getidviaje(){
        return $this->idviaje;
    }
    //Metodos de escritura de los atributos de la clase
    public function setpdocumento($pdocumento){
        $this->pdocumento = $pdocumento;
    }
    public function setptelefono($tel){
        $this->ptelefono = $tel;
    }
    public function setidviaje($var){
        $this->idviaje = $var;
    }
    
    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = "Pasajero: ". $this->getnombre()." ".$this->getapellido()."\nDNI: ".$this->getpdocumento().
        "\nTel: ".$this->getptelefono() . 
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
        $this->setnombre($nom);
        $this->setapellido($ape);
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
					$this->setnombre($fila['pnombre']);
					$this->setapellido($fila['papellido']);
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
                '".$this->getnombre()."',
                '".$this->getapellido()."',
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
                papellido = '".$this->getapellido()."',
                pnombre = '".$this->getnombre()."',
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