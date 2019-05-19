<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\SimpleBus;

/**
 * Abstract class AbstractCommand
 */
abstract class AbstractCommand implements CommandInterface
{
    private $data;

    /**
     * {@inheritDoc}
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): string
    {
        return $this->data;
    }
}