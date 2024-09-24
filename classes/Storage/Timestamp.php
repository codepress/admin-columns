<?php

declare(strict_types=1);

namespace AC\Storage;

use AC\Expirable;
use LogicException;

final class Timestamp implements Expirable, KeyValue
{

    private KeyValue $storage;

    public function __construct(KeyValue $storage)
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

    public function validate($value): bool
    {
        return (bool) preg_match('/^[1-9]\d*$/', (string)$value);
    }

    public function get()
    {
        return $this->storage->get();
    }

    public function delete(): void
    {
        $this->storage->delete();
    }

    public function save($value): void
    {
        if ( ! $this->validate($value)) {
            throw new LogicException('Value needs to be a positive integer.');
        }

        $this->storage->save((int)$value);
    }

}