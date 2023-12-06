<?php

namespace AC\ColumnRepository;

use AC\ColumnCollection;
use AC\ColumnIterator;

interface Sort
{

    public function sort(ColumnIterator $columns): ColumnCollection;

}