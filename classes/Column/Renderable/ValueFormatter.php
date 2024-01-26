<?php

declare(strict_types=1);

namespace AC\Column\Renderable;

use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class ValueFormatter
{

    private $settings;

    private $options;

    private $separator;

    public function __construct(SettingCollection $settings, ArrayImmutable $options, string $separator = '')
    {
        $this->settings = $settings;
        $this->options = $options;
        $this->separator = $separator;
    }

    public function format($value, $id): string
    {
        $formatter = Formatter\Aggregate::from_settings($this->settings);

        if ($value instanceof ValueCollection) {
            $formatted_values = [];
            foreach ($value as $single_value) {
                $formatted_values[] = (string)$formatter->format(
                    new Value($single_value->get_id(), $single_value->get_value()),
                    $this->options
                );
            }

            return implode($this->separator, $formatted_values);
        }

        // TODO original column will render the id, because Value object turns value into and an id when value=null.

        return (string)$formatter->format(
            new Value($id, $value),
            $this->options
        );
    }

}