<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter\Post\PageTemplate;

class PageTemplateFactory extends ColumnFactory
{

    private PostTypeSlug $post_type;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        PostTypeSlug $post_type
    ) {
        parent::__construct($base_settings_builder);

        $this->post_type = $post_type;
    }

    public function get_column_type(): string
    {
        return 'column-page_template';
    }

    public function get_label(): string
    {
        return __('Page Template', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new PageTemplate($this->post_type));

        return $formatters;
    }

}