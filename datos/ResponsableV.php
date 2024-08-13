<?php
include_once 'Persona.php';
class ResponsableV extends Persona{

    private $rnumeroempleado;
    private $rnumerolicencia;

    //Metodo constructor de la clase
    public function __construct(){
        parent::__construct();
        $this->rnumeroempleado = "";
        $this->rnumerolicencia = "";
    }
    //Metodos de acceso a los datos de la clase
    public function getrnumeroempleado(){
        return $this->rnumeroempleado;
    }
    public function getrnumerolicencia(){
        return $this->rnumerolicencia;
    }
    //Metodos de escritura de los atributos de la clase
    public function setrnumeroempleado($id){
        $this->rnumeroempleado = $id;
    }
    public function setrnumerolicencia($lic){
        $this->rnumerolicencia = $lic;
    }
	public function cargar($id,$lic,$Nom,$Ape){		
		$this->setrnumeroempleado($id);
        $this->setrnumerolicencia($lic);
		$this->setnombre($Nom);
		$this->setapellido($Ape);
    }


    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = "Responsable del viaje: ". $this->getnombre()." ".$this->getapellido().
        "\tNro Empleado: ".$this->getrnumeroempleado().
        "\t Numero de licencia: ".$this->getrnumerolicencia().
        "\n-----------------------------------------------\n";
        return $cadena;
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
	 * Recupera los datos de un responsable por numero empleado
	 * @param int $nro
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($nro){
		$base=new BaseDatos();
		$consultaSQL="Select * from responsable where rnumeroempleado=".$nro;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
				if($fila=$base->Registro()){					
				    $this->setrnumeroempleado($nro);
					$this->setnombre($fila['rnombre']);
					$this->setapellido($fila['rapellido']);
					$this->setrnumerolicencia($fila['rnumerolicencia']);
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
     * Insertar el responsable en la BD
     * @return boolean
     */
    public function insertar(){
		$consultaSQL="INSERT INTO responsable(rnumerolicencia, rnombre, rapellido) 
				VALUES (".$this->getrnumerolicencia().",
                '".$this->getnombre()."',
                '".$this->getapellido()."')";	
		return $this->realizarconsulta($consultaSQL);
	}
    /**
     * Modificar un pasajero en la BD
     * @return boolean
     */
    public function modificar(){
		$consultaSQL = "UPDATE responsable SET 
                rnumerolicencia = ".$this->getrnumerolicencia().",
                rnombre = '".$this->getnombre()."',
                rapellido = '".$this->getapellido()."' WHERE rnumeroempleado = ". $this->getrnumeroempleado();
		return $this->realizarconsulta($consultaSQL);
	}
	
    /**
     * Eliminar un pasajero de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM responsable WHERE rnumeroempleado=".$this->getrnumeroempleado();
		return $this->realizarconsulta($consultaSQL);
	}

    /**
     * Devuelve un array con la coleccion de Responsables
     * @return array
     */
	public static function listar(){
	    $arregloResponsable = null;
		$base = new BaseDatos();
		$consultaSQL = "SELECT * FROM responsable  ORDER BY rapellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloResponsable = array();
				while($registro = $base->Registro()){
					
					$nro = $registro['rnumeroempleado'];
					$lic = $registro['rnumerolicencia'];
					$nom = $registro['rnombre'];
					$ape = $registro['rapellido'];
				
					$responsable = new ResponsableV();
					$responsable->cargar($nro, $lic, $nom, $ape);
					array_push($arregloResponsable, $responsable);	
				}			
		 	}else{
		 		$this->setmensajeoperacion($base->getError());
			}
		 }else{
		 	$this->setmensajeoperacion($base->getError());
		 }	
		 return $arregloResponsable;
	}

}