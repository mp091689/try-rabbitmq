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
 * Class CreateContactCommandHandler
 */
class CreateContactCommandHandler extends AbstractCommandHandler
{
    /**
     * Creates new contact.
     *
     * @param CommandInterface $command
     *
     * @return void
     * @throws \Exception
     */
    public function handle(CommandInterface $command): void
    {
        $contact = new Contact();
        $contact->hydrate(json_decode($command->getData(), true), true);
        if ($this->isValidEntity($contact)) {
            $this->em->getConnection()->connect();
            $this->em->persist($contact);
            $this->em->flush();
            $this->em->clear();
            $this->em->getConnection()->close();
        }
    }
}