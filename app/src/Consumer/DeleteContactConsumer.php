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
 * Class DeleteContactConsumer
 */
class DeleteContactConsumer implements ConsumerInterface
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
     * @param LoggerInterface        $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CacheServiceInterface $cacheService,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->cacheService = $cacheService;
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
            $id = $msg->getBody();
            $this->entityManager->getConnection()->connect();
            $contact = $this->entityManager->getRepository(Contact::class)->findOneBy(['id' => $id]);
            if (!$contact) {
                $this->logger->error("Contact was not found with id: {$id}");

                return;
            }
            $entity = $this->entityManager->merge($contact);
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $cacheKey = $id . '_' . Contact::class;
            $this->cacheService->delValue($cacheKey);
            $this->entityManager->clear();
            $this->entityManager->getConnection()->close();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}