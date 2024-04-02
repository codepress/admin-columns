<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\CharacterLimit;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\Slug;
use AC\Setting\FormatterCollection;

class SlugFactory extends BaseColumnFactory
{

    private $character_limit;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        CharacterLimit $character_limit
    ) {
        parent::__construct($component_factory_registry);

        $this->character_limit = $character_limit;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->character_limit);
    }

    protected function get_label(): string
    {
        return __('Slug', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-slug';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Slug());

        return parent::get_formatters($components, $config, $formatters);
    }

}