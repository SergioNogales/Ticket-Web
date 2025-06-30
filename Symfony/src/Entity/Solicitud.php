<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolicitudRepository::class)]
/**
     * @ORM\Entity
     * @ORM\Table(name="solicitud")
     */
class Solicitud
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer", name: "idSolicitud", options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 45, nullable: true, name: "rolSolicitado")]
    private ?string $rolSolicitado = null;

    #[ORM\Column(type: "string", length: 45, nullable: true)]
    private ?string $estado = null;

    #[ORM\OneToOne(targetEntity: "Usuario")]
    #[ORM\JoinColumn(name: "Usuario_email", referencedColumnName: "email", nullable: false)]
    private Usuario $usuario;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRolSolicitado(): ?string
    {
        return $this->rolSolicitado;
    }

    public function setRolSolicitado(?string $rolSolicitado): self
    {
        $this->rolSolicitado = $rolSolicitado;
        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;
        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }
}