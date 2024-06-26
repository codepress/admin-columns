<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

abstract class CollectionAware implements Formatter
{

    public function format(Value $value): Value
    {
        if ($value->get_value() instanceof ValueCollection) {
            $collection = new ValueCollection();

            foreach ($value->get_value() as $item) {
                $collection->add($this->format_single($item));
            }

            return $value->with_value($collection);
        }

        return $value->with_value(
            $this->format_single($value)
        );
    }

    abstract protected function format_single(Value $value): Value;

}