<?php
require_once 'controladoraDoctrine.php';
class register_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function getControladora()
    {
        return $this->controladoraDoctrine;
    }

    public function procesarRegistro($datosFormulario) 
    {
        $email = trim($datosFormulario["email"]);
        $password = trim($datosFormulario["password"]);
        $confirmPassword = trim($datosFormulario["newpassword"]);
        $error = '';
        $success = false;
        $usuario = null;

        if (empty($email)) 
        {
            $error = "El campo email está vacío.";
        } 
        elseif (empty($password) || empty($confirmPassword)) 
        {
            $error = "Los campos de contraseña están vacíos.";
        } 
        elseif ($password !== $confirmPassword) 
        {
            $error = "Las contraseñas no coinciden.";
        } 

        if (empty($error)) 
        {
            $usuario = $this->controladoraDoctrine->buscarUsuario($email);

            if (empty($usuario)) 
            {

                $fechaCaducidad = new \DateTime($datosFormulario["fecha"]);
                $password = $this->getControladora()->getHash($password);
                $ccv = $this->getControladora()->cifrar(trim($datosFormulario["ccv"]), "ccv");
                $tarjeta = $this->getControladora()->cifrar(trim($datosFormulario["tarjeta"]), "tarjeta");
                $this->controladoraDoctrine->addUsuario($email, $password, $datosFormulario["telephone"], $datosFormulario["direccion"], $datosFormulario["localidad"], $datosFormulario["name"], $datosFormulario["codigo_postal"], $tarjeta, $fechaCaducidad, $ccv);
                return ['success' => true];
            } 
            else 
            {
                $error = "Correo ya registrado.";
            }
        }

        return [
            'error' => $error,
            'success' => $success,
        ];
    }
}
?>