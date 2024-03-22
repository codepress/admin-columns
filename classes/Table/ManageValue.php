<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Exception\ValueNotFoundException;
use AC\ListScreen;
use AC\Registerable;
use AC\Sanitize\Kses;
use AC\Setting\CollectionFormatter;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

abstract class ManageValue implements Registerable
{

    private $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    // TODO
    public function render_cell(string $column_name, $id, string $fallback_value = null): ?string
    {
        $column = $this->list_screen->get_column($column_name);

        if ( ! $column) {
            return $fallback_value;
        }

        $value = new Value($id);

        try {
            foreach ($column->get_formatters() as $formatter) {
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

        if (is_scalar($value->get_value())
            && apply_filters('ac/column/value/sanitize', true, $column, $id, $this->list_screen)) {
            $value = $value->with_value((new Kses())->sanitize((string)$value));
        }

        // You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
        if ($column->is_original() && ac_helper()->string->is_empty($value)) {
            return $fallback_value;
        }

        return (string)apply_filters('ac/column/value', (string)$value, $id, $column, $this->list_screen);
    }
}