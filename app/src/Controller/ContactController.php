<?php
declare(strict_types=1);

namespace App\Controller;

use App\Producer\ContactProducerInterface;
use App\Repository\ContactRepositoryInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Routing\ClassResourceInterface;
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

    public function __construct(
        ContactRepositoryInterface $contactRepository,
        ContactProducerInterface $contactProducer,
        CommandBus $commandBus
    ) {
        $this->contactRepository = $contactRepository;
        $this->contactProducer = $contactProducer;
        $this->commandBus = $commandBus;
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
