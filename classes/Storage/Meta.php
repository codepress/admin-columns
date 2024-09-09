<?php

declare(strict_types=1);

namespace AC\Storage;

use AC\MetaType;

class Meta
{

    private $meta_type;

    public function __construct(MetaType $meta_type)
    {
        $this->meta_type = $meta_type;
    }

    public function get(int $id, string $key)
    {
        return get_metadata((string)$this->meta_type, $id, $key);
    }

    public function update(int $id, string $key, $value): void
    {
        update_metadata((string)$this->meta_type, $id, $key, $value);
    }

    public function delete(int $id, string $key): void
    {
        delete_metadata((string)$this->meta_type, $id, $key);
    }

}