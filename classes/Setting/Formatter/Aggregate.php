<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

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

    private static function get_formatters(SettingCollection $settings): array
    {
        $formatters = [];

        foreach ($settings as $setting) {
            if ($setting instanceof Formatter) {
                $formatters[] = $setting;
            }
        }

        return $formatters;
    }

    public static function from_settings(SettingCollection $settings): self
    {
        return new self(self::get_formatters($settings));
    }

    // TODO David check if this is ever required, otherwise remove and stick to static factory
    public function with_settings(SettingCollection $settings)
    {
        $formatters = array_merge(
            $this->data,
            self::get_formatters($settings)
        );

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
                $value = $formatter->format($value, $options);
            }
        }

        return $value;
    }

}