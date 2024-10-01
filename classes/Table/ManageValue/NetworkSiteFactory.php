<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Registerable;
use AC\Table\ManageValueFactory;
use AC\TableScreen;
use LogicException;

// TODO move to PRO
class NetworkSiteFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof \ACP\TableScreen\NetworkSite;
    }

    public function create(TableScreen $table_screen, Column $column): Registerable
    {
        if ( ! $this->can_create($table_screen)) {
            throw new LogicException('Invalid table screen.');
        }

        return new NetworkSite($column);
    }

}