<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
/**
    * @ORM\Entity
    * @ORM\Table(name="reserva")
    */
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer", name: "idReserva", options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(type: "boolean", nullable: false)]
    private bool $pagado;

    #[ORM\Column(type: "datetime", nullable: false)]
    private DateTime $fecha;

    #[ORM\ManyToOne(targetEntity: "Usuario")]
    #[ORM\JoinColumn(name: "Usuario_email", referencedColumnName: "email", nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: "Evento")]
    #[ORM\JoinColumn(name: "Evento_idEvento", referencedColumnName: "idEvento", nullable: false)]
    private ?Evento $evento = null;

    #[ORM\Column(type: "boolean", nullable: false)]
    private bool $cancelado;

    #[ORM\Column(type: "boolean", nullable: false)]
    private bool $asistencia;

    #[ORM\Column(type: "string", length: 9, nullable: false)]
    private string $dni;

    #[ORM\Column(type: "string", length: 45, nullable: false)]
    private string $nombre;

    #[ORM\Column(type: "integer", nullable: false)]
    private int $edad;

    public function __construct()
    {
        $this->fecha = new DateTime();
        $this->pagado = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPagado(): bool
    {
        return $this->pagado;
    }

    public function setPagado(bool $pagado): self
    {
        $this->pagado = $pagado;
        return $this;
    }

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(DateTime $fecha): self
    {
        $this->fecha = $fecha;
        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(Evento $evento): self
    {
        $this->evento = $evento;
        return $this;
    }

    public function getDni(): ?String
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;
        return $this;
    }

    public function getNombre(): ?String
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getCancelado(): ?bool
    {
        return $this->cancelado;
    }

    public function setCancelado(bool $cancelado): self
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    public function setAsistido(bool $asistido): self
    {
        $this->asistencia = $asistido;
        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): self
    {
        $this->edad = $edad;
        return $this;
    }
}