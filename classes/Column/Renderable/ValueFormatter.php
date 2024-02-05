<?php

declare(strict_types=1);

namespace AC\Column\Renderable;

use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

// TODO
class ValueFormatter
{

    private $settings;

    public function __construct(SettingCollection $settings)
    {
        $this->settings = $settings;
    }

    public function format($value, $id): string
    {
        $formatter = Formatter\Aggregate::from_settings($this->settings);

        if ($value instanceof ValueCollection) {
            $formatted_values = [];
            foreach ($value as $single_value) {
                $formatted_values[] = (string)$formatter->format(
                    new Value($single_value->get_id(), $single_value->get_value())
                );
            }

            // TODO modify separator
            return implode(', ', $formatted_values);
        }

        // TODO original column will render the id, because Value object turns value into and an id when value=null.

        return (string)$formatter->format(
            new Value($id, $value)
        );
    }

}