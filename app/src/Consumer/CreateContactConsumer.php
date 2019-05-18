<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Consumer;

use App\SimpleBus\CreateContactCommand;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

/**
 * Class CreateContactConsumer
 */
class CreateContactConsumer implements ConsumerInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * ContactConsumer constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {

        $command = new CreateContactCommand();
        $command->data = $msg->getBody();
        $this->commandBus->handle($command);
    }
}