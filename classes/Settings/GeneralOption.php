<?php

namespace AC\Settings;

use AC\Storage;

class GeneralOption
{

    private Storage\OptionData $storage;

    private ?array $data = null;

    public function __construct()
    {
        $this->storage = new Storage\Option('cpac_general_options');
    }

    public function get(string $key)
    {
        return $this->all()[$key] ?? null;
    }

    private function all(): array
    {
        if (null === $this->data) {
            $data = $this->storage->get();

            $this->data = $data && is_array($data)
                ? $data
                : [];
        }

        return $this->data;
    }

    private function flush_cache(): void
    {
        $this->data = null;
    }

    public function delete(string $key): void
    {
        $data = $this->all();

        unset($data[$key]);

        $data
            ? $this->storage->save($data)
            : $this->storage->delete();

        $this->flush_cache();
    }

    public function save(string $key, $value): void
    {
        $data = $this->all();

        $data[$key] = $value;

        $this->storage->save($data);

        $this->flush_cache();
    }

}