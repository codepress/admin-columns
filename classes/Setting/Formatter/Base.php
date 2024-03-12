<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

abstract class Base implements Formatter
{

    public function format(Value $value): Value
    {
        if ($value->get_value() instanceof ValueCollection) {
            $collection = new ValueCollection();

            iterator_apply($value->get_value(), function ($value) use ($collection) {
                $collection->add($this->format_single($value));
            });

            return $value->with_value($collection);
        }

        return $this->format_single($value);
    }

    abstract protected function format_single(Value $value): Value;

}