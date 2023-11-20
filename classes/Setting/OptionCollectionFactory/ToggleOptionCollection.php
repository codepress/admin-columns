<?php

declare(strict_types=1);

namespace AC\Setting\OptionCollectionFactory;

use AC\Setting\OptionCollection;
use AC\Setting\OptionCollectionFactory;

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