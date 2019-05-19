<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\SimpleBus;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Abstract class AbstractCommandHandler
 */
abstract class AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
     * Handles passed command.
     *
     * @param CommandInterface $command The command for handling.
     *
     * @return void
     */
    abstract public function handle(CommandInterface $command): void;

    /**
     * Validates passed entity. If no errors true will be returned otherwise false will be returned.
     *
     * @param mixed $entity Any doctrine entity object.
     *
     * @return bool
     */
    protected function isValidEntity($entity): bool
    {
        $violations = $this->validator->validate($entity);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            $this->logger->error('Validation error', $errors);

            return false;
        }

        return true;
    }
}