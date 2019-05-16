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
     * {@inheritDoc}
     */
    public function find($id);

    /**
     * Find all contacts by firstName.
     *
     * @param string $firstName The first name of contact.
     *
     * @return Contact[]
     */
    public function findAllByFirstName(string $firstName): array;
}
