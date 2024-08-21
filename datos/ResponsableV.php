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
	public function cargarRe($doc, $Nom, $Ape, $tel, $id, $lic){	
		parent::cargar($doc, $Nom, $Ape, $tel);	
		$this->setrnumeroempleado($id);
        $this->setrnumerolicencia($lic);
    }


    //Metodo para mostrar los datos de los atributos como string
    public function __toString(){
        $cadena = "";
        $cadena = "Responsable del viaje: ". parent::__toString() .
        "\nNro Empleado: ".$this->getrnumeroempleado().
        "\nNumero de licencia: ".$this->getrnumerolicencia().
        "\n-----------------------*------------------------\n";
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
    public function Buscar($dni){
		$base=new BaseDatos();
		$consultaSQL="Select * from responsable where pdocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
				if($fila=$base->Registro()){		
					parent::Buscar($dni);			
					$this->setrnumeroempleado($fila['rnumeroempleado']);
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
		if (parent::insertar()){
			$consultaSQL="INSERT INTO responsable(pdocumento, rnumeroempleado, rnumerolicencia) 
					VALUES (".$this->getpdocumento().",
					".$this->getrnumeroempleado().",
					".$this->getrnumerolicencia().")";	
		}
		return $this->realizarconsulta($consultaSQL);
	}

    /**
     * Modificar un pasajero en la BD
     * @return boolean
     */
    public function modificar(){
		if (parent::modificar()){
			$consultaSQL = "UPDATE responsable SET 
                rnumeroempleado = ".$this->getrnumeroempleado().",
                rnumerolicencia = ".$this->getrnumerolicencia()."
                WHERE pdocumento = ". $this->getpdocumento();
		}
		return $this->realizarconsulta($consultaSQL);
	}
	
    /**
     * Eliminar un pasajero de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM responsable WHERE pdocumento=".$this->getpdocumento();
		$this->realizarconsulta($consultaSQL);
		return parent::eliminar();
	}

    /**
     * Devuelve un array con la coleccion de Responsables
	 * @param string
     * @return array
     */
	public function listar($condicion=""){
	    $arregloResponsable = null;
		$base = new BaseDatos();
		$consultaSQL = "SELECT * FROM responsable ";
		if ($condicion != ""){
			$consultaSQL = $consultaSQL . " WHERE " . $condicion;
		} 
		$consultaSQL .=  " ORDER BY pdocumento;";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloResponsable = array();
				while($registro = $base->Registro()){
				
					$responsable = new ResponsableV();
					$responsable->Buscar($registro['pdocumento']);
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