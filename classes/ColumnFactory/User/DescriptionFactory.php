<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\WordLimitFactory;

class DescriptionFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        WordLimitFactory $word_limit_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($word_limit_factory);
        $this->add_component_factory($before_after_factory);
    }

    protected function get_label(): string
    {
        return __('Description', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_description';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\User\Meta('description'));
    }

}