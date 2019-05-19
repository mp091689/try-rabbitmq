<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Consumer;

use App\SimpleBus\Contact\UpdateContactCommand;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

/**
 * Class UpdateContactConsumer
 */
class UpdateContactConsumer implements ConsumerInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ContactConsumer constructor.
     *
     * @param CommandBus      $commandBus
     * @param LoggerInterface $logger
     */
    public function __construct(
        CommandBus $commandBus,
        LoggerInterface $logger
    ) {
        $this->commandBus = $commandBus;
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
            $command = new UpdateContactCommand();
            $command->setData($msg->getBody());
            $this->commandBus->handle($command);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            return self::MSG_REJECT;
        }
    }
}