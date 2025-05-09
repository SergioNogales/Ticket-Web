<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="actividad")
 */
class Actividad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idActividad", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $orden;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $plazas;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $lugar;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumn(name="Evento_idEvento", referencedColumnName="idEvento", nullable=false)
     */
    private $evento;

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

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;
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

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(string $lugar): self
    {
        $this->lugar = $lugar;
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

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(Evento $evento): self
    {
        $this->evento = $evento;
        return $this;
    }
}