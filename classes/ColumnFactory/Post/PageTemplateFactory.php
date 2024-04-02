<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\PageTemplate;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;

class PageTemplateFactory extends BaseColumnFactory
{

    private $post_type;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        PostTypeSlug $post_type
    ) {
        parent::__construct($component_factory_registry);

        $this->post_type = $post_type;
    }

    public function get_column_type(): string
    {
        return 'column-page_template';
    }

    protected function get_label(): string
    {
        return __('Page Template', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PageTemplate($this->post_type));

        return parent::get_formatters($components, $config, $formatters);
    }

}