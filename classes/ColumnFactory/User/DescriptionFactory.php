<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class DescriptionFactory extends BaseColumnFactory
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

    public function get_label(): string
    {
        return __('Description', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_description';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\User\Meta('description'));
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->word_limit_factory);
        $factories->add($this->before_after_factory);
    }

}