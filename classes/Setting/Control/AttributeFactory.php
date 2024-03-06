<?php

declare(strict_types=1);

namespace AC\Setting\Control;

use AC\Setting\Type\Attribute;

final class AttributeFactory
{

    public static function create_data(string $name, string $value): Attribute
    {
        return new Attribute('data-' . $name, $value);
    }

}