<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository implements ContactRepositoryInterface
{
    /**
     * ContactRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(
        RegistryInterface $registry
    ) {
        parent::__construct($registry, Contact::class);
    }

    /**
     * {@inheritDoc}
     * @throws \Doctrine\ORM\ORMException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $query = $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->useResultCache(true, null, md5($id . '_' . $this->getEntityName()))
            ->getResult();
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->useResultCache(true, null, md5('ALL_' . $this->getEntityName()))
            ->getResult();
    }
}

