<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
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

    public function get_column_type(): string
    {
        return 'column-excerpt';
    }

    public function get_label(): string
    {
        return __('Excerpt', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        parent::add_component_factories($factories);

        $factories->add($this->string_limit);
        $factories->add($this->before_after);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new AC\Value\Formatter\Post\Excerpt());
    }

}