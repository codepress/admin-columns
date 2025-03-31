<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\DatePublishFormatted;

class DatePublishFactory extends BaseColumnFactory
{

    private Date $date_factory;

    public function __construct(BaseSettingsBuilder $base_settings_builder, Date $date_factory)
    {
        parent::__construct($base_settings_builder);

        $this->date_factory = $date_factory;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->date_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->prepend(new DatePublishFormatted())
                     ->add(new DatePublishFormatted());
    }

    public function get_column_type(): string
    {
        return 'column-date_published';
    }

    public function get_label(): string
    {
        return __('Date Published', 'codepress-admin-columns');
    }

}