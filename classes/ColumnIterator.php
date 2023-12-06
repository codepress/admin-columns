<?php

namespace AC;

use Countable;
use Iterator;

interface ColumnIterator extends Iterator, Countable
{

    public function exists(string $name): bool;

    public function get(string $name): Column;

    public function keys(): array;

}