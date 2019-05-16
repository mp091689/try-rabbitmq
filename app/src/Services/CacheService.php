<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Services;


use Psr\Cache\CacheItemPoolInterface;

class CacheService implements CacheServiceInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * CacheService constructor.
     *
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(
        CacheItemPoolInterface $cacheItemPool
    ) {
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * {@inheritDoc}
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function setValue(string $key, $value, int $expire = null): void
    {
        $cache = $this->cacheItemPool->getItem(md5($key));
        $cache->set($value);
        $cache->expiresAfter($expire);
        $this->cacheItemPool->save($cache);
    }

    /**
     * {@inheritDoc}
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getValue(string $key)
    {
        $cache = $this->cacheItemPool->getItem(md5($key));
        if ($cache->isHit()) {
            return $cache->get();
        }

        return null;
    }
}
