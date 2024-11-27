<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\PostTitle;
use AC\Value\Formatter\Wrapper;

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

    public function get_column_type(): string
    {
        return 'column-title_raw';
    }

    public function get_label(): string
    {
        return __('Title Only', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->character_limit_factory);
        $factories->add($this->post_link_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new PostTitle());
        $formatters->add(new Wrapper('<span class="row-title">', '</span>'));
    }

}