<?php

declare(strict_types=1);

namespace AC\Setting\Control\OptionCollectionFactory;

use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\OptionCollectionFactory;

final class ToggleOptionCollection implements OptionCollectionFactory
{

    public function create(): OptionCollection
    {
        return OptionCollection::from_array([
            'on',
            'off',
        ], false);
    }

}