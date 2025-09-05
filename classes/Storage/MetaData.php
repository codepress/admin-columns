<?php

namespace AC\Storage;

use AC\MetaType;

class MetaData
{

    private MetaType $meta_type;

    public function __construct(?MetaType $meta_type = null)
    {
        $this->meta_type = $meta_type ?? MetaType::create_post_type();
    }

    public function get(int $id, string $key, bool $single = true)
    {
        return get_metadata((string)$this->meta_type, $id, $key, $single);
    }

    public function update(int $id, string $key, $data): void
    {
        update_metadata((string)$this->meta_type, $id, $key, $data);
    }

    public function delete(int $id, string $key): void
    {
        delete_metadata((string)$this->meta_type, $id, $key);
    }

}