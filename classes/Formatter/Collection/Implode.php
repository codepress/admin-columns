<?php

declare(strict_types=1);

namespace AC\Formatter\Collection;

use AC\CollectionFormatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Implode implements CollectionFormatter
{

    private string $separator;

    public function __construct(?string $separator = null)
    {
        $this->separator = $separator ?? ', ';
    }

    public function format(ValueCollection $collection): Value
    {
        $values = [];

        foreach ($collection as $item) {
            $value = (string)$item;

            if ('' === $value) {
                continue;
            }

            $values[] = $value;
        }

        return new Value(
            $collection->get_id(),
            implode($this->separator, $values)
        );
    }

}