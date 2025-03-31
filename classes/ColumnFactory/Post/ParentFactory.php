<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactory\PostProperty;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\PostParentId;

class ParentFactory extends BaseColumnFactory
{

    private $post_factory;

    private $post_link_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        PostProperty $post_factory,
        PostLink $post_link_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->post_factory = $post_factory;
        $this->post_link_factory = $post_link_factory;
    }

    public function get_column_type(): string
    {
        return 'column-parent';
    }

    public function get_label(): string
    {
        return __('Parent', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_factory);
        $factories->add($this->post_link_factory);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new PostParentId());

        return $formatters;
    }

}