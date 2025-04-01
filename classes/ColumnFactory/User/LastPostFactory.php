<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class LastPostFactory extends ColumnFactory
{

    private ComponentFactory\PostType $post_type;

    private ComponentFactory\PostStatus $post_status;

    private ComponentFactory\PostProperty $post_property;

    private ComponentFactory\PostLink $post_link;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ComponentFactory\PostType $post_type,
        ComponentFactory\PostStatus $post_status,
        ComponentFactory\PostProperty $post_property,
        ComponentFactory\PostLink $post_link
    ) {
        parent::__construct($base_settings_builder);

        $this->post_type = $post_type;
        $this->post_status = $post_status;
        $this->post_property = $post_property;
        $this->post_link = $post_link;
    }

    public function get_label(): string
    {
        return __('Last Post', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-last_post';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $post_type = $config->has('post_type') ? (array)$config->get('post_type') : null;
        $post_status = $config->has('post_status') ? (array)$config->get('post_status') : null;

        return parent::get_formatters($config)
                     ->prepend(new AC\Value\Formatter\User\LastPost($post_type, $post_status));
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