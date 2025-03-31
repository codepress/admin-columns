<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PostLink;
use AC\Setting\ComponentFactory\PostProperty;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;
use AC\Value\Formatter\MapToId;

class PostFactory extends BaseColumnFactory
{

    private PostProperty $post_property;

    private PostLink $post_link;

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->post_property->create($config),
            $this->post_link->create($config),
        ]);
    }

    public function get_column_type(): string
    {
        return 'column-post';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new MapToId(new Property('comment_post_ID')));

        return $formatters;
    }

}