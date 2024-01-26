<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;

class DateFormat extends Settings\Column
{

    use SettingTrait;

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    public function __construct(Column $column, Specification $specification)
    {
        $this->name = 'date_save_format';
        $this->label = __('Date Save Format', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
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

        parent::__construct($column, $specification);
    }

}