<?php

declare(strict_types=1);

namespace AC;

interface DefaultColumnHandler
{

    public function handle(TableScreen $table_screen, ColumnCollection $default_columns): ColumnCollection;

}