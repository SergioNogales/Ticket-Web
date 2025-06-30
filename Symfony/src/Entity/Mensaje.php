<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: MensajeRepository::class)]
class Mensaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'emisor_email', referencedColumnName: 'email', nullable: false)]
    private ?Usuario $emisor = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'destinatario_email', referencedColumnName: 'email', nullable: false)]
    private ?Usuario $destinatario = null;

    #[ORM\Column(type: 'text')]
    private string $mensaje;

    #[ORM\Column(type: 'datetime')]
    private DateTime $fechaEnvio;

    public function __construct()
    {
        $this->fechaEnvio = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmisor(): ?Usuario
    {
        return $this->emisor;
    }

    public function setEmisor(Usuario $emisor): self
    {
        $this->emisor = $emisor;
        return $this;
    }

    public function getDestinatario(): ?Usuario
    {
        return $this->destinatario;
    }

    public function setDestinatario(Usuario $destinatario): self
    {
        $this->destinatario = $destinatario;
        return $this;
    }

    public function getMensaje(): string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;
        return $this;
    }

    public function getFechaEnvio(): DateTime
    {
        return $this->fechaEnvio;
    }

    public function setFechaEnvio(DateTime $fechaEnvio): self
    {
        $this->fechaEnvio = $fechaEnvio;
        return $this;
    }
}