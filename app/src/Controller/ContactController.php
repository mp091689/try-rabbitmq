<?php
declare(strict_types=1);

namespace App\Controller;

use App\Producer\ContactProducerInterface;
use App\Repository\ContactRepositoryInterface;
use App\SimpleBus\MyService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactController
 *
 * @Annotations\RouteResource("Contact", pluralize=false)
 */
class ContactController extends AbstractFOSRestController implements ClassResourceInterface
{
    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * @var ContactProducerInterface
     */
    private $contactProducer;

    public function __construct(
        ContactRepositoryInterface $contactRepository,
        ContactProducerInterface $contactProducer
    ) {
        $this->contactRepository = $contactRepository;
        $this->contactProducer = $contactProducer;
    }

    /**
     * @return JsonResponse
     */
    public function cgetAction(): JsonResponse
    {
        // TODO: implement pagination/filtering/search

        $contacts = $this->contactRepository->findAll();

        return $this->json($contacts);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        $contact = $this->contactRepository->find($id);

        if (empty($contact)) {
            $status = Response::HTTP_NOT_FOUND;

            return $this->json(
                [
                    'status' => Response::$statusTexts[$status],
                    'message' => "Record was not found, id: {$id}",
                ],
                $status
            );
        }

        return $this->json($contact);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $this->contactProducer->publish($request->getContent(), 'create');
        $status = Response::HTTP_OK;

        return $this->json(
            [
                'status' => Response::$statusTexts[$status],
                'message' => 'Sent to the queue',
            ],
            $status
        );
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function putAction(Request $request, int $id): JsonResponse
    {
        $data = json_encode(['id' => $id] + json_decode($request->getContent(), true));
        $this->contactProducer->publish($data, 'update');
        $status = Response::HTTP_OK;

        return $this->json(
            [
                'status' => Response::$statusTexts[$status],
                'message' => 'Sent to the queue',
            ],
            $status
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        $this->contactProducer->publish($id, 'delete');

        $status = Response::HTTP_OK;

        return $this->json(
            [
                'status' => Response::$statusTexts[$status],
                'message' => 'Sent to the queue',
            ],
            $status
        );
    }
}
