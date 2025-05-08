<?php
/*class usuario
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

    public function __construct($email = "", $password = "", $rol = "cliente", $telefono = "", $direccion = "", $localidad = "", $nombre = "", $codigo_postal = "", $tarjeta = "", $mes_caducidad = "", $year_caducidad = "", $ccv = "", $saldo = 0) 
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
    }

    public function editarUsuario($nuevoemail, $password, $telefono, $direccion, $localidad, $nombre, $codigo_postal, $tarjeta, $mes_caducidad, $year_caducidad, $ccv)
    {
        $conn = connect();
        if (!$conn) return false;

        $fechaCaducidad = $year_caducidad . '-' . $mes_caducidad . '-01';
        $stmt = $conn->prepare("UPDATE usuario SET email = :nuevoemail, password = :password, telefono = :telefono, direccion = :direccion, localidad = :localidad, nombre = :nombre, codigoPostal = :codigoPostal, numTarjeta = :numTarjeta, fechaCaducidad = :fechaCaducidad, ccv = :ccv WHERE email = :emailOriginal");
        $stmt->bindParam(':nuevoemail', $nuevoemail);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':localidad', $localidad);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':codigoPostal', $codigo_postal);
        $stmt->bindParam(':numTarjeta', $tarjeta);
        $stmt->bindParam(':fechaCaducidad', $fechaCaducidad);
        $stmt->bindParam(':ccv', $ccv);
        $stmt->bindParam(':emailOriginal', $this->email);

        $resultado = $stmt->execute();

        if ($resultado) 
        {
            $this->email = $nuevoemail;
            $this->password = $password;
            $this->telefono = $telefono;
            $this->direccion = $direccion;
            $this->localidad = $localidad;
            $this->nombre = $nombre;
            $this->codigo_postal = $codigo_postal;
            $this->tarjeta = $tarjeta;
            $this->mes_caducidad = $mes_caducidad;
            $this->year_caducidad = $year_caducidad;
            $this->ccv = $ccv;
            
            $_SESSION['usuario'] = $this;
        }
    }

    public function solicitar($solicitud)
    {
        $conn = connect();
        if (!$conn) return false;
        $usuarios = getUsuarios();

        foreach ($usuarios as &$usuario) 
        {
            if ($usuario->email === $this->email) 
            {
                $stmt = $conn->query("SELECT COUNT(*) AS total FROM solicitud");
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $numSolicitudes = $resultado['total'];

                $stmt = $conn->prepare("INSERT INTO solicitud (idSolicitud, rolSolicitado, estado, Usuario_email) VALUES (:idSolicitud, :rolSolicitado, 'pendiente', :Usuario_email)");
                $stmt->bindParam(':idSolicitud', $numSolicitudes);
                $stmt->bindParam(':rolSolicitado', $solicitud);
                $stmt->bindParam(':Usuario_email', $this->email);
                $stmt->execute();
                return true;
            }
        }
        return false;
    }

    public function getSolicitud()
    {
        $conn = connect();
        if (!$conn) return false;

        $stmt = $conn->prepare("SELECT * FROM solicitud WHERE Usuario_email = :email");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if($resultado)
        {
            return $resultado;
        }
        return false;    
    }

    
    public function getPendiente()
    {
        $conn = connect();
        if (!$conn) return false;

        $stmt = $conn->prepare("SELECT * FROM solicitud WHERE Usuario_email = :email AND estado = 'pendiente'");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if($resultado)
        {
            return $resultado;
        }
        return false;    
    }

    public function aprobarSolicitud()
    {
        $conn = connect();
        if (!$conn) return false;
    
        $stmt = $conn->prepare("SELECT idSolicitud, rolSolicitado FROM solicitud WHERE Usuario_email = :email AND estado = 'pendiente' ORDER BY idSolicitud DESC LIMIT 1");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$solicitud) {
            return false; 
        }

        $stmt = $conn->prepare("UPDATE usuario SET rol = :rol WHERE email = :email");
        $stmt->bindParam(':rol', $solicitud['rolSolicitado']);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE solicitud SET estado = 'aprobada' WHERE idSolicitud = :id");
        $stmt->bindParam(':id', $solicitud['idSolicitud'], PDO::PARAM_INT);
        $stmt->execute();

        $this->rol = $solicitud['rolSolicitado'];
        return true;
    }

    public function denegarSolicitud()
    {
        $conn = connect();
        if (!$conn) return false;
    
        $stmt = $conn->prepare("SELECT idSolicitud, rolSolicitado FROM solicitud WHERE Usuario_email = :email AND estado = 'pendiente' ORDER BY idSolicitud DESC LIMIT 1");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$solicitud) {
            return false; 
        }

        $stmt = $conn->prepare("UPDATE solicitud SET estado = 'denegada' WHERE idSolicitud = :id");
        $stmt->bindParam(':id', $solicitud['idSolicitud'], PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }
}

function connect()
{   
    try
    {
        $DSN = "mysql:dbname=mydb;host=127.0.0.1;port=3306;charset=utf8";
        $conn = new PDO($DSN, "root", "");
        return $conn;
    }
    catch(PDOException $ex)
    {
        echo "Error al conectar a la base de datos";
        return null;
    }
}

function getUsuarios()
{
    $conn = connect();
    if (!$conn) return [];
    $usuarios = [];

    $conn = connect();

    $stmt = $conn->query("SELECT * FROM usuario");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $fecha = explode('-', $row['fechaCaducidad']);
        $mes_caducidad = isset($fecha[1]) ? $fecha[1] : "";
        $year_caducidad = isset($fecha[0]) ? $fecha[0] : "";
        $usuarios[] = new usuario( $row['email'], $row['password'], $row['rol'], $row['telefono'], $row['direccion'], $row['localidad'], $row['nombre'], $row['codigoPostal'], $row['numTarjeta'], $mes_caducidad, $year_caducidad, $row['ccv'], $row['saldo']);
    }

    return $usuarios;
}

function registrarUsuario(usuario $usuario)
{ 
    $conn = connect();
    if (!$conn) return false;

    $stmt = $conn->prepare("INSERT INTO usuario (email, password, rol, telefono, direccion, localidad, nombre, codigoPostal, numTarjeta, fechaCaducidad, ccv, saldo) VALUES (:email, :password, :rol, :telefono, :direccion, :localidad, :nombre, :codigoPostal, :numTarjeta, :fechaCaducidad, :ccv, :saldo)");

    $fechaCaducidad = $usuario->year_caducidad . '-' . $usuario->mes_caducidad . "-01";
    $stmt->bindParam(':email', $usuario->email);
    $stmt->bindParam(':password', $usuario->password);
    $stmt->bindParam(':rol', $usuario->rol);
    $stmt->bindParam(':telefono', $usuario->telefono);
    $stmt->bindParam(':direccion', $usuario->direccion);
    $stmt->bindParam(':localidad', $usuario->localidad);
    $stmt->bindParam(':nombre', $usuario->nombre);
    $stmt->bindParam(':codigoPostal', $usuario->codigo_postal);
    $stmt->bindParam(':numTarjeta', $usuario->tarjeta);
    $stmt->bindParam(':fechaCaducidad', $fechaCaducidad);
    $stmt->bindParam(':ccv', $usuario->ccv);
    $stmt->bindParam(':saldo', $usuario->saldo);

    $resultado = $stmt->execute();
    
    return $resultado;
}

class evento
{
    public $idEvento;
    public $nombre;
    public $descripcion;
    public $tipo;
    public $plazas;
    public $precio;
    public $lugar;
    public $fInicio;
    public $fFin;
    public $actividades;

    public function __construct($idEvento = null, $nombre = "", $descripcion = "", $tipo = "", $plazas = 0, $precio = 0.0, $lugar = "", $fInicio = null, $fFin = null) 
    {
        $this->idEvento = $idEvento;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->tipo = $tipo;
        $this->plazas = $plazas;
        $this->precio = $precio;
        $this->lugar = $lugar;
        $this->fInicio = $fInicio;
        $this->fFin = $fFin;
        $this->actividades = getActividades($idEvento);
    }

    public function editarEvento($idEvento = "", $nombre = "", $descripcion = "", $tipo = "", $plazas = 0, $precio = 0.0, $lugar = "", $fInicio = null, $fFin = null) 
    {
        $conn = connect();
        if (!$conn) return false;

        $stmt = $conn->prepare("UPDATE evento SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, plazas = :plazas, precio = :precio, lugar = :lugar, fInicio = :fInicio, fFin = :fFin WHERE idEvento = :idEvento");

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':plazas', $plazas, PDO::PARAM_INT);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':lugar', $lugar);
        $stmt->bindParam(':fInicio', $fInicio);
        $stmt->bindParam(':fFin', $fFin);
        $stmt->bindParam(':idEvento', $idEvento, PDO::PARAM_INT);

        $resultado = $stmt->execute();

        if ($resultado) 
        {
            $this->idEvento = $idEvento;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->tipo = $tipo;
            $this->plazas = $plazas;
            $this->precio = $precio;
            $this->lugar = $lugar;
            $this->fInicio = $fInicio;
            $this->fFin = $fFin;
            $this->actividades = getActividades($idEvento);
        }
    }

    public function addActividad($nombre = "", $descripcion = "", $orden = 0, $plazas = 0, $lugar = "", $fecha = null)
    {
        $conn = connect();
        if (!$conn) return false;
    
        $stmt = $conn->prepare("INSERT INTO actividad (nombre, descripcion, orden, plazas, lugar, fecha, Evento_idEvento) VALUES (:nombre, :descripcion, :orden, :plazas, :lugar, :fecha, :evento)");
    
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
        $stmt->bindParam(':plazas', $plazas, PDO::PARAM_INT);
        $stmt->bindParam(':lugar', $lugar);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':evento', $this->idEvento, PDO::PARAM_INT);
    
        $resultado = $stmt->execute();
    
        if ($resultado) 
        {
            $idActividad = $conn->lastInsertId();
            $this->actividades[] = new actividad($idActividad, $nombre, $descripcion, $orden, $plazas, $lugar, $fecha);
            return true;
        }
        
        return false;
    }

    public function editarActividad($idActividad="", $nombre = "", $descripcion = "", $orden = 0, $plazas = 0, $lugar = "", $fecha = null)
    {
        $conn = connect();
        if (!$conn) return false;
    
        $stmt = $conn->prepare("UPDATE actividad SET nombre = :nombre, descripcion = :descripcion, orden = :orden, plazas = :plazas, lugar = :lugar, fecha = :fecha, Evento_idEvento = :evento WHERE idActividad = :idActividad");
        $stmt->bindParam(':idActividad', $idActividad, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
        $stmt->bindParam(':plazas', $plazas, PDO::PARAM_INT);
        $stmt->bindParam(':lugar', $lugar);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':evento', $this->idEvento, PDO::PARAM_INT);
    
        $resultado = $stmt->execute();
    
        if ($resultado) 
        {
            $idActividad = $conn->lastInsertId();
            $this->actividades[] = new actividad($idActividad, $nombre, $descripcion, $orden, $plazas, $lugar, $fecha);
            return true;
        }
        
        return false;
    }
}

function addEvento($nombre = "", $descripcion = "", $tipo = "", $plazas = 0, $precio = 0.0, $lugar = "", $fInicio = null, $fFin = null, $promotor = null) 
{
    $conn = connect();
    if (!$conn) return false;

    $stmt = $conn->prepare("INSERT INTO evento (nombre, descripcion, tipo, plazas, precio, lugar, fInicio, fFin, Usuario_email) VALUES (:nombre, :descripcion, :tipo, :plazas, :precio, :lugar, :fInicio, :fFin, :email)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':plazas', $plazas, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':lugar', $lugar);
    $stmt->bindParam(':fInicio', $fInicio);
    $stmt->bindParam(':fFin', $fFin);
    $stmt->bindParam(':email', $promotor->email);
    $resultado = $stmt->execute();

    if (!$resultado) 
    {
        return "tetaaa";
    }

    $idEvento = $conn->lastInsertId();
    $evento = new evento($idEvento, $nombre, $descripcion, $tipo, $plazas, $precio, $lugar, $fInicio, $fFin);
    return $evento;
}

function getEventos($email)
{
    $conn = connect();
    if (!$conn) return "";

    $stmt = $conn->prepare("SELECT * FROM evento WHERE Usuario_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $eventos[] = new evento( $row['idEvento'], $row['nombre'], $row['descripcion'], $row['tipo'], $row['plazas'], $row['precio'], $row['lugar'], $row['fInicio'], $row['fFin']);
    }
    if(empty($eventos))
    {
        return "";
    }
    return $eventos;
}

function getEvento($idEvento)
{
    $conn = connect();
    if (!$conn) return "";

    $stmt = $conn->prepare("SELECT * FROM evento WHERE idEvento = :idEvento");
    $stmt->bindParam(':idEvento', $idEvento);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $evento = new evento( $row['idEvento'], $row['nombre'], $row['descripcion'], $row['tipo'], $row['plazas'], $row['precio'], $row['lugar'], $row['fInicio'], $row['fFin']);
    }
    return $evento;
}

function getAllEventos()
{
    $conn = connect();
    if (!$conn) return "";

    $stmt = $conn->prepare("SELECT * FROM evento");
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $eventos[] = new evento( $row['idEvento'], $row['nombre'], $row['descripcion'], $row['tipo'], $row['plazas'], $row['precio'], $row['lugar'], $row['fInicio'], $row['fFin']);
    }
    if(!$eventos)
    {
        return "";
    }
    return $eventos;
}

class actividad
{
    public $idActividad;
    public $nombre;
    public $descripcion;
    public $orden;
    public $plazas;
    public $lugar;
    public $fecha;

    public function __construct($idActividad = null, $nombre = "", $descripcion = "", $orden = 0, $plazas = 0, $lugar = "", $fecha = null) 
    {
        $this->idActividad = $idActividad;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->orden = $orden;
        $this->plazas = $plazas;
        $this->lugar = $lugar;
        $this->fecha = $fecha;
    }
}

function getActividad($idActividad)
{
    $conn = connect();
    if (!$conn) return "";

    $actividad = "";

    $stmt = $conn->prepare("SELECT * FROM actividad WHERE idActividad = :actividad");
    $stmt->bindParam(':actividad', $idActividad, PDO::PARAM_INT);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $actividad = new actividad( $row['idActividad'], $row['nombre'], $row['descripcion'], $row['orden'], $row['plazas'], $row['lugar'], $row['fecha']);
    }
    if(empty($actividad))
    {
        return false;
    }
    return $actividad;
}

function getActividades($idEvento)
{
    $conn = connect();
    if (!$conn) return "";

    $actividades = [];

    $stmt = $conn->prepare("SELECT * FROM actividad WHERE Evento_idEvento = :evento");
    $stmt->bindParam(':evento', $idEvento);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
        $actividades[] = new actividad( $row['idActividad'], $row['nombre'], $row['descripcion'], $row['orden'], $row['plazas'], $row['lugar'], $row['fecha']);
    }
    if(!$actividades)
    {
        return [];
    }
    return $actividades;
}

function deleteEvento($idEvento) {
    $conn = connect();
    if (!$conn) return false;

    $conn->beginTransaction();

    $stmt = $conn->prepare("DELETE FROM actividad WHERE Evento_idEvento = :idEvento");
    $stmt->bindParam(':idEvento', $idEvento, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM evento WHERE idEvento = :idEvento");
    $stmt->bindParam(':idEvento', $idEvento, PDO::PARAM_INT);
    $resultado = $stmt->execute();

    $conn->commit();
    return $resultado;
}

function deleteActividad($idActividad) {
    $conn = connect();
    if (!$conn) return false;

    $stmt = $conn->prepare("DELETE FROM actividad WHERE idActividad = :idActividad");
    $stmt->bindParam(':idActividad', $idActividad, PDO::PARAM_INT);
    $resultado = $stmt->execute();

    if(!empty($resultado))
    {
        return true;
    }
    return false;
}
*/

require_once "C:/xampp\htdocs\DWII\p3\bootstrap.php";

function buscarUsuario($email) {
    $entityManager = require "C:/xampp\htdocs\DWII\p3\bootstrap.php";
    $usuarioRepository = $entityManager->getRepository('Usuario');
    $usuario = $usuarioRepository->findOneBy(['email' => $email]);
    
    return $usuario;
}

?>