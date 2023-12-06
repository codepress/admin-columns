<?php

namespace AC;

use AC\ColumnRepository\Filter;
use AC\ColumnRepository\Sort;

interface ColumnRepository
{

    public function find_all(Filter $filter = null, Sort $sort = null): ColumnCollection;

    public function find(string $name): ?Column;

}