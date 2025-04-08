<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Preferences\Preference;
use AC\Preferences\SiteFactory;
use AC\Type\TableId;

class TableListOrder
{

    private Preference $storage;

    public function __construct(int $user_id = null)
    {
        $this->storage = (new SiteFactory())->create('list_order', $user_id);
    }

    public function get_order(TableId $table_id): array
    {
        return $this->storage->find((string)$table_id) ?: [];
    }

    public function set_order(TableId $table_id, array $order): void
    {
        $this->storage->save((string)$table_id, $order);
    }
}