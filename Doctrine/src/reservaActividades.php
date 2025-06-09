<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`reserva actividades`")
 */
class ReservaActividades
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idReservaActividad", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Actividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Actividad_idActividad", referencedColumnName="idActividad"),
     *   @ORM\JoinColumn(name="Actividad_Evento_idEvento", referencedColumnName="Evento_idEvento")
     * })
     */
    private $actividad;

    /**
     * @ORM\ManyToOne(targetEntity="Reserva")
     * @ORM\JoinColumn(name="Reserva_idReserva", referencedColumnName="idReserva")
     */
    private $reserva;
    
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $cancelado;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $asistencia;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $fecha;

    public function __construct()
    {
        $this->fecha = new DateTime();
    }

     public function getId(): ?int
    {
        return $this->id;
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

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(DateTime $fecha): self
    {
        $this->fecha = $fecha;
        return $this;
    }

    public function setReserva(?Reserva $reserva): self
    {
        $this->reserva = $reserva;
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

    public function getAsistencia(): bool
    {
        return $this->asistencia;
    }

    public function setAsistido(bool $asistido): self
    {
        $this->asistencia = $asistido;
        return $this;
    }

    public function getFechaCreacion(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFechaCreacion(DateTime $fechaCreacion): self
    {
        $this->fecha = $fechaCreacion;
        return $this;
    }
}