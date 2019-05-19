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
     */
    public function getContacts(Request $request)
    {
        $query = $this->contactRepository->getFilterQuery($request->query->getAlnum('filter'));
        $result = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        return $result;
    }
}