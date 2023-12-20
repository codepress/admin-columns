<?php

declare(strict_types=1);

namespace AC;

interface ColumnTypesFactory
{

    public function create(TableScreen $table_screen): ?ColumnTypeCollection;

}