<?php

namespace AC\Storage\Repository;

use AC\Preferences\Preference;
use AC\Preferences\SiteFactory;
use AC\Type\ListScreenId;

class UserColumnOrder
{

    private SiteFactory $storage_factory;

    public function __construct(SiteFactory $storage_factory)
    {
        $this->storage_factory = $storage_factory;
    }

    private function storage(): Preference
    {
        return $this->storage_factory->create('column_order');
    }

    public function save(ListScreenId $id, array $column_names): void
    {
        $this->storage()->save(
            (string)$id,
            $column_names
        );
    }

    public function get(ListScreenId $id): array
    {
        return $this->storage()->find((string)$id) ?: [];
    }

    public function delete(ListScreenId $id): void
    {
        $this->storage()->delete(
            (string)$id
        );
    }

}