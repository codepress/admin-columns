<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\ModifiedDate;

class LastModifiedFactory extends ColumnFactory
{

    private Date $date_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        Date $date_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->date_factory = $date_factory;
    }

    public function get_column_type(): string
    {
        return 'column-modified';
    }

    public function get_label(): string
    {
        return __('Last Modified', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->date_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new ModifiedDate());

        return $formatters;
    }

}