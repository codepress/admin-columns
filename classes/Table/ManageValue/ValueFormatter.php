<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Type\ColumnId;
use AC\Type\Value;

interface ValueFormatter
{

    public function format(ColumnId $id, Value $value): Value;

}