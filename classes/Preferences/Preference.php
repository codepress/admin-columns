<?php

declare(strict_types=1);

namespace AC\Preferences;

use AC\Storage\UserData;

class Preference
{

    private $storage;

    public function __construct(UserData $storage)
    {
        $this->storage = $storage;
    }

    public function find_all(): ?array
    {
        return $this->storage->get() ?: [];
    }

    public function find(string $option)
    {
        $data = $this->storage->get();

        return $data[$option] ?? null;
    }

    public function save(string $option, $value): void
    {
        $data = $this->storage->get();

        $data[$option] = $value;

        $this->storage->save($data);
    }

    public function delete(string $option): void
    {
        $data = $this->storage->get();

        unset($data[$option]);

        $data
            ? $this->storage->save($data)
            : $this->storage->delete();
    }

    public function delete_all(): void
    {
        $this->storage->delete();
    }

}