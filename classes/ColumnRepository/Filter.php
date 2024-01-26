<?php

namespace AC\ColumnRepository;

use AC\ColumnCollection;
use AC\ColumnIterator;

interface Filter
{

    public function filter(ColumnIterator $columns): ColumnCollection;

}