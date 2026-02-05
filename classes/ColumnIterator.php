<?php

declare(strict_types=1);

namespace AC;

use Countable;
use Iterator;

interface ColumnIterator extends Iterator, Countable
{

    public function first(): ?Column;

}