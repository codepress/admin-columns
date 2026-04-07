<?php

declare(strict_types=1);

namespace AC\DefaultColumnHandler;

use AC\ColumnCollection;
use AC\DefaultColumnHandler;
use AC\TableScreen;

final class Aggregate implements DefaultColumnHandler
{

    /**
     * @var DefaultColumnHandler[]
     */
    private array $handlers = [];

    public function add(DefaultColumnHandler $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function handle(TableScreen $table_screen, ColumnCollection $default_columns): ColumnCollection
    {
        foreach ($this->handlers as $handler) {
            $default_columns = $handler->handle($table_screen, $default_columns);
        }

        return $default_columns;
    }

}