<?php

declare(strict_types=1);

namespace AC;

use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use AC\Type\ValueCollection;

interface CollectionFormatter
{

    /**
     * @throws ValueNotFoundException
     */
    public function format(ValueCollection $collection): Value;

}