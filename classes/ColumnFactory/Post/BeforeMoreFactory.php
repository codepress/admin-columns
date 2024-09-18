<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\WordLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\BeforeMoreContent;

final class BeforeMoreFactory extends BaseColumnFactory
{

    private $word_limit_factory;

    public function __construct(ComponentFactoryRegistry $component_factory_registry, WordLimit $word_limit_factory)
    {
        parent::__construct($component_factory_registry);

        $this->word_limit_factory = $word_limit_factory;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->word_limit_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new BeforeMoreContent());
    }

    public function get_column_type(): string
    {
        return 'column-before_moretag';
    }

    public function get_label(): string
    {
        return __('More Tag', 'codepress-admin-columns');
    }

}