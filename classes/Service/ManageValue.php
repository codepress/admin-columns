<?php

declare(strict_types=1);

namespace AC\Service;

use AC\ListScreen;
use AC\Registerable;
use AC\Table\ManageValueFactory\Aggregate;
use AC\TableScreen;

// TODO Proof-of-concept
class ManageValue implements Registerable
{

    private Aggregate $aggregate;

    public function __construct(Aggregate $aggregate)
    {
        $this->aggregate = $aggregate;
    }

    public function register(): void
    {
        add_action('ac/table/list_screen', [$this, 'handle'], 10, 2);
    }

    public function handle(ListScreen $list_screen, TableScreen $table_screen): void
    {
        $service = $this->aggregate->create($table_screen, $list_screen->get_columns());

        if ($service) {
            $service->register();
        }
    }

}