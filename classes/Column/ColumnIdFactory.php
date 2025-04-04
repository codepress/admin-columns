<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Setting\Config;
use AC\Type\ColumnId;

final class ColumnIdFactory
{

    public static function createFromConfig(Config $config): ColumnId
    {
        $id = (string)$config->get('name');

        if (ColumnId::is_valid_id($id)) {
            return new ColumnId($id);
        }

        return self::create();
    }

    public static function create(): ColumnId
    {
        return new ColumnId(uniqid());
    }

}