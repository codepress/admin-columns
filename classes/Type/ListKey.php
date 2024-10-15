<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

final class ListKey
{

    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;

        if ( ! self::validate($key)) {
            throw new InvalidArgumentException('List key can not be empty.');
        }
    }

    public static function validate(string $key): bool
    {
        return '' !== $key;
    }

    public function equals(ListKey $key): bool
    {
        return $this->key === (string)$key;
    }

    public function __toString(): string
    {
        return $this->key;
    }

}