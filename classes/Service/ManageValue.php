<?php

declare(strict_types=1);

namespace AC\Service;

use AC\ListScreen;
use AC\Registerable;
use AC\Table\ManageValue\AggregateServiceFactory;
use AC\TableScreen;

class ManageValue implements Registerable
{

    private AggregateServiceFactory $factory;

    public function __construct(AggregateServiceFactory $factory)
    {
        $this->factory = $factory;
    }

    public function register(): void
    {
        add_action('ac/table/list_screen', [$this, 'handle'], 10, 2);
    }

    public function handle(ListScreen $list_screen, TableScreen $table_screen): void
    {
        $service = $this->factory->create($table_screen, $list_screen);

        if ($service) {
            $service->register();
        }
    }

}