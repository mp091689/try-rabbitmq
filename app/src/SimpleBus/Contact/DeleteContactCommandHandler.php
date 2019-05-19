<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\SimpleBus\Contact;

use App\Entity\Contact;
use App\SimpleBus\AbstractCommandHandler;
use App\SimpleBus\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DeleteContactCommandHandler
 */
class DeleteContactCommandHandler extends AbstractCommandHandler
{
    /**
     * Creates new contact.
     *
     * @param CommandInterface $command
     *
     * @return void
     */
    public function handle(CommandInterface $command): void
    {
        $uuid = $command->getData();
        $this->em->getConnection()->connect();
        $contact = $this->em->getRepository(Contact::class)->findOneBy(['uuid' => $uuid]);
        if (!$contact) {
            $this->logger->error("Contact was not found with uuid: {$uuid}");

            return;
        }
        $entity = $this->em->merge($contact);
        $this->em->remove($entity);
        $this->em->flush();
        $this->em->clear();
        $this->em->getConnection()->close();
    }
}