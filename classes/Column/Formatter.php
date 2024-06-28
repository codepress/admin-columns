<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Setting;
use AC\Setting\Formatter\PositionAware;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Formatter
{

    /**
     * @var Setting\FormatterCollection
     */
    private $formatters;

    public function __construct(Setting\FormatterCollection $formatters = null)
    {
        if ($formatters === null) {
            $formatters = new Setting\FormatterCollection();
        }

        $this->formatters = $formatters;
    }

    public function format(int $id): string
    {
        $formatters = [];

        foreach ($this->formatters as $formatter) {
            $position = 0;

            if ($formatter instanceof PositionAware) {
                $position = $formatter->get_position();
            }

            $formatters[$position][] = $formatter;
        }

        ksort($formatters);

        $formatters = new Setting\FormatterCollection(array_merge(...$formatters));

        $value = $this->get_value($id);

        foreach ($formatters as $formatter) {
            if ($value->get_value() instanceof ValueCollection) {
                $collection = new ValueCollection();

                iterator_apply($value->get_value(), static function ($value) use ($collection, $formatter) {
                    $collection->add($formatter->format($value));
                });

                $value = $value->with_value($collection);
            } else {
                $value = $formatter->format($value);
            }
        }

        return (string)$value;
    }

    public function get_value(int $id): Value
    {
        return new Value($id);
    }

}