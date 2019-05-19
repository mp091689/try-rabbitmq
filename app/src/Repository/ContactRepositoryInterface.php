<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Common\Persistence\ObjectRepository;

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
}
