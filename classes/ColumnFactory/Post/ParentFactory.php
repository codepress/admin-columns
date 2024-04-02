<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactory\PostProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\PostParent;
use AC\Setting\FormatterCollection;

class ParentFactory extends BaseColumnFactory
{

    private $post_factory;

    private $post_link_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        PostProperty $post_factory,
        PostLink $post_link_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->post_factory = $post_factory;
        $this->post_link_factory = $post_link_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->post_factory);
        $this->add_component_factory($this->post_link_factory);
    }

    public function get_column_type(): string
    {
        return 'column-parent';
    }

    protected function get_label(): string
    {
        return __('Parent', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PostParent());

        return parent::get_formatters($components, $config, $formatters);
    }

}