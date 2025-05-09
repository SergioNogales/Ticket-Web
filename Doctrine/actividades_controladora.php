<?php
require_once 'controladoraDoctrine.php';
class actividades_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function getControladora()
    {
        return $this->controladoraDoctrine;
    }

    public function buscarEventos($usuario)
    {
        return $this->controladoraDoctrine->buscarEventos($usuario);
    }

    public function getEventos()
    {
        return $this->controladoraDoctrine->getEventos();
    }

    public function procesarCreacion($datosFormulario)
    {
        $nombre = trim($datosFormulario["nombre"] ?? '');
        $lugar = trim($datosFormulario["lugar"] ?? '');
        $descripcion = trim($datosFormulario["descripcion"] ?? '');
        $orden = trim($datosFormulario["orden"] ?? '');
        $plazas = trim($datosFormulario["plazas"] ?? '');
        $fecha = new \DateTime($datosFormulario['fecha']);
        $errores = [];
        $success = false;

        if(empty($nombre)) 
        {
            $errores['nombre'] = "El campo del nombre de la actividad está vacío.";
        }
        
        if(empty($lugar)) 
        {
            $errores['lugar'] = "El campo del lugar de la actividad está vacío.";
        }
        
        if(empty($descripcion)) 
        {
            $errores['descripcion'] = "El campo de la descripción de la actividad está vacío.";
        } 
        
        if(empty($orden)) 
        {
            $errores['orden'] = "El campo del orden de la actividad está vacío.";
        } 
        elseif(!is_numeric($orden) || $orden <= 0) 
        {
            $errores['orden'] = "El orden debe ser un número positivo.";
        }
        
        if(empty($plazas))
        {
            $errores['plazas'] = "El campo del número de plazas está vacío.";
        } 
        elseif(!is_numeric($plazas) || $plazas <= 0) 
        {
            $errores['plazas'] = "El número de plazas debe ser un número positivo.";
        }
        
        if(empty($fecha)) 
        {
            $errores['fecha'] = "El campo de la fecha está vacío.";
        }
        
        if($errores === []) 
        {
            $success = true;
            $tempEvent = $_SESSION['evento'];
            $this->getControladora()->addActividad($tempEvent, $nombre, $descripcion, $orden, $plazas, $lugar, $fecha);
        }
        else
        {
            $success = false;
        }
        return array('errores' => $errores, 'success' => $success);
    }

    public function procesarEdicion($datosFormulario)
    {
        $id = $_SESSION["actividad"]->getId();
        $nombre = trim($datosFormulario["nombre"] ?? '');
        $lugar = trim($datosFormulario["lugar"] ?? '');
        $descripcion = trim($datosFormulario["descripcion"] ?? '');
        $orden = trim($datosFormulario["orden"] ?? '');
        $plazas = trim($datosFormulario["plazas"] ?? '');
        $fecha = new \DateTime($datosFormulario['fecha']);
        $errores = [];
        $success = false;

        if(empty($nombre)) 
        {
            $errores['nombre'] = "El campo del nombre de la actividad está vacío.";
        }
        
        if(empty($lugar)) 
        {
            $errores['lugar'] = "El campo del lugar de la actividad está vacío.";
        }
        
        if(empty($descripcion)) 
        {
            $errores['descripcion'] = "El campo de la descripción de la actividad está vacío.";
        } 
        
        if(empty($orden)) 
        {
            $errores['orden'] = "El campo del orden de la actividad está vacío.";
        } 
        elseif(!is_numeric($orden) || $orden <= 0) 
        {
            $errores['orden'] = "El orden debe ser un número positivo.";
        }
        
        if(empty($plazas))
        {
            $errores['plazas'] = "El campo del número de plazas está vacío.";
        } 
        elseif(!is_numeric($plazas) || $plazas <= 0) 
        {
            $errores['plazas'] = "El número de plazas debe ser un número positivo.";
        }
        
        if(empty($fecha)) 
        {
            $errores['fecha'] = "El campo de la fecha está vacío.";
        }
        
        if($errores === []) 
        {
            $success = true;
            $this->getControladora()->actualizarActividad($id, $nombre, $descripcion, $orden, $plazas, $lugar, $fecha);
        }
        else
        {
            $success = false;
        }
        return array('errores' => $errores, 'success' => $success);
    }
}
?>