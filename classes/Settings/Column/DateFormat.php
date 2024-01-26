<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC\Column;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingTrait;
=======
use AC\Expression\Specification;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings;

class DateFormat extends Settings\Column
{

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    public function __construct(Specification $specification)
    {
<<<<<<< HEAD
        $this->name = 'date_save_format';
        $this->label = __('Date Save Format', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
=======
        $input = Input\Option\Single::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
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