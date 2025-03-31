<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactory\PostProperty;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;
use AC\Value\Formatter\MapToId;

class PostFactory extends BaseColumnFactory
{

    private $post_property;

    private $post_link;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        PostProperty $post_property,
        PostLink $post_link
    ) {
        parent::__construct($base_settings_builder);

        $this->post_property = $post_property;
        $this->post_link = $post_link;
    }

    public function get_label(): string
    {
        return __('Post', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_property);
        $factories->add($this->post_link);
    }

    public function get_column_type(): string
    {
        return 'column-post';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new MapToId(new Property('comment_post_ID')));
    }

}