<?php

namespace AC\Settings;

use AC\Storage;

class GeneralOption
{

    private $storage;

    public function __construct(Storage\OptionData $storage)
    {
        $this->storage = $storage;
    }

    public function get(string $key)
    {
        $data = $this->storage->get() ?: [];

        return $data[$key] ?? null;
    }

    public function delete(string $key): void
    {
        $data = $this->storage->get() ?: [];

        unset($data[$key]);

        $data
            ? $this->storage->save($data)
            : $this->storage->delete();
    }

    public function save(string $key, $value): void
    {
        $data = $this->storage->get() ?: [];

        $data[$key] = $value;

        $this->storage->save($data);
    }

}