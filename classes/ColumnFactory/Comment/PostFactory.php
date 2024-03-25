<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactory\PostProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Comment\Property;
use AC\Setting\Formatter\MapToId;
use AC\Setting\FormatterCollection;

class PostFactory extends ColumnFactory
{

    private $post_property;

    private $post_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        PostProperty $post_property,
        PostLink $post_link
    ) {
        parent::__construct($component_factory_registry);

        $this->post_property = $post_property;
        $this->post_link = $post_link;
    }

    protected function get_label(): string
    {
        return __('Post', 'codepress-admin-columns');
    }

    protected function add_component_factories(): void
    {
        $this->add_component_factory($this->post_property);
        $this->add_component_factory($this->post_link);

        parent::add_component_factories();
    }

    public function get_type(): string
    {
        return 'column-post';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new MapToId(new Property('comment_post_ID')));

        return parent::get_formatters($components, $config, $formatters);
    }
}