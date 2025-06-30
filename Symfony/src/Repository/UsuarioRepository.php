<?php
// src/Repository/UsuarioRepository.php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\ServicioContraseña;

class UsuarioRepository extends ServiceEntityRepository
{
    private $em;
    private $servicioContraseña;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, ServicioContraseña $servicioContraseña)
    {
        parent::__construct($registry, Usuario::class);
        $this->em = $em;
        $this->servicioContraseña = $servicioContraseña;
    }

    public function createUser($email, $telefono, $nombre, $direccion, $localidad, $codigoPostal, $fecha, $contraseña, $ccv, $numTarjeta) 
    {
        $usuario = new Usuario();
        $usuario->setEmail($email);
        $usuario->setTelefono($telefono);
        $usuario->setNombre($nombre);
        $usuario->setDireccion($direccion);
        $usuario->setLocalidad($localidad);
        $usuario->setCodigoPostal($codigoPostal);
        $usuario->setFechaCaducidad(new \DateTime($fecha));
        
        $contraseñaHash= $this->servicioContraseña->hashear($contraseña, 'contraseña');
        $usuario->setPassword($contraseñaHash);
        $ccvCifrado= $this->servicioContraseña->cifrar($ccv, 'ccv');
        $usuario->setCcv($ccvCifrado);
        $numTarjetaCifrado= $this->servicioContraseña->cifrar($numTarjeta, 'tarjeta');
        $usuario->setNumTarjeta($numTarjetaCifrado);

        $this->em->persist($usuario);
        $this->em->flush();

        return true;
    }

    public function searchUser($email)
    {
        $user = $this->em->getRepository(Usuario::class)->findOneBy(['email' => $email]);
        return $user;
    }

    public function searchAllChat(): array
    {
        return $this->em->getRepository(Usuario::class)->findBy(['rol' => ['promotor', 'admin']]);
    }

    public function searchAllUsers(): array
    {
        return $this->em->getRepository(Usuario::class)->findAll();
    }

    public function validUser($email, $contraseña)
    {
        $user = $this->searchUser($email);
        $contraseña_ = $this->servicioContraseña->hashear($contraseña, 'contraseña');
        if($user->getPassword() === $contraseña_)
        {
            return true;
        }
        return false;
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.email)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countPromotor(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.email)')
            ->where('u.rol = :rol')
            ->setParameter('rol', 'promotor')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAdmin(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.email)')
            ->where('u.rol = :rol')
            ->setParameter('rol', 'admin')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function setRol(Usuario $usuario, $rol)
    {
        $usuario->setRol($rol);
        $this->em->persist($usuario);
        $this->em->flush();
        return true;
    }

    public function actualizarSaldo(Usuario $usuario, $saldo)
    {
        $usuario->setSaldo($saldo);
        $this->em->persist($usuario);
        $this->em->flush();
        return true;
    }

    public function editUser($emailviejo, $emailnuevo, $telefono = null, $nombre = null, $direccion = null, $localidad = null, $codigoPostal = null, $fecha = null, $contraseña = null, $ccv = null, $numTarjeta = null) 
    {
        $usuario = $this->searchUser($emailviejo);
        
        if($emailnuevo != $emailviejo)
        {
            $usuario->setEmail($emailnuevo);
        }

        if($telefono != null)
        {
            $usuario->setTelefono($telefono);
        }
        if($nombre != null)
        {
            $usuario->setNombre($nombre);
        }
        if($direccion != null)
        {
            $usuario->setDireccion($direccion);
        }
        if($localidad != null)
        {
            $usuario->setLocalidad($localidad);
        }
        if($codigoPostal != null)
        {
            $usuario->setCodigoPostal($codigoPostal);
        }
        if($fecha != null)
        {
            $usuario->setFechaCaducidad(new \DateTime($fecha));
        }
        if($contraseña != null)
        {
            $contraseñaHash= $this->servicioContraseña->hashear($contraseña, 'contraseña');
            $usuario->setPassword($contraseñaHash);
        }
        if($ccv != null)
        {
            $ccvCifrado= $this->servicioContraseña->cifrar($ccv, 'ccv');
            $usuario->setCcv($ccvCifrado);
        }
        if($numTarjeta != null)
        {
            $numTarjetaCifrado= $this->servicioContraseña->cifrar($numTarjeta, 'tarjeta');
            $usuario->setNumTarjeta($numTarjetaCifrado);
        }

        $this->em->persist($usuario);
        $this->em->flush();

        return true;
    }
}