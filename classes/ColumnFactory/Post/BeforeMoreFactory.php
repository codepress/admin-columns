<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\WordLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\Post\BeforeMoreContent;

final class BeforeMoreFactory extends ColumnFactory
{

    private $word_limit_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        WordLimit $word_limit_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->word_limit_factory = $word_limit_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->word_limit_factory);
    }

    public function get_type(): string
    {
        return 'column-before_moretag';
    }

    protected function get_label(): string
    {
        return __('More Tag', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components, $formatters = []): array
    {
        return parent::get_formatters($components, [
            new BeforeMoreContent(),
        ]);
    }

}
