<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
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
        $this->input = Input\Option\Single::create_select(
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


    //    protected function define_options()
    //    {
    //        return [
    //            'date_save_format' => self::FORMAT_DATE,
    //        ];
    //    }
    //
    //    public function create_view()
    //    {
    //        $select = $this->create_element('select')
    //                       ->set_options([
    //                           self::FORMAT_DATE           => sprintf(
    //                               '%s (%s)',
    //                               __('Date', 'codepress-admin-columns'),
    //                               'Y-m-d'
    //                           ),
    //                           self::FORMAT_DATETIME       => __('Datetime (ISO)', 'codepress-admin-columns'),
    //                           self::FORMAT_UNIX_TIMESTAMP => __('Timestamp', 'codepress-admin-columns'),
    //                       ]);
    //
    //        return new View([
    //            'label'   => __('Date Save Format', 'codepress-admin-columns'),
    //            'setting' => $select,
    //        ]);
    //    }
    //
    //    public function set_date_save_format($format): void
    //    {
    //        $this->date_save_format = $format;
    //    }
    //
    //    public function get_date_save_format(): string
    //    {
    //        return $this->date_save_format;
    //    }

}