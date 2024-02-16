<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\PostFactory;
use AC\Settings\Column\PostLinkFactory;
use AC\Settings\Column\PostStatusFactory;
use AC\Settings\Column\PostTypeFactory;

class LastPostFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        PostTypeFactory $post_type_factory,
        PostStatusFactory $post_status_factory,
        PostFactory $post_factory,
        PostLinkFactory $post_link_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($post_type_factory);
        $this->add_component_factory($post_status_factory);
        $this->add_component_factory($post_factory);
        $this->add_component_factory($post_link_factory);
    }

    protected function get_label(): string
    {
        return __('Last Post', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-last_post';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        $post_type = $config->has('post_type') ? (array)$config->get('post_type') : null;
        $post_status = $config->has('post_status') ? (array)$config->get('post_status') : null;

        return parent::create_formatter_builder($components, $config)->prepend(
            new Formatter\User\LastPost($post_type, $post_status)
        );
    }

}