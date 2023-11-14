<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

final class ListKey
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;

        $this->validate();
    }

    private function validate(): void
    {
        if ('' === $this->key) {
            throw new InvalidArgumentException('List key can not be empty.');
        }
    }

    public function is_network(): bool
    {
        return ac_helper()->string->starts_with($this->key, 'wp-ms_');
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