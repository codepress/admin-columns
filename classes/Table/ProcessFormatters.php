<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Exception\ValueNotFoundException;
use AC\Setting\CollectionFormatter;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\Type\Value;
use AC\Type\ValueCollection;
use AC\Value\Formatter\Collection\Separator;

class ProcessFormatters
{

    private $formatters;

    private $empty_value;

    public function __construct(FormatterCollection $formatters, string $empty_value = '&ndash;')
    {
        $this->formatters = $formatters;
        $this->empty_value = $empty_value;
    }

    public function format(Value $value): Value
    {
        if (0 === $this->formatters->count()) {
            // TODO test
            return $value;
        }

        $id = $value->get_id();

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
                            // TODO
                            //                            $_value = $formatter->format($item);
                            //
                            //                            if ( ! $_value instanceof Value) {
                            //                                echo '<pre>';
                            //                                print_r($_value);
                            //                                echo '</pre>';
                            //                                exit;
                            //                            }

                            $collection->add($formatter->format($item));
                        }

                        $value = $collection;
                    }
                }

                if ($formatter instanceof CollectionFormatter && $value instanceof ValueCollection) {
                    $value = $formatter->format($value);
                }
            }
        } catch (ValueNotFoundException $e) {
            $value = new Value($id, $this->empty_value);
        }

        if ($value instanceof ValueCollection) {
            $value = (new Separator())->format($value);
        }

        if ($value->get_value() !== null && '' === (string)$value) {
            $value = $value->with_value($this->empty_value);
        }

        return $value;
    }

}