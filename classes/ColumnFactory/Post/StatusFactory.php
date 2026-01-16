<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PostStatusIcon;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class StatusFactory extends BaseColumnFactory
{

    private PostStatusIcon $post_status_icon;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        PostStatusIcon $post_status_icon
    ) {
        parent::__construct($default_settings_builder);

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->post_status_icon->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new AC\Formatter\Post\PostStatus());

        return $formatters;
    }

}