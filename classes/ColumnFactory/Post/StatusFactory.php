<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\PostStatusIcon;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class StatusFactory extends BaseColumnFactory
{

    private PostStatusIcon $post_status_icon;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        PostStatusIcon $post_status_icon
    ) {
        parent::__construct($base_settings_builder);

        $this->post_status_icon = $post_status_icon;
    }

    public function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-status';
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_status_icon);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new Formatter\Post\PostStatus());

        return $formatters;
    }

}