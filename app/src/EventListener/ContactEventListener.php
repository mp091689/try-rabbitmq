<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\EventListener;


use App\Entity\Contact;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Event\OnFlushEventArgs;

class ContactEventListener
{
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $cache = $em->getConfiguration()->getResultCacheImpl();

        $this->clearCache($uow->getScheduledEntityInsertions(), $cache);
        $this->clearCache($uow->getScheduledEntityUpdates(), $cache);
        $this->clearCache($uow->getScheduledEntityDeletions(), $cache);
    }

    private function clearCache(array $schedule, Cache $cache): void
    {
        foreach ($schedule as $entity) {
            if ($entity instanceof Contact) {
                $cache->delete(md5($entity->getId() . '_' . Contact::class));
                $cache->delete(md5('ALL_' . Contact::class));
            }
        }
    }
}