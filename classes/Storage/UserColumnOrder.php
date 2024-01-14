<?php

namespace AC\Storage;

use AC\Preferences\Site;
use AC\Type\ListScreenId;

class UserColumnOrder
{

    private $user_preference;

    public function __construct()
    {
        $this->user_preference = new Site('column_order');
    }

    public function save(ListScreenId $id, array $column_names): void
    {
        $this->user_preference->set((string)$id, $column_names);
    }

    public function exists(ListScreenId $id): bool
    {
        return null !== $this->get($id);
    }

    public function get(ListScreenId $id): array
    {
        return $this->user_preference->get((string)$id) ?: [];
    }

    public function delete(ListScreenId $id): void
    {
        $this->user_preference->delete((string)$id);
    }

}