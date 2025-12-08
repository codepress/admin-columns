<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\CollectionFormatter;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\Type\Value;
use AC\Type\ValueCollection;

final class Aggregate implements Formatter
{

    private FormatterCollection $formatters;

    public function __construct(FormatterCollection $formatters)
    {
        $this->formatters = $formatters;
    }

    public static function create(array $formatters): self
    {
        return new self(new FormatterCollection($formatters));
    }

    public function format(Value $value)
    {
        foreach ($this->formatters as $formatter) {
            if ($formatter instanceof Formatter) {
                if ($value instanceof Value) {
                    try {
                        $value = $formatter->format($value);
                    } catch (ValueNotFoundException $e) {
                        // Return empty value
                        return new Value($value->get_id(), '');
                    }

                    continue;
                }

                if ($value instanceof ValueCollection) {
                    $collection = new ValueCollection($value->get_id());

                    foreach ($value as $item) {
                        try {
                            $_value = $formatter->format($item);
                        } catch (ValueNotFoundException $e) {
                            // Skip non-Value instances and continue with next item in the collection
                            continue;
                        }

                        if ($_value instanceof Value) {
                            $collection->add($_value);
                        }
                    }

                    $value = $collection;
                }
            }

            if ($formatter instanceof CollectionFormatter && $value instanceof ValueCollection) {
                try {
                    $value = $formatter->format($value);
                } catch (ValueNotFoundException $e) {
                    // Return empty value
                    return new Value($value->get_id(), '');
                }
            }
        }

        return $value;
    }

}