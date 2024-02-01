<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class DateFormat extends Settings\Column
{

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    public function __construct(string $date_format, Specification $specification)
    {
        parent::__construct(
            __('Date Save Format', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
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
                $date_format
            ),
            $specification
        );
    }

}