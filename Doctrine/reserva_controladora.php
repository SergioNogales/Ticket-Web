<?php
require_once 'controladoraDoctrine.php';
class reserva_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function getControladora()
    {
        return $this->controladoraDoctrine;
    }

    public function buscarReservaEvento($datosFormulario)
    {
        $_SESSION['evento'] = $this->getControladora()->buscarEvento($datosFormulario['evento']);
        if(isset($_SESSION['evento']) && $_SESSION['evento'] !== "")
        {
            header("Location: detallesEvento.php");
        }
    }

    public function buscarReservaActividad($id)
    {
        $reserva = $this->getControladora()->buscarReservaActividad($id, $_SESSION['usuario']->getEmail());
        if(!empty($reserva))
        {
            return $reserva;
        }
        else
        {
            return false;
        }
    }

    public function procesarReservaEvento($datosFormulario)
    {
        $idEvento = $_SESSION['reserva'];
        $evento = $this->getControladora()->buscarEvento($idEvento);
        $usuario = $_SESSION['usuario'];
        $_SESSION['evento'] = $evento;

        $nuevoSaldo = $usuario->getSaldo() - $evento->getPrecio();
        $this->getControladora()->actualizarSaldo($usuario->getEmail(), $nuevoSaldo);

        $this->getControladora()->addReserva($usuario, $evento, $datosFormulario['dni'], $datosFormulario['name'], $datosFormulario['edad']);
        header("Location: reserva.php");
    }

    public function procesarReserva($datosFormulario)
    {
        if($datosFormulario['accion'] == "reserva")
        {
            if(!empty($_SESSION["reserva"]))
            {
                $id = $_SESSION["reserva"]->getId();
                $usuario = $_SESSION['usuario'];
                $evento = $_SESSION["reserva"]->getEvento();

                $nuevoSaldo = $usuario->getSaldo() + $evento->getPrecio();
                $this->getControladora()->actualizarSaldo($usuario->getEmail(), $nuevoSaldo);

                $this->getControladora()->deleteReserva($id);
                header("Location: detallesEvento.php");
            }
        }
        if($datosFormulario['accion'] == "reservaActividad")
        {
            if(!empty($_SESSION["reserva"]))
            {
                $actividad = $this->getControladora()->buscarActividad($datosFormulario['actividad']);
                $this->getControladora()->addReservaActividad($actividad, $_SESSION["reserva"]);
                echo $actividad->getEvento()->getId();
            }
        }
        if($datosFormulario['accion'] == "borrarActividad")
        {
            if(!empty($_SESSION["reserva"]))
            {
                $this->procesarBorradoActividad($datosFormulario['reservaId']);
            }
        }
    }

    public function procesarBorradoActividad($id)
    {
        if(!empty($_SESSION["actividad"]))
        {
            $this->getControladora()->deleteReservaActividad($id);
            header("Location: reserva.php");
        }
    }

    public function procesarSeleccion($datosFormulario)
    {
        $_SESSION['reserva'] = $this->getControladora()->buscarReserva($datosFormulario['reserva']);
        $_SESSION['evento'] = $_SESSION['reserva']->getEvento();
        if(isset($_SESSION['evento']) && $_SESSION['evento'] !== "")
        {
            header("Location: reserva.php");
        }
    }
}
?>