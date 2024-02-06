<?php

declare(strict_types=1);

namespace AC\Setting\Component;

use AC\Setting\Component\Type\Attribute;

final class AttributeFactory
{

    public static function create_data(string $name, string $value): Attribute
    {
        return new Attribute('data-' . $name, $value);
    }

    public static function create_label(string $value): Attribute
    {
        return new Attribute('label', $value);
    }

    public static function create_description(string $value): Attribute
    {
        return new Attribute('description', $value);
    }

}