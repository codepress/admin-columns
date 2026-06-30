<?php

declare(strict_types=1);

namespace AC\Helper;

/**
 * @deprecated 7.0.14 Use {@see Dashicon} instead. Will be removed in 7.1.
 */
class Icon extends Creatable
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

        return (new Dashicon(
            (string)$data->icon,
            (string)$data->class,
            '' !== (string)$data->title ? (string)$data->title : null,
            is_string($data->tooltip) && '' !== $data->tooltip ? $data->tooltip : null
        ))->render();
    }

    public function yes(?string $tooltip = null, ?string $title = null, ?string $class = null): string
    {
        return Dashicon::yes($tooltip, $title, $class ?: 'green')->render();
    }

    public function no(?string $tooltip = null, ?string $title = null, ?string $class = 'red'): string
    {
        return Dashicon::no($tooltip, $title, $class)->render();
    }

    public function yes_or_no(bool $is_true, ?string $tooltip = null): string
    {
        return Dashicon::yes_or_no($is_true, $tooltip)->render();
    }

}
