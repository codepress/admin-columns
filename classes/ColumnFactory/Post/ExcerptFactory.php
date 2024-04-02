<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class ExcerptFactory extends BaseColumnFactory
{

    private $string_limit;

    private $before_after;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\StringLimit $string_limit,
        ComponentFactory\BeforeAfter $before_after
    ) {
        parent::__construct($component_factory_registry);

        $this->string_limit = $string_limit;
        $this->before_after = $before_after;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->string_limit);
        $this->add_component_factory($this->before_after);
    }

    public function get_type(): string
    {
        return 'column-excerpt';
    }

    protected function get_label(): string
    {
        return __('Excerpt', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Post\Excerpt());

        return parent::get_formatters($components, $config, $formatters);
    }

}