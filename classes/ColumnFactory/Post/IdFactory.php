<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;

class IdFactory extends BaseColumnFactory
{

    private $before_after_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->before_after_factory = $before_after_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->before_after_factory);
    }

    public function get_column_type(): string
    {
        return 'column-postid';
    }

    public function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

}