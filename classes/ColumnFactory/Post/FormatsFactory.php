<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UseIcon;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FormatsFactory extends BaseColumnFactory
{

    private UseIcon $post_format_icon_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        UseIcon $post_format_icon_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->post_format_icon_factory = $post_format_icon_factory;
    }

    public function get_column_type(): string
    {
        return 'column-post_formats';
    }

    public function get_label(): string
    {
        return __('Post Format', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->post_format_icon_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config)
                            ->prepend(new Formatter\Post\PostFormat());

        if ('on' === $config->get('use_icon')) {
            $formatters->add(new Formatter\Post\PostFormatIcon());
        } else {
            $formatters->add(new Formatter\Post\PostFormatLabel());
        }

        return $formatters;
    }

}