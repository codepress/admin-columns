<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\CollectionFormatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class Count implements CollectionFormatter
{

    public function format(ValueCollection $collection)
    {
        $count = count($collection);

        if ($count === 0) {
            throw new ValueNotFoundException('');
        }

        return new Value(count($collection));
    }

}