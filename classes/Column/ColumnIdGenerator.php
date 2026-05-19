<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Type\ColumnId;
use AC\Type\KeyGenerator;

final class ColumnIdGenerator extends KeyGenerator
{

    public function generate(): ColumnId
    {
        return new ColumnId($this->generate_raw());
    }

}