<?php
declare(strict_types=1);

namespace App\Controller;

use App\Producer\ContactProducerInterface;
use App\Repository\ContactRepositoryInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Ramsey\Uuid\Uuid;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
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

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        ContactRepositoryInterface $contactRepository,
        ContactProducerInterface $contactProducer,
        CommandBus $commandBus,
        PaginatorInterface $paginator
    ) {
        $this->contactRepository = $contactRepository;
        $this->contactProducer = $contactProducer;
        $this->commandBus = $commandBus;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function cgetAction(Request $request): JsonResponse
    {
        $query = $this->contactRepository->findAllQuery();
        $result = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        return $this->json($result);
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function getAction(string $uuid): JsonResponse
    {
        $contact = $this->contactRepository->findOneByUuid($uuid);

        if (empty($contact)) {
            $status = Response::HTTP_NOT_FOUND;

            return $this->json(
                [
                    'status' => Response::$statusTexts[$status],
                    'message' => "Record was not found, uuid: {$uuid}",
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
     * @throws \Exception
     */
    public function postAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $data['uuid'] = Uuid::uuid4()->toString();
        $this->contactProducer->publish(json_encode($data), 'create');
        $status = Response::HTTP_OK;

        return $this->json(
            [
                'status' => Response::$statusTexts[$status],
                'message' => "Sent to the queue. Entity uuid: {$data['uuid']}",
            ],
            $status
        );
    }

    /**
     * @param Request $request
     * @param string  $uuid
     *
     * @return JsonResponse
     */
    public function putAction(Request $request, string $uuid): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $data['uuid'] = $uuid;
        $this->contactProducer->publish(json_encode($data), 'update');
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
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function deleteAction(string $uuid): JsonResponse
    {
        $this->contactProducer->publish($uuid, 'delete');

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
