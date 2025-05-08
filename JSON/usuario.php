<?php
class usuario
{
    public $email;
    public $password;
    public $rol;
    public $telefono;
    public $direccion;
    public $localidad;
    public $nombre;
    public $codigo_postal;
    public $tarjeta;
    public $mes_caducidad;
    public $year_caducidad;
    public $ccv;
    public $saldo;
    public $solicitud;

    public function __construct($email = "", $password = "", $rol = "cliente", $telefono = "", $direccion = "", $localidad = "", $nombre = "", $codigo_postal = "", $tarjeta = "", $mes_caducidad = "", $year_caducidad = "", $ccv = "", $saldo = 0, $solicitud = "no") 
    {
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->localidad = $localidad;
        $this->nombre = $nombre;
        $this->codigo_postal = $codigo_postal;
        $this->tarjeta = $tarjeta;
        $this->mes_caducidad = $mes_caducidad;
        $this->year_caducidad = $year_caducidad;
        $this->ccv = $ccv;
        $this->saldo = $saldo;
        $this->solicitud = $solicitud;
    }

    public function editarUsuario($nuevoemail, $password, $telefono, $direccion, $localidad, $nombre, $codigo_postal, $tarjeta, $mes_caducidad, $year_caducidad, $ccv)
    {
        $file = 'usuarios.json';
        $usuarios = json_decode(file_get_contents($file), true);
        $tempUser = $_SESSION['usuario'];

        foreach ($usuarios as &$usuario) 
        {
            if ($usuario['email'] === $tempUser->email) 
            {
                $usuario['email'] = $nuevoemail;
                $usuario['password'] = $password;
                $usuario['telefono'] = $telefono;
                $usuario['direccion'] = $direccion;
                $usuario['localidad'] = $localidad;
                $usuario['nombre'] = $nombre;
                $usuario['codigo_postal'] = $codigo_postal;
                $usuario['tarjeta'] = $tarjeta;
                $usuario['mes_caducidad'] = $mes_caducidad;
                $usuario['year_caducidad'] = $year_caducidad;
                $usuario['ccv'] = $ccv;

                file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
                $_SESSION['usuario'] = new usuario( $usuario['email'], $usuario['password'], $usuario['rol'], $usuario['telefono'], $usuario['direccion'], $usuario['localidad'], $usuario['nombre'], $usuario['codigo_postal'], $usuario['tarjeta'], $usuario['mes_caducidad'], $usuario['year_caducidad'], $usuario['ccv'], $usuario['saldo'], $usuario['solicitud']);
                return true;
            }
        }
        return false;
    }

    public function solicitar($solicitud)
    {
        $file = 'usuarios.json';
        $usuarios = json_decode(file_get_contents($file), true);
        $tempUser = $_SESSION['usuario'];

        foreach ($usuarios as &$usuario) 
        {
            if ($usuario['email'] === $tempUser->email) 
            {
                $usuario['solicitud'] = $solicitud . "_pendiente";

                file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
                $_SESSION['usuario'] = new usuario( $usuario['email'], $usuario['password'], $usuario['rol'], $usuario['telefono'], $usuario['direccion'], $usuario['localidad'], $usuario['nombre'], $usuario['codigo_postal'], $usuario['tarjeta'], $usuario['mes_caducidad'], $usuario['year_caducidad'], $usuario['ccv'], $usuario['saldo'], $usuario['solicitud']);
                return true;
            }
        }
        return false;
    }

    public function aprobarSolicitud()
    {
        $file = 'usuarios.json';
        $usuarios = json_decode(file_get_contents($file), true);
        $tempUser = $this;

        foreach ($usuarios as &$usuario) 
        {
            if ($usuario['email'] === $tempUser->email) 
            {
                $usuario['solicitud'] = str_replace("_pendiente", "_aprobada", $usuario['solicitud']);
                $usuario['rol'] = str_replace("_aprobada", "", $usuario['solicitud']);;

                file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
            }
        }
        return false;
    }

    public function denegarSolicitud()
    {
        $file = 'usuarios.json';
        $usuarios = json_decode(file_get_contents($file), true);
        $tempUser = $this;

        foreach ($usuarios as &$usuario) 
        {
            if ($usuario['email'] === $tempUser->email) 
            {
                $usuario['solicitud'] = str_replace("_pendiente", "_denegada", $usuario['solicitud']);
                file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }
}
?>