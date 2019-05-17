<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Consumer;

use App\Entity\Contact;
use App\Services\CacheServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UpdateContactConsumer
 */
class UpdateContactConsumer implements ConsumerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CacheServiceInterface
     */
    private $cacheService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ContactConsumer constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param CacheServiceInterface  $cacheService
     * @param ValidatorInterface     $validator
     * @param LoggerInterface        $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CacheServiceInterface $cacheService,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->cacheService = $cacheService;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        try {
            $body = json_decode($msg->getBody(), true);
            $this->entityManager->getConnection()->connect();
            $contact = $this->entityManager->getRepository(Contact::class)->find($body['id']);
            if (!$contact) {
                $this->logger->error("Contact was not found with id: {$body['id']}");

                return;
            }
            $contact->setFirstName($body['data']['firstName']);
            $errors = $this->validator->validate($contact);
            if (count($errors) > 0) {
                $this->logger->error((string)$errors);

                return;
            }
            $this->entityManager->merge($contact);
            $this->entityManager->flush();
            $cacheKey = $contact->getId() . '_' . Contact::class;
            $this->cacheService->setValue($cacheKey, $contact);
            $this->entityManager->clear();
            $this->entityManager->getConnection()->close();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}