<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reserva actividades")
 */
class ReservaActividades
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Actividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Actividad_idActividad", referencedColumnName="idActividad", nullable=false),
     *   @ORM\JoinColumn(name="Actividad_Evento_idEvento", referencedColumnName="Evento_idEvento", nullable=false)
     * })
     */
    private $actividad;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Reserva")
     * @ORM\JoinColumn(name="Reserva_idReserva", referencedColumnName="idReserva", nullable=false)
     */
    private $reserva;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $fechaCreacion;

    public function __construct()
    {
        $this->fechaCreacion = new DateTime();
    }

    public function getActividad(): ?Actividad
    {
        return $this->actividad;
    }

    public function setActividad(?Actividad $actividad): self
    {
        $this->actividad = $actividad;
        return $this;
    }

    public function getReserva(): ?Reserva
    {
        return $this->reserva;
    }

    public function setReserva(?Reserva $reserva): self
    {
        $this->reserva = $reserva;
        return $this;
    }

    public function getFechaCreacion(): ?DateTime
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(DateTime $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;
        return $this;
    }
}