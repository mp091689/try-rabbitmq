<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class ContactProducer
 */
class ContactProducer extends Producer implements ContactProducerInterface
{
}
