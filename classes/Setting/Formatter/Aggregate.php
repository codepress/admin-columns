<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

// TODO David consider the factory methods in a trait, as it's basically just calling 'add' many times over
final class Aggregate implements Formatter
{

    /**
     * @var Formatter[][]
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

    public function with_settings(SettingCollection $settings)
    {
        $formatters = array_merge(...$this->data);

        foreach ($settings as $setting) {
            if ($setting instanceof Formatter) {
                $formatters[] = $setting;
            }
        }

        return new self($formatters);
    }

    public function add(Formatter $formatter): void
    {
        $position = 0;

        if ($formatter instanceof PositionAware) {
            $position = $formatter->get_position();
        }

        $this->data[$position][] = $formatter;
    }

    public function format(Value $value, array $options): Value
    {
        ksort($this->data);

        foreach ($this->data as $formatters) {
            foreach ($formatters as $formatter) {
                $value = $formatter->format($value, $options);
            }
        }

        return $value;
    }

}