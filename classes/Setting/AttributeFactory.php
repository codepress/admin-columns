<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Attribute;

final class AttributeFactory
{
    public static function create_refresh(): Attribute
    {
        return new Attribute('refresh', 'config');
    }

    public static function create_help_reference(string $reference): Attribute
    {
        return new Attribute('help-ref', $reference);
    }

    public static function create_required(): Attribute
    {
        return new Attribute('required', 'true');
    }

}
