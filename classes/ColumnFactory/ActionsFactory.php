<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Message;

// TODO Allow only one
class ActionsFactory extends BaseColumnFactory
{

    public function __construct(ComponentFactoryRegistry $component_factory_registry)
    {
        parent::__construct($component_factory_registry);

        $this->add_component_factory(new ComponentFactory\ActionIcons());
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Message('<span></span>'));

        return parent::get_formatters($components, $config, $formatters);
    }

    public function get_label(): string
    {
        return __('Actions', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-actions';
    }

}