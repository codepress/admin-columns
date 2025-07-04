<?php

declare(strict_types=1);

namespace AC\Plugin\Install;

use AC;

final class Database implements AC\Plugin\Install
{

    private AC\Storage\Table $table;

    public function __construct(AC\Storage\Table $table)
    {
        $this->table = $table;
    }

    public function install(): void
    {
        if ( ! $this->table->exists()) {
            $this->table->create();
        }
    }

}