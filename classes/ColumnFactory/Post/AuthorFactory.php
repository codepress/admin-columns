<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\UserFactory;

class AuthorFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        UserFactory $user_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($user_factory);
        $this->add_component_factory($before_after_factory);
    }

    public function get_type(): string
    {
        return 'column-author_name';
    }

    protected function get_label(): string
    {
        return __('Author', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Formatter\Post\Author());
    }

}
