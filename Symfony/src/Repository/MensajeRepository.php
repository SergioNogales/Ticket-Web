<?php

namespace App\Repository;

use App\Entity\Mensaje;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Mensaje>
 */
class MensajeRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Mensaje::class);
        $this->em = $em;
    }

    public function getConversacion(Usuario $usuario1, Usuario $usuario2): array
    {
        return $this->createQueryBuilder('m')
            ->where('(m.emisor = :usuario1 AND m.destinatario = :usuario2) OR (m.emisor = :usuario2 AND m.destinatario = :usuario1)')
            ->setParameter('usuario1', $usuario1)
            ->setParameter('usuario2', $usuario2)
            ->orderBy('m.fechaEnvio', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function enviarMensaje(Usuario $emisor, Usuario $destinatario, string $contenido)
    {
        $mensaje = new Mensaje;
        $mensaje->setEmisor($emisor);
        $mensaje->setDestinatario($destinatario);
        $mensaje->setMensaje($contenido);
        $mensaje->setFechaEnvio(new \DateTime());
        $this->em->persist($mensaje);
        $this->em->flush();

        return true;
    }

    public function searchEscritos(Usuario $usuario): array
    {
        $qb = $this->createQueryBuilder('m');
        return $qb
            ->select('DISTINCT u')
            ->join('App\Entity\Usuario', 'u', 'WITH', $qb->expr()->orX($qb->expr()->andX('m.emisor = :usuario', 'u = m.destinatario'), $qb->expr()->andX('m.destinatario = :usuario', 'u = m.emisor')))
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();
    }
    
}