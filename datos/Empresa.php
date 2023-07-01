<?php
class Empresa{

    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;

    //Metodo constructor de la clase
    public function __construct(){
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
    }

    public function getidempresa(){
        return $this->idempresa;
    }
    public function getenombre(){
        return $this->enombre;
    }
    public function getedireccion(){
        return $this->edireccion;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion ;
    }
    public function setidempresa($id){
        $this->idempresa = $id;
    }
    public function setenombre($nom){
        $this->enombre = $nom;
    }
    public function setedireccion($dir){
        $this->edireccion = $dir;
    }
    public function setmensajeoperacion($mensajeoperacion){
        $this->mensajeoperacion=$mensajeoperacion;
    }

    public function __toString(){
        $cadena = "";
        $cadena = "Id de Empresa: " . $this->getidempresa()."\t".
                    "Nombre: " . $this->getenombre() . "\t".
                    "Direccion: " . $this->getedireccion() . 
                    "\n-------------------------------\n";
        return $cadena;
    }
	public function cargar($id,$nom,$dir){		
        $this->setidempresa($id);
		$this->setenombre($nom);
		$this->setedireccion($dir);
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
	 * Recupera los datos de una empresa por idempresa
	 * @param int $nro
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($nro){
		$base=new BaseDatos();
		$consultaSQL="SELECT * FROM empresa WHERE idempresa = ".$nro;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
				if($fila=$base->Registro()){					
				    $this->setidempresa($nro);
					$this->setenombre($fila['enombre']);
					$this->setedireccion($fila['edireccion']);				
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
     * Insertar la empresa en la BD
     * @return boolean
     */
    public function insertar(){
		$consultaSQL="INSERT INTO empresa(enombre, edireccion) 
				VALUES ('".$this->getenombre()."',
                '".$this->getedireccion()."')";	
		return $this->realizarconsulta($consultaSQL);
	}

        /**
     * Modificar un pasajero en la BD
     * @return boolean
     */
    public function modificar(){
		$consultaSQL = "UPDATE empresa SET 
                enombre = '".$this->getenombre()."',
                edireccion = '".$this->getedireccion()."' WHERE idempresa = ". $this->getidempresa();
		return $this->realizarconsulta($consultaSQL);
	}
	
    /**
     * Eliminar un pasajero de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM empresa WHERE idempresa = ".$this->getidempresa();
		return $this->realizarconsulta($consultaSQL);
	}

    
    /**
     * Devuelve un array con la coleccion de Empresas
     * @return array
     */
	public function listar(){
	    $arregloEmp = null;
		$base = new BaseDatos();
		$consultaSQL = "SELECT * FROM empresa ORDER BY enombre ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloEmp = array();
				while($registro = $base->Registro()){
					
					$id = $registro['idempresa'];
					$nom = $registro['enombre'];
					$dir = $registro['edireccion'];
				
					$empresa = new Empresa();
					$empresa->cargar($id, $nom, $dir);
					array_push($arregloEmp, $empresa);	
				}			
		 	}else{
		 		$this->setmensajeoperacion($base->getError());
			}
		 }else{
		 	$this->setmensajeoperacion($base->getError());
		 }	
		 return $arregloEmp;
	}
}
?>