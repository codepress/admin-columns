<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\PostContent;
use AC\Setting\FormatterCollection;

class DescriptionFactory extends ColumnFactory
{

    private $string_limit;

    private $before_after;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        StringLimit $string_limit,
        BeforeAfter $before_after
    ) {
        parent::__construct($component_factory_registry);

        $this->string_limit = $string_limit;
        $this->before_after = $before_after;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->string_limit);
        $this->add_component_factory($this->before_after);
    }

    public function get_type(): string
    {
        return 'column-description';
    }

    protected function get_label(): string
    {
        return __('Description', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PostContent());

        return parent::get_formatters($components, $config, $formatters);
    }

}