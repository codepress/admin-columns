<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactoryRegistry;

class IdFactory extends ColumnFactory
{

    private $before_after_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->before_after_factory = $before_after_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->before_after_factory);
    }

    protected function add_common_component_factories(): void
    {
        $this->add_component_factory($this->before_after_factory);
    }

    public function get_type(): string
    {
        return 'column-postid';
    }

    protected function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

}