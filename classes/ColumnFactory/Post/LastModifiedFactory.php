<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\ModifiedDate;

class LastModifiedFactory extends ColumnFactory
{

    private $date_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        Date $date_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->date_factory = $date_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->date_factory);
    }

    public function get_type(): string
    {
        return 'column-modified';
    }

    protected function get_label(): string
    {
        return __('Last Modified', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components, Config $config): array
    {
        return array_merge([new Formatter\Post\ModifiedDate()], parent::get_formatters($components, $config))
    }

}