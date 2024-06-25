<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Attribute;

final class AttributeFactory
{

    public static function create_refresh()
    {
        return new Attribute('refresh', 'config');
    }

}