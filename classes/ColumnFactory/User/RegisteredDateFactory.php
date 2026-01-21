<?php

declare(strict_types=1);

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class RegisteredDateFactory extends BaseColumnFactory
{

    private Date $date_format;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        Date $date_format
    ) {
        parent::__construct($default_settings_builder);

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
            new AC\Formatter\User\Property('user_registered'),
            new AC\Formatter\Date\Timestamp(),
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