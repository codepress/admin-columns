<?php

namespace AC\Settings\Column;

use AC\Settings;

// TODO
class Time extends Settings\Column\DateTimeFormat
{

    public function create_view()
    {
        $view = parent::create_view();

        $view->set('label', __('Time Format', 'codepress-admin-columns'));
        $view->set('tooltip', __('This will determine how the time will be displayed.', 'codepress-admin-columns'));

        return $view;
    }

    protected function get_custom_format_options()
    {
        $options['wp_default'] = $this->get_default_html_label(__('WordPress Time Format', 'codepress-admin-columns'));

        $formats = [
            'H:i:s',
            'g:i A',
        ];

        foreach ($formats as $format) {
            $options[$format] = $this->get_html_label_from_date_format($format);
        }

        return $options;
    }

    protected function get_wp_default_format()
    {
        return get_option('time_format');
    }

}