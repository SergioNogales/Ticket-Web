<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
/**
     * @ORM\Entity
     * @ORM\Table(name="evento")
     */
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', name: 'idEvento', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $nombre;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: 'string', length: 45, nullable: false)]
    private string $tipo;

    #[ORM\Column(type: 'integer', nullable: false, options: ['unsigned' => true])]
    private int $plazas;

    #[ORM\Column(type: 'float', nullable: false)]
    private float $precio;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $lugar;

    #[ORM\Column(type: 'date', name: 'finicio', nullable: false)]
    private \DateTimeInterface $fechaInicio;

    #[ORM\Column(type: 'date', name: 'ffin', nullable: false)]
    private \DateTimeInterface $fechaFin;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'Usuario_email', referencedColumnName: 'email', nullable: false)]
    private Usuario $usuario;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getPlazas(): ?int
    {
        return $this->plazas;
    }

    public function setPlazas(int $plazas): self
    {
        $this->plazas = $plazas;
        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;
        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(string $lugar): self
    {
        $this->lugar = $lugar;
        return $this;
    }

    public function getFechaInicio(): ?DateTime
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(?DateTime $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;
        return $this;
    }

    public function getFechaFin(): ?DateTime
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?DateTime $fechaFin): self
    {
        $this->fechaFin = $fechaFin;
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
}

