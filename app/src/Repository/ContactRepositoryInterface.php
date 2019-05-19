<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Query;

/**
 * Interface ContactRepositoryInterface
 *
 * @package App\Repository
 */
interface ContactRepositoryInterface extends ObjectRepository
{
    /**
     * Find entity by uuid.
     *
     * @param string $uuid UUID of needed entity.
     *
     * @return Contact|null
     */
    public function findOneByUuid(string $uuid): ?Contact;

    /**
     * Returns filter query for paginator.
     *
     * @param string $filter
     *
     * @return Query
     */
    public function getFilterQuery(string $filter): Query;
}
