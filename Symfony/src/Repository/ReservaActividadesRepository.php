<?php

namespace App\Repository;

use App\Entity\ReservaActividades;
use App\Entity\Actividad;
use App\Entity\Reserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ReservaActividadesRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, ReservaActividades::class);
        $this->em = $em;
    }

    public function createReservaActividad(Actividad $actividad, Reserva $reserva, bool $cancelado = false, ?DateTime $fecha = null): bool 
    {
        $reservaActividad = new ReservaActividades();
        $reservaActividad->setActividad($actividad);
        $reservaActividad->setReserva($reserva);
        $reservaActividad->setCancelado($cancelado);
        $reservaActividad->setAsistido(false);
        $reservaActividad->setFecha($fecha ?? new \DateTime());
        
        $this->em->persist($reservaActividad);
        $this->em->flush();

        return true;
    }

    public function searchReservaActividad(string $id): ?ReservaActividades
    {
        return $this->em->getRepository(ReservaActividades::class)->find($id);
    }

    public function searchReservasByActividad(Actividad $actividad): array
    {
        return $this->em->getRepository(ReservaActividades::class)->findBy(['actividad' => $actividad, 'cancelado' => false], ['fecha' => 'DESC']);
    }

    public function searchReservasByReserva(Reserva $reserva): array
    {
        return $this->em->getRepository(ReservaActividades::class)->findBy(['reserva' => $reserva, 'cancelado' => false], ['fecha' => 'DESC']);
    }

    public function cancelarReservaActividad(string $id): bool
    {
        $reserva = $this->searchReservaActividad($id);
        $reserva->setCancelado(true);
        
        $this->em->persist($reserva);
        $this->em->flush();
        return true;
    }

    public function confirmarAsistencia(string $id): bool
    {
        $reserva = $this->searchReservaActividad($id);
        $reserva->setAsistido(true);
        
        $this->em->persist($reserva);
        $this->em->flush();
        return true;
    }

    public function deleteReservaActividad(string $id): bool
    {
        $reservaActividad = $this->searchReservaActividad($id);
        
        if ($reservaActividad === null) 
        {
            return false;
        }

        $this->em->remove($reservaActividad);
        $this->em->flush();

        return true;
    }

    public function contarPlazasOcupadas(Actividad $actividad): int
    {
        $query = $this->em->createQuery('SELECT COUNT(ra.id) FROM App\Entity\ReservaActividades ra WHERE ra.actividad = :actividad AND ra.cancelado = false')->setParameter('actividad', $actividad);
        return (int) $query->getSingleScalarResult();
    }

    public function totalReservasActividades(): int
    {
        $query = $this->em->createQuery('SELECT COUNT(ra.id) FROM App\Entity\ReservaActividades ra');
        return (int) $query->getSingleScalarResult();
    }
}