<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\CollectionFormatter;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

final class Aggregate implements Formatter
{

    private $formatters;

    public function __construct(FormatterCollection $formatters)
    {
        $this->formatters = $formatters;
    }

    public function format(Value $value): Value
    {
        $formatted_value = $value;

        foreach ($this->formatters as $formatter) {
            if ($formatter instanceof Formatter) {
                if ($formatted_value instanceof Value) {
                    $formatted_value = $formatter->format($formatted_value);

                    continue;
                }

                if ($formatted_value instanceof ValueCollection) {
                    $collection = new ValueCollection($formatted_value->get_id());

                    foreach ($formatted_value as $item) {
                        $collection->add($formatter->format($item));
                    }

                    $formatted_value = $collection;
                }
            }

            if ($formatter instanceof CollectionFormatter && $formatted_value instanceof ValueCollection) {
                $formatted_value = $formatter->format($formatted_value);
            }
        }

        if ($formatted_value instanceof ValueCollection) {
            $formatter = new Formatter\Collection\Separator();
            $formatted_value = $value->with_value($formatter->format($formatted_value));
        }

        return $formatted_value;
    }

}