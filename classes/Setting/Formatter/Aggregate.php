<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

final class Aggregate implements Formatter
{

    /**
     * @var Formatter[]
     */
    private $data = [];

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
    }

    public static function from_settings(SettingCollection $settings): self
    {
        $formatters = [];

        foreach ($settings as $setting) {
            if ($setting instanceof Formatter) {
                $formatters[] = $setting;
            }
        }

        return new self($formatters);
    }

    public function add(Formatter $formatter): self
    {
        $this->data[] = $formatter;

        return $this;
    }

    public function prepend(Formatter $formatter): self
    {
        array_unshift($this->data, $formatter);

        return $this;
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