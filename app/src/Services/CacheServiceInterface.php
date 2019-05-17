<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Services;

/**
 * Interface CacheServiceInterface
 */
interface CacheServiceInterface
{
    /**
     * Set value to the cache with specified key.
     *
     * @param string   $key    The key of cache record.
     * @param mixed    $value  The value of cache record.
     *
     * @param int|null $expire Time of expiration in seconds.
     *
     * @return void
     */
    public function setValue(string $key, $value, int $expire = null): void;

    /**
     * Get value from the cache with the specified key.
     *
     * @param string $key The key to find value.
     *
     * @return mixed|null
     */
    public function getValue(string $key);

    /**
     * Delete value from the cache with the specific key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function delValue(string $key): void;
}