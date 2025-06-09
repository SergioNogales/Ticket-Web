<?php
require_once 'controladoraDoctrine.php';
class eventos_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function getControladora()
    {
        return $this->controladoraDoctrine;
    }

    public function procesarSeleccion($datosFormulario)
    {
        $_SESSION['evento'] = $this->getControladora()->buscarEvento($datosFormulario['evento']);
        if(isset($_SESSION['evento']) && $_SESSION['evento'] !== "")
        {
            header("Location: detallesEvento.php");
        }
    }

    public function procesarCreacion($datosFormulario)
    {
        $nombre = trim($datosFormulario["nombre"] ?? '');
        $ubicacion = trim($datosFormulario["ubicacion"] ?? '');
        $descripcion = trim($datosFormulario["descripcion"] ?? '');
        $precio = trim($datosFormulario["precio"] ?? '');
        $numEntrada = trim($datosFormulario["numEntrada"] ?? '');
        $tipo = trim($datosFormulario["tipo"] ?? '');
        $fInicio = $datosFormulario["fInicio"] ?? '';
        $fFin = $datosFormulario["fFin"] ?? '';
        $errores = [];

        if(empty($nombre)) 
        {
            $resultado['errores'] = "El campo del nombre del evento está vacío.";
        }
        
        if(empty($ubicacion)) 
        {
            $errores['ubicacion']= "El campo de la ubicación del evento está vacío.";
        }
        
        if(empty($descripcion)) 
        {
            $errores['descripcion'] = "El campo de la descripción del evento está vacío.";
        } 
        
        if(empty($precio)) 
        {
            $errores['precio'] = "El campo del precio de cada entrada está vacío.";
        } 
        elseif(!is_numeric($precio) || $precio <= 0) 
        {
            $errores['precio'] = "El precio debe ser un número positivo.";
        }
        
        if(empty($numEntrada))
        {
            $errores['numEntrada'] = "El campo del número de entradas está vacío.";
        } 
        elseif(!is_numeric($numEntrada) || $numEntrada <= 0) 
        {
            $errores['numEntrada'] = "El número de plazas debe ser un número positivo.";
        }
        
        if(empty($tipo)) 
        {
            $errores['tipo'] = "El campo del tipo de evento está vacío.";
        } 
        elseif(!in_array($tipo, ['concierto', 'cine', 'prueba deportiva', 'exposicion'])) 
        {
            $errores['tipo']  = "El tipo de evento debe ser concierto, cine, prueba deportiva o exposicion.";
        } 
        if(empty($fInicio)) 
        {
            $errores['fInicio'] = "El campo de fecha de inicio del evento está vacío.";
        } 

        if(empty($fFin)) 
        {
            $errores['fFin'] = "El campo de fecha de fin del evento está vacío.";
        } 

        $fechaFin = new \DateTime($datosFormulario['fFin']);
        $fechaInicio = new \DateTime($datosFormulario['fInicio']);

        if($fechaFin < $fechaInicio)
        {
            $errores['fFin'] = "La Fecha de fin es anterior a la de inicio.";
        }

        if($errores === []) 
        {

            $success = true;
            $this->getControladora()->addEvento($datosFormulario['nombre'], $datosFormulario['descripcion'], $datosFormulario['tipo'], $datosFormulario['numEntrada'], $datosFormulario['precio'], $datosFormulario['ubicacion'], $fechaInicio, $fechaFin, $_SESSION['usuario']);
        }
        else
        {
            $success = false;
        }

        return $resultado = array('errores' => $errores, 'success' => $success);
    }

    public function procesarEdicion($datosFormulario)
    {
        $nombre = trim($datosFormulario["nombre"] ?? '');
        $ubicacion = trim($datosFormulario["ubicacion"] ?? '');
        $descripcion = trim($datosFormulario["descripcion"] ?? '');
        $precio = trim($datosFormulario["precio"] ?? '');
        $numEntrada = trim($datosFormulario["numEntrada"] ?? '');
        $tipo = trim($datosFormulario["tipo"] ?? '');
        $fInicio = $datosFormulario["fInicio"] ?? '';
        $fFin = $datosFormulario["fFin"] ?? '';
        $errores = [];

        if(empty($nombre)) 
        {
            $resultado['errores'] = "El campo del nombre del evento está vacío.";
        }
        
        if(empty($ubicacion)) 
        {
            $errores['ubicacion']= "El campo de la ubicación del evento está vacío.";
        }
        
        if(empty($descripcion)) 
        {
            $errores['descripcion'] = "El campo de la descripción del evento está vacío.";
        } 
        
        if(empty($precio)) 
        {
            $errores['precio'] = "El campo del precio de cada entrada está vacío.";
        } 
        elseif(!is_numeric($precio) || $precio <= 0) 
        {
            $errores['precio'] = "El precio debe ser un número positivo.";
        }
        
        if(empty($numEntrada))
        {
            $errores['numEntrada'] = "El campo del número de entradas está vacío.";
        } 
        elseif(!is_numeric($numEntrada) || $numEntrada <= 0) 
        {
            $errores['numEntrada'] = "El número de plazas debe ser un número positivo.";
        }
        
        if(empty($tipo)) 
        {
            $errores['tipo'] = "El campo del tipo de evento está vacío.";
        } 
        elseif(!in_array($tipo, ['concierto', 'cine', 'prueba deportiva', 'exposicion'])) 
        {
            $errores['tipo']  = "El tipo de evento debe ser concierto, cine, prueba deportiva o exposicion.";
        } 
        if(empty($fInicio)) 
        {
            $errores['fInicio'] = "El campo de fecha de inicio del evento está vacío.";
        } 

        if(empty($fFin)) 
        {
            $errores['fFin'] = "El campo de fecha de fin del evento está vacío.";
        } 

        $fechaFin = new \DateTime($datosFormulario['fFin']);
        $fechaInicio = new \DateTime($datosFormulario['fInicio']);

        if($fechaFin < $fechaInicio)
        {
            $errores['fFin'] = "La Fecha de fin es anterior a la de inicio.";
        }

        if($errores === []) 
        {

            $success = true;
            $id = $_SESSION['evento']->getId();
            $this->getControladora()->actualizarEvento($id, $datosFormulario['nombre'], $datosFormulario['descripcion'], $datosFormulario['tipo'], $datosFormulario['numEntrada'], $datosFormulario['precio'], $datosFormulario['ubicacion'], $fechaInicio, $fechaFin, $_SESSION['usuario']);
            header("Location: listado_eventos.php");
        }
        else
        {
            $success = false;
        }

        return $resultado = array('errores' => $errores, 'success' => $success);
    }

    public function buscarEventos($usuario)
    {
        return $this->controladoraDoctrine->buscarEventos($usuario);
    }

    public function getEventos()
    {
        return $this->controladoraDoctrine->getEventos();
    }

    public function procesarDetalles($datosFormulario)
    {
        if(isset($datosFormulario['accion']) && isset($datosFormulario['reserva']))
        {
            $evento = $this->getControladora()->buscarEvento($_SESSION['evento']);

            if($evento->getPrecio() < $_SESSION['usuario']->getSaldo())
            {
                $_SESSION['reserva'] = $datosFormulario['reserva'];
                header("Location: inscripcion_evento.php");
            }
            else
            {
                return "No tiene saldo suficiente";
            }
        }

        if(isset($datosFormulario['accion']) && isset($datosFormulario['id_evento'])) 
        {
            $idEvento = $datosFormulario['id_evento'];
            $evento = $this->getControladora()->buscarEvento($idEvento);
            $_SESSION['evento'] = $evento;

            switch($datosFormulario['accion']) 
            {
                case 'modificar':
                    header("Location: editar_evento.php");
                    break;
                    
                case 'borrar':
                    if($this->getControladora()->deleteEvento($idEvento)) 
                    {
                        $_SESSION['evento'] = "";
                        header("Location: listado_eventos.php");
                    }
                    break;
                    
                case 'añadir_actividad':
                    header("Location: crear_actividad.php");
                    break;
            }
        }

        if(isset($datosFormulario['accion']) && isset($datosFormulario['id_actividad'])) 
        {
            $idActividad = $datosFormulario['id_actividad'];
            $_SESSION['actividad'] = $this->getControladora()->buscarActividad($idActividad);
            
            switch($datosFormulario['accion']) 
            {
                case 'borrarActividad':
                    $idActividad = $datosFormulario['id_actividad'];
                    if($this->getControladora()->deleteActividad($idActividad)) 
                    {
                        $_SESSION['actividad'] = "";
                        header("Location: detallesEvento.php");
                    }
                    break;

                case 'modificarActividad':
                        header("Location: editar_actividad.php");
                    break;
            }
        }
    }

    public function perteneceEvento ($usuario, $evento_)
    {
        if($usuario->getRol() != "promotor")
        {
            return false;
        }

        $eventos = $this->buscarEventos($usuario);
        foreach ($eventos as $evento)
        {
            if($evento->getId() == $evento_->getId())
            {
                return true;
            }
        }

        return false;
    }

    public function perteneceActividad ($usuario, $actividad_)
    {
        if($usuario->getRol() != "promotor")
        {
            return false;
        }

        $eventos = $this->buscarEventos($usuario);
        foreach ($eventos as $evento)
        {
            $actividades = $this->getCOntroladora()->buscarActividades($evento);
            foreach ($actividades as $actividad)
            {
                if($actividad->getId() == $actividad_->getId())
                {
                    return true;
                }
            }
        }

        return false;
    }
    
}
?>