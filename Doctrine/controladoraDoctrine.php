<?php
require_once "C:/xampp\htdocs\DWII\p3\bootstrap.php";

class controladoraDoctrine {

    private $entityManager;

    public function __construct() 
    {
        $this->entityManager = require "C:/xampp\htdocs\DWII\p3\bootstrap.php";
    }

    public function buscarUsuario($email) 
    {
        $usuarioRepository = $this->entityManager->getRepository(Usuario::class);
        $usuario = $usuarioRepository->findOneBy(['email' => $email]);
        return $usuario;
    }

    public function getUsuarios()
    {
        $usuarioRepository = $this->entityManager->getRepository(Usuario::class);
        return $usuarioRepository->findAll();
    }
    
    public function buscarPendiente(Usuario $usuario) 
    {
        $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
        $solicitud = $solicitudRepository->findOneBy(['usuario' => $usuario, 'estado' => 'pendiente']);
        return $solicitud;
    }

    public function buscarSolicitudes(Usuario $usuario) 
    {
        $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
        $solicitudes = $solicitudRepository->findBy(['usuario' => $usuario]);
        return $solicitudes;
    }

    public function getSolicitudes()
    {
        $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
        return $solicitudRepository->findAll();
    }

    public function buscarEventos(Usuario $usuario)
    {
        $eventoRepository = $this->entityManager->getRepository(Evento::class);
        $eventos = $eventoRepository->findBy(['usuario' => $usuario]);
        return $eventos;
    }

    public function buscarEvento($id)
    {
        $eventoRepository = $this->entityManager->getRepository(Evento::class);
        $evento = $eventoRepository->findOneBy(['id' => $id]);
        return $evento;
    }

    public function getEventos()
    {
        $usuarioRepository = $this->entityManager->getRepository(Evento::class);
        return $usuarioRepository->findAll();
    }

    public function buscarActividades(Evento $evento)
    {
        $actividadRepository = $this->entityManager->getRepository(Actividad::class);
        $actividades = $actividadRepository->findBy(['evento' => $evento]);
        return $actividades;
    }

    public function buscarActividad($id)
    {
        $actividadRepository = $this->entityManager->getRepository(Actividad::class);
        $actividad = $actividadRepository->findOneBy(['id' => $id]);
        return $actividad;
    }

    public function addSolicitud($rolSolicitado, $usuario)
    {
        $usuario = $this->buscarUsuario($usuario->getEmail());
        if(!empty($usuario))
        {
            $solicitud = new Solicitud();
            $solicitud->setRolSolicitado($rolSolicitado);
            $solicitud->setEstado("pendiente");
            $solicitud->setUsuario($usuario);
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
        }
    }
        
    public function addUsuario($email, $password, $telephone, $direccion, $localidad, $name, $codigo_postal, $numTarjeta, $fechaCaducidad, $ccv)
    {
        $usuario = new usuario($email, $password, "cliente", $telephone, $direccion, $localidad, $name, $codigo_postal, $numTarjeta, $fechaCaducidad, $ccv, 0);
        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
    }

    public function addEvento(string $nombre, ?string $descripcion, string $tipo, int $plazas, float $precio, string $lugar, DateTime $fechaInicio, DateTime $fechaFin, Usuario $usuario): void 
    {
        $email = $usuario->getEmail();
        $usuario = $this->buscarUsuario($email);

        $evento = new Evento();
        $evento->setNombre($nombre);
        $evento->setDescripcion($descripcion);
        $evento->setTipo($tipo);
        $evento->setPlazas($plazas);
        $evento->setPrecio($precio);
        $evento->setLugar($lugar);
        $evento->setFechaInicio($fechaInicio);
        $evento->setFechaFin($fechaFin);
        $evento->setUsuario($usuario);

        $this->entityManager->persist($evento);
        $this->entityManager->flush();
    }

    public function addActividad(Evento $evento, string $nombre, ?string $descripcion, int $orden, int $plazas, string $lugar, DateTime $fecha)
    {
        $idEvento = $evento->getId();
        $evento = $this->buscarEvento($idEvento);

        $actividad = new Actividad();
        $actividad->setNombre($nombre);
        $actividad->setDescripcion($descripcion);
        $actividad->setOrden($orden);
        $actividad->setPlazas($plazas);
        $actividad->setLugar($lugar);
        $actividad->setFecha($fecha);
        $actividad->setEvento($evento);

        $this->entityManager->persist($actividad);
        $this->entityManager->flush();
    }

    public function addReserva(Usuario $usuario, Evento $evento)
    {
        $email = $usuario->getEmail();
        $usuario = $this->buscarUsuario($email);
        $id = $evento->getId();
        $evento = $this->buscarEvento($id);

        $reserva = new Reserva();
        $reserva->setPagado(false);
        $reserva->setFecha(new \DateTime());
        $reserva->setUsuario($usuario);
        $reserva->setEvento($evento);
        
        $this->entityManager->persist($reserva);
        $this->entityManager->flush();
        $_SESSION["reserva"] = $reserva;
    }

    public function actualizarActividad($id, string $nombre, ?string $descripcion, int $orden, int $plazas, string $lugar, DateTime $fecha)
    {
        $actividad = $this->buscarActividad($id);
        $actividad->setNombre($nombre);
        $actividad->setDescripcion($descripcion);
        $actividad->setOrden($orden);
        $actividad->setPlazas($plazas);
        $actividad->setLugar($lugar);
        $actividad->setFecha($fecha);

        $this->entityManager->persist($actividad);
        $this->entityManager->flush();
    }

    public function actualizarUsuario($prevemail, $email, $password, $telephone, $direccion, $localidad, $name, $codigo_postal, $numTarjeta, $fechaCaducidad, $ccv)
    {
        $usuario = $this->buscarUsuario($prevemail);

        $usuario->setEmail($email);
        $usuario->setPassword($password);
        $usuario->setTelefono($telephone);
        $usuario->setDireccion($direccion);
        $usuario->setLocalidad($localidad);
        $usuario->setNombre($name);
        $usuario->setCodigoPostal($codigo_postal);
        $usuario->setNumTarjeta($numTarjeta);
        $usuario->setFechaCaducidad($fechaCaducidad);
        $usuario->setCcv($ccv);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
    }

    public function actualizarEvento(string $id, string $nombre, ?string $descripcion, string $tipo, int $plazas, float $precio, string $lugar, DateTime $fechaInicio, DateTime $fechaFin, Usuario $usuario): void 
    {
        $email = $usuario->getEmail();
        $usuario = $this->buscarUsuario($email);
        $evento = $this->buscarEvento($id);

        $evento->setNombre($nombre);
        $evento->setDescripcion($descripcion);
        $evento->setTipo($tipo);
        $evento->setPlazas($plazas);
        $evento->setPrecio($precio);
        $evento->setLugar($lugar);
        $evento->setFechaInicio($fechaInicio);
        $evento->setFechaFin($fechaFin);
        $evento->setUsuario($usuario);

        $this->entityManager->persist($evento);
        $this->entityManager->flush();
    }

    public function aprobarSolicitud(Solicitud $solicitud) 
    {
        $solicitud->setEstado("aprobada");
        $usuario = $solicitud->getUsuario();
        $usuario->setRol($solicitud->getRolSolicitado());
        $this->entityManager->persist($usuario);
        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();
    }

    public function denegarSolicitud(Solicitud $solicitud) 
    {
        $solicitud->setEstado("denegada");
        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();
    }

    public function deleteEvento($id)
    {
        $eventoRepository = $this->entityManager->getRepository(Evento::class);
        $evento = $eventoRepository->findOneBy(['id' => $id]);
        if(!empty($evento))
        {
            $this->entityManager->remove($evento);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function deleteActividad($id)
    {
        $actividadRepository = $this->entityManager->getRepository(Actividad::class);
        $actividad = $actividadRepository->findOneBy(['id' => $id]);
        if(!empty($actividad))
        {
            $this->entityManager->remove($actividad);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function deleteReserva($id)
    {
        $reservaRepository = $this->entityManager->getRepository(Reserva::class);
        $reserva = $reservaRepository->findOneBy(['id' => $id]);
        if(!empty($reserva))
        {
            $this->entityManager->remove($reserva);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

}
?>