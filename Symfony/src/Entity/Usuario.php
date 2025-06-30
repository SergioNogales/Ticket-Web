<?php
namespace App\Entity;

use App\Repository\UsuarioRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
/**
     * @ORM\Entity
     * @ORM\Table(name="usuario")
     */

    class Usuario implements PasswordAuthenticatedUserInterface
    {
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', options: ['default' => 'cliente'])]
    private string $rol = 'cliente';

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $localidad = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(name: 'codigoPostal', type: 'string', length: 10, nullable: true)]
    private ?string $codigoPostal = null;

    #[ORM\Column(name: 'numTarjeta', type: 'string', length: 128, nullable: true)]
    private ?string $numTarjeta = null;

    #[ORM\Column(name: 'fechaCaducidad', type: 'date', nullable: true)]
    private ?DateTime $fechaCaducidad = null;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $ccv = null;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $saldo = 0;

        public function __construct(string $email = "", string $password = "", string $rol = "cliente", string $telefono = "", string $direccion = "", string $localidad = "", string $nombre = "", string $codigoPostal = "", string $numTarjeta = "", DateTime $fechaCaducidad = null, string $ccv = "", float $saldo = 0) 
        {
            $this->email = $email;
            $this->password = $password;
            $this->rol = $rol;
            $this->telefono = $telefono;
            $this->direccion = $direccion;
            $this->localidad = $localidad;
            $this->nombre = $nombre;
            $this->codigoPostal = $codigoPostal;
            $this->numTarjeta = $numTarjeta;
            $this->fechaCaducidad = $fechaCaducidad;
            $this->ccv = $ccv;
            $this->saldo = $saldo;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function setEmail(string $email): self
        {
            $this->email = $email;
            return $this;
        }
        
        public function getRol(): string
        {
            return $this->rol;
        }

        public function setRol(string $rol): self
        {
            $this->rol = $rol;
            return $this;
        }

        public function getPassword(): string
        {
            return (string) $this->password;
        }

        public function setPassword(string $password): self
        {
            $this->password = $password;
            return $this;
        }

        public function getTelefono(): ?string
        {
            return $this->telefono;
        }

        public function setTelefono(?string $telefono): self
        {
            $this->telefono = $telefono;
            return $this;
        }

        public function getDireccion(): ?string
        {
            return $this->direccion;
        }

        public function setDireccion(?string $direccion): self
        {
            $this->direccion = $direccion;
            return $this;
        }

        public function getLocalidad(): ?string
        {
            return $this->localidad;
        }

        public function setLocalidad(?string $localidad): self
        {
            $this->localidad = $localidad;
            return $this;
        }

        public function getNombre(): ?string
        {
            return $this->nombre;
        }

        public function setNombre(?string $nombre): self
        {
            $this->nombre = $nombre;
            return $this;
        }

        public function getCodigoPostal(): ?string
        {
            return $this->codigoPostal;
        }

        public function setCodigoPostal(?string $codigoPostal): self
        {
            $this->codigoPostal = $codigoPostal;
            return $this;
        }

        public function getNumTarjeta(): ?string
        {
            return $this->numTarjeta;
        }

        public function setNumTarjeta(?string $numTarjeta): self
        {
            $this->numTarjeta = $numTarjeta;
            return $this;
        }

        public function getFechaCaducidad(): ?DateTime
        {
            return $this->fechaCaducidad;
        }

        public function setFechaCaducidad(?DateTime $fechaCaducidad): self
        {
            $this->fechaCaducidad = $fechaCaducidad;
            return $this;
        }

        public function getCcv(): ?string
        {
            return $this->ccv;
        }

        public function setCcv(?string $ccv): self
        {
            $this->ccv = $ccv;
            return $this;
        }

        public function getSaldo(): ?float
        {
            return $this->saldo;
        }

        public function setSaldo(float $saldo): self
        {
            $this->saldo = $saldo;
            return $this;
        }
    }
?>
