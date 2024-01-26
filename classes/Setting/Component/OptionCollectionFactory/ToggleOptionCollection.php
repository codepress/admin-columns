<?php

declare(strict_types=1);

namespace AC\Setting\Component\OptionCollectionFactory;

use AC\Setting\Component\OptionCollection;
use AC\Setting\Component\OptionCollectionFactory;

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