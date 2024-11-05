<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;
use AC\View;

class Color implements Formatter
{

    public function format(Value $value): Value
    {
        $color = (string)$value;

        if ($color !== '') {
            $view = new View([
                'background_color' => $color,
                'color'            => $this->get_contrast_color($color),
                'label'            => $color,
            ]);

            return $value->with_value(
                $view->set_template('column/color-box')->render()
            );
        }

        return $value;
    }

    private function get_contrast_color(string $hex): string
    {
        $rgb = $this->hex_to_rgb($hex);

        $contrast = ($rgb[0] * 0.299 + $rgb[1] * 0.587 + $rgb[2] * 0.114) < 186
            ? 'fff'
            : '333';

        return $this->hex_format($contrast, true);
    }

    private function hex_to_rgb(string $hex): array
    {
        $hex = $this->hex_format($hex);

        return sscanf($hex, '%2x%2x%2x') ?: [];
    }

    private function hex_format(string $hex, bool $prefix = false): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if ($prefix) {
            $hex = '#' . $hex;
        }

        return strtolower($hex);
    }

}