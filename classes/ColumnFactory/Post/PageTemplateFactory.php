<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\PageTemplate;

class PageTemplateFactory extends ColumnFactory
{

    private $post_type;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        string $post_type
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->post_type = $post_type;
    }

    public function get_type(): string
    {
        return 'column-page_template';
    }

    protected function get_label(): string
    {
        return __('Page Template', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->add(new PageTemplate($this->post_type));
    }

}