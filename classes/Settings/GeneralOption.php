<?php

namespace AC\Settings;

use AC\Storage;

class GeneralOption
{

    private Storage\OptionData $storage;

    public function __construct(Storage\OptionData $storage)
    {
        $this->storage = $storage;
    }

    public function get(string $key)
    {
        return $this->all()[$key] ?? null;
    }

    public function all(): array
    {
        $data = $this->storage->get();

        return $data && is_array($data)
            ? $data
            : [];
    }

    public function delete(string $key): void
    {
        $data = $this->all();

        unset($data[$key]);

        $data
            ? $this->storage->save($data)
            : $this->storage->delete();
    }

    public function save(string $key, $value): void
    {
        $data = $this->all();

        $data[$key] = $value;

        $this->storage->save($data);
    }

}