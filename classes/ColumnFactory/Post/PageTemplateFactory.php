<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\PageTemplate;
use AC\FormatterCollection;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Type\PostTypeSlug;

class PageTemplateFactory extends BaseColumnFactory
{

    private PostTypeSlug $post_type;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        PostTypeSlug $post_type
    ) {
        parent::__construct($default_settings_builder);

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