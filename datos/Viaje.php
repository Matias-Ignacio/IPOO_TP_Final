<?php
class Viaje{
    //Informacion de viajes
    //Los atributos son el idviaje del viaje, el vdestino, persona responsable del viaje
    // la cantidad maxima de pasajeros y un array de objetos clase Pasajero
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $objResponsable;  //objeto clase ResponsableV
    private $objEmpresa; //objeto clase Empresa
    private $vimporte;
    private $colPasajeros ;//arraay de objetos clase Pasajero
    private $mensajeoperacion;

    public function __construct(){   
        //Metodo constructor de la clase Viaje
        $this->idviaje = 0;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = 0;
        $this->objResponsable = new ResponsableV();
        $this->objEmpresa = new Empresa();
        $this->vimporte = 0;
        $this->colPasajeros = array();
        $this->mensajeoperacion = "";
    }

    //Metodo para obtener los datos del viaje
    public function getidviaje(){
        return $this->idviaje;
    }
    public function getvdestino(){
        return $this->vdestino;
    }
    public function getvcantmaxPasajeros(){
        return $this->vcantmaxpasajeros;
    }
    public function getobjResponsable(){
        return $this->objResponsable;
    }
    public function getobjEmpresa(){
        return $this->objEmpresa;
    }
    public function getColPasajeros(){
        return $this->colPasajeros;
    } 
    public function getvimporte(){
        return $this->vimporte;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    //Metodos para modificar los datos del viaje---------------
    public function setidviaje($cod){
        $this->idviaje = $cod;
    }
    public function setvdestino($dest){
        $this->vdestino = $dest;
    }
    public function setvcantmaxPasajeros($max){
        $this->vcantmaxpasajeros = $max;
    }
    public function setobjResponsable($objResp){
        $this->objResponsable = $objResp;
    }
    public function setobjEmpresa($obj){
        $this->objEmpresa = $obj;
    }
    public function setColPasajeros($colPasajeros){
        $this->colPasajeros = $colPasajeros;
    }
    public function setvimporte($var){
        $this->vimporte = $var;
    }
    public function setmensajeoperacion($var){
        $this->mensajeoperacion = $var;
    }
    //Metodos especiales de la clase
    /**
     * Metodo que agrega un pasajero al arreglo de pasajeros
     * @param Pasajero
     */
    public function setAgregarPasajero($pasajeroNuevo){

        array_push($this->colPasajeros, $pasajeroNuevo);

    }
    /**
     * Metodo que devuelve la cantidad actual de pasajeros
     * cargados en el viaje
     * @return int
     */
    public function getCantPasajeros(){
        return count($this->colPasajeros);
    }
    /**
     * Metodo que modifica los datos del viaje, no la lista de pasajeros
     * @param $cod
     * @param $dest
     * @param $objEmp
     * @param $objResp
     * @param $max
     * @param $imp
     */
    public function cargar($cod, $dest, $max, $objEmp, $objResp, $imp){
        $this->setidviaje($cod);
        $this->setvdestino($dest);
        $this->setvcantmaxPasajeros($max);
        $this->setobjEmpresa($objEmp);
        $this->setobjResponsable($objResp);
        $this->setvimporte($imp);
    }
    /**
     * Metodo para saber si hay cargado algun pasajero en el viaje
     * devuelve false si el viaje esta vacio
     * @return boolean
     */
    public function getHayPasajeros(){
        $hay = false;
        if (count($this->colPasajeros)){
            $hay = true;
        }
        return $hay;
    }
    /**
     * informa la cantidad de pasajeros en el viaje
     * @return int
     */
    public function cantPasajeros(){
        return count($this->colPasajeros);
    }
    public function espDisponible(){
        return $this->getvcantmaxPasajeros() - count($this->colPasajeros);
    }
    /**
     * Metodo recibe por parametros los datos de un pasajero,
     * recorrre el arreglo de pasajeros buscando si ya esta cargado
     * devuelve falso si no esta cargado el pasajero
     * @param $pasajero
     * @return boolean
     */
    public function existePasajero($pasajero){
        $existe = false;
        $i = 0;
        if ($this->getHayPasajeros()){
            do{
               $existe = ($pasajero->compararPasajero($this->getColPasajeros()[$i]));
               $i++;
            }while (($existe == false) && ($this->getCantPasajeros() > $i));
        }
        return $existe;
    }

    /**
     * Metodo para borrar un pasajero mediante el DNI
     * retorna true si se borro un pasajero
     * @param $dni
     * @return
     */
    public function borrarPasajero($dni){
        $exito = false;
        $i = 0;
        $arregloPasajeros = array();
        $arregloPasajeros = $this->getColPasajeros();
        do{
            if ($arregloPasajeros[$i]->getDni() == $dni){
                unset($arregloPasajeros[$i]);
                $this->setColPasajeros($arregloPasajeros);
                $exito = true;
            }
            $i++;
        }while(($exito == false) && ($this->getCantPasajeros() > $i));
        return $exito;
    }

    //Metodo para mostrar los datos de los atributos como string
	public function __toString(){
        $cadena = "";
        $cadena = "idviaje : ". $this->getidviaje(). "\t".
                "Destino: ". $this->getvdestino(). "\n".
                "Numero empleado Responsable: " . $this->getobjResponsable()->getrnumeroempleado()."\n".
                "Nombre: " . $this->getobjResponsable()->getrnombre()." ".$this->getobjResponsable()->getrapellido()."\n".
                "Cant Maxima Pasajeros: ". $this->getvcantmaxPasajeros(). "\t".
                "Cantidad de pasajeros actual: " . $this->cantPasajeros(). "\n".
                "Importe: ". $this->getvimporte() . "\tEmpresa: ". $this->getobjEmpresa()->getenombre(). 
                "\n------------------------------------------------\n";
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
	 * Recupera los datos de un viaje por el id
	 * @param int $id
	 * @return boolean true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($id){
		$base=new BaseDatos();
		$consultaSQL="SELECT * FROM viaje WHERE idviaje = ".$id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){
				if($registro=$base->Registro()){	
                    $objEmp = new Empresa();
                    $objResp = new ResponsableV();				
				    $this->setidviaje($id);
					$this->setvdestino($registro['vdestino']);
					$this->setvcantmaxpasajeros($registro['vcantmaxpasajeros']);
                    $objEmp->Buscar($registro['idempresa']);
					$this->setobjEmpresa($objEmp);
                    $objResp->Buscar($registro['rnumeroempleado']);
                    $this->setobjResponsable($objResp);
                    $this->setvimporte($registro['vimporte']);
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
     * Insertar el viaje en la BD
     * @return boolean
     */
    public function insertar(){

		$consultaSQL="INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
				VALUES ('".$this->getvdestino()."',
                ".$this->getvcantmaxPasajeros().",
                ".$this->getobjEmpresa()->getidempresa().",
                ".$this->getobjResponsable()->getrnumeroempleado().",
                ".$this->getvimporte().")";	
		return $this->realizarconsulta($consultaSQL);
	}

    /**
     * Modifica el viaje
     * @return boolean
     */
	public function modificar(){
		$consultaSQL="UPDATE viaje SET 
                vdestino = '".$this->getvdestino()."',
                vcantmaxpasajeros = ".$this->getvcantmaxPasajeros().",
                idempresa = ".$this->getobjEmpresa()->getidempresa().",
                rnumeroempleado = ".$this->getobjResponsable()->getrnumeroempleado().",
                vimporte = ".$this->getvimporte()." WHERE idviaje = ". $this->getidviaje();
		return $this->realizarconsulta($consultaSQL);
	}
	
    /**
     * Elimina un viaje de la BD
     * @return boolean
     */
	public function eliminar(){
        $consultaSQL = "DELETE FROM viaje WHERE idviaje = ".$this->getidviaje();
        return $this->realizarconsulta($consultaSQL);
	}

    
     /** 
     * 
     * @param Empresa $objEmp
     * @return array
     */
	public function listar($objEmp){
	    $arregloViajes = null;
		$base = new BaseDatos();
        $objResp = new ResponsableV();
		$consultaSQL = "SELECT * FROM viaje WHERE idempresa = " . $objEmp->getidempresa() ." ORDER BY vdestino ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaSQL)){				
				$arregloViajes = array();
				while($registro = $base->Registro()){
					$colPasajeros = array();
					$id = $registro['idviaje'];
					$des = $registro['vdestino'];
					$cmp = $registro['vcantmaxpasajeros'];
                    $objEmp->Buscar($registro['idempresa']);
					$objResp->Buscar($registro['rnumeroempleado']);                  
                    $imp = $registro['vimporte'];				
					$viaje=new Viaje();
					$viaje->cargar($id, $des, $cmp, $objEmp, $objResp, $imp);

                    $pasajero = new Pasajero();
                    $colPasajeros = $pasajero->listar($id);
                    $viaje->setColPasajeros($colPasajeros);

					array_push($arregloViajes,$viaje);	
				}			
		 	}else{
		 		$this->setmensajeoperacion($base->getError());
			}
		 }else{
		 	$this->setmensajeoperacion($base->getError());
		 }	
		 return $arregloViajes;
	}


}