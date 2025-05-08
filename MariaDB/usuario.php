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
        $usuarios = obtenerUsuarios();

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

    public function obtenerSolicitud()
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

    
    public function obtenerPendiente()
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

        $stmt = $conn->prepare("UPDATE usuario SET rol = :rol, solicitud = 'aprobada' WHERE email = :email");
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

function obtenerUsuarios()
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
?>