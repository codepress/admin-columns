<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class ExcerptFactory extends BaseColumnFactory
{

    private $string_limit;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        StringLimit $string_limit
    ) {
        parent::__construct($component_factory_registry);

        $this->string_limit = $string_limit;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->string_limit);
    }

    protected function get_label(): string
    {
        return __('Excerpt', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-excerpt';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Comment\Property('comment_content'));

        return parent::get_formatters($components, $config, $formatters);
    }

}