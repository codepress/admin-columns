<?php

declare(strict_types=1);

namespace AC\Type;

use Throwable;

abstract class KeyGenerator
{

    protected function generate_raw(): string
    {
        do {
            try {
                $key = substr(bin2hex(random_bytes(7)), 0, 13);
            } catch (Throwable $e) {
                $key = uniqid();
            }
        } while ( ! strpbrk($key, 'abcdef'));

        return $key;
    }

}