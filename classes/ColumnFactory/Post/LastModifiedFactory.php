<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\ModifiedDate;

class LastModifiedFactory extends BaseColumnFactory
{

    private $date_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        Date $date_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->date_factory = $date_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->date_factory);
    }

    public function get_column_type(): string
    {
        return 'column-modified';
    }

    public function get_label(): string
    {
        return __('Last Modified', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new ModifiedDate());

        return parent::get_formatters($components, $config, $formatters);
    }

}