<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\SimpleBus;

/**
 * Interface CommandInterface
 */
interface CommandInterface
{
    /**
     * Sets data to handle by command handler.
     *
     * @param string $data
     */
    public function setData(string $data): void;

    /**
     * Returns data to handle by command handler.
     *
     * @return string
     */
    public function getData(): string;
}
