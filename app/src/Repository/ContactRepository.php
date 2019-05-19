<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
     */
    public function findOneByUuid(string $uuid): ?Contact
    {
        return $query = $this->createQueryBuilder('c')
            ->andWhere('c.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->useResultCache(true, null, md5($uuid . '_' . $this->getEntityName()))
            ->getOneOrNullResult();
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

    /**
     * {@inheritDoc}
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('c')->getQuery();
    }
}

