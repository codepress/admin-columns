<?php

declare(strict_types=1);

namespace AC\Setting\Control\OptionCollectionFactory;

use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\OptionCollectionFactory;

final class ToggleOptionCollection implements OptionCollectionFactory
{

    public const ON = 'on';
    public const OFF = 'off';

    public function create(): OptionCollection
    {
        return OptionCollection::from_array([
            self::ON,
            self::OFF,
        ], false);
    }

}