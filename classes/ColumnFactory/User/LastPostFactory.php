<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class LastPostFactory extends BaseColumnFactory
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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $post_type = $config->has('post_type') ? (array)$config->get('post_type') : null;
        $post_status = $config->has('post_status') ? (array)$config->get('post_status') : null;
        $formatters->prepend(new AC\Value\Formatter\User\LastPost($post_type, $post_status));
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_type);
        $factories->add($this->post_status);
        $factories->add($this->post_property);
        $factories->add($this->post_link);
    }

}