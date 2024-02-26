<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

final class Aggregate implements Formatter
{

    /**
     * @var Formatter[]
     */
    private $data;

    public function __construct(array $formatters = [])
    {
        $this->data = $formatters;
    }

    public function format(Value $value): Value
    {
        $positioned_formatters = [];

        foreach ($this->data as $formatter) {
            $position = 0;

            if ($formatter instanceof PositionAware) {
                $position = $formatter->get_position();
            }

            $positioned_formatters[$position][] = $formatter;
        }

        ksort($positioned_formatters);

        foreach ($positioned_formatters as $formatters) {
            foreach ($formatters as $formatter) {
                $value = $this->format_collection(
                    $value,
                    $formatter
                );
            }
        }

        return $value;
    }

    private function format_collection(Value $value, Formatter $formatter): Value
    {
        $collection = $value->get_value();

        if ( ! $collection instanceof ValueCollection) {
            return $formatter->format($value);
        }

        $values = new ValueCollection();

        foreach ($collection as $_value) {
            $values->add($formatter->format($_value));
        }

        return $value->with_value(
            $values
        );
    }

}