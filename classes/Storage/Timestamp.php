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

    public function get()
    {
        return $this->storage->get();
    }

    public function delete(): bool
    {
        return $this->storage->delete();
    }

    public function save(int $value): bool
    {
        if ( ! $this->validate($value)) {
            throw new LogicException('Value needs to be a positive integer.');
        }

        return $this->storage->save($value);
    }

}