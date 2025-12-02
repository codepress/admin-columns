<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Setting\Formatter;
use AC\Type\ColumnId;

interface RenderFactory
{

    public function create(ColumnId $columnId): ?Formatter;

}