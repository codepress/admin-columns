<?php

namespace AC\Helper;

class Icon
{

    public function dashicon(array $args = []): string
    {
        $defaults = [
            'icon'    => '',
            'title'   => '',
            'class'   => '',
            'tooltip' => '',
        ];

        $data = (object)wp_parse_args($args, $defaults);

        $class = 'dashicons dashicons-' . $data->icon;

        if ($data->class) {
            $class .= ' ' . trim($data->class);
        }

        $attributes = [];

        if ($data->title) {
            $attributes[] = sprintf('title="%s"', esc_attr($data->title));
        }

        if ($data->tooltip && is_string($data->tooltip)) {
            $attributes[] = ac_helper()->html->get_tooltip_attr($data->tooltip);
        }

        return sprintf(
            '<span class="%s" %s></span>',
            esc_attr($class),
            implode(' ', $attributes)
        );
    }

    public function yes(string $tooltip = null, string $title = null, string $class = 'green'): string
    {
        if (null === $title) {
            $title = __('Yes');
        }

        return $this->dashicon([
            'icon'    => 'yes',
            'class'   => $class,
            'title'   => $title,
            'tooltip' => $tooltip,
        ]);
    }

    public function no(string $tooltip = null, string $title = null, string $class = 'red'): string
    {
        if (null === $title) {
            $title = __('No');
        }

        return $this->dashicon([
            'icon'    => 'no-alt',
            'class'   => $class,
            'title'   => $title,
            'tooltip' => $tooltip,
        ]);
    }

    // TODO check usages for `bool`
    public function yes_or_no(bool $is_true, string $tooltip = null): string
    {
        return $is_true
            ? $this->yes($tooltip)
            : $this->no($tooltip);
    }

}