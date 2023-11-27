<?php

namespace AC\ColumnRepository;

use AC\ColumnCollection;

interface Sort
{

    public function sort(ColumnCollection $columns): ColumnCollection;

}