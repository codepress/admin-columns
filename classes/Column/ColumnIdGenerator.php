<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Setting\Config;
use AC\Type\ColumnId;
use AC\Type\KeyGenerator;

final class ColumnIdGenerator extends KeyGenerator
{

    public function from_config(Config $config): ColumnId
    {
        $id = (string)$config->get('name');

        if (ColumnId::is_valid_id($id)) {
            return new ColumnId($id);
        }

        return $this->generate();
    }

    public function generate(): ColumnId
    {
        return new ColumnId($this->generate_raw());
    }

}