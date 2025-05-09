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

    public function procesarBorrado($datosFormulario)
    {
        if(!empty($_SESSION["reserva"]))
        {
            $id = $_SESSION["reserva"]->getId();
            $this->getControladora()->deleteReserva($id);
            header("Location: detallesEvento.php");
        }
    }
}
?>