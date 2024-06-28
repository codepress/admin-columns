<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\DateFormat;

use AC\Setting\ComponentFactory;
use AC\Setting\Control\OptionCollection;

class Time extends ComponentFactory\DateFormat
{

    protected function get_default_option(): string
    {
        return 'wp_default';
    }

    protected function get_date_options(): OptionCollection
    {
        $options = [
            'diff'       => __('Time Difference', 'codepress-admin-columns'),
            'wp_default' => __('WordPress Time Format', 'codepress-admin-columns'),
        ];

        $formats = [
            'H:i:s',
            'g:i A',
        ];

        foreach ($formats as $format) {
            $options[$format] = wp_date($format);
        }

        return OptionCollection::from_array($options);
    }

}