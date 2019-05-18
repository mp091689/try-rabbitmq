<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\SimpleBus;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DeleteContactCommandHandler
 */
class DeleteContactCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Dependency Injection constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     * @param LoggerInterface        $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * Creates new contact.
     *
     * @param DeleteContactCommand $command
     *
     * @return void
     */
    public function handle(DeleteContactCommand $command): void
    {
        $id = $command->data;
        $this->em->getConnection()->connect();
        $contact = $this->em->getRepository(Contact::class)->findOneBy(['id' => $id]);
        if (!$contact) {
            $this->logger->error("Contact was not found with id: {$id}");

            return;
        }
        $entity = $this->em->merge($contact);
        $this->em->remove($entity);
        $this->em->flush();
        $this->em->clear();
        $this->em->getConnection()->close();
    }
}