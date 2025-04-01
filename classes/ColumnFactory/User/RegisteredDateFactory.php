<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class RegisteredDateFactory extends ColumnFactory
{

    private Date $date_format;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        Date $date_format
    ) {
        parent::__construct($base_settings_builder);

        $this->date_format = $date_format;
    }

    public function get_label(): string
    {
        return __('Registered', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_registered';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new Formatter\User\Property('user_registered'),
            new Formatter\Timestamp(),
        ]);

        return $formatters->merge(parent::get_formatters($config));
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->date_format->create($config),
        ]);
    }

}