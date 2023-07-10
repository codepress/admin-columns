<?php

namespace AC\Storage;

use AC\Expirable;
use LogicException;

final class Timestamp implements Expirable
{

    /**
     * @var KeyValuePair
     */
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

    /**
     * @param int $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return preg_match('/^[1-9]\d*$/', $value);
    }

    /**
     * @param array $args
     *
     * @return mixed
     */
    public function get(array $args = [])
    {
        return $this->storage->get($args);
    }

    public function delete()
    {
        return $this->storage->delete();
    }

    /**
     * @param int $value
     *
     * @return bool
     * @throws LogicException
     */
    public function save($value)
    {
        if ( ! $this->validate($value)) {
            throw new LogicException('Value needs to be a positive integer.');
        }

        return $this->storage->save($value);
    }

}