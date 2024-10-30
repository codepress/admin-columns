<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;
use AC\Request;
use AC\TableScreen;
use AC\TableScreen\TableRowsFactory\Aggregate;

class TableRows implements Registerable
{

    private Aggregate $factory;

    public function __construct(Aggregate $factory)
    {
        $this->factory = $factory;
    }

    public function register(): void
    {
        add_action('ac/table/screen', [$this, 'handle']);
    }

    public function handle(TableScreen $table_screen): void
    {
        $table_rows = $this->factory->create($table_screen);

        if ($table_rows && $table_rows->is_request(new Request())) {
            $table_rows->register();
        }
    }

}