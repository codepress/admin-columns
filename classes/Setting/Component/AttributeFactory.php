<?php

declare(strict_types=1);

namespace AC\Setting\Component;

use AC\Setting\Component\Type\Attribute;

final class AttributeFactory
{

    public static function create_class(string $value): Attribute
    {
        return new Attribute('class', $value);
    }

    public static function create_data(string $name, string $value): Attribute
    {
        return new Attribute('data-' . $name, $value);
    }

    // TODO David

    public static function create_label(string $value): Attribute
    {
        return new Attribute('label', $value);
    }

    // TODO David
    public static function create_description(string $value): Attribute
    {
        return new Attribute('description', $value);
    }

}