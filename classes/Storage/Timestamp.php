<?php

namespace AC\Storage;

use AC\Expirable;
use LogicException;

final class Timestamp implements Expirable
{

    private $storage;

    public function __construct(KeyValuePair $storage)
    {
        $this->storage = $storage;
    }

    public function is_expired(int $value = null): bool
    {
        if (null === $value) {
            $value = time();
        }

        return $value > (int)$this->get();
    }

    public function validate(int $value): bool
    {
        return $value > 0;
    }

    public function get(array $args = [])
    {
        return $this->storage->get($args);
    }

    public function delete(): void
    {
        $this->storage->delete();
    }

    public function save(int $value): void
    {
        if ( ! $this->validate($value)) {
            throw new LogicException('Value needs to be a positive integer.');
        }

        $this->storage->save($value);
    }

}