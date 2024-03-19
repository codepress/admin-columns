<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class DateSaveFormat extends Builder
{

    public const NAME = 'comment_status';

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    protected function get_label(Config $config): ?string
    {
        return __('Date Save Format', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'date_save_format',
            OptionCollection::from_array([
                self::FORMAT_DATE           => sprintf(
                    '%s (%s)',
                    __('Date', 'codepress-admin-columns'),
                    'Y-m-d'
                ),
                self::FORMAT_DATETIME       => __('Datetime (ISO)', 'codepress-admin-columns'),
                self::FORMAT_UNIX_TIMESTAMP => __('Timestamp', 'codepress-admin-columns'),
            ]),
            (string)$config->get('date_save_format') ?: self::FORMAT_DATE
        );
    }

}