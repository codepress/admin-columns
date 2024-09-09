<?php

declare(strict_types=1);

namespace AC\Storage;

use AC\MetaType;

class MetaRepository
{

    private $meta_type;

    private $key;

    public function __construct(MetaType $meta_type, string $key)
    {
        $this->meta_type = $meta_type;
        $this->key = $key;
    }

    private function get_storage(): Meta
    {
        return new Meta($this->meta_type);
    }

    public function get(int $id)
    {
        return $this->get_storage()->get($id, $this->key);
    }

    public function save(int $id, $value): void
    {
        $this->get_storage()->update($id, $this->key, $value);
    }

    public function delete(int $id): void
    {
        $this->get_storage()->delete($id, $this->key);
    }

}