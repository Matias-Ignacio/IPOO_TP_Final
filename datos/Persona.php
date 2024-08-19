<?php
class Persona{

    private $pdocumento;
    private $nombre;
    private $apellido;
    private $ptelefono;
    private $mensajeoperacion;

    //Metodo constructor de la clase
    public function __construct(){
        $this->pdocumento = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->ptelefono = 0;
    }
    //Metodos de acceso a los datos de la clase

    public function getpdocumento(){
        return $this->pdocumento;
    }
    public function getnombre(){
        return $this->nombre;
    }
    public function getapellido(){
        return $this->apellido;
    }
    public function getptelefono(){
        return $this->ptelefono;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    //Metodos de escritura de los atributos de la clase
    public function setpdocumento($var){
        $this->pdocumento = $var;
    }
    public function setnombre($nom){
        $this->nombre = $nom;
    }
    public function setapellido($ape){
        $this->apellido = $ape;
    }
    public function setptelefono($tel){
        $this->ptelefono = $tel;
    }
    public function setmensajeoperacion($var){
        $this->mensajeoperacion = $var;
    }
    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = $this->getnombre()." ".$this->getapellido().
        "\nTel: ".$this->getptelefono().
        "\nDNI: ".$this->getpdocumento() ;
        return $cadena;
    }    
    /**
     * Caragr los datos del pasajero
     * @param $doc
     * @param $nom
     * @param $ape
     * @param $tel
     */
    public function cargar($doc, $nom, $ape, $tel){
        $this->setpdocumento($doc);
        $this->setnombre($nom);        
        $this->setapellido($ape);      
        $this->setptelefono($tel);
    }

    /**
     * Realiza la consulta en la base de datos
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
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base = new BaseDatos();
		$consultaSQL= "SELECT * FROM persona WHERE pdocumento = ".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
				if($fila=$base->Registro()){					
				    $this->setpdocumento($dni);
					$this->setnombre($fila['pnombre']);    
					$this->setapellido($fila['papellido']);  
					$this->setptelefono($fila['ptelefono']);
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
     * Insertar persona en la BD
     * @return boolean
     */
    public function insertarPe(){
		$consultaSQL="INSERT INTO persona(pdocumento, pnombre, papellido, ptelefono) 
				VALUES (".$this->getpdocumento().",
                '".$this->getnombre()."',
                '".$this->getapellido()."',
                ".$this->getptelefono().")";	
		return $this->realizarconsulta($consultaSQL);
	}


    /**
     * Modificar una persona en la BD
     * @return boolean
     */
    public function modificar(){
		$consultaSQL="UPDATE persona SET 
                papellido = '".$this->getapellido()."',
                pnombre = '".$this->getnombre()."',
                ptelefono = ".$this->getptelefono()." WHERE pdocumento = ". $this->getpdocumento();
		return $this->realizarconsulta($consultaSQL);
	}
	
    /**
     * Eliminar una persona de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM persona WHERE pdocumento=".$this->getpdocumento();
		return $this->realizarconsulta($consultaSQL);
	}

    /**
     * Devuelve un array con la coleccion de Personas que cumplen la condicion
     * arreglo con dni a listar
     * @param string
     * @return array
     */
	public function listarPe($condicion=""){
	    $arregloPersonas = null;
		$base = new BaseDatos();
		$consultaSQL = "SELECT * FROM persona ";
        if ($condicion != ""){
            $consultaSQL = $consultaSQL . " WHERE " . $condicion;
        }
        $consultaSQL .= " ORDER BY apellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloPersonas = array();
				while($registro = $base->Registro()){
					
					$NroDoc = $registro['pdocumento'];
					$Nombre = $registro['pnombre'];
					$Apellido = $registro['papellido'];
					$Telefono = $registro['ptelefono'];
				
					$persona=new Persona();
					$persona->cargar($NroDoc,$Nombre,$Apellido,$Telefono);
					array_push($arregloPersonas,$persona);	
				}			
		 	}else{
                $this->setmensajeoperacion($base->getError());
			}
		 }else{
            $this->setmensajeoperacion($base->getError());
		 }	
		 return $arregloPersonas;
	}
}

?>