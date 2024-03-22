<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class DescriptionFactory extends ColumnFactory
{

    private $word_limit_factory;

    private $before_after_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\WordLimit $word_limit_factory,
        ComponentFactory\BeforeAfter $before_after_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->word_limit_factory = $word_limit_factory;
        $this->before_after_factory = $before_after_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->word_limit_factory);
        $this->add_component_factory($this->before_after_factory);
    }

    protected function get_label(): string
    {
        return __('Description', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_description';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Meta('description'));

        return parent::get_formatters($components, $config, $formatters);
    }

}