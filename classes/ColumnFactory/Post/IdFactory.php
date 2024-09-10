<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;

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

    public function get_column_type(): string
    {
        return 'column-postid';
    }

    public function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        parent::add_component_factories($factories);

        $factories->add($this->before_after_factory);
    }

}