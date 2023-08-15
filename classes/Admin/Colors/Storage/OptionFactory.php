<?php

declare(strict_types=1);

namespace AC\Admin\Colors\Storage;

use AC\Storage\Option;
use InvalidArgumentException;

final class OptionFactory
{

    private const PREFIX = '_ac_colors_';

    public function create(string $key): Option
    {
        if (strpos($key, self::PREFIX) === 0) {
            throw new InvalidArgumentException('Prefix is managed storage.');
        }

        return new Option(self::PREFIX . $key);
    }

}