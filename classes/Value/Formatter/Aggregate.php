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
        try {
            foreach ($this->formatters as $formatter) {
                if ($formatter instanceof Formatter) {
                    if ($value instanceof Value) {
                        $value = $formatter->format($value);

                        continue;
                    }

                    if ($value instanceof ValueCollection) {
                        $collection = new ValueCollection($value->get_id());

                        foreach ($value as $item) {
                            $_value = $formatter->format($item);

                            if ($_value instanceof Value) {
                                $collection->add($_value);
                            }
                        }

                        $value = $collection;
                    }
                }

                if ($formatter instanceof CollectionFormatter && $value instanceof ValueCollection) {
                    $value = $formatter->format($value);
                }
            }
        } catch (ValueNotFoundException $e) {
            // TODO maybe continue when looping over a ValueCollection...
            // TODO for example when you need total of all image sizes, but one image URL is not local and does not have a valid image size
            return new Value($value->get_id(), '');
        }

        return $value;
    }

}