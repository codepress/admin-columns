<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\PostTitle;
use AC\Setting\FormatterCollection;

class TitleRawFactory extends BaseColumnFactory
{

    private $character_limit_factory;

    private $post_link_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        CharacterLimit $character_limit_factory,
        PostLink $post_link_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->character_limit_factory = $character_limit_factory;
        $this->post_link_factory = $post_link_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->character_limit_factory);
        $this->add_component_factory($this->post_link_factory);
    }

    public function get_type(): string
    {
        return 'column-title_raw';
    }

    protected function get_label(): string
    {
        return __('Title Only', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PostTitle());

        return parent::get_formatters($components, $config, $formatters);
    }

}