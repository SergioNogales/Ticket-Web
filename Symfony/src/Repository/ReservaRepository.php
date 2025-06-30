<?php

namespace App\Repository;

use App\Entity\Reserva;
use App\Entity\Usuario;
use App\Entity\Evento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Reserva>
 */
class ReservaRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Reserva::class);
        $this->em = $em;
    }

    public function createReserva(Usuario $usuario, Evento $evento, string $dni, string $nombre, int $edad, bool $pagado = false, bool $cancelado = false): bool 
    {
        $reserva = new Reserva();
        $reserva->setUsuario($usuario);
        $reserva->setEvento($evento);
        $reserva->setDni($dni);
        $reserva->setNombre($nombre);
        $reserva->setEdad($edad);
        $reserva->setPagado($pagado);
        $reserva->setCancelado($cancelado);
        $reserva->setAsistido(false);
        
        $this->em->persist($reserva);
        $this->em->flush();

        return true;
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.cancelado = 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function searchReserva(string $id): ?Reserva
    {
        return $this->em->getRepository(Reserva::class)->find($id);
    }

    public function searchReservasByEvento(Evento $evento): array
    {
        return $this->em->getRepository(Reserva::class)->findBy(['evento' => $evento, 'cancelado' => false]);
    }

    public function searchReservasByUsuario(Usuario $usuario): array
    {
        return $this->em->getRepository(Reserva::class)->findBy(['usuario' => $usuario, 'cancelado' => false]);
    }

    public function editReserva(string $id, ?string $dni = null, ?string $nombre = null, ?int $edad = null, ?bool $pagado = null): bool 
    {
        $reserva = $this->searchReserva($id);
        if ($reserva === null) 
        {
            return false;
        }

        if ($dni !== null) 
        {
            $reserva->setDni($dni);
        }
        if ($nombre !== null) 
        {
            $reserva->setNombre($nombre);
        }
        if ($edad !== null) 
        {
            $reserva->setEdad($edad);
        }
        if ($pagado !== null) 
        {
            $reserva->setPagado($pagado);
        }
        
        $this->em->persist($reserva);
        $this->em->flush();

        return true;
    }

    public function cancelarReserva(string $id): bool
    {
        $reserva = $this->searchReserva($id);
        $reserva->setCancelado(true);
        
        $this->em->persist($reserva);
        $this->em->flush();
        return true;
    }

    public function confirmarAsistencia(string $id): bool
    {
        $reserva = $this->searchReserva($id);
        $reserva->setAsistido(true);
        
        $this->em->persist($reserva);
        $this->em->flush();
        return true;
    }

    public function deleteReserva(string $id): bool
    {
        $reserva = $this->searchReserva($id);
        
        if ($reserva === null) 
        {
            return false;
        }

        $this->em->remove($reserva);
        $this->em->flush();

        return true;
    }

    public function PlazasOcupadas(Evento $evento): int
    {
        $query = $this->em->createQuery('SELECT COUNT(r.id) FROM App\Entity\Reserva r WHERE r.evento = :evento AND r.cancelado = false')->setParameter('evento', $evento);
        return (int) $query->getSingleScalarResult();
    }

    public function totalReservas(): int
    {
        $query = $this->em->createQuery('SELECT COUNT(r.id) FROM App\Entity\Reserva r');
        return (int) $query->getSingleScalarResult();
    }

    public function searchAsistentes(Evento $evento): array
    {
        $query = $this->em->createQuery('SELECT u FROM App\Entity\Usuario u JOIN App\Entity\Reserva r WITH r.usuario = u WHERE r.evento = :evento AND r.cancelado = false')->setParameter('evento', $evento);
        return $query->getResult();
    }
}