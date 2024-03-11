<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\DateFormat;

use AC\Setting\ComponentFactory;
use AC\Setting\Control\OptionCollection;

class Date extends ComponentFactory\DateFormat
{

    protected function get_date_options(): OptionCollection
    {
        $options = [
            'diff'       => __('Time Difference', 'codepress-admin-columns'),
            'wp_default' => __('WordPress Date Format', 'codepress-admin-columns'),
        ];

        $formats = [
            'j F Y',
            'Y-m-d',
            'm/d/Y',
            'd/m/Y',
        ];

        foreach ($formats as $format) {
            $options[$format] = wp_date($format);
        }

        return OptionCollection::from_array($options);
    }

}