<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use App\Services\CacheServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository implements ContactRepositoryInterface
{
    /**
     * @var CacheServiceInterface
     */
    private $cacheService;

    /**
     * ContactRepository constructor.
     *
     * @param RegistryInterface     $registry
     * @param CacheServiceInterface $cacheService
     */
    public function __construct(
        RegistryInterface $registry,
        CacheServiceInterface $cacheService
    ) {
        parent::__construct($registry, Contact::class);
        $this->cacheService = $cacheService;
    }

    /**
     * {@inheritDoc}
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $cacheKey = $id . '_' . $this->getEntityName();
        $cache = $this->cacheService->getValue($cacheKey);
        if ($cache !== null) {
            return $cache;
        }
        $result = $query = $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        $this->cacheService->setValue($cacheKey, $result);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        $cacheKey = 'ALL_' . Contact::class;
        $cache = $this->cacheService->getValue($cacheKey);
        if ($cache !== null) {
            return $cache;
        }
        $result = $query = $this->findBy([]);
        $this->cacheService->setValue($cacheKey, $result);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllByFirstName(string $firstName): array
    {
        $query = $this->createQueryBuilder('c')
            ->setMaxResults(100)->setFirstResult(0)
            ->andWhere('c.firstName = :val')
            ->setParameter('val', $firstName)
            ->getQuery();

        return $query->getResult();
    }
}

