<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\SimpleBus\Contact;

use App\Entity\Contact;
use App\SimpleBus\AbstractCommandHandler;
use App\SimpleBus\CommandInterface;

/**
 * Class UpdateContactCommandHandler
 */
class UpdateContactCommandHandler extends AbstractCommandHandler
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
        $data = json_decode($command->getData(), true);
        $this->em->getConnection()->connect();
        $contact = $this->em->getRepository(Contact::class)->findOneByUuid($data['uuid']);
        if (!$contact) {
            $this->logger->error("Contact was not found with uuid: {$data['uuid']}");

            return;
        }
        $contact->hydrate($data, false);
        if ($this->isValidEntity($contact)) {
            $this->em->merge($contact);
            $this->em->flush();
            $this->em->clear();
            $this->em->getConnection()->close();
        }
    }
}