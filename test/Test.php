<?php
include_once '../datos/BaseDatos.php';
include_once '../datos/Viaje.php';
include_once '../datos/Pasajero.php';
include_once '../datos/ResponsableV.php';
include_once '../datos/Empresa.php';


$idEmpresaActual = 1;


/**
 * Visualiza el menu de opciones en la pantalla
 */
function menuGeneral(){
    $objViajeActual = new Viaje();
   // $objViajeActual->buscar(1);
   // $objViajeActual = inicializarViaje($objViajeActual);
    $objEmpresaActual = new Empresa();
    $objEmpresaActual->buscar(1);
    do{
        do{
        echo "\n\t**************  MENU PRINCIPAL  ***************\n
        (1)\tGestionar Viajes
        (2)\tGestionar Pasajeros
        (3)\tGestionar Persona Responsable del Viaje
        (4)\tGestionar Empresa \n
        (5)\tSeleccionar Viaje
        (6)\tSeleccionar Empresa\n
        (0)\tSalir\n
        ***********************************************\n
        Ingrese una opción del menú: ";
        $op1 = trim(fgets(STDIN));
        }while (!($op1 >= 0 && $op1 < 7));
        if($op1 == 5){
            $objViajeActual = seleccionarViaje($objEmpresaActual);
        }elseif($op1 == 6){
            $idEmpresaActual = seleccionarEmpresa();
        }elseif (!($op1 == 0)){
            $op1 *= 10;
            do{
                if ($op1==10){
                    echo "\n\t*************** GESTIONAR VIAJE ***************\n";
                    mostrarEmpresaActual($objEmpresaActual);
                }elseif ($op1==20){
                    echo "\n\t************* GESTIONAR PASAJERO **************\n";
                    if($objViajeActual->getidviaje() == 0){
                        $objViajeActual = seleccionarViaje($objEmpresaActual);}
                    mostrarViajeActual($objViajeActual);
                }elseif ($op1==30){
                    echo "\n\t************ GESTIONAR RESPONSABLE ************\n";
                    mostrarEmpresaActual($objEmpresaActual);
                }elseif ($op1==40){
                    echo "\n\t************** GESTIONAR EMPRESA **************\n";
                }  
                do{
                    echo "\t\t(1)Agregar\n";
                    echo "\t\t(2)Buscar\n";
                    echo "\t\t(3)Listar\n";
                    echo "\t\t(4)Modificar\n";
                    echo "\t\t(5)Eliminar\n\n";
                    echo "\t\t(0)Volver al Menu Principal\n";
                    echo "\t***********************************************\n";
                    echo "\tIngrese una opción del menú: ";
                    $op2 = trim(fgets(STDIN));
                }while (!($op2 >= 0 && $op2 < 6));
                $op2 = $op1 + $op2;
                switch ($op2) {
                    case 11:
                        viajeAgregar();
                        break;
                    case 12:
                        viajeBuscar();
                        break;
                    case 13:
                        viajeListar($objEmpresaActual);
                        break;
                    case 14:
                        viajeModificar();
                        break;  
                    case 15:
                        viajeEliminar();
                        break;
                    case 21:
                        
                        pasajeroAgregar($objViajeActual);
                        break;
                    case 22:
                        pasajeroBuscar();
                        break;
                    case 23:
                        pasajeroListar($objViajeActual);
                        break;
                    case 24:
                        pasajeroModificar();
                        break;  
                    case 25:
                        pasajeroEliminar();
                        break; 
                    case 31:
                        responsableAgregar();
                        break;
                    case 32:
                        responsableBuscar();
                        break;
                    case 33:
                        responsableListar();
                        break;
                    case 34:
                        responsableModificar();
                        break;  
                    case 35:
                        responsableEliminar();
                        break; 
                    case 41:
                        empresaAgregar();
                        break;
                    case 42:
                        empresaBuscar();
                        break;
                    case 43:
                        empresaListar();
                        break;
                    case 44:
                        empresaModificar();
                        break;  
                    case 45:
                        empresaEliminar();
                        break;                                                          
                    default:
                        break;              
                }
                $op2 -= $op1;
                if($op2!=0){readline("\n\nPresione ENTER para continuar... ");}
            }while (!($op2 === 0));   
        } 
    }while(!($op1 == 0));
}
// ************************** GESTION VIAJE *********************************************
/**
 * Identificar el viaje con el idviaje para gestionar los pasajeros
 * @param Empresa $objEmpresa
 * @return Viaje
 */
function seleccionarViaje($objEmpresa){
    echo "-------------------------------------------\n";
    mostrarEmpresaActual($objEmpresa);
    viajeListar($objEmpresa);
    $idViaje = readline("Seleccione el id del viaje: ");
    $objViaje = new Viaje();
    $objViaje->buscar($idViaje);
    $objViaje = inicializarViaje($objViaje);
    return $objViaje;
}
/**
 * @param Viaje
 * @return Viaje
 */
function inicializarViaje($objViajeActual){
    $pasajero = new Pasajero();
    $colPasajeros = $pasajero->listar($objViajeActual->getidviaje());
    $objViajeActual->setColPasajeros($colPasajeros);
    return $objViajeActual;
}
function mostrarViajeActual($objViaje){
    echo "Viaje Actual... \n";
    echo $objViaje;
}
/**
 * Agregar un VIAJE a la base de datos
 */
function viajeAgregar(){
    echo "\n----- Agregar un Viaje -----\n";
    $nuevoViaje = new Viaje();
    $objEmp = new Empresa();
    $objEmp->Buscar($GLOBALS["idEmpresaActual"]);
    $objResp = new ResponsableV();
    $des = readline("Destino: ");
    $res = readline("Nro Empleado Responsable: ");
    $objResp->Buscar($res);
    $max = readline("Cantidad maxima de pasajeros: ");
    $imp = readline("Importe del viaje: ");
    $nuevoViaje->cargar(0, $des, $max, $objEmp, $objResp, $imp);
    if ($nuevoViaje->insertar()){
        echo "Viaje agregado con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoViaje->getmensajeoperacion() ."\n";
    }
}
/**
 * Menu Buscar VIAJES
 */
function viajeBuscar(){
    echo "\n----- Busccar un Viaje -----\n";
    $nuevoViaje = new Viaje();
    $id = readline("Id del viaje: ");
    if ($nuevoViaje->Buscar($id)){
        echo $nuevoViaje;
    }else{
        echo "Error en la busqueda...\n";
    }
}




/** 
 * Listar VIAJES
 * @param Empresa $objEmpresa
 */
function viajeListar($objEmpresa){
    echo "\n--------------- Lista de Viajes ----------------\n";
    $colViajes = array();
    $nuevoViaje = new Viaje();
    $colViajes = $nuevoViaje->listar($objEmpresa);
    foreach ($colViajes as $viaje) {
        echo $viaje;
    }
}




/**
 * Modificar VIAJES
 */
function viajeModificar(){
    $nuevoViaje = new Viaje();
    $nuevoResp = new ResponsableV();
    $nuevaEmp = new Empresa();
    echo "\n----- Modificar un Viaje -----\n";
    $id = readline("Id del viaje: ");
    if ($nuevoViaje->Buscar($id)){
        $var = readline("Destino: ".$nuevoViaje->getvdestino()."  (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoViaje->setvdestino($var);}
        $var = readline("Cantidad maxima de pasajeros: ".$nuevoViaje->getvcantmaxPasajeros()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoViaje->setvcantmaxPasajeros($var);}
        $var = readline("Nro empleado responsable: ".$nuevoViaje->getobjResponsable()->getrnumeroempleado()."  (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoResp->Buscar($var);
            $nuevoViaje->setobjResponsable($$nuevoResp);}   
        $var = readline("Importe: ".$nuevoViaje->getvimporte()."  (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoViaje->setvimporte($var);}            
    }else{
        echo "Error en la busqueda...\n";
    }
    if ($nuevoViaje->modificar()){
        echo "Viaje modificado con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoViaje->getmensajeoperacion() ."\n";
    }          

}
/**
 * Eliminar VIAJE
 */
function viajeEliminar(){
    echo "\nEliminar Viaje: -----------\n";
    $nro = readline("Id de Viaje: ");
    $nuevoViaje = new Viaje();
    $nuevoViaje->Buscar($nro);
    $pasajero = new Pasajero();
    $colPasajeros = $pasajero->listar($nro);
    if (count($colPasajeros)){
        echo "No se puede eliminar, tiene pasajeros cargados\n";
    }else{
        if ($nuevoViaje->eliminar()){
            echo "Viaje eliminado con exito...\n";
        }else{
            echo "Error en la operacion " . $nuevoViaje->getmensajeoperacion() ."\n";
        } 
    }   
}
// ************************** GESTION PASAJERO ********************************************
/**
 * Agregar PASAJEROS a la base de datos
 * @param Viaje $objViaje
 * @return Viaje
 */
function pasajeroAgregar($objViaje){
    echo "\n----- Agregar un Pasajero -----\n";
    $nuevoPasajero = new Pasajero();
    echo "Viaje activo: " . $objViaje->getidviaje() .
    "\nCantidad de pasajeros actual: ".$objViaje->cantPasajeros().
    " Cupos disponibles: ". $objViaje->espDisponible()." cupos.\n";
    if($objViaje->espDisponible()){
        $nom = readline("Nombre: ");
        $ape = readline("Apellido: ");
        $nro = readline("D.N.I.: ");
        $tel = readline("Telefono: ");
        $nuevoPasajero->cargar($nro,$nom,$ape,$tel,$objViaje->getidviaje());
        if ($nuevoPasajero->insertar()){
            $objViaje->setAgregarPasajero($nuevoPasajero);
            echo "Pasajero agregado con exito...\n";
        }else{
            echo "Error en la operacion " . $nuevoPasajero->getmensajeoperacion() ."\n";
        }
    }else{
        readline("No hay espacio en este viaje");
    }
    return $objViaje;
}
/**
 * Menu Buscar PASAJERO
 */
function pasajeroBuscar(){
    echo "\n----- Busccar un Pasajero -----\n";
    $nuevoPasajero = new Pasajero();
    $id = readline("Dni de pasajero: ");
    if ($nuevoPasajero->Buscar($id)){
        echo $nuevoPasajero;
    }else{
        echo "Error en la busqueda...\n";
    }
}
/**
 * Listar PASAJERO
 * @param Viaje
 */
function pasajeroListar($objViaje){
    echo "\n------------ Lista de Pasajeros -----------\n";
    $colPasajeros = array();
    $nuevoPasajero = new Pasajero();
    $colPasajeros = $nuevoPasajero->listar($objViaje->getidviaje());
    foreach ($colPasajeros as $pas) {
        echo $pas;
    }
}
/**
 * Modificar PASAJERO
 */
function pasajeroModificar(){
    $nuevoPasajero = new Pasajero();
    echo "\n----- Modificar un Pasajero -----\n";
    $var = readline("DNI: ");
    if ($nuevoPasajero->Buscar($var)){
        $var = readline("Nombre: ".$nuevoPasajero->getpnombre()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoPasajero->setpnombre($var);}
        $var = readline("Apellido: ".$nuevoPasajero->getpapellido()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoPasajero->setpapellido($var);}
        $var = readline("Telefono: ".$nuevoPasajero->getptelefono()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoPasajero->setptelefono($var);
        }
        if ($nuevoPasajero->modificar()){
            echo "Pasajero modificado con exito...\n";
        }else{
            echo "Error en la operacion " . $nuevoPasajero->getmensajeoperacion() ."\n";
        }   
    }else{
        echo "Error en la busqueda..." . $nuevoPasajero->getmensajeoperacion() ."\n";
    }
}
/**
 * Eliminar Pasajero
 */
function pasajeroEliminar(){
    echo "\nEliminar Pasajero: -----------\n";
    $nro = readline("Dni del Pasajero: ");
    $nuevoPasajero = new Pasajero();
    $nuevoPasajero->setpdocumento($nro);
    if ($nuevoPasajero->eliminar()){
        echo "Pasajero eliminado con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoPasajero->getmensajeoperacion() ."\n";
    }     
}

// ************************* GESTION RESPONSABLE ******************************************
/**
 * Agregar RESPONSABLE del viaje a la base de datos
 */
function responsableAgregar(){
    echo "\n----- Agregar un Responsable de Viaje -----\n";
    $nuevoResponsable = new ResponsableV();
    $nom = readline("Nombre: ");
    $ape = readline("Apellido: ");
    $nro = readline("Nro Licencia: ");
    $nuevoResponsable->cargar(0, $nro, $nom, $ape);
    if ($nuevoResponsable->insertar()){
        echo "Responsable agregado con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoResponsable->getmensajeoperacion() ."\n";
    }    
}
/**
 * Menu Buscar RESPONSABLE
 */
function responsableBuscar(){
    echo "\n----- Busccar un Responsable -----\n";
    $nuevoResponsable = new ResponsableV();
    $id = readline("Nro del Responsable: ");
    if ($nuevoResponsable->Buscar($id)){
        echo $nuevoResponsable;
    }else{
        echo "Error en la busqueda...\n";
    }
}
/**
 * Listar RESPONSABLE
 */
function responsableListar(){
    $colResponsables = array();
    echo "\n------------ Lista de responsables ------------\n";
    $nuevoResponsable = new ResponsableV();
    $colResponsables = $nuevoResponsable->listar();
    foreach ($colResponsables as $res) {
        echo $res;
    }
}
/**
 * Modificar RESPONSABLE
 */
function responsableModificar(){
    $nuevoRes = new ResponsableV();
    echo "\n----- Modificar un Responsable de viajes -----\n";
    $var = readline("Nro Empleado: ");
    if ($nuevoRes->Buscar($var)){
        $var = readline("Nombre: ".$nuevoRes->getrnombre()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoRes->setrnombre($var);}
        $var = readline("Apellido: ".$nuevoRes->getrapellido()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoRes->setrapellido($var);}
        $var = readline("Licencia: ".$nuevoRes->getrnumerolicencia()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoRes->setrnumerolicencia($var);}             
    }else{
        echo "Error en la busqueda...\n";
    }
    if ($nuevoRes->modificar()){
        echo "Responsable modificado con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoRes->getmensajeoperacion() ."\n";
    } 
}
/**
 * Eliminar RESPONSABLE
 */
function responsableEliminar(){
    echo "\nEliminar Responsable de viajes: -----------\n";
    $nro = readline("Número de empleado: ");
    $nuevoResponsable = new ResponsableV();
    $nuevoResponsable->setrnumeroempleado($nro);
    if ($nuevoResponsable->eliminar()){
        echo "Responsable eliminado con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoResponsable->getmensajeoperacion() ."\n";
    }  
}
// *************************** GESTION EMPRESA *********************************************
/**
 * Selecciona la empresa actual
 * @return int
 */
function seleccionarEmpresa(){
    echo "-------------------------------------------\n";
    empresaListar();
    $idEmpresa = readline("Seleccione la empresa Actual: ");
    return $idEmpresa;
}
/**
 * Mostrar la empresa actual
 * @param Empresa $objEmpresa
 */
function mostrarEmpresaActual($objEmpresa){
    echo "\nEmpresa Actual... \n". $objEmpresa. "\n";
}
/**
 * Agregar una EMPRESA a la base de datos
 */
function empresaAgregar(){
    echo "\n----- Agregar una Empresa -----\n";
    $objEmpresa = new Empresa();
    $nom = readline("Nombre de la Empresa: ");
    $dir = readline("Direccion: ");
    $objEmpresa->cargar(0,$nom,$dir);
    if ($objEmpresa->insertar()){
        echo "Empresa agregada con exito...\n";
    }else{
        echo "Error en la operacion " . $objEmpresa->getmensajeoperacion() ."\n";
    }
}
/**
 * Menu Buscar EMPRESA
 */
function empresaBuscar(){
    echo "\n----- Busccar una Empresa -----\n";
    $nuevaEmpresa = new Empresa();
    $id = readline("Id de la Empresa: ");
    if ($nuevaEmpresa->Buscar($id)){
        echo $nuevaEmpresa;
    }else{
        echo "Error en la busqueda...\n";
    }    
}
/**
 * Listar EMPRESA
 */
function empresaListar(){
    $colEmpresas = array();
    echo "\n------------ Lista de Empresas ------------\n";
    $nuevaEmpresa = new Empresa();
    $colEmpresas = $nuevaEmpresa->listar();
    foreach ($colEmpresas as $emp) {
        echo $emp;
    }
}
/**
 * Modificar EMPRESA
 */
function empresaModificar(){
    $nuevoEmp = new Empresa();
    echo "\n----- Modificar una Empresa -----\n";
    $var = readline("Id de Empresa: ");
    if ($nuevoEmp->Buscar($var)){
        $var = readline("Nombre: ".$nuevoEmp->getenombre()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoEmp->setenombre($var);}
        $var = readline("Direccion: ".$nuevoEmp->getedireccion()." (ENTER no modifica) ");
        if(!($var == "")){
            $nuevoEmp->setedireccion($var);}           
    }else{
        echo "Error en la operacio...\n";
    }
    if ($nuevoEmp->modificar()){
        echo "Empresa modificada con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevoEmp->getmensajeoperacion() ."\n";
    } 
}
/**
 * Eliminar
 */
function empresaEliminar(){
    echo "\nEliminar Empresa: -----------";
    $id = readline("Numero de Id: ");
    $nuevaEmpresa = new Empresa();
    $nuevaEmpresa->setidempresa($id);
    if ($nuevaEmpresa->eliminar()){
        echo "Empresa eliminada con exito...\n";
    }else{
        echo "Error en la operacion " . $nuevaEmpresa->getmensajeoperacion() ."\n";
    }  
}

echo menuGeneral();



?>