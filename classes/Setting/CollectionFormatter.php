<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use AC\Type\ValueCollection;

// TODO move to root
interface CollectionFormatter
{

    /**
     * @throws ValueNotFoundException
     */
    public function format(ValueCollection $collection): Value;

}