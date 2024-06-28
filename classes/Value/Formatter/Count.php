<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\CollectionFormatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Count implements CollectionFormatter
{

    public function format(ValueCollection $collection)
    {
        $count = count($collection);

        if ($count === 0) {
            throw new ValueNotFoundException('');
        }

        return new Value($count);
    }

}