<?php

declare(strict_types=1);

namespace AC;

trait OpCacheInvalidateTrait
{

    /**
     * Check if the file exists, if opcache is enabled and invalidates the cache
     */
    protected function opcache_invalidate(string $filename, bool $force = false): void
    {
        if (function_exists('opcache_invalidate') && is_file($filename)) {
            opcache_invalidate($filename, $force);
        }
    }

}