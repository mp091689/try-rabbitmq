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
 * Class UpdateContactCommandHandler
 */
class UpdateContactCommandHandler
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
     * @param UpdateContactCommand $command
     *
     * @return void
     */
    public function handle(UpdateContactCommand $command): void
    {
        $data = json_decode($command->data, true);
        $this->em->getConnection()->connect();
        $contact = $this->em->getRepository(Contact::class)->findOneBy(['id' => $data['id']]);
        if (!$contact) {
            $this->logger->error("Contact was not found with id: {$data['id']}");

            return;
        }
        $contact->setFirstName($data['firstName']);
        $errors = $this->validator->validate($contact);
        if (count($errors) > 0) {
            $this->logger->error((string)$errors);

            return;
        }
        $this->em->merge($contact);
        $this->em->flush();
        $this->em->clear();
        $this->em->getConnection()->close();
    }
}