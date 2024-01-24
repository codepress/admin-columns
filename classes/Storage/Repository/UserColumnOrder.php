<?php

namespace AC\Storage\Repository;

use AC\Preferences\SiteFactory;
use AC\Type\ListScreenId;

class UserColumnOrder
{

    private $storage;

    public function __construct()
    {
        $this->storage = (new SiteFactory())->create('column_order');
    }

    public function save(ListScreenId $id, array $column_names): void
    {
        $this->storage->save(
            (string)$id,
            $column_names
        );
    }

    public function get(ListScreenId $id): array
    {
        return $this->storage->find((string)$id) ?: [];
    }

    public function delete(ListScreenId $id): void
    {
        $this->storage->delete(
            (string)$id
        );
    }

}