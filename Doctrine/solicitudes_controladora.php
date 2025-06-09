<?php
require_once 'controladoraDoctrine.php';
class solicitudes_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function getControladora()
    {
        return $this->controladoraDoctrine;
    }

    public function solicitudCliente($datosFormulario)
    {
        $success = true;
        $solicitud = $datosFormulario["rol"];
        $usuario = $_SESSION["usuario"];
        $this->controladoraDoctrine->addSolicitud($solicitud, $usuario);
        return [
            'success' => $success
        ];
    }

    public function solicitudAdmin($datosFormulario)
    {
        $usuarios = $this->getControladora()->getUsuarios();
        foreach ($usuarios as $usuario) 
        {
            if ($usuario->getEmail() === $datosFormulario["email"])
            {
                if($datosFormulario["accion"] === "aceptar")
                {
                    $this->aprobarSolicitud($usuario);
                }
                if($datosFormulario["accion"] === "denegar")
                {
                    $this->denegarSolicitud($usuario);
                }
            }
        }
    }

    public function buscarPendiente($usuario) 
    {
        return $this->controladoraDoctrine->buscarPendiente($usuario);
    }

    public function buscarSolicitudes($usuario) 
    {
        return $this->controladoraDoctrine->buscarSolicitudes($usuario);
    }

    public function getSolicitudes()
    {
        return $this->controladoraDoctrine->getSolicitudes();
    }

    public function aprobarSolicitud($usuario)
    {
        return $this->controladoraDoctrine->aprobarSolicitud($this->controladoraDoctrine->buscarPendiente($usuario));
    }

    public function denegarSolicitud($usuario)
    {
        return $this->controladoraDoctrine->denegarSolicitud($this->controladoraDoctrine->buscarPendiente($usuario));
    }
}
?>