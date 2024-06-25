<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Exception\ValueNotFoundException;
use AC\Sanitize\Kses;
use AC\Setting\CollectionFormatter;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

// TODO not a factory
class ManageValueFactory
{

    public function render(int $id, FormatterCollection $formatters): string
    {
        $value = new Value($id);

        // TODO
        //        if ($this->formatters->count() === 0) {
        //            return $fallback_value;
        //        }

        try {
            // TODO abstract to its own class
            foreach ($formatters as $formatter) {
                if ($formatter instanceof Formatter) {
                    if ($value instanceof Value) {
                        $value = $formatter->format($value);

                        continue;
                    }

                    if ($value instanceof ValueCollection) {
                        $collection = new ValueCollection($value->get_id());

                        foreach ($value as $item) {
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
            $value = new Value($id, '&ndash;');
        }

        if ($value instanceof ValueCollection) {
            $formatter = new Formatter\Collection\Separator();
            $value = $formatter->format($value);
        }

        if ('' === (string)$value) {
            $value = $value->with_value('&ndash;');
        }

        // TODO used by filter
        $column = null;
        $list_screen = null;

        if (is_scalar($value->get_value())
            // TODO
            && apply_filters('ac/column/value/sanitize', true, $column, $id, $list_screen)
        ) {
            $value = $value->with_value((new Kses())->sanitize((string)$value));
        }

        // You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
        // TODO
        //        if ($column->is_original() && ac_helper()->string->is_empty($value)) {
        //            return $fallback_value;
        //        }

        return (string)$value;

        // TODO re-apply filter
        //return (string)apply_filters('ac/column/value', (string)$value, $id, $column, $list_screen);
    }

}