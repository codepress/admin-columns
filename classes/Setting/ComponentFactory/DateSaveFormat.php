<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class DateSaveFormat extends BaseComponentFactory
{

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    protected function get_label(Config $config): ?string
    {
        return __('Stored Date Format', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This is the format in which dates are stored and saved (also used for sorting, filtering, and editing).', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        $options = [
            self::FORMAT_DATE           => sprintf(
                '%s (%s)',
                __('Date', 'codepress-admin-columns'),
                'Y-m-d'
            ),
            self::FORMAT_DATETIME       => sprintf(
                '%s (%s)',
                __('Datetime', 'codepress-admin-columns'),
                'Y-m-d H:i:s'
            ),
            self::FORMAT_UNIX_TIMESTAMP => sprintf(
                '%s (%s)',
                __('Timestamp', 'codepress-admin-columns'),
                __('seconds', 'codepress-admin-columns')
            ),
        ];

        $options = (array)apply_filters('ac/column/date_save_format/options', $options);

        return OptionFactory::create_select(
            'date_save_format',
            OptionCollection::from_array($options),
            (string)$config->get('date_save_format') ?: self::FORMAT_DATE
        );
    }

}