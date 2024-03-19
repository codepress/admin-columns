<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\LastModifiedAuthor;

class LastModifiedAuthorFactory extends ColumnFactory
{

    private $user_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserProperty $user_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->user_factory = $user_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();
        
        $this->add_component_factory($this->user_factory);
    }

    public function get_type(): string
    {
        return 'column-last_modified_author';
    }

    protected function get_label(): string
    {
        return __('Last Modified Author', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new LastModifiedAuthor());
    }

}