<?php

declare(strict_types=1);

namespace AC\Setting\OptionCollectionFactory;

use AC\Setting\OptionCollection;
use AC\Setting\OptionCollectionFactory;
use AC\Setting\Type\Option;

final class ToggleOptionCollection implements OptionCollectionFactory
{

    public function create(): OptionCollection
    {
        return new OptionCollection([
            Option::from_value('on'),
            Option::from_value('off'),
        ]);
    }

}