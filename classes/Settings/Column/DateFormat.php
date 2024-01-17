<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Settings;

class DateFormat extends Settings\Column
{

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    public function __construct(Specification $specification)
    {
        $input = Input\Option\Single::create_select(
            OptionCollection::from_array([
                self::FORMAT_DATE           => sprintf(
                    '%s (%s)',
                    __('Date', 'codepress-admin-columns'),
                    'Y-m-d'
                ),
                self::FORMAT_DATETIME       => __('Datetime (ISO)', 'codepress-admin-columns'),
                self::FORMAT_UNIX_TIMESTAMP => __('Timestamp', 'codepress-admin-columns'),
            ]),
            self::FORMAT_DATE
        );

        parent::__construct(
            'date_save_format',
            __('Date Save Format', 'codepress-admin-columns'),
            '',
            $input,
            $specification
        );
    }

}