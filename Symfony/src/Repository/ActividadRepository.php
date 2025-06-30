<?php

namespace App\Repository;

use App\Entity\Actividad;
use App\Entity\Evento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class ActividadRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Actividad::class);
        $this->em = $em;
    }

    public function createActividad(string $nombre, string $descripcion, int $orden, int $plazas, string $lugar, \DateTime $fecha, \DateTime $hora, Evento $evento): bool 
    {
        $actividad = new Actividad();
        $actividad->setNombre($nombre);
        $actividad->setDescripcion($descripcion);
        $actividad->setOrden($orden);
        $actividad->setPlazas($plazas);
        $actividad->setLugar($lugar);
        $actividad->setFecha($fecha);
        $actividad->setHora($hora);
        $actividad->setEvento($evento);
        
        $this->em->persist($actividad);
        $this->em->flush();

        return true;
    }

    public function searchActividad(int $id)
    {
        return $this->em->getRepository(Actividad::class)->find($id);
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function searchActividades(Evento $evento)
    {
        return $this->em->getRepository(Actividad::class)->findBy(['evento' => $evento], ['orden' => 'ASC']);    
    }

    public function editActividad(string $nombre, string $descripcion, int $orden, int $plazas, string $lugar, \DateTime $fecha, \DateTime $hora, string $actividad_): bool 
    {
        $actividad = $this->searchActividad($actividad_);
        if ($actividad === null) 
        {
            return false;
        }

        $actividad->setNombre($nombre);
        $actividad->setDescripcion($descripcion);
        $actividad->setOrden($orden);
        $actividad->setPlazas($plazas);
        $actividad->setLugar($lugar);
        $actividad->setFecha($fecha);
        $actividad->setHora($hora);

        $this->em->persist($actividad);
        $this->em->flush();

        return true;
    }

    public function deleteActividad(string $id): bool
    {
        $actividad = $this->searchActividad($id);
        
        if ($actividad === null) 
        {
            return false;
        }

        $this->em->remove($actividad);
        $this->em->flush();

        return true;
    }
}