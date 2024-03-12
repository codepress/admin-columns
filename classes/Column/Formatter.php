<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Setting;
use AC\Setting\Formatter\PositionAware;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class Formatter
{

    private $formatters;

    public function __construct(Setting\FormatterCollection $formatters = null)
    {
        if ($formatters === null) {
            $formatters = new Setting\FormatterCollection();
        }

        $this->set_formatters($formatters);
    }

    private function set_formatters(Setting\FormatterCollection $formatters): void
    {
        $this->formatters = [];

        foreach ($formatters as $formatter) {
            $position = 0;

            if ($formatter instanceof PositionAware) {
                $position = $formatter->get_position();
            }

            $this->formatters[$position][] = $formatter;
        }

        ksort($this->formatters);
    }

    public function format(int $id): string
    {
        $positioned_formatters = [];

        foreach ($this->formatters as $formatter) {
            $position = 0;

            if ($formatter instanceof PositionAware) {
                $position = $formatter->get_position();
            }

            $positioned_formatters[$position][] = $formatter;
        }

        ksort($positioned_formatters);
    }

    public function get_value(int $id): Value
    {
        return new Value($id);
    }

    protected function format_value(Value $value): Value
    {
        $positioned_formatters = [];

        foreach ($this->formatters as $formatter) {
            $position = 0;

            if ($formatter instanceof PositionAware) {
                $position = $formatter->get_position();
            }

            $positioned_formatters[$position][] = $formatter;
        }

        ksort($positioned_formatters);

        if ($value->get_value() instanceof ValueCollection) {
            $collection = new ValueCollection();

            iterator_apply($value->get_value(), [$collection, 'add']);

            $value = $value->with_value($collection);
        }

        return $value;
    }

}