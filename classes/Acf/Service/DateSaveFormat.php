<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf;
use AC\Registerable;

class DateSaveFormat implements Registerable
{

    public const DATE_FORMAT = 'Ymd';

    public function register(): void
    {
        if ( ! Acf::is_active()) {
            return;
        }

        add_filter('ac/column/date_save_format/options', [$this, 'date_save_format_options']);
    }

    public function date_save_format_options(array $options): array
    {
        $options[self::DATE_FORMAT] = sprintf(
            'ACF %s (%s)',
            __('Date Format', 'codepress-admin-columns'),
            'Ymd'
        );

        return $options;
    }

}