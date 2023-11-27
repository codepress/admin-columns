<?php

namespace AC\ColumnRepository;

use AC\ColumnCollection;

interface Filter
{

    public function filter(ColumnCollection $columns): ColumnCollection;

}