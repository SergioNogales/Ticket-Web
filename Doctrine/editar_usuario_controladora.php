<?php
require_once 'controladoraDoctrine.php';
class editar_usuario_controladora {

    private $controladoraDoctrine;
    
    public function __construct() 
    {
        $this->controladoraDoctrine = new controladoraDoctrine();
    }

    public function procesarEdicion($datosFormulario) 
    {
        $email = trim($datosFormulario["email"]);
        $password = trim($datosFormulario["password"]);
        $error = '';
        $success = false;
        $usuario = null;

        if (empty($email)) 
        {
            $error = "El campo email está vacío.";
        } 
        elseif (empty($password)) 
        {
            $error = "El campo contraseña está vacío.";
        } 

        if (empty($error)) 
        {
            $usuario = $this->controladoraDoctrine->buscarUsuario($email);

            if (!empty($usuario)) 
            {

                $fechaCaducidad = new DateTime($datosFormulario["year_caducidad"].'-'.$datosFormulario["mes_caducidad"].'-01');
                $this->controladoraDoctrine->actualizarUsuario($_SESSION['usuario']->getEmail(), $email, $password, $datosFormulario["telephone"], $datosFormulario["direccion"], $datosFormulario["localidad"], $datosFormulario["name"], $datosFormulario["codigo_postal"], $datosFormulario["tarjeta"], $fechaCaducidad, $datosFormulario["ccv"]);
                return ['success' => true];
            } 
            else 
            {
                return ['error' => true];
            }
        }

        return [
            'error' => $error,
            'success' => $success,
        ];
    }
}
?>