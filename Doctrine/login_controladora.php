<?php
require_once 'controladoraDoctrine.php';
class login_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function getControladora()
    {
        return $this->controladoraDoctrine;
    }

    public function procesarLogin($datosFormulario) 
    {
        $email = trim($datosFormulario["email"]);
        $password = $this->getControladora()->getHash(trim($datosFormulario["password"]));
        $error = '';
        $success = false;
        $usuario = null;

        if (empty($email)) 
        {
            $error = "El campo email está vacio.";
        }
        if ($password === '') 
        {
            $error = "El campo contraseña está vacio.";
        }

        if (empty($error)) 
        {
            $usuario = $this->controladoraDoctrine->buscarUsuario($email);

            if (!empty($usuario)) 
            {
                if ($usuario->getEmail() === $email) 
                {
                    if ($password === $usuario->getPassword()) 
                    {
                        $_SESSION['usuario'] = $usuario;
                        $success = true;
                    } 
                    else 
                    {
                        $error = "Contraseña incorrecta.";
                    }
                }
            } 
            else 
            {
                $error = "Usuario no encontrado.";
            }
        }

        return [
            'error' => $error,
            'success' => $success,
            'usuario' => $usuario
        ];
    }
}
?>