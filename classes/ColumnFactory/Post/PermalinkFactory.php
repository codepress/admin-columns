<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\IsLink;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class PermalinkFactory extends BaseColumnFactory
{

    private BeforeAfter $before_after;

    private IsLink $is_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        BeforeAfter $before_after,
        IsLink $is_link
    ) {
        parent::__construct(
            $component_factory_registry
        );
        $this->before_after = $before_after;
        $this->is_link = $is_link;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->is_link);
        $factories->add($this->before_after);
    }

    public function get_column_type(): string
    {
        return 'column-permalink';
    }

    public function get_label(): string
    {
        return __('Permalink', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\Post\Permalink());

        if ($config->get('is_link') === 'on') {
            $formatters->add(new Formatter\Linkable());
        }

        $formatters->add(Formatter\BeforeAfter::create_from_config($config));
    }

}