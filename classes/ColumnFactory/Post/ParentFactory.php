<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactory\PostProperty;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\PostParentId;

class ParentFactory extends ColumnFactory
{

    private PostProperty $post_factory;

    private PostLink $post_link_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        PostProperty $post_factory,
        PostLink $post_link_factory
    ) {
        parent::__construct($default_settings_builder);

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->post_factory->create($config),
            $this->post_link_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new PostParentId());

        return $formatters;
    }

}