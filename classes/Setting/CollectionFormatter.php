<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Type\Value;

interface CollectionFormatter
{

    /**
     * @return Value|ValueCollection
     * @throws ValueNotFoundException
     */
    public function format(ValueCollection $collection);

}