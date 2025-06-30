<?php

namespace App\Repository;

use App\Entity\Evento;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class EventoRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Evento::class);
        $this->em = $em;
    }

    public function createEvent($nombre, string $descripcion, string $tipo, int $plazas, float $precio, string $lugar, \DateTime $fechaInicio, \DateTime $fechaFin, Usuario $usuario): bool 
    {
        $evento = new Evento();
        $evento->setNombre($nombre);
        $evento->setTipo($tipo);
        $evento->setPlazas($plazas);
        $evento->setPrecio($precio);
        $evento->setLugar($lugar);
        $evento->setFechaInicio($fechaInicio);
        $evento->setFechaFin($fechaFin);
        $evento->setUsuario($usuario);
        $evento->setDescripcion($descripcion);
        
        $this->em->persist($evento);
        $this->em->flush();

        return true;
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function searchEvent(string $id): ?Evento
    {
        return $this->em->getRepository(Evento::class)->find($id);
    }

    public function searchEvents(Usuario $usuario): array
    {
        return $this->em->getRepository(Evento::class)->findBy(['usuario' => $usuario]);
    }

    public function searchAllEvents(): array
    {
        return $this->em->getRepository(Evento::class)->findAll();
    }

    public function searchEventsFiltrados(string $tipo): array
    {
        return $this->em->getRepository(Evento::class)->findBy(['tipo' => $tipo]);
    }

    public function editEvent(int $id, ?string $nombre = null, ?string $tipo = null, ?int $plazas = null, ?float $precio = null, ?string $lugar = null, ?\DateTime $fechaInicio = null, ?\DateTime $fechaFin = null, ?string $descripcion = null): bool 
    {
        $evento = $this->searchEvent($id);
        if ($evento === null) 
        {
            return false;
        }
        if ($nombre !== null) 
        {
            $evento->setNombre($nombre);
        }
        if ($tipo !== null) 
        {
            $evento->setTipo($tipo);
        }
        if ($plazas !== null) 
        {
            $evento->setPlazas($plazas);
        }
        if ($precio !== null) 
        {
            $evento->setPrecio($precio);
        }
        if ($lugar !== null) 
        {
            $evento->setLugar($lugar);
        }
        if ($fechaInicio !== null) 
        {
            $evento->setFechaInicio($fechaInicio);
        }
        if ($fechaFin !== null) 
        {
            $evento->setFechaFin($fechaFin);
        }
        if ($descripcion !== null) 
        {
            $evento->setDescripcion($descripcion);
        }

        $this->em->persist($evento);
        $this->em->flush();

        return true;
    }

    public function deleteEvent(string $id): bool
    {
        $evento = $this->searchEvent($id);
        
        if ($evento === null) 
        {
            return false;
        }

        $this->em->remove($evento);
        $this->em->flush();

        return true;
    }
}