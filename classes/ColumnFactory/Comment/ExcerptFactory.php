<?php

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
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

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        parent::add_component_factories($factories);

        $factories->add($this->string_limit);
    }

    public function get_label(): string
    {
        return __('Excerpt', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-excerpt';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new AC\Value\Formatter\Comment\Property('comment_content'));
    }

}