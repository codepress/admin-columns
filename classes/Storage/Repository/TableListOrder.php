<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Preferences\SiteFactory;
use AC\Type\ListKey;

class TableListOrder
{

    private $storage;

    public function __construct(int $user_id = null)
    {
        $this->storage = (new SiteFactory())->create('list_order', $user_id);
    }

    public function get_order(ListKey $list_key): array
    {
        return $this->storage->find((string)$list_key) ?: [];
    }

    public function set_order(ListKey $list_key, array $order): void
    {
        $this->storage->save((string)$list_key, $order);
    }
}