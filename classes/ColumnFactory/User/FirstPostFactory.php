<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FirstPostFactory extends BaseColumnFactory
{

    private ComponentFactory\PostType $post_type;

    private ComponentFactory\PostStatus $post_status;

    private ComponentFactory\PostProperty $post_property;

    private ComponentFactory\PostLink $post_link;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ComponentFactory\PostType $post_type,
        ComponentFactory\PostStatus $post_status,
        ComponentFactory\PostProperty $post_property,
        ComponentFactory\PostLink $post_link
    ) {
        parent::__construct($default_settings_builder);

        $this->post_type = $post_type;
        $this->post_status = $post_status;
        $this->post_property = $post_property;
        $this->post_link = $post_link;
    }

    public function get_label(): string
    {
        return __('First Post', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-first_post';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $post_type = $config->has('post_type') ? (array)$config->get('post_type', []) : null;
        $post_status = $config->has('post_status') ? (array)$config->get('post_status', []) : null;

        return parent::get_formatters($config)
                     ->prepend(new Formatter\User\FirstPost($post_type, $post_status));
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->post_type->create($config),
            $this->post_status->create($config),
            $this->post_property->create($config),
            $this->post_link->create($config),
        ]);
    }

}