<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\Property;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\LinkablePostProperty;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class PostFactory extends BaseColumnFactory
{

    private LinkablePostProperty $post_property;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        LinkablePostProperty $post_property
    ) {
        parent::__construct($default_settings_builder);

        $this->post_property = $post_property;
    }

    public function get_label(): string
    {
        return __('Post', 'codepress-admin-columns');
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->post_property->create($config),
        ]);
    }

    public function get_column_type(): string
    {
        return 'column-post';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new Property('comment_post_ID'));

        return $formatters;
    }

}