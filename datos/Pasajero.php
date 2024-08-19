<?php
include_once 'Persona.php';
class Pasajero extends Persona{
     private $idviaje;
    private $mensajeoperacion;

    //Metodo constructor de la clase
    public function __construct(){
        parent::__construct();
        $this->idviaje = 0;
    }
    //Metodos de acceso a los datos de la clase
    public function getidviaje(){
        return $this->idviaje;
    }
    //Metodos de escritura de los atributos de la clase
    public function setidviaje($var){
        $this->idviaje = $var;
    }
    
    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = "Pasajero: ". parent::__toString() .
        "\n------------------------------------------\n";
        return $cadena;
    }
    /**
     * Caragr los datos del pasajero
     * @param $doc
     * @param $idviaje
     */
    public function cargarPa($doc, $nom, $ape, $tel, $idviaje){
        parent::cargar($doc, $nom, $ape, $tel);
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
				    parent::Buscar($dni);   
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
        if (parent::insertarPe()){
		$consultaSQL="INSERT INTO pasajero(pdocumento, idviaje) 
				VALUES (".$this->getpdocumento().",
                ".$this->getidviaje().")";	
        }           
		return $this->realizarconsulta($consultaSQL);
	}


    /**
     * Modificar un pasajero en la BD
     * @return boolean
     */
    public function modificar(){
        /*if (parent::modificar()){
		$consultaSQL="UPDATE pasajero SET WHERE pdocumento = ". $this->getpdocumento();
        }
        return $this->realizarconsulta($consultaSQL);*/
        return parent::modificar();
	}
	
    /**
     * Eliminar un pasajero de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM pasajero WHERE pdocumento=".$this->getpdocumento();
        $this->realizarconsulta($consultaSQL);
        return parent::eliminar();
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
		$consultaSQL = "SELECT * FROM pasajero WHERE idviaje = " . $idviaje; //." ORDER BY papellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloPasajeros = array();
				while($registro = $base->Registro()){
					$pasajero=new Pasajero();
                    $pasajero->Buscar($registro['pdocumento']);
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