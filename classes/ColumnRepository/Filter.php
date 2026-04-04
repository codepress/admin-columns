<?php

declare(strict_types=1);

namespace AC\ColumnRepository;

use AC\ColumnCollection;
use AC\ColumnIterator;

interface Filter
{

    public function filter(ColumnIterator $columns): ColumnCollection;

}