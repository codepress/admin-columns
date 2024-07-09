<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;

class ActionsFactory extends BaseColumnFactory
{

    public function __construct(ComponentFactoryRegistry $component_factory_registry)
    {
        parent::__construct($component_factory_registry);

        $this->add_component_factory(new ComponentFactory\ActionIcons());
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