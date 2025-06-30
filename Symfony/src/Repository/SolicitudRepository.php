<?php
/**
 * @extends ServiceEntityRepository<Solicitud>
 */

namespace App\Repository;

use App\Entity\Solicitud;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class SolicitudRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Solicitud::class);
        $this->em = $em;
    }

    public function createSolicitud(?string $rolSolicitado, Usuario $usuario): bool 
    {
        $solicitud = new Solicitud();
        $solicitud->setRolSolicitado($rolSolicitado);
        $solicitud->setEstado('pendiente');
        $solicitud->setUsuario($usuario);
        
        $this->em->persist($solicitud);
        $this->em->flush();

        return true;
    }

    public function aprobarSolicitud(Usuario $usuario)
    {
        $solicitud = $this->searchSolicitud($usuario);
        if(!empty($solicitud))
        {
            $solicitud->setEstado('aprobada');
            $this->em->persist($solicitud);
            $this->em->flush();
            return true;
        }
    }

    public function denegarSolicitud(Usuario $usuario)
    {
        $solicitud = $this->searchSolicitud($usuario);
        if(!empty($solicitud))
        {
            $solicitud->setEstado('denegada');
            $this->em->persist($solicitud);
            $this->em->flush();
            return true;
        }
    }

    public function searchSolicitud_(String $id): Solicitud
    {
        return $this->em->getRepository(Solicitud::class)->findOneById($id);
    }

    public function searchSolicitud(Usuario $usuario): ?Solicitud
    {
        return $this->em->getRepository(Solicitud::class)->findOneBy(['usuario' => $usuario, 'estado' => 'pendiente']);
    }

    public function searchSolicitudes(): array
    {
        return $this->em->getRepository(Solicitud::class)->findAll();
    }

    public function editSolicitud(string $id, ?string $rolSolicitado = null, ?string $estado = null): bool 
    {
        $solicitud = $this->searchSolicitud_($id);
        
        if ($solicitud === null) 
        {
            return false;
        }

        if ($rolSolicitado !== null) 
        {
            $solicitud->setRolSolicitado($rolSolicitado);
        }
        if ($estado !== null) 
        {
            $solicitud->setEstado($estado);
        }

        $this->em->persist($solicitud);
        $this->em->flush();

        return true;
    }

    public function deleteSolicitud(string $id): bool
    {
        $solicitud = $this->searchSolicitud_($id);
        
        if ($solicitud === null) 
        {
            return false;
        }

        $this->em->remove($solicitud);
        $this->em->flush();

        return true;
    }
}
