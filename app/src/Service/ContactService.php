<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Service;

use App\Repository\ContactRepositoryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactService
 */
class ContactService
{
    public const DEFAULT_LIMIT = 20;
    public const MAX_LIMIT = 1000;

    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        ContactRepositoryInterface $contactRepository,
        PaginatorInterface $paginator
    ) {
        $this->contactRepository = $contactRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     * @throws \Exception
     */
    public function getContacts(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', self::DEFAULT_LIMIT);
        if ($limit > 1000) {
            throw new \Exception("Limit is high: {$limit}. Maximum limit value: " . self::MAX_LIMIT);
        }
        $query = $this->contactRepository->getFilterQuery($request->query->getAlnum('filter'));
        $result = $this->paginator->paginate($query, $page, $limit);

        return $result;
    }
}