<?php

declare(strict_types=1);

namespace AC\Type;

abstract class KeyGenerator
{

    // TODO David rethink this, was uniqeid not just fine?
    protected function generate_raw(): string
    {
        return bin2hex(random_bytes(7));
    }

}