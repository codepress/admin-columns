<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

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
                $value = $formatter->format($value);
            }
        }

        return $value;
    }

}