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

    public static function create_readonly(): Attribute
    {
        return new Attribute('readonly', 'true');
    }

    // A multiple select keeps its list open while choosing, unless a setting asks for the opposite.
    public static function create_close_on_select(): Attribute
    {
        return new Attribute('close-on-select', 'true');
    }

}
